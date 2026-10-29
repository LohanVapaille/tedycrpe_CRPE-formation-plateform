{{-- resources/views/admin/evenements/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Événements')

@section('content')
    <div class="admin-header">
        <h1>Événements</h1>
        <a href="{{ route('admin.evenements.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Nouvel événement
        </a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Visibilité</th>
                    <th>Élève concerné</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->start_time->format('d/m/Y H:i') }}</td>
                        <td>{{ $event->end_time->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($event->user_id)
                                <span
                                    style="background:#36717b; color:white; padding:2px 8px; border-radius:4px; font-size:0.8rem;">🔒
                                    Privé</span>
                            @else
                                <span
                                    style="background:#89c2cd; color:white; padding:2px 8px; border-radius:4px; font-size:0.8rem;">🌐
                                    Public</span>
                            @endif
                        </td>
                        <td>{{ $event->user ? $event->user->firstname . ' ' . $event->user->name : '—' }}</td>
                        <td class="col-actions">
                            <div class="table-actions">
                                <a href="{{ route('admin.evenements.edit', $event) }}" class="btn btn-secondary btn-icon">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <form action="{{ route('admin.evenements.destroy', $event) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-icon"><i class='bx bx-trash'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection