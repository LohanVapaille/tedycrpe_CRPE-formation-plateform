@extends('layouts.admin')
@section('title', 'Gestion des Messages')

@section('content')
    <div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
        <h2 style="color: #1e4d56; margin-bottom: 20px;">Boîte de réception des contacts</h2>

        @if(session('success'))
            <div style="color: green; background: #e8f5e9; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        <table
            style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background: #386b73; color: white; text-align: left;">
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px;">Email</th>
                    <th style="padding: 15px;">Message</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr
                        style="border-bottom: 1px solid #e2e8f0; background: {{ $msg->is_read ? '#fafafa' : '#fff' }}; font-weight: {{ $msg->is_read ? 'normal' : 'bold' }};">
                        <td style="padding: 15px; font-size: 0.9rem; color: #64748b;">
                            {{ $msg->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding: 15px; color: #1e293b;">{{ $msg->email }}</td>
                        <td style="padding: 15px; color: #475569; max-width: 400px; word-break: break-word;">
                            {{ $msg->message }}
                        </td>
                        <td style="padding: 15px; display: flex; gap: 10px; justify-content: center;">
                            {{-- Bouton Lu/Non lu --}}
                            <form action="{{ route('admin.messages.toggle', $msg) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    style="background: #e2e8f0; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">
                                    {{ $msg->is_read ? 'Marquer non lu' : 'Marquer lu' }}
                                </button>
                            </form>

                            {{-- Bouton Supprimer --}}
                            <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST"
                                onsubmit="return confirm('Supprimer ce message ?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 30px; text-align: center; color: #94a3b8;">Aucun message reçu pour le
                            moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $messages->links() }}
        </div>
    </div>
@endsection