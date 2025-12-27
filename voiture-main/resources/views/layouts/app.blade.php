<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AutoImport Hub') - Importation & Location de Véhicules</title>
    
    <meta name="description" content="Plateforme complète pour l'importation de voitures, location de véhicules et vente de pièces détachées en Afrique de l'Ouest.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @yield('styles')
</head>
<body class="h-full font-sans antialiased text-slate-200 selection:bg-amber-500/30">
    <div class="flex flex-col min-h-screen">
        <!-- Navigation -->
        <header class="sticky top-0 z-50 transition-all duration-300 border-b bg-slate-950/80 backdrop-blur-md border-slate-800" id="main-header">
            <div class="container px-4 mx-auto lg:px-8">
                <nav class="flex items-center justify-between h-20">
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 text-2xl font-bold tracking-tighter transition hover:opacity-90">
                            <span class="text-amber-500">AUTO</span>
                            <span class="text-white">IMPORT</span>
                            <span class="px-1.5 py-0.5 text-xs font-black bg-white text-slate-950 rounded uppercase tracking-tighter">Hub</span>
                        </a>
                    </div>
                    
                    <ul class="items-center hidden gap-8 md:flex">
                        <li><a href="{{ url('/') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('/') ? 'text-amber-500' : 'text-slate-400' }}">Accueil</a></li>
                        <li><a href="{{ route('cars.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('cars*') ? 'text-amber-500' : 'text-slate-400' }}">Voitures</a></li>
                        <li><a href="{{ route('parts.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('parts*') ? 'text-amber-500' : 'text-slate-400' }}">Pièces</a></li>
                        <li><a href="{{ route('rental.index') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('rental*') ? 'text-amber-500' : 'text-slate-400' }}">Location</a></li>
                        <li><a href="{{ route('revisions.create') }}" class="text-sm font-medium transition hover:text-amber-500 {{ Request::is('revisions*') ? 'text-amber-500' : 'text-slate-400' }}">Révision</a></li>
                    </ul>

                    <div class="flex items-center gap-4">
                        @auth
                            <div class="flex items-center gap-3">
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-semibold transition bg-transparent border border-amber-500/50 text-amber-500 rounded-xl hover:bg-amber-500/10">Admin</a>
                                @endif
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition bg-amber-600 rounded-xl hover:bg-amber-500 shadow-lg shadow-amber-900/20">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                    Mon Compte
                                </a>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold transition text-slate-400 hover:text-white">Connexion</a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-white transition bg-amber-600 rounded-xl hover:bg-amber-500 shadow-lg shadow-amber-900/20">S'inscrire</a>
                        @endauth
                        
                        <button class="flex items-center justify-center w-10 h-10 transition border md:hidden border-slate-800 rounded-xl hover:bg-slate-900" id="mobile-menu-btn">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                    </div>
                </nav>
            </div>
            
            <!-- Mobile Menu -->
            <div class="hidden border-t md:hidden border-slate-800 bg-slate-950" id="mobile-menu">
                <ul class="flex flex-col p-4 py-6 gap-4">
                    <li><a href="{{ url('/') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('/') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-400' }}">Accueil</a></li>
                    <li><a href="{{ route('cars.index') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('cars*') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-400' }}">Voitures</a></li>
                    <li><a href="{{ route('parts.index') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('parts*') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-400' }}">Pièces</a></li>
                    <li><a href="{{ route('rental.index') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('rental*') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-400' }}">Location</a></li>
                    <li><a href="{{ route('revisions.create') }}" class="block px-4 py-2 text-base font-medium rounded-lg {{ Request::is('revisions*') ? 'bg-amber-500/10 text-amber-500' : 'text-slate-400' }}">Révision</a></li>
                </ul>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
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

        <!-- Footer -->
        <footer class="border-t bg-slate-950 border-slate-900">
            <div class="container px-4 py-16 mx-auto lg:px-8">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-4 lg:gap-8">
                    <div class="space-y-6">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 text-2xl font-bold tracking-tighter">
                            <span class="text-amber-500">AUTO</span>
                            <span class="text-white">IMPORT</span>
                        </a>
                        <p class="text-sm leading-relaxed text-slate-400">
                            Votre partenaire de confiance pour l'importation de véhicules de qualité depuis l'Europe et l'Asie vers l'Afrique de l'Ouest. Expertise, sécurité et transparence.
                        </p>
                        <div class="flex items-center gap-4">
                            <a href="#" class="p-2 transition rounded-lg bg-slate-900 hover:text-amber-500 text-slate-400">
                                <i data-lucide="facebook" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="p-2 transition rounded-lg bg-slate-900 hover:text-amber-500 text-slate-400">
                                <i data-lucide="instagram" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="p-2 transition rounded-lg bg-slate-900 hover:text-amber-500 text-slate-400">
                                <i data-lucide="twitter" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 lg:col-span-2">
                        <div class="space-y-6">
                            <h4 class="text-sm font-bold tracking-widest uppercase text-amber-500">Services</h4>
                            <ul class="space-y-4">
                                <li><a href="#" class="text-sm transition text-slate-400 hover:text-white hover:translate-x-1 inline-block">Importation Directe</a></li>
                                <li><a href="#" class="text-sm transition text-slate-400 hover:text-white hover:translate-x-1 inline-block">Location de Véhicules</a></li>
                                <li><a href="#" class="text-sm transition text-slate-400 hover:text-white hover:translate-x-1 inline-block">Pièces Détachées</a></li>
                                <li><a href="#" class="text-sm transition text-slate-400 hover:text-white hover:translate-x-1 inline-block">Révision technique</a></li>
                            </ul>
                        </div>
                        <div class="space-y-6">
                            <h4 class="text-sm font-bold tracking-widest uppercase text-amber-500">Assistance</h4>
                            <ul class="space-y-4">
                                <li><a href="#" class="text-sm transition text-slate-400 hover:text-white hover:translate-x-1 inline-block">Contactez-nous</a></li>
                                <li><a href="#" class="text-sm transition text-slate-400 hover:text-white hover:translate-x-1 inline-block">FAQ</a></li>
                                <li><a href="#" class="text-sm transition text-slate-400 hover:text-white hover:translate-x-1 inline-block">Ports de destination</a></li>
                                <li><a href="#" class="text-sm transition text-slate-400 hover:text-white hover:translate-x-1 inline-block">Mentions légales</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-sm font-bold tracking-widest uppercase text-amber-500">Contact</h4>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <i data-lucide="phone" class="w-5 h-5 mt-0.5 text-amber-500"></i>
                                <span class="text-sm text-slate-400">+228 90 00 00 00</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i data-lucide="mail" class="w-5 h-5 mt-0.5 text-amber-500"></i>
                                <span class="text-sm text-slate-400">contact@autoimport.com</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i data-lucide="map-pin" class="w-5 h-5 mt-0.5 text-amber-500"></i>
                                <span class="text-sm text-slate-400">Lomé, Quartier Administratif, Togo</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-12 mt-12 border-t border-slate-900/50">
                    <p class="text-xs text-center text-slate-500">
                        &copy; {{ date('Y') }} AutoImport Hub. Développé avec excellence pour l'Afrique de l'Ouest.
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
            if (window.scrollY > 20) {
                header.classList.add('bg-slate-950/95', 'shadow-2xl', 'h-16');
                header.classList.remove('h-20');
            } else {
                header.classList.remove('bg-slate-950/95', 'shadow-2xl', 'h-16');
                header.classList.add('h-20');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
