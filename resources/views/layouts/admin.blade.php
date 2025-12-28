<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Console') - AutoImport Hub</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @yield('styles')
</head>
<body class="h-full font-sans antialiased text-slate-200 selection:bg-amber-500/30 overflow-hidden">
    <div class="flex h-screen bg-slate-950">
        <!-- Persistent Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 border-r border-slate-900 bg-slate-950 flex flex-col">
            <div class="p-8 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-xl font-bold tracking-tighter transition hover:opacity-90">
                    <span class="text-amber-500">AUTO</span>
                    <span class="text-white tracking-widest uppercase italic">IMPORT</span>
                    <span class="px-1.5 py-0.5 text-[10px] font-black bg-amber-500 text-slate-950 rounded uppercase tracking-tighter">Admin</span>
                </a>
                <button id="close-sidebar" class="lg:hidden text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div class="flex-grow overflow-y-auto px-6 py-4 custom-scrollbar">
                <!-- Group 1: Overview -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic">Vue d'ensemble</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.reports') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/reports*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="pie-chart" class="w-5 h-5"></i> Rapports & Stats
                        </a>
                    </nav>
                </div>

                <!-- Group 2: Ventes & Opérations -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic">Ventes & Opérations</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.orders-cars.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/orders-cars*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i> Commandes Véhicules
                        </a>
                        <a href="{{ route('admin.rentals.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/rentals*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="calendar-check" class="w-5 h-5"></i> Locations
                        </a>
                        <a href="{{ route('admin.orders-parts.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/orders-parts*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="package-search" class="w-5 h-5"></i> Commandes Pièces
                        </a>
                        <a href="{{ route('admin.revisions.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/revisions*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="wrench" class="w-5 h-5"></i> Demandes Révision
                            <span class="ml-auto bg-amber-500/10 text-amber-500 text-[10px] px-2 py-0.5 rounded-lg group-hover:bg-amber-500 group-hover:text-slate-950 transition">{{ $pendingRevisions ?? 0 }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Group 3: Catalogue -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic">Catalogue & Stock</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.cars.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/cars*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="car-front" class="w-5 h-5"></i> Inventaire Stock
                        </a>
                        <a href="{{ route('admin.parts-inventory.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/parts-inventory*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="package" class="w-5 h-5"></i> Gestion Pièces
                        </a>
                        <a href="{{ route('admin.ports.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/ports*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="anchor" class="w-5 h-5"></i> Ports & Logistique
                        </a>
                    </nav>
                </div>

                <!-- Group 4: Finance & Users -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic">Finance & Utilisateurs</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/payments*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="credit-card" class="w-5 h-5"></i> Paiements
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/users*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="users" class="w-5 h-5"></i> Clients & Staff
                        </a>
                        <a href="{{ route('admin.logs') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/logs*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="history" class="w-5 h-5"></i> Journal d'activités
                        </a>
                    </nav>
                </div>

                <!-- Group 5: Configuration -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic">Configuration</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.content') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/content*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="layout-template" class="w-5 h-5"></i> Gestion Contenu
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/settings*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <i data-lucide="settings" class="w-5 h-5"></i> Paramètres
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Bottom Action -->
            <div class="p-6 border-t border-slate-900">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-4 w-full px-4 py-3 text-sm font-bold text-rose-500 rounded-2xl hover:bg-rose-500/10 transition">
                        <i data-lucide="log-out" class="w-5 h-5"></i> Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-grow flex flex-col min-w-0 overflow-hidden relative">
            <!-- Topbar -->
            <header class="h-20 border-b border-slate-900 bg-slate-950/80 backdrop-blur-md flex items-center justify-between px-8 z-30">
                <button id="open-sidebar" class="lg:hidden p-2 text-slate-400 hover:text-white bg-slate-900 rounded-xl">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <div class="hidden md:flex items-center gap-4 bg-slate-900/50 border border-slate-800 rounded-2xl px-4 py-2 w-96 group focus-within:border-amber-500/50 transition duration-300">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 group-focus-within:text-amber-500"></i>
                    <input type="text" placeholder="Rechercher une commande, un véhicule..." class="bg-transparent border-none text-xs font-medium focus:ring-0 text-white placeholder-slate-600 w-full">
                </div>

                <div class="flex items-center gap-6">
                    <button class="relative p-2 text-slate-400 hover:text-white transition group">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-amber-500 rounded-full border-2 border-slate-950 shadow-sm shadow-amber-500/50"></span>
                    </button>
                    
                    <div class="flex items-center gap-4 pl-6 border-l border-slate-900">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-black text-white tracking-tight leading-none italic uppercase">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                            <div class="text-[10px] font-black text-amber-500/80 uppercase tracking-widest mt-1 leading-none italic">Administrateur</div>
                        </div>
                        <div class="w-10 h-10 overflow-hidden rounded-xl border border-slate-800 bg-slate-900 p-0.5">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom.' '.Auth::user()->nom) }}&background=fbbf24&color=000" alt="Admin" class="w-full h-full object-cover rounded-lg">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow overflow-y-auto p-8 lg:p-12 custom-scrollbar">
                <!-- Flash Messages -->
                <div class="fixed top-24 right-4 z-[100] w-full max-w-sm pointer-events-none">
                    @if(session('success'))
                        <div class="flex items-center gap-3 p-4 mb-4 text-sm font-medium border pointer-events-auto bg-slate-900/90 backdrop-blur border-emerald-500/50 text-emerald-400 rounded-2xl shadow-2xl animate-in slide-in-from-right duration-500">
                            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="flex items-center gap-3 p-4 mb-4 text-sm font-medium border pointer-events-auto bg-slate-900/90 backdrop-blur border-rose-500/50 text-rose-400 rounded-2xl shadow-2xl animate-in slide-in-from-right duration-500">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        lucide.createIcons();
        
        // Sidebar Toggle for Mobile
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        
        if (openBtn && sidebar && closeBtn) {
            openBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
            });
            closeBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.1);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(251, 191, 36, 0.1);
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(251, 191, 36, 0.3);
        }
    </style>
    @yield('scripts')
</body>
</html>
