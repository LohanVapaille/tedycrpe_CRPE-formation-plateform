{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Utilisateurs')

@php
    $pillAcces = [
        'CRPE BAC + 3' => 'is-blue',
        'CRPE BAC + 3 E-learning' => 'is-blue',
        'CRPE BAC + 5' => 'is-purple',
        'CRPE BAC + 5 E-learning' => 'is-purple',
        '3ème voie/Interne' => 'is-amber',
    ];
@endphp

@section('content')
    <div class="admin-header">
        <div>
            <h1>Utilisateurs</h1>
            <p class="admin-subtitle">{{ $users->where('is_tutor', false)->count() }} élève(s) ·
                {{ $users->where('is_tutor', true)->count() }} tuteur(s)</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Nouvel utilisateur
        </a>
    </div>

    <div class="admin-toolbar">
        <div class="admin-search">
            <i class='bx bx-search'></i>
            <input type="search" placeholder="Rechercher un nom, un email, une académie…" autocomplete="off"
                data-table-search="#users-table" data-table-filter="#users-filter" data-table-empty="#users-empty">
        </div>
        <div style="min-width: 220px;">
            <select id="users-filter" data-native class="form-select">
                <option value="">Toutes les formules</option>
                @foreach(\App\Models\User::ACCES as $valeur => $libelle)
                    <option value="{{ $valeur }}">{{ $libelle }}</option>
                @endforeach
                <option value="tuteur">Tuteurs</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table id="users-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Académie</th>
                    <th>Accès</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr data-filter="{{ $user->is_tutor ? 'tuteur' : $user->access }}">
                        <td class="cell-user">
                            <strong>{{ $user->firstname }} {{ $user->name }}</strong>
                            @if($user->is_tutor)
                                <span class="pill is-brand"><i class='bx bx-crown'></i> Tuteur</span>
                            @endif
                            <span class="cell-sub" title="{{ $user->email }}">{{ $user->email }}</span>
                        </td>
                        <td>{{ $user->academie ?: '—' }}</td>
                        <td>
                            <span class="pill {{ $pillAcces[$user->access] ?? '' }}">
                                {{ \App\Models\User::ACCES[$user->access] ?? $user->access }}
                            </span>
                        </td>
                        <td class="col-actions">
                            <div class="table-actions">
                                @if(!$user->is_tutor && $user->chats->isNotEmpty())
                                    <button type="button" class="btn btn-secondary btn-icon open-chat-btn" title="Discuter"
                                        data-chat-id="{{ $user->chats->first()->id }}"
                                        data-student-name="{{ $user->firstname }} {{ $user->name }}">
                                        <i class='bx bx-message-square-dots'></i>
                                    </button>
                                @endif
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary btn-icon" title="Modifier">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Supprimer {{ addslashes($user->firstname . ' ' . $user->name) }} ? Cette action est définitive.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-icon" title="Supprimer"><i class='bx bx-trash'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="admin-search-empty" id="users-empty">Aucun utilisateur ne correspond à la recherche.</p>
    </div>

    @push('scripts')
        <script>
            // Le chat (popup, envoi, temps réel) est géré par chat-notifications.js
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.open-chat-btn').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        window.openChat?.(parseInt(btn.dataset.chatId), btn.dataset.studentName);
                    });
                });
            });
        </script>
    @endpush
@endsection
