<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Console') - {{ $siteSettings['site_name'] ?? 'AutoImport Hub' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @yield('styles')
</head>
<body class="h-full font-sans antialiased text-slate-600 dark:text-slate-200 selection:bg-amber-500/30 overflow-hidden bg-white dark:bg-slate-950 transition-colors duration-300">
    <div class="flex h-screen bg-white dark:bg-slate-950">
        <!-- Persistent Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 flex flex-col">
            <div class="p-8 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center py-2 transition-all duration-500 hover:opacity-90">
                    @php
                        $displayMode = $siteSettings['site_display_mode'] ?? 'both';
                        $hasLogo = isset($siteSettings['site_logo']) && !empty($siteSettings['site_logo']);
                        $siteName = $siteSettings['site_name'] ?? 'AutoImport Hub';
                        $isDefaultName = in_array($siteName, ['AutoImport Hub', 'AutoImport']);
                    @endphp

                    <div class="flex items-center">
                        @if($hasLogo && ($displayMode === 'logo' || $displayMode === 'both'))
                            <img src="{{ $siteSettings['site_logo'] }}" alt="Logo" class="h-8 w-auto object-contain transition-transform duration-500 group-hover:scale-105">
                        @endif

                        @if($hasLogo && $displayMode === 'both' && ($displayMode === 'text' || $displayMode === 'both'))
                             <div class="h-6 w-px bg-gradient-to-b from-transparent via-amber-500 to-transparent mx-4 opacity-40"></div>
                        @endif

                        @if($displayMode === 'text' || $displayMode === 'both')
                            <div class="flex flex-col">
                                @if($isDefaultName)
                                    <div class="flex items-center text-sm tracking-tighter leading-none">
                                        <span class="font-black text-slate-900 dark:text-white transition-colors">AUTO</span>
                                        <span class="font-light text-amber-500">IMPORT</span>
                                    </div>
                                @else
                                    <span class="text-[11px] font-black tracking-widest text-slate-900 dark:text-white uppercase italic leading-none transition-colors">{{ $siteName }}</span>
                                @endif
                                <span class="text-[7px] font-black tracking-[0.3em] text-slate-500 uppercase mt-1">Admin Panel</span>
                            </div>
                        @endif
                    </div>
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
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.reports') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/reports*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="pie-chart" class="w-5 h-5"></i> Rapports & Stats
                        </a>
                    </nav>
                </div>

                <!-- Group 2: Ventes & Opérations -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic transition-colors">Ventes & Opérations</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.orders-cars.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/orders-cars*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i> Commandes Véhicules
                        </a>
                        <a href="{{ route('admin.rentals.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/rentals*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="calendar-check" class="w-5 h-5"></i> Locations
                        </a>
                        <a href="{{ route('admin.orders-parts.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/orders-parts*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="package-search" class="w-5 h-5"></i> Commandes Pièces
                        </a>
                        <a href="{{ route('admin.revisions.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/revisions*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="wrench" class="w-5 h-5"></i> Demandes Révision
                            <span class="ml-auto bg-amber-500/10 text-amber-500 text-[10px] px-2 py-0.5 rounded-lg group-hover:bg-amber-500 group-hover:text-slate-950 transition">{{ $pendingRevisions ?? 0 }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Group 3: Catalogue -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic transition-colors">Catalogue & Stock</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.cars.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/cars*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="car-front" class="w-5 h-5"></i> Inventaire Stock
                        </a>
                        <a href="{{ route('admin.parts-inventory.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/parts-inventory*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="package" class="w-5 h-5"></i> Gestion Pièces
                        </a>
                        <a href="{{ route('admin.ports.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/ports*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="anchor" class="w-5 h-5"></i> Ports & Logistique
                        </a>
                    </nav>
                </div>

                <!-- Group 4: Finance & Users -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic transition-colors">Finance & Utilisateurs</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/payments*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="credit-card" class="w-5 h-5"></i> Paiements
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/users*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="users" class="w-5 h-5"></i> Clients & Staff
                        </a>
                        <a href="{{ route('admin.logs') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/logs*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="history" class="w-5 h-5"></i> Journal d'activités
                        </a>
                    </nav>
                </div>

                <!-- Group 5: Configuration -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic transition-colors">Configuration</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.content') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/content*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="layout-template" class="w-5 h-5"></i> Gestion Contenu
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/settings*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="settings" class="w-5 h-5"></i> Paramètres
                        </a>
                    </nav>
                </div>

                <!-- Group 6: Relations & Marketing (Nouveau) -->
                <div class="mb-6">
                    <h4 class="px-4 text-[10px] font-black tracking-[0.2em] uppercase text-slate-600 mb-4 italic transition-colors">Croissance & Support</h4>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/coupons*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="tag" class="w-5 h-5"></i> Coupons & Promos
                        </a>
                        <a href="{{ route('admin.suppliers.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/suppliers*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="container" class="w-5 h-5"></i> Fournisseurs
                        </a>
                         <a href="{{ route('admin.tickets.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/tickets*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="message-circle-question" class="w-5 h-5"></i> Support Client
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-4 px-4 py-3 text-sm font-bold rounded-2xl transition {{ Request::is('admin/invoices*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white' }}">
                            <i data-lucide="file-text" class="w-5 h-5"></i> Factures PDF
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Bottom Action -->
            <div class="p-6 border-t border-slate-200 dark:border-slate-900">
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
            <header class="h-20 border-b border-slate-200 dark:border-slate-900 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md flex items-center justify-between px-8 z-30 transition-colors">
                <button id="open-sidebar" class="lg:hidden p-2 text-slate-400 hover:text-white bg-slate-100 dark:bg-slate-900 rounded-xl">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <form action="{{ route('admin.global-search') }}" method="GET" class="hidden md:flex items-center gap-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-2 w-96 group focus-within:border-amber-500/50 transition duration-300">
                    <button type="submit">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                    </button>
                    <input type="text" name="q" placeholder="Rechercher (CMD, VIN, Nom)..." class="bg-transparent border-none text-xs font-medium focus:ring-0 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 w-full" value="{{ request('q') }}">
                </form>

                <div class="flex items-center gap-6">
                    <button id="theme-toggle" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:bg-amber-500 hover:text-slate-950 transition-all border border-slate-200 dark:border-slate-800">
                        <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                        <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                    </button>

                    <button class="relative p-2 text-slate-400 hover:text-white transition group">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-amber-500 rounded-full border-2 border-white dark:border-slate-950 shadow-sm shadow-amber-500/50"></span>
                    </button>
                    
                    <div class="flex items-center gap-4 pl-6 border-l border-slate-200 dark:border-slate-900">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-black text-slate-900 dark:text-white tracking-tight leading-none italic uppercase italic transition-colors">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                            <div class="text-[10px] font-black text-amber-500/80 uppercase tracking-widest mt-1 leading-none italic">Administrateur</div>
                        </div>
                        <div class="w-10 h-10 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-0.5 transition-colors">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom.' '.Auth::user()->nom) }}&background=fbbf24&color=000" alt="Admin" class="w-full h-full object-cover rounded-lg">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow overflow-y-auto p-8 lg:p-12 custom-scrollbar">
                <!-- Notification Portal (Modal Alert) -->
                <div id="notificationPortal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-md" onclick="closeNotification()"></div>
                    <div class="relative bg-slate-900 border border-white/10 w-full max-w-sm overflow-hidden shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] rounded-[3rem] animate-in fade-in zoom-in duration-300">
                        <!-- Design Accents -->
                        <div id="notif_accent" class="absolute top-0 inset-x-0 h-1"></div>
                        
                        <div class="p-10 text-center">
                            <div id="notif_icon_container" class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center border-4 border-slate-950 shadow-xl">
                                <i id="notif_icon" data-lucide="bell" class="w-10 h-10"></i>
                            </div>
                            
                            <h3 id="notif_title" class="text-xl font-black text-white italic uppercase tracking-tighter mb-2"></h3>
                            <p id="notif_message" class="text-slate-400 font-bold text-[10px] uppercase tracking-widest leading-relaxed"></p>
                            
                            <button onclick="closeNotification()" class="mt-8 w-full py-4 bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl transition border border-white/5">Fermer</button>
                        </div>
                    </div>
                </div>
                <!-- Confirmation Portal (Deletion) -->
                <div id="confirmDeleteModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xl" onclick="closeDeleteModal()"></div>
                    <div class="relative bg-slate-900 border border-rose-500/20 w-full max-w-sm overflow-hidden shadow-[0_0_50px_-12px_rgba(225,29,72,0.2)] rounded-[3rem] animate-in fade-in zoom-in duration-300">
                        <div class="absolute top-0 inset-x-0 h-1 bg-rose-500"></div>
                        
                        <div class="p-10 text-center">
                            <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center border-4 border-slate-950 shadow-xl bg-rose-500 text-slate-950">
                                <i data-lucide="trash-2" class="w-10 h-10"></i>
                            </div>
                            
                            <h3 class="text-xl font-black text-white italic uppercase tracking-tighter mb-2">Confirmation Critique</h3>
                            <p id="delete_modal_message" class="text-slate-400 font-bold text-[10px] uppercase tracking-widest leading-relaxed">Cette action est irréversible. Voulez-vous vraiment supprimer cet élément ?</p>
                            
                            <form id="globalDeleteForm" method="POST" class="mt-8 flex gap-4">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-4 bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl transition border border-white/5">Annuler</button>
                                <button type="submit" class="flex-1 py-4 bg-rose-600 hover:bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl transition shadow-xl shadow-rose-900/20">Supprimer</button>
                            </form>
                        </div>
                    </div>
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

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                if (localStorage.getItem('theme') === 'dark') {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                }
            });
        }

        // Notification System
        function showAlertModal(title, message, type = 'success') {
            const portal = document.getElementById('notificationPortal');
            const icon = document.getElementById('notif_icon');
            const iconCont = document.getElementById('notif_icon_container');
            const accent = document.getElementById('notif_accent');
            
            document.getElementById('notif_title').innerText = title;
            document.getElementById('notif_message').innerText = message;
            
            // Themes
            if (type === 'success') {
                accent.className = 'absolute top-0 inset-x-0 h-1 bg-emerald-500';
                iconCont.className = 'w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center border-4 border-slate-950 shadow-xl bg-emerald-500 text-slate-950';
                icon.setAttribute('data-lucide', 'check-circle');
            } else if (type === 'error') {
                accent.className = 'absolute top-0 inset-x-0 h-1 bg-rose-500';
                iconCont.className = 'w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center border-4 border-slate-950 shadow-xl bg-rose-500 text-slate-950';
                icon.setAttribute('data-lucide', 'alert-circle');
            } else {
                accent.className = 'absolute top-0 inset-x-0 h-1 bg-amber-500';
                iconCont.className = 'w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center border-4 border-slate-950 shadow-xl bg-amber-500 text-slate-950';
                icon.setAttribute('data-lucide', 'bell');
            }
            
            lucide.createIcons();
            portal.classList.remove('hidden');
            portal.classList.add('flex');
        }

        function closeNotification() {
            const portal = document.getElementById('notificationPortal');
            portal.classList.add('hidden');
            portal.classList.remove('flex');
        }

        // Auto-trigger from Session
        @if(session('success'))
            showAlertModal('Opération Réussie', "{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showAlertModal('Erreur Système', "{{ session('error') }}", 'error');
        @endif

        // Deletion Confirmation System
        function confirmDeletion(actionUrl, message = null) {
            const portal = document.getElementById('confirmDeleteModal');
            const form = document.getElementById('globalDeleteForm');
            const msgEl = document.getElementById('delete_modal_message');
            
            if (message) msgEl.innerText = message;
            form.action = actionUrl;
            
            portal.classList.remove('hidden');
            portal.classList.add('flex');
            lucide.createIcons();
        }

        function closeDeleteModal() {
            const portal = document.getElementById('confirmDeleteModal');
            portal.classList.add('hidden');
            portal.classList.remove('flex');
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
