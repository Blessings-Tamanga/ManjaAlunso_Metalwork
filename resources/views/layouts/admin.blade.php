<!DOCTYPE html>
<html lang="en" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | ManjaAlunso')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        /* Admin overrides */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 260px;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            padding: 2rem 1rem;
            flex-shrink: 0;
        }
        .admin-sidebar .nav-logo {
            display: block;
            margin-bottom: 2rem;
        }
        .admin-sidebar a {
            display: block;
            padding: 0.6rem 1rem;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition);
        }
        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background: rgba(37, 99, 235, 0.1);
            color: var(--text);
        }
        .admin-sidebar a i {
            margin-right: 10px;
        }
        .admin-content {
            flex: 1;
            padding: 2rem;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .admin-header h1 {
            font-size: 1.8rem;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .table th {
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        .btn-sm {
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
        }
        .alert {
            padding: 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #16a34a;
            color: white;
        }
        .alert-danger {
            background: #dc2626;
            color: white;
        }
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="nav-logo">Manja<span>Alunso</span></a>
            <div style="margin-bottom: 1.5rem; padding: 0.5rem 1rem;">
                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                    <i class="ri-user-line" style="margin-right: 6px;"></i>{{ Auth::user()->name }}
                </div>
            </div>
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="ri-dashboard-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="ri-tools-line"></i> Services
                </a>
                <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                    <i class="ri-folder-line"></i> Projects
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <i class="ri-chat-quote-line"></i> Testimonials
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <i class="ri-mail-line"></i> Messages
                </a>
                <a href="{{ route('admin.galleries.index') }}" class="{{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                    <i class="ri-image-line"></i> Gallery
                </a>
                <a href="{{ route('admin.site-settings.index') }}" class="{{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}">
                    <i class="ri-settings-3-line"></i> Settings
                </a>
                <a href="{{ route('home') }}" target="_blank">
                    <i class="ri-eye-line"></i> View Site
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
                    @csrf
                    <button type="submit" style="display: block; width: 100%; text-align: left; padding: 0.6rem 1rem; border-radius: var(--radius-sm); color: var(--text-secondary); background: none; border: none; cursor: pointer; font-size: 0.9rem; transition: all var(--transition);">
                        <i class="ri-logout-box-line" style="margin-right: 10px;"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="admin-content">
            <div class="admin-header">
                <h1>@yield('heading', 'Dashboard')</h1>
                <div>
                    <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch theme">
                        <i class="ri-moon-line"></i>
                        <i class="ri-sun-line"></i>
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/script.js') }}" defer></script>
    @stack('scripts')
</body>
</html>