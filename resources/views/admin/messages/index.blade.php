@extends('layouts.admin')
@section('title', 'Gestion des Messages')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Boîte de réception des contacts</h1>
        </div>


        <div class="card shadow mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 150px;">Date</th>
                                <th style="width: 250px;">Email</th>
                                <th>Message</th>
                                <th class="text-center pe-4" style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                                <tr class="{{ $msg->is_read ? 'text-muted' : 'fw-bold text-dark' }}"
                                    style="{{ $msg->is_read ? '' : 'background-color: rgba(56, 107, 115, 0.03);' }}">

                                    <td class="ps-4 text-secondary" style="font-size: 0.9rem; white-space: nowrap;">
                                        {{ $msg->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="text-truncate" style="max-width: 250px;" title="{{ $msg->email }}">
                                        {{ $msg->email }}
                                    </td>

                                    {{-- Clic sur l'aperçu --}}
                                    <td style="cursor: pointer;" onclick="toggleModal('{{ $msg->id }}', true)">
                                        <div class="text-truncate" style="max-width: 100%;">
                                            {{ Str::limit($msg->message, 80, '...') }}
                                        </div>
                                    </td>

                                    <td class="pe-4">
                                        <div class="d-flex gap-2 justify-content-center">
                                            {{-- Clic sur le bouton œil --}}
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="toggleModal('{{ $msg->id }}', true)" title="Lire le message">
                                                <i class="bx bx-show"></i>
                                            </button>

                                            <form action="{{ route('admin.messages.toggle', $msg) }}" method="POST" class="m-0">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm {{ $msg->is_read ? 'btn-outline-secondary' : 'btn-outline-info' }}"
                                                    title="{{ $msg->is_read ? 'Marquer comme non lu' : 'Marquer comme lu' }}">
                                                    <i
                                                        class="bx {{ $msg->is_read ? 'bxs-envelope-open' : 'bxs-envelope' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" class="m-0"
                                                onsubmit="return confirm('Supprimer définitivement ce message ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class='bx bxs-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">
                                        <i class="bx bx-cabinet fa-2x mb-3 d-block text-muted"></i>
                                        Aucun message reçu pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $messages->links() }}
        </div>
    </div>

    {{-- LES MODALES SONT ICI : En dehors du tableau avec styles gérés nativement --}}
    @foreach($messages as $msg)
        <div class="modal custom-modal-fade" id="messageModal{{ $msg->id }}" tabindex="-1" aria-hidden="true"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; justify-content: center; align-items: center;">
            <div class="modal-dialog modal-dialog-centered m-0" style="width: 100%; max-width: 500px; padding: 1rem;">
                <div class="modal-content shadow-lg border-0" style="background: white; border-radius: 8px; overflow: hidden;">
                    <div class="modal-header bg-light p-3"
                        style="border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
                        <h5 class="modal-title text-dark fw-bold m-0" style="font-size: 1.1rem;">
                            Détails du message
                        </h5>
                        <button type="button" class="btn-close border-0 bg-transparent"
                            onclick="toggleModal('{{ $msg->id }}', false)" aria-label="Close"
                            style="font-size: 1.25rem; cursor: pointer;"></button>
                    </div>
                    <div class="modal-body text-start p-4">
                        <div class="mb-3">
                            <small class="text-uppercase text-muted d-block mb-1"
                                style="font-size: 0.75rem; font-weight: bold; letter-spacing: 0.5px;">Reçu le</small>
                            <p class="text-dark mb-0">{{ $msg->created_at->format('d/m/Y \à H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-uppercase text-muted d-block mb-1"
                                style="font-size: 0.75rem; font-weight: bold; letter-spacing: 0.5px;">Expéditeur</small>
                            <p class="text-dark fw-bold mb-0">{{ $msg->email }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-uppercase text-muted d-block mb-1"
                                style="font-size: 0.75rem; font-weight: bold; letter-spacing: 0.5px;">Message complet</small>
                            <div class="p-3 rounded text-dark text-break"
                                style="background: #f8f9fa; border: 1px solid #eaeaea; white-space: pre-line; line-height: 1.5; max-height: 250px; overflow-y: auto;">
                                {{ $msg->message }}
                            </div>
                        </div>
                    </div>

                    {{-- Footer Flexbox CSS pur sans dépendances --}}
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; background: #fdfdfd; border-top: 1px solid #eaeaea; padding: 1rem 1.25rem;">
                        <button type="button" onclick="toggleModal('{{ $msg->id }}', false)"
                            style="background: #f1f3f5; color: #495057; border: 1px solid #ced4da; border-radius: 6px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease-in-out;"
                            onmouseover="this.style.background='#e9ecef'; this.style.color='#212529';"
                            onmouseout="this.style.background='#f1f3f5'; this.style.color='#495057';">
                            Fermer
                        </button>

                        <a href="mailto:{{ $msg->email }}?subject=Réponse à votre message - Tedy Florette Formation"
                            style="display: flex; align-items: center; gap: 8px; text-decoration: none; background: #386b73; color: white; border: 0; border-radius: 6px; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease-in-out; box-shadow: 0 2px 4px rgba(56, 107, 115, 0.15);"
                            onmouseover="this.style.background='#2d565c'; this.style.transform='translateY(-1px)';"
                            onmouseout="this.style.background='#386b73'; this.style.transform='translateY(0)';">
                            <i class="bx bx-reply" style="font-size: 1.1rem;"></i>
                            <span>Répondre par email</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Script JS Vanilla pour l'ouverture / fermeture autonome --}}
    <script>
        function toggleModal(id, show) {
            const modal = document.getElementById('messageModal' + id);
            if (modal) {
                modal.style.display = show ? 'flex' : 'none';
            }
        }

        // Ferme la modale si l'utilisateur clique en dehors de la boîte
        window.addEventListener('click', function (event) {
            if (event.target.classList.contains('custom-modal-fade')) {
                event.target.style.display = 'none';
            }
        });
    </script>
@endsection