{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – @yield('title')</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            min-height: 100vh;
            font-family: sans-serif;
            background: #f4f6f9;
            overflow-x: hidden;
        }

        /* --- BURGER TOGGLE (Mobile) --- */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1000;
            background: #1e293b;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            align-items: center;
            justify-content: center;
        }

        /* --- SIDEBAR --- */
        .admin-sidebar {
            width: 240px;
            background: #1e293b;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            z-index: 999;
            transition: transform 0.3s ease;
        }

        .admin-sidebar h2 {
            font-size: 1.1rem;
            padding: 0 1.5rem 2rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .admin-sidebar a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background: #334155;
            color: white;
        }

        .admin-sidebar a i {
            font-size: 1.2rem;
        }

        /* --- MAIN CONTENT --- */
        .admin-content {
            margin-left: 240px;
            padding: 2rem;
            flex: 1;
            min-width: 0;
            /* Évite que les éléments enfants larges cassent le flex */
            transition: padding 0.3s ease;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
            flex-wrap: wrap;
            /* Aligne les boutons sous le titre si l'écran est trop petit */
        }

        .admin-header h1 {
            font-size: 1.5rem;
            color: #1e293b;
        }

        /* --- BUTTONS & ELEMENTS --- */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        /* Conteneur pour rendre les tableaux scrollables horizontalement sur mobile */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            white-space: nowrap;
            /* Empêche le texte des cellules de revenir à la ligne brutalement */
        }

        th {
            background: #f1f5f9;
            padding: 0.85rem 1rem;
            text-align: left;
            font-size: 0.85rem;
            color: #64748b;
        }

        td {
            padding: 0.85rem 1rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.6rem 0.85rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        .home:hover {
            background: #1e293b;
        }

        /* --- OVERLAY MOBILE --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 998;
        }

        /* -------------------------------------------
           MEDIA QUERIES (Écrans < 768px)
        ------------------------------------------- */
        @media (max-width: 768px) {
            .mobile-toggle {
                display: flex;
            }

            .admin-sidebar {
                transform: translateX(-100%);
                /* Masquée par défaut sur mobile */
            }

            .admin-sidebar.open {
                transform: translateX(0);
                /* Affichée quand on clique sur le burger */
            }

            .admin-sidebar.open+.sidebar-overlay {
                display: block;
            }

            .admin-content {
                margin-left: 0;
                padding: 5rem 1rem 1.5rem 1.1rem;
                /* Plus d'espace en haut pour le bouton burger */
            }

            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <button class="mobile-toggle" id="menuToggle">
        <i class='bx bx-menu'></i>
    </button>

    <aside class="admin-sidebar" id="sidebar">
        <a class="home" href="{{ route('home') }}">
            <h2>Retour au site</h2>
        </a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class='bx bx-group'></i> Utilisateurs
        </a>
        <a href="{{ route('admin.formations') }}" class="{{ request()->routeIs('admin.formations*') ? 'active' : '' }}">
            <i class='bx bx-book'></i> Modules
        </a>
        <a href="{{ route('admin.cours') }}" class="{{ request()->routeIs('admin.cours*') ? 'active' : '' }}">
            <i class='bx bx-file'></i> Cours
        </a>

        <a href="{{ route('admin.evenements') }}" class="{{ request()->routeIs('admin.evenements*') ? 'active' : '' }}">
            <i class='bx bx-calendar'></i> Événements
        </a>
        <a href="{{ route('admin.devoirs.index') }}"
            class="{{ request()->routeIs('admin.devoirs.index') ? 'active' : '' }}">
            <i class='bx bxs-book-content'></i> Rendus de devoirs
        </a>
        <a href="{{ route('admin.messages.index') }}"
            class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
            <i class='bx bx-envelope'></i>Gestion des emails
        </a>
        <a href="{{ route('admin.contenu') }}" class="{{ request()->routeIs('admin.contenu*') ? 'active' : '' }}">
            <i class='bx bx-edit-alt'></i> Contenu du site
        </a>
    </aside>

    <div class="sidebar-overlay" id="overlay"></div>

    <main class="admin-content">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            function toggleMenu() {
                sidebar.classList.toggle('open');
            }

            toggleBtn.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', toggleMenu);
        });
    </script>

    @stack('scripts')

    <script>
        window.chatConfig = {
            currentUserId: {{ Auth::id() }},
            isTuteur: {{ Auth::user()->is_tutor ? 'true' : 'false' }},
            initialChatId: {{ Auth::check() && !Auth::user()->is_tutor ? ($chat->id ?? 'null') : 'null' }},
            baseUrl: "{{ url('') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

    @auth
        @include('components.chat')

        @if(Auth::user()->is_tutor)
            <div class="tutor-channels-btn" id="open-channels-btn"
                style="position: fixed; bottom: 20px; right: 20px; background: #2c6e7a; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; cursor: pointer; z-index: 999; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                <i class='bx bxs-chat'></i>
                <span id="channels-notif-badge" class="notif-badge" style="display:none;">0</span>
            </div>
            <div id="channels-popup" class="chat-popup"
                style="display: none; position: fixed; bottom: 90px; right: 20px; width: 350px; height: 450px; background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.15); z-index: 1000; flex-direction: column; overflow: hidden;">
                <div class="chat-header"
                    style="background: #2c6e7a; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="margin: 0; font-size: 1rem;"><i class='bx bxs-user-detail'></i> Mes canaux élèves</h4>
                    <button id="close-channels-btn"
                        style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">&times;</button>
                </div>
                <div class="channels-body" id="channels-list-container" style="flex: 1; overflow-y: auto; padding: 0.5rem;">
                    <p style="text-align: center; color: gray; margin-top: 2rem;">Chargement des conversations...</p>
                </div>
            </div>
        @else
            <div class="tutor-chat-btn">
                <i class='dashboard-toggle-btn-i bx bxs-message-rounded-dots'></i>
                <span id="chat-notif-badge" class="notif-badge" style="display:none;">0</span>
            </div>
        @endif
    @endauth
</body>

</html>