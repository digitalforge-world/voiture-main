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
    
    <style>
        @keyframes loading-bar {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-loading-bar {
            width: 100%;
            animation: loading-bar 2s infinite ease-in-out;
        }

        /* Hide body content while preloader is visible */
        body {
            overflow: hidden;
        }

        /* Preloader CSS pour assurer la visibilité et la disparition */
        #preloader {
            opacity: 1;
            transition: opacity 1s ease-in-out;
            z-index: 9999;
        }

        #preloader.opacity-0 {
            opacity: 0;
            pointer-events: none;
        }

        /* Hide main content until preloader is gone */
        main {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        main.show {
            opacity: 1;
        }

        #preloader-content {
            opacity: 0;
            transition: opacity 0.7s ease-in-out;
        }

        #preloader-content.opacity-100 {
            opacity: 1;
        }
    </style>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @yield('styles')
</head>
<body class="font-sans antialiased text-slate-600 dark:text-slate-200 selection:bg-amber-500/30 bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950 transition-opacity duration-1000">
        <video id="preloader-video" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
            <source src="{{ asset('images/14481683-uhd_4096_2160_25fps.mp4') }}" type="video/mp4">
        </video>
        {{-- Overlay sombre pour lisibilité sans flou --}}
        <div class="absolute inset-0 bg-slate-950/40"></div>

        <div id="preloader-content" class="relative z-10 flex flex-col items-center opacity-0 transition-opacity duration-700">
            <div class="flex items-center text-4xl tracking-tighter leading-none mb-4">
                <span class="font-black text-white">Auto</span>
                <span class="font-light text-amber-500">Import</span>
            </div>
            <span class="mt-4 text-[10px] font-black tracking-[0.4em] text-amber-500 animate-pulse">Initialisation de l'excellence</span>
        </div>
    </div>

    <div class="flex flex-col min-h-screen">
        <!-- Navigation -->
        <header class="fixed top-0 left-0 right-0 z-[60] transition-all duration-500 border-b bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-slate-200 dark:border-slate-800" id="main-header">
            <div class="container px-4 mx-auto">
                <nav class="flex items-center justify-between h-16 lg:h-20 transition-all duration-500" id="main-nav">
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
                                    <div class="h-6 lg:h-8 w-px bg-gradient-to-b from-transparent via-amber-500 to-transparent mx-2 lg:mx-6 opacity-40 group-hover:opacity-100 group-hover:h-10 transition-all duration-700"></div>
                                @endif

                                @if($displayMode === 'text' || $displayMode === 'both')
                                    <div class="flex flex-col">
                                        @if($isDefaultName)
                                            <div class="flex items-center text-lg lg:text-2xl tracking-tighter leading-none">
                                                <span class="font-black text-slate-900 dark:text-white transition-colors">Auto</span>
                                                <span class="font-light text-amber-500">Import</span>
                                            </div>
                                            <div class="flex items-center gap-1 lg:gap-2 mt-0.5 lg:mt-1.5 items-center">
                                                <span class="text-[5px] lg:text-[8px] font-black tracking-[0.4em] text-slate-500">Solutions</span>
                                                <div class="h-px w-4 lg:w-8 bg-white/10 group-hover:w-12 group-hover:bg-amber-500/50 transition-all duration-700"></div>
                                            </div>
                                        @else
                                            <span class="text-base lg:text-xl font-black tracking-tight text-slate-900 dark:text-white leading-none transition-colors">{{ $siteName }}</span>
                                            <span class="text-[5px] lg:text-[7px] font-black tracking-[0.5em] text-amber-500/60 mt-0.5 lg:mt-1.5 group-hover:text-amber-500 transition-colors">Automotive Excellence</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    
                    <ul class="items-center hidden gap-8 md:flex">
                        <li><a href="{{ url('/') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('/') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Accueil</a></li>
                        <li><a href="{{ route('cars.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('voitures*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Voitures</a></li>
                        <li><a href="{{ route('parts.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('pieces*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Pièces</a></li>
                        <li><a href="{{ route('rental.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('location*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Location</a></li>
                        <li><a href="{{ route('revisions.create') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('revisions*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Révision</a></li>
                    </ul>

                    <div class="flex items-center gap-2 lg:gap-3">
                        @auth
                            <div class="flex items-center gap-2 lg:gap-3">
                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="px-2 lg:px-4 py-2 lg:py-2.5 text-[10px] lg:text-xs font-black uppercase tracking-widest transition bg-transparent border border-amber-500/50 text-amber-500 rounded-xl hover:bg-amber-500/10">Admin</a>
                                @endif
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 lg:px-5 py-2 lg:py-2.5 text-[10px] lg:text-xs font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-xl hover:bg-slate-950 hover:text-white shadow-lg shadow-amber-500/20">
                                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 lg:w-4 lg:h-4"></i>
                                    <span class="hidden sm:inline">Dashboard</span>
                                </a>
                            </div>
                        @else
                            <!-- Suivi -->
                            <a href="{{ route('tracking.index') }}" class="inline-flex items-center gap-2 p-2 lg:px-4 lg:py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/5 hover:border-amber-500 hover:text-amber-500 transition-all">
                                <i data-lucide="package" class="w-4 h-4 lg:w-4.5 lg:h-4.5"></i>
                                <span class="hidden lg:inline text-[10px] font-black uppercase tracking-widest">Suivi</span>
                            </a>
                        @endauth
                        
                        <!-- Recherche -->
                        <button id="global-search-btn" class="hidden md:flex items-center justify-center p-2 lg:p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:border-amber-500 hover:text-amber-500 transition-all border border-slate-200 dark:border-white/5">
                            <i data-lucide="search" class="w-4 h-4 lg:w-4.5 lg:h-4.5"></i>
                        </button>

                        <button id="theme-toggle" class="flex items-center justify-center p-2 lg:p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:border-amber-500 hover:text-amber-500 transition-all border border-slate-200 dark:border-white/5">
                            <i data-lucide="sun" class="w-4 h-4 lg:w-4.5 lg:h-4.5 hidden dark:block"></i>
                            <i data-lucide="moon" class="w-4 h-4 lg:w-4.5 lg:h-4.5 block dark:hidden"></i>
                        </button>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Mobile Menu Modal -->
        <div id="mobileMenuModal" class="fixed inset-0 z-[200] hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-2xl transition-opacity duration-500 opacity-0" id="mobileMenuBackdrop"></div>
            
            <!-- Drawer -->
            <div class="absolute right-0 top-0 bottom-0 w-[85%] max-w-[320px] bg-white dark:bg-slate-950 border-l border-slate-200 dark:border-slate-800 shadow-2xl transition-transform duration-500 translate-x-full flex flex-col" id="mobileMenuDrawer">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-900 bg-white dark:bg-slate-950">
                    <div class="flex items-center text-lg tracking-tighter leading-none">
                        <span class="font-black text-slate-900 dark:text-white">Auto</span>
                        <span class="font-light text-amber-500">Import</span>
                    </div>
                    <button class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-rose-500 transition-all" onclick="toggleMobileMenu()">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <nav class="flex-grow p-6 py-8 overflow-y-auto">
                    <ul class="space-y-4">
                        @php
                            $navLinks = [
                                ['name' => 'Accueil', 'url' => url('/'), 'active' => Request::is('/'), 'icon' => 'home'],
                                ['name' => 'Voitures en vente', 'url' => route('cars.index'), 'active' => Request::is('voitures*'), 'icon' => 'car-front'],
                                ['name' => 'Pièces détachées', 'url' => route('parts.index'), 'active' => Request::is('pieces*'), 'icon' => 'settings'],
                                ['name' => 'Location de voitures', 'url' => route('rental.index'), 'active' => Request::is('location*'), 'icon' => 'key'],
                                ['name' => 'Entretien & Révision', 'url' => route('revisions.create'), 'active' => Request::is('revisions*'), 'icon' => 'tool'],
                            ];
                        @endphp
                        
                        @foreach($navLinks as $link)
                        <li class="transform transition-all" style="transition-delay: {{ $loop->index * 50 }}ms">
                            <a href="{{ $link['url'] }}" class="flex items-center gap-4 p-4 rounded-2xl group transition-all {{ $link['active'] ? 'bg-amber-500 text-slate-950' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5' }}">
                                <div class="p-2 rounded-lg {{ $link['active'] ? 'bg-slate-900/10' : 'bg-slate-100 dark:bg-slate-900 group-hover:bg-amber-500/10' }}">
                                    <i data-lucide="{{ $link['icon'] }}" class="w-5 h-5 {{ $link['active'] ? 'text-slate-900' : 'group-hover:text-amber-500' }}"></i>
                                </div>
                                <span class="font-bold text-sm">{{ $link['name'] }}</span>
                                <i data-lucide="chevron-right" class="w-4 h-4 ml-auto opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1"></i>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </nav>
                
                <div class="p-6 border-t border-slate-100 dark:border-slate-900 bg-slate-50 dark:bg-slate-900">
                    <a href="{{ route('tracking.index') }}" class="flex items-center justify-center gap-3 w-full py-4 bg-slate-900 dark:bg-amber-500 text-white dark:text-slate-950 font-bold rounded-2xl text-xs shadow-lg transition-transform active:scale-95">
                        <i data-lucide="package" class="w-4 h-4"></i>
                        Suivre ma commande
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-grow pb-24 lg:pb-0">
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
            
            {{-- Header Spacer --}}
            <div id="header-spacer" class="h-16 lg:h-20 transition-all duration-500"></div>

            @yield('content')
        </main>

        @if(!isset($hideFooter) || !$hideFooter)
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
                            <a href="#" class="p-2.5 transition rounded-xl bg-slate-200 dark:bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-500 dark:text-slate-400 group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            </a>
                            <a href="#" class="p-2.5 transition rounded-xl bg-slate-200 dark:bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-500 dark:text-slate-400 group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                            </a>
                            <a href="#" class="p-2.5 transition rounded-xl bg-slate-200 dark:bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-500 dark:text-slate-400 group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                            </a>
                            <a href="#" class="p-2.5 transition rounded-xl bg-slate-200 dark:bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-slate-500 dark:text-slate-400 group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="space-y-6">
                        <h4 class="text-sm font-bold tracking-widest text-amber-500">Nos Services</h4>
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
                        <h4 class="text-sm font-bold tracking-widest text-amber-500">Ports Couverts</h4>
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
                        &copy; {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'AutoImport Hub' }}. Tous droits réservés. Développé avec excellence par <a href="https://digitalforges.org">Digitalforge</a>.
                    </p>
                </div>
            </div>
        </footer>
        @endif
    </div>

    <!-- Global Search Modal -->
    <div id="searchModal" class="fixed inset-0 z-[200] hidden overflow-hidden">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xl transition-opacity duration-300" onclick="closeSearchModal()"></div>
        <div class="relative min-h-screen flex items-start justify-center p-4 pt-20 pointer-events-none">
            <div id="searchModalContent" class="relative w-full max-w-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl overflow-hidden pointer-events-auto transform transition-all duration-300 translate-y-[-20%] opacity-0">
                <div class="p-6 border-b border-slate-100 dark:border-white/5">
                    <div class="relative flex items-center">
                        <i data-lucide="search" class="absolute left-5 w-5 h-5 text-slate-400"></i>
                        <input type="text" id="global-search-input" placeholder="Modèle, pièce ou location..." 
                               class="w-full pl-14 pr-6 py-4 bg-slate-50 dark:bg-slate-950 border-none rounded-2xl text-base font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 transition-all outline-none">
                    </div>
                </div>
                <div id="search-results" class="max-h-[60vh] overflow-y-auto p-4 custom-scrollbar">
                    <div class="py-20 text-center space-y-4 opacity-50">
                        <i data-lucide="search" class="w-12 h-12 mx-auto text-slate-300"></i>
                        <p class="text-sm font-black tracking-widest text-slate-400">Tapez pour commencer à chercher...</p>
                    </div>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-950/50 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-[10px] font-black tracking-widest text-slate-400">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5"><kbd class="px-2 py-0.5 bg-white dark:bg-slate-800 border dark:border-white/10 rounded">Esc</kbd> Fermer</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Order Modal -->
    <div id="orderModal" class="fixed inset-0 z-[200] hidden overflow-hidden">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-xl transition-opacity duration-500" onclick="closeOrderModal()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4 py-8 pointer-events-none">
            <div id="orderModalContent" class="relative w-full max-w-4xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-white/5 rounded-3xl shadow-2xl overflow-hidden pointer-events-auto transform transition-all duration-500 translate-y-[-20%] opacity-0 flex flex-col md:flex-row">
                
                {{-- Modale gauche - Photo --}}
                <div class="w-full md:w-1/2 relative bg-slate-100 dark:bg-slate-900 overflow-hidden group">
                    <img id="order-modal-img" src="" class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    
                    <div class="absolute bottom-6 left-6 right-6">
                        <div class="flex items-center gap-2 mb-2">
                             <span id="order-modal-marque" class="text-[9px] font-black text-amber-500 uppercase tracking-[0.3em]">MARQUE</span>
                             <div class="w-1 h-1 rounded-full bg-white/30"></div>
                             <span class="text-[9px] font-bold text-white/60 uppercase tracking-widest">Importation Directe</span>
                        </div>
                        <h3 id="order-modal-modele" class="text-2xl font-black text-white tracking-tighter leading-tight">MODELE</h3>
                        
                        <div class="mt-4 flex items-baseline gap-2">
                            <span id="order-modal-prix" class="text-2xl font-black text-white">0</span>
                            <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">FCFA (FOB)</span>
                        </div>
                    </div>

                    <button class="absolute top-6 left-6 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white md:hidden" onclick="closeOrderModal()">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </button>
                </div>

                {{-- Modale droite - Formulaire --}}
                <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic">Finaliser la commande</h2>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">Étape finale · Sans engagement immédiat</p>
                        </div>
                        <button class="hidden md:flex p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-rose-500 transition-all border border-slate-100 dark:border-white/5" onclick="closeOrderModal()">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form id="order-modal-form" method="POST" action="" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div class="relative group">
                                <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                                <input type="text" name="client_nom" required placeholder="Votre nom complet" 
                                       class="w-full py-4 pl-12 pr-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-semibold text-slate-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="relative group">
                                    <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                                    <input type="tel" name="client_telephone" required placeholder="Téléphone" 
                                           class="w-full py-4 pl-12 pr-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-semibold text-slate-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
                                </div>
                                <div class="relative group">
                                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                                    <input type="email" name="client_email" placeholder="Email" 
                                           class="w-full py-4 pl-12 pr-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-semibold text-slate-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                    <i data-lucide="anchor" class="w-3 h-3 text-amber-500"></i>
                                    Port de destination
                                </label>
                                <div class="relative group">
                                    <select name="port_id" id="modal-port-select" required
                                            class="w-full py-4 px-5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-bold text-slate-900 dark:text-white appearance-none cursor-pointer focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
                                        @if(isset($allPorts))
                                            @foreach($allPorts as $port)
                                                <option value="{{ $port->id }}" data-frais="{{ $port->frais_base }}">{{ $port->nom }} ({{ $port->pays }})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Breakdown --}}
                        <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800 space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Logistique & Transport</span>
                                <span id="modal-frais-port" class="font-black text-slate-900 dark:text-white">0 FCFA</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium tracking-tight">Douane Estimée (10%)</span>
                                <span id="modal-frais-douane" class="font-black text-amber-600">0 FCFA</span>
                            </div>
                            <div class="pt-3 border-t-2 border-dashed border-slate-200 dark:border-slate-800 flex items-center justify-between">
                                <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest">Total tout inclus</span>
                                <div class="text-right">
                                    <div id="modal-total-cost" class="text-lg font-black text-amber-500 tracking-tighter leading-none">0</div>
                                    <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Estimation indicative</div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="group relative w-full py-5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-amber-500/20 transition-all flex items-center justify-center gap-3 active:scale-95 overflow-hidden">
                             <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                             <span class="relative z-10">Confirmer ma commande</span>
                             <i data-lucide="arrow-right" class="w-4 h-4 relative z-10 group-hover:translate-x-2 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        lucide.createIcons();

        // Syncing text with video
        const preVideo = document.getElementById('preloader-video');
        const preContent = document.getElementById('preloader-content');
        if (preVideo && preContent) {
            preVideo.onplaying = () => {
                preContent.classList.add('opacity-100');
            };
            // Fallback: show content after 500ms even if video doesn't play
            setTimeout(() => {
                preContent.classList.add('opacity-100');
            }, 500);
        }
        
        // Handling Preloader (Minimum 1 seconds, Max 3 seconds)
        const preloader = document.getElementById('preloader');
        const mainContent = document.querySelector('main');
        if (preloader) {
            const preloaderStartTime = Date.now();
            
            const hidePreloader = () => {
                const currentTime = Date.now();
                const elapsedTime = currentTime - preloaderStartTime;
                const remainingTime = Math.max(0, 1000 - elapsedTime);
                
                setTimeout(() => {
                    preloader.classList.add('opacity-0');
                    // Show main content
                    if (mainContent) {
                        mainContent.classList.add('show');
                    }
                    document.body.style.overflow = 'auto';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 1000);
                }, remainingTime);
            };

            if (document.readyState === 'loading') {
                window.addEventListener('load', hidePreloader);
            } else {
                hidePreloader();
            }
            // Fallback: hide preloader after 3 seconds anyway
            setTimeout(hidePreloader, 3000);
        }

        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenuModal = document.getElementById('mobileMenuModal');
        const mobileMenuBackdrop = document.getElementById('mobileMenuBackdrop');
        const mobileMenuDrawer = document.getElementById('mobileMenuDrawer');

        function toggleMobileMenu() {
            if (mobileMenuModal.classList.contains('hidden')) {
                mobileMenuModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    mobileMenuBackdrop.classList.remove('opacity-0');
                    mobileMenuDrawer.classList.remove('translate-x-full');
                }, 10);
            } else {
                mobileMenuBackdrop.classList.add('opacity-0');
                mobileMenuDrawer.classList.add('translate-x-full');
                setTimeout(() => {
                    mobileMenuModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 500);
            }
        }

        menuBtn?.addEventListener('click', toggleMobileMenu);
        mobileMenuBackdrop?.addEventListener('click', toggleMobileMenu);
        
        // Sticky Header Effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            const nav = document.getElementById('main-nav');
            const parc = document.getElementById('parc');
            
            if (window.scrollY > 20) {
                header.classList.add('shadow-2xl', 'bg-white/95', 'dark:bg-slate-950/95');
                nav.classList.replace('h-20', 'h-16');
                document.getElementById('header-spacer').classList.replace('h-20', 'h-16');
                if (parc) {
                    parc.classList.replace('top-[80px]', 'top-[64px]');
                }
            } else {
                header.classList.remove('shadow-2xl', 'bg-white/95', 'dark:bg-slate-950/95');
                nav.classList.replace('h-16', 'h-20');
                document.getElementById('header-spacer').classList.replace('h-16', 'h-20');
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
        // Global Search Logic
        const searchBtn = document.getElementById('global-search-btn');
        const searchModal = document.getElementById('searchModal');
        const searchContent = document.getElementById('searchModalContent');
        const searchInput = document.getElementById('global-search-input');
        const searchResults = document.getElementById('search-results');
        let searchTimeout;

        function openSearchModal() {
            searchModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                searchContent.classList.remove('translate-y-[-20%]', 'opacity-0');
                searchInput.focus();
            }, 10);
        }

        function closeSearchModal() {
            searchContent.classList.add('translate-y-[-20%]', 'opacity-0');
            setTimeout(() => {
                searchModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        searchBtn?.addEventListener('click', openSearchModal);

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) closeSearchModal();
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                openSearchModal();
            }
        });

        searchInput?.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const q = e.target.value.trim();
            
            if (q.length < 2) {
                searchResults.innerHTML = `
                    <div class="py-20 text-center space-y-4 opacity-50">
                        <i data-lucide="search" class="w-12 h-12 mx-auto text-slate-300"></i>
                        <p class="text-sm font-black tracking-widest text-slate-400">Tapez au moins 2 caractères...</p>
                    </div>
                `;
                lucide.createIcons();
                return;
            }

            searchResults.innerHTML = '<div class="py-20 text-center"><div class="inline-block w-8 h-8 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div></div>';

            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/global-search?q=${encodeURIComponent(q)}`);
                    const data = await response.json();
                    
                    if (data.results.length === 0) {
                        searchResults.innerHTML = `
                            <div class="py-20 text-center space-y-4">
                                <i data-lucide="frown" class="w-12 h-12 mx-auto text-slate-300"></i>
                                <p class="text-sm font-black tracking-widest text-slate-400 italic">Aucun résultat trouvé pour "${q}"</p>
                            </div>
                        `;
                    } else {
                        searchResults.innerHTML = data.results.map(item => `
                            <a href="${item.url}" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-white/10 transition-all group">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-lg border border-slate-100 dark:border-white/5 flex-shrink-0 flex items-center justify-center">
                                    ${item.img 
                                        ? `<img src="${item.img}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">`
                                        : `<div class="text-${item.color}-500"><i data-lucide="${item.type === 'Suivi' ? 'package' : 'search'}" class="w-6 h-6"></i></div>`
                                    }
                                </div>
                                <div class="flex-grow min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-${item.color}-500/10 text-${item.color}-500 border border-${item.color}-500/20">${item.type}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest truncate">${item.subtitle}</span>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter truncate">${item.title}</h4>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-black text-amber-500 italic whitespace-nowrap">${item.price}</div>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 ml-auto mt-1 transform group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>
                        `).join('');
                    }
                    lucide.createIcons();
                } catch (err) {
                    console.error('Search failed:', err);
                    searchResults.innerHTML = '<p class="p-8 text-center text-rose-500 font-bold uppercase text-[10px]">Erreur lors de la recherche.</p>';
                }
            }, 300);
        });

        // Global Order Modal Logic
        const orderModal = document.getElementById('orderModal');
        const orderModalContent = document.getElementById('orderModalContent');
        const orderModalForm = document.getElementById('order-modal-form');
        const modalPortSelect = document.getElementById('modal-port-select');
        let currentCarPrice = 0;

        function openOrderModal(car) {
            currentCarPrice = car.prix;
            
            // Populate Modal Content
            document.getElementById('order-modal-img').src = car.photo;
            document.getElementById('order-modal-marque').textContent = car.marque;
            document.getElementById('order-modal-modele').textContent = car.modele;
            document.getElementById('order-modal-prix').textContent = new Intl.NumberFormat('fr-FR').format(car.prix);
            
            // Setup Form Action
            orderModalForm.action = `/voitures/${car.slug}/order`;
            
            // Set port if provided
            if (car.portId && modalPortSelect) {
                modalPortSelect.value = car.portId;
            }
            
            // Re-calculate
            updateModalCosts();
            
            // Show Modal
            orderModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                orderModalContent.classList.remove('translate-y-[-20%]', 'opacity-0');
            }, 10);
            
            lucide.createIcons();
        }

        function closeOrderModal() {
            orderModalContent.classList.add('translate-y-[-20%]', 'opacity-0');
            setTimeout(() => {
                orderModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        function updateModalCosts() {
            if (!modalPortSelect) return;
            const option = modalPortSelect.options[modalPortSelect.selectedIndex];
            if (!option) return;
            
            const fraisPort = parseFloat(option.dataset.frais) || 0;
            const fraisDouane = currentCarPrice * 0.10;
            const total = currentCarPrice + fraisPort + fraisDouane;
            
            document.getElementById('modal-frais-port').textContent = new Intl.NumberFormat('fr-FR').format(fraisPort) + ' FCFA';
            document.getElementById('modal-frais-douane').textContent = new Intl.NumberFormat('fr-FR').format(fraisDouane) + ' FCFA';
            document.getElementById('modal-total-cost').textContent = new Intl.NumberFormat('fr-FR').format(total);
        }

        modalPortSelect?.addEventListener('change', updateModalCosts);

        // Escape to close
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && orderModal && !orderModal.classList.contains('hidden')) closeOrderModal();
        });

        // Initialisation globale des icônes au chargement
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    {{-- Bottom Navigation Bar (Mobile Only) --}}
    <div class="fixed bottom-0 left-0 right-0 z-[150] lg:hidden">
        <div class="bg-white/95 dark:bg-slate-950/95 backdrop-blur-xl border-t border-slate-200 dark:border-white/5 pb-6 pt-3 px-1 shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
            <div class="flex items-center justify-around">
                {{-- Accueil --}}
                <a href="{{ url('/') }}" class="flex flex-col items-center gap-1 min-w-[58px]">
                    <i data-lucide="home" class="w-5 h-5 {{ Request::is('/') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}"></i>
                    <span class="text-[8px] font-bold tracking-tighter {{ Request::is('/') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Accueil</span>
                </a>

                {{-- Recherche --}}
                <button onclick="openSearchModal()" class="flex flex-col items-center gap-1 min-w-[58px]">
                    <i data-lucide="search" class="w-5 h-5 text-slate-500 dark:text-slate-400"></i>
                    <span class="text-[8px] font-bold tracking-tighter text-slate-500 dark:text-slate-400">Recherche</span>
                </button>

                {{-- Stock (Voitures) --}}
                <a href="{{ route('cars.index') }}" class="flex flex-col items-center gap-1 min-w-[58px]">
                    <i data-lucide="car" class="w-5 h-5 {{ Request::is('voitures*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}"></i>
                    <span class="text-[8px] font-bold tracking-tighter {{ Request::is('voitures*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Voitures</span>
                </a>

                {{-- Pièces --}}
                <a href="{{ route('parts.index') }}" class="flex flex-col items-center gap-1 min-w-[58px]">
                    <i data-lucide="settings" class="w-5 h-5 {{ Request::is('pieces*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}"></i>
                    <span class="text-[8px] font-bold tracking-tighter {{ Request::is('pieces*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Pièces</span>
                </a>

                {{-- Location --}}
                <a href="{{ route('rental.index') }}" class="flex flex-col items-center gap-1 min-w-[58px]">
                    <i data-lucide="key" class="w-5 h-5 {{ Request::is('location*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}"></i>
                    <span class="text-[8px] font-bold tracking-tighter {{ Request::is('location*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Location</span>
                </a>

                {{-- Révision --}}
                <a href="{{ route('revisions.create') }}" class="flex flex-col items-center gap-1 min-w-[58px]">
                    <i data-lucide="wrench" class="w-5 h-5 {{ Request::is('revisions*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}"></i>
                    <span class="text-[8px] font-bold tracking-tighter {{ Request::is('revisions*') ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">Révision</span>
                </a>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
