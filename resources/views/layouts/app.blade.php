<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $siteSettings['site_name'] ?? 'AutoImport Hub') - Importation & Location de Véhicules</title>
    
    <meta name="description" content="Plateforme complète pour l'importation de voitures, location de véhicules et vente de pièces détachées en Afrique de l'Ouest.">
    
    @if(isset($siteSettings['site_favicon']))
        <link rel="icon" type="image/x-icon" href="{{ $siteSettings['site_favicon'] }}">
    @endif
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @yield('styles')
</head>
<body class="font-sans antialiased text-slate-600 dark:text-slate-200 selection:bg-amber-500/30 bg-white dark:bg-slate-950 transition-colors duration-300">
    <div class="flex flex-col min-h-screen">
        <!-- Navigation -->
        <header class="sticky top-0 z-[60] transition-all duration-500 border-b bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-slate-200 dark:border-slate-800" id="main-header">
            <div class="container px-4 mx-auto lg:px-8">
                <nav class="flex items-center justify-between h-20 transition-all duration-500" id="main-nav">
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/') }}" class="group flex items-center py-2">
                            @php
                                $displayMode = $siteSettings['site_display_mode'] ?? 'both';
                                $hasLogo = isset($siteSettings['site_logo']) && !empty($siteSettings['site_logo']);
                                $siteName = $siteSettings['site_name'] ?? 'AutoImport Hub';
                                $isDefaultName = in_array($siteName, ['AutoImport Hub', 'AutoImport']);
                            @endphp

                            <div class="flex items-center">
                                @if($hasLogo && ($displayMode === 'logo' || $displayMode === 'both'))
                                    <div class="relative flex items-center">
                                        <img src="{{ $siteSettings['site_logo'] }}" alt="Logo" class="h-11 w-auto object-contain transition-all duration-700 group-hover:scale-105 filter drop-shadow-[0_0_10px_rgba(251,191,36,0.1)]">
                                    </div>
                                @endif

                                @if($hasLogo && $displayMode === 'both' && ($displayMode === 'text' || $displayMode === 'both'))
                                    <div class="h-8 w-px bg-gradient-to-b from-transparent via-amber-500 to-transparent mx-6 opacity-40 group-hover:opacity-100 group-hover:h-10 transition-all duration-700"></div>
                                @endif

                                @if($displayMode === 'text' || $displayMode === 'both')
                                    <div class="flex flex-col">
                                        @if($isDefaultName)
                                            <div class="flex items-center text-2xl tracking-tighter leading-none">
                                                <span class="font-black text-slate-900 dark:text-white transition-colors">AUTO</span>
                                                <span class="font-light text-amber-500">IMPORT</span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1.5 items-center">
                                                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500">Solutions</span>
                                                <div class="h-px w-8 bg-white/10 group-hover:w-12 group-hover:bg-amber-500/50 transition-all duration-700"></div>
                                            </div>
                                        @else
                                            <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white uppercase italic leading-none transition-colors">{{ $siteName }}</span>
                                            <span class="text-[7px] font-black tracking-[0.5em] text-amber-500/60 uppercase mt-1.5 group-hover:text-amber-500 transition-colors">Automotive Excellence</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    
                    <ul class="items-center hidden gap-8 md:flex">
                        <li><a href="{{ url('/') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('/') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Accueil</a></li>
                        <li><a href="{{ route('cars.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('cars*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Voitures</a></li>
                        <li><a href="{{ route('parts.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('parts*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Pièces</a></li>
                        <li><a href="{{ route('rental.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('rental*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Location</a></li>
                        <li><a href="{{ route('revisions.create') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('revisions*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Révision</a></li>
                    </ul>

                    <div class="flex items-center gap-4">
                        @auth
                            <div class="flex items-center gap-3">
                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-semibold transition bg-transparent border border-amber-500/50 text-amber-500 rounded-xl hover:bg-amber-500/10">Admin</a>
                                @endif
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition bg-amber-600 rounded-xl hover:bg-amber-500 shadow-lg shadow-amber-900/20">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-white transition">
                                        <i data-lucide="log-out" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Client anonyme : Bouton de Tracking -->
                            <a href="{{ route('tracking.index') }}" class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold transition bg-slate-800 text-white border border-slate-700 rounded-xl hover:bg-amber-500 hover:text-slate-950 hover:border-amber-500 group">
                                <i data-lucide="search" class="w-4 h-4 text-amber-500 group-hover:text-slate-950 transition-colors"></i>
                                Suivre ma commande
                            </a>
                        @endauth
                        
                        <button id="theme-toggle" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:bg-amber-500 hover:text-slate-950 dark:hover:bg-amber-500 dark:hover:text-slate-950 transition-all duration-300 border border-slate-200 dark:border-slate-800">
                            <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                            <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                        </button>

                        <button class="flex items-center justify-center w-10 h-10 transition border md:hidden border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-900" id="mobile-menu-btn">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                    </div>
                </nav>
            </div>
            
            <!-- Mobile Menu -->
            <div class="hidden border-t md:hidden border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950" id="mobile-menu">
                <ul class="flex flex-col p-4 py-6 gap-4">
                    <li><a href="{{ url('/') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('/') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Accueil</a></li>
                    <li><a href="{{ route('cars.index') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('cars*') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Voitures</a></li>
                    <li><a href="{{ route('parts.index') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('parts*') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Pièces</a></li>
                    <li><a href="{{ route('rental.index') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('rental*') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Location</a></li>
                    <li><a href="{{ route('revisions.create') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('revisions*') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Révision</a></li>
                </ul>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <!-- Flash Messages -->
            <div class="fixed top-24 right-4 z-[100] w-full max-w-sm pointer-events-none">
                @if(session('success'))
                    <div class="flex items-center gap-3 p-4 mb-4 text-sm font-medium border pointer-events-auto bg-white/90 dark:bg-slate-900/90 backdrop-blur border-emerald-500/50 text-emerald-600 dark:text-emerald-400 rounded-2xl shadow-xl dark:shadow-2xl animate-in slide-in-from-right duration-500">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 p-4 mb-4 text-sm font-medium border pointer-events-auto bg-white/90 dark:bg-slate-900/90 backdrop-blur border-rose-500/50 text-rose-600 dark:text-rose-400 rounded-2xl shadow-xl dark:shadow-2xl animate-in slide-in-from-right duration-500">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500"></i>
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            @yield('content')
        </main>

        <!-- Footer Structuré -->
        <footer class="border-t bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-900 transition-colors duration-300">
            <div class="container px-4 py-16 mx-auto lg:px-8">
                <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-5 lg:gap-8">
                    <!-- Branding & Description -->
                    <div class="space-y-8 lg:col-span-2">
                        <a href="{{ url('/') }}" class="group inline-flex items-center">
                            @php
                                $displayMode = $siteSettings['site_display_mode'] ?? 'both';
                                $hasLogo = isset($siteSettings['site_logo']) && !empty($siteSettings['site_logo']);
                                $siteName = $siteSettings['site_name'] ?? 'AutoImport Hub';
                                $isDefaultName = in_array($siteName, ['AutoImport Hub', 'AutoImport']);
                            @endphp

                            <div class="flex items-center">
                                @if($hasLogo && ($displayMode === 'logo' || $displayMode === 'both'))
                                    <img src="{{ $siteSettings['site_logo'] }}" alt="Logo" class="h-10 w-auto object-contain transition-all duration-700 opacity-80 group-hover:opacity-100 group-hover:scale-105">
                                @endif

                                @if($hasLogo && $displayMode === 'both' && ($displayMode === 'text' || $displayMode === 'both'))
                                    <div class="h-8 w-px bg-white/10 mx-6"></div>
                                @endif

                                @if($displayMode === 'text' || $displayMode === 'both')
                                    <div class="flex flex-col">
                                        @if($isDefaultName)
                                            <div class="flex items-center text-xl tracking-tighter leading-none">
                                                <span class="font-black text-white">AUTO</span>
                                                <span class="font-light text-amber-500">IMPORT</span>
                                            </div>
                                        @else
                                            <span class="text-lg font-black tracking-tight text-white uppercase italic leading-none">{{ $siteName }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                        <p class="text-xs leading-relaxed text-slate-500 max-w-sm uppercase tracking-widest font-bold">
                             {{ $siteSettings['site_description'] ?? 'Plateforme complète pour l\'importation, la location et l\'entretien automobile en Afrique de l\'Ouest.' }}
                        </p>
                        <div class="flex items-center gap-4">
                            <a href="#" class="p-2.5 transition rounded-xl bg-slate-200 dark:bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-500 dark:text-slate-400">
                                <i data-lucide="facebook" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="p-2.5 transition rounded-xl bg-slate-200 dark:bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-500 dark:text-slate-400">
                                <i data-lucide="instagram" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="p-2.5 transition rounded-xl bg-slate-200 dark:bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-500 dark:text-slate-400">
                                <i data-lucide="twitter" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="p-2.5 transition rounded-xl bg-slate-200 dark:bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-500 dark:text-slate-400">
                                <i data-lucide="linkedin" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="space-y-6">
                        <h4 class="text-sm font-bold tracking-widest uppercase text-amber-500">Nos Services</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('cars.index') }}" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Importation Directe
                            </a></li>
                            <li><a href="{{ route('rental.index') }}" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Location de Véhicules
                            </a></li>
                            <li><a href="{{ route('parts.index') }}" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Pièces Détachées
                            </a></li>
                            <li><a href="{{ route('revisions.create') }}" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Révision & Diagnostic
                            </a></li>
                            <li><a href="{{ route('parts.compatibility') }}" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Compatibilité Pièces
                            </a></li>
                        </ul>
                    </div>

                    <!-- Ports Disponibles -->
                    <div class="space-y-6">
                        <h4 class="text-sm font-bold tracking-widest uppercase text-amber-500">Ports Couverts</h4>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-2 text-sm text-slate-400">
                                <span class="text-base">🇹🇬</span> Port de Lomé
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-400">
                                <span class="text-base">🇧🇯</span> Port de Cotonou
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-400">
                                <span class="text-base">🇬🇭</span> Port de Tema
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-400">
                                <span class="text-base">🇨🇮</span> Port d'Abidjan
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-400">
                                <span class="text-base">🇸🇳</span> Port de Dakar
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-400">
                                <span class="text-base">🇧🇫</span> Ouagadougou
                            </li>
                        </ul>
                    </div>

                    <!-- Informations Légales & Contact -->
                    <div class="space-y-6">
                        <h4 class="text-sm font-bold tracking-widest uppercase text-amber-500">Informations</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Conditions d'utilisation
                            </a></li>
                            <li><a href="#" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Confidentialité
                            </a></li>
                            <li><a href="#" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Mentions légales
                            </a></li>
                            <li><a href="#" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> FAQ
                            </a></li>
                            <li><a href="#" class="text-sm transition text-slate-400 hover:text-amber-500 hover:translate-x-1 inline-flex items-center gap-2">
                                <i data-lucide="chevron-right" class="w-3 h-3"></i> Nous contacter
                            </a></li>
                        </ul>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="pt-8 mt-8 text-center border-t border-slate-200 dark:border-slate-900/50">
                    <p class="text-xs text-slate-500">
                        &copy; {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'AutoImport Hub' }}. Tous droits réservés. Développé avec excellence pour l'Afrique de l'Ouest.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        lucide.createIcons();
        
        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Sticky Header Effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            const nav = document.getElementById('main-nav');
            const parc = document.getElementById('parc');
            
            if (window.scrollY > 20) {
                header.classList.add('shadow-2xl', 'bg-white/95', 'dark:bg-slate-950/95');
                nav.classList.replace('h-20', 'h-16');
                if (parc) {
                    parc.classList.replace('top-[80px]', 'top-[64px]');
                }
            } else {
                header.classList.remove('shadow-2xl', 'bg-white/95', 'dark:bg-slate-950/95');
                nav.classList.replace('h-16', 'h-20');
                if (parc) {
                    parc.classList.replace('top-[64px]', 'top-[80px]');
                }
            }
        });

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        
        themeToggleBtn.addEventListener('click', () => {
            // if set via local storage previously
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }

            // if NOT set via local storage previously
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
    </script>
    @yield('scripts')
</body>
</html>
