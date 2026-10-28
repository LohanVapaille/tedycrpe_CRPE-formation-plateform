<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Matiere extends Model
{
    protected $fillable = ['name', 'module_associe', 'bloc_id', 'ordre'];

    public function cours(): HasMany
    {
        // Toujours dans l'ordre défini dans l'admin (glisser-déposer)
        return $this->hasMany(Cours::class, 'id_matiere')->orderBy('ordre')->orderBy('id');
    }

    public function bloc(): BelongsTo
    {
        return $this->belongsTo(Bloc::class);
    }

    /**
     * Regroupe des modules par bloc (Leçon, EPS, Entretien...), dans l'ordre des blocs défini dans l'admin.
     * Les modules sans bloc vont dans "Autres" ; si aucun n'a de bloc, un seul groupe sans titre.
     *
     * @return Collection<int, array{titre: ?string, matieres: Collection}>
     */
    public static function parBloc(Collection $matieres): Collection
    {
        if ($matieres instanceof \Illuminate\Database\Eloquent\Collection) {
            $matieres->loadMissing('bloc');
        }

        // "Sans bloc" = aucun bloc, ou bloc supprimé entre-temps
        [$avecBloc, $sansBloc] = $matieres->partition(fn($m) => $m->bloc !== null);
        $sansBloc = $sansBloc->values();

        if ($avecBloc->isEmpty()) {
            return collect([['titre' => null, 'matieres' => $matieres->values()]]);
        }

        $groupes = $avecBloc
            ->groupBy('bloc_id')
            ->sortBy(fn($liste) => [$liste->first()->bloc->ordre, $liste->first()->bloc->id])
            ->map(fn($liste) => ['titre' => $liste->first()->bloc->name, 'matieres' => $liste->values()])
            ->values();

        if ($sansBloc->isNotEmpty()) {
            $groupes->push(['titre' => 'Autres', 'matieres' => $sansBloc]);
        }

        return $groupes;
    }
}
