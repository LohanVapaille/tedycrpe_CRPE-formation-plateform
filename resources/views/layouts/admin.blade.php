{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin – @yield('title')</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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
        }

        .admin-sidebar {
            width: 240px;
            background: #1e293b;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
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

        .admin-content {
            margin-left: 240px;
            padding: 2rem;
            flex: 1;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .admin-header h1 {
            font-size: 1.5rem;
            color: #1e293b;
        }

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

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
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
            background: ##1e293b;

        }
    </style>
    @stack('styles')
</head>

<body>

    <aside class="admin-sidebar">
        <a class="home" href="{{ route('home') }}">
            <h2>Retour au site</h2>
        </a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class='bx bx-group'></i> Utilisateurs
        </a>
        <a href="{{ route('admin.formations') }}" class="{{ request()->routeIs('admin.formations*') ? 'active' : '' }}">
            <i class='bx bx-book'></i> Matières
        </a>
        <a href="{{ route('admin.cours') }}" class="{{ request()->routeIs('admin.cours*') ? 'active' : '' }}">
            <i class='bx bx-file'></i> Cours
        </a>
        <a href="{{ route('admin.contenu') }}" class="{{ request()->routeIs('admin.contenu*') ? 'active' : '' }}">
            <i class='bx bx-edit-alt'></i> Contenu du site
        </a>
        <a href="{{ route('admin.evenements') }}" class="{{ request()->routeIs('admin.evenements*') ? 'active' : '' }}">
            <i class='bx bx-calendar'></i> Événements
        </a>
    </aside>

    <main class="admin-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>