@extends('layouts.app')

@section('title', 'Bienvenue sur AutoImport Hub')

@section('content')
<div class="relative overflow-hidden">
    <!-- Hero Section -->
    <div class="relative pt-16 pb-32 lg:pt-32 lg:pb-48">
        <!-- Background Elements -->
        <div class="absolute inset-x-0 top-0 h-[800px] -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-amber-500/10 via-slate-950/0 to-slate-950"></div>
            <div class="absolute top-0 opacity-20 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-amber-500 rounded-full blur-[120px]"></div>
        </div>

        <div class="container px-4 mx-auto lg:px-8">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div class="space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 text-xs font-bold tracking-wider uppercase border border-amber-500/30 bg-amber-500/10 text-amber-500 rounded-full">
                        <span class="relative flex w-2 h-2">
                            <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-amber-400"></span>
                            <span class="relative inline-flex w-2 h-2 rounded-full bg-amber-500"></span>
                        </span>
                        Leader de l'importation au Togo
                    </div>
                    
                    <h1 class="text-5xl font-black leading-tight tracking-tight text-white lg:text-7xl">
                        Importation Directe <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">En Toute Confiance.</span>
                    </h1>
                    
                    <p class="max-w-xl mx-auto text-lg leading-relaxed lg:mx-0 text-slate-400">
                        Trouvez votre véhicule idéal en Europe ou en Asie et laissez-nous gérer toute l'expédition, la douane et la livraison jusqu'à votre porte. Rapide, sécurisé et transparent.
                    </p>
                    
                    <div class="flex flex-col items-center gap-4 sm:flex-row lg:justify-start">
                        <a href="{{ route('cars.index') }}" class="group relative px-8 py-4 text-sm font-bold text-slate-950 bg-amber-500 rounded-2xl transition hover:bg-amber-400 shadow-[0_0_20px_rgba(245,158,11,0.3)] flex items-center gap-2 overflow-hidden">
                            <span class="relative z-10 transition group-hover:-translate-x-1">Explorer le Catalogue</span>
                            <i data-lucide="arrow-right" class="relative z-10 w-5 h-5 transition transform group-hover:translate-x-1"></i>
                            <div class="absolute inset-0 transition-transform duration-500 translate-x-full bg-white/20 group-hover:translate-x-0"></div>
                        </a>
                        <a href="#services" class="px-8 py-4 text-sm font-bold text-white transition border border-slate-800 bg-slate-900/50 backdrop-blur rounded-2xl hover:bg-slate-800">
                            Nos Services
                        </a>
                    </div>

                    <div class="grid grid-cols-3 gap-8 pt-8 border-t border-slate-900/50">
                        <div>
                            <div class="text-3xl font-bold text-white">500+</div>
                            <div class="text-xs font-medium uppercase text-slate-500 tracking-widest">Voitures Livrées</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white">12</div>
                            <div class="text-xs font-medium uppercase text-slate-500 tracking-widest">Ports Couverts</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white">4.9/5</div>
                            <div class="text-xs font-medium uppercase text-slate-500 tracking-widest">Avis Clients</div>
                        </div>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <!-- Dashboard Mockup/Visual -->
                    <div class="relative z-10 p-2 overflow-hidden rounded-[2.5rem] bg-slate-900/40 backdrop-blur-sm shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=1000" alt="Luxury Car" class="object-cover w-full rounded-2xl shadow-inner aspect-[4/3] grayscale-[0.2] hover:grayscale-0 transition duration-700">
                        
                        <!-- Floating Card -->
                        <div class="absolute p-4 border shadow-2xl bg-slate-950/90 backdrop-blur-md border-amber-500/30 rounded-2xl top-1/2 -right-8 animate-bounce-slow">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-amber-500 rounded-xl text-slate-950">
                                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold uppercase text-slate-500 tracking-tighter">Économisez jusqu'à</div>
                                    <div class="text-2xl font-bold text-amber-500">25%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- decorative circles -->
                    <div class="absolute -top-12 -left-12 w-64 h-64 bg-amber-600/20 rounded-full blur-[80px]"></div>
                    <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-slate-500/10 rounded-full blur-[80px]"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div id="services" class="py-24 bg-slate-950">
        <div class="container px-4 mx-auto lg:px-8">
            <div class="max-w-2xl mx-auto mb-16 text-center">
                <h2 class="mb-4 text-3xl font-bold text-white lg:text-4xl">Une Solution Complète</h2>
                <div class="w-20 h-1.5 bg-amber-500 mx-auto rounded-full mb-6"></div>
                <p class="text-slate-400">Tout ce dont vous avez besoin pour gérer votre mobilité et celle de votre entreprise en un seul endroit.</p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                @php
                    $services = [
                        ['title' => 'Importation', 'icon' => 'ship', 'desc' => 'Catalogue de véhicules neufs et d\'occasion livrés partout au Togo.'],
                        ['title' => 'Location', 'icon' => 'key', 'desc' => 'Large choix de véhicules pour vos déplacements personnels ou professionnels.'],
                        ['title' => 'Pièces Détachées', 'icon' => 'settings', 'desc' => 'Trouvez des pièces certifiées compatibles avec votre modèle exact.'],
                        ['title' => 'Révision', 'icon' => 'wrench', 'desc' => 'Service technique complet pour maintenir votre véhicule au top.']
                    ];
                @endphp

                @foreach($services as $service)
                <div class="group p-8 transition border bg-slate-900 border-slate-800 rounded-[2rem] hover:border-amber-500/50 hover:shadow-2xl hover:shadow-amber-900/10">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 transition transform bg-slate-950 group-hover:bg-amber-500 rounded-2xl group-hover:rotate-6 text-amber-500 group-hover:text-slate-950">
                        <i data-lucide="{{ $service['icon'] }}" class="w-8 h-8"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-white">{{ $service['title'] }}</h3>
                    <p class="text-sm leading-relaxed text-slate-400">{{ $service['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Featured Cars -->
    <div class="py-24 bg-slate-900/30">
        <div class="container px-4 mx-auto lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 mb-16 md:flex-row">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-bold text-white lg:text-4xl">Nouveautés en Catalogue</h2>
                    <p class="mt-2 text-slate-400">Derniers arrivages soigneusement sélectionnés.</p>
                </div>
                <a href="{{ route('cars.index') }}" class="flex items-center gap-2 text-sm font-bold transition text-amber-500 hover:text-amber-400 group">
                    Voir tout le stock <i data-lucide="move-right" class="w-4 h-4 transition group-hover:translate-x-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($featuredCars as $car)
                    <div class="group bg-slate-900/40 rounded-[2.5rem] overflow-hidden hover:bg-slate-900/60 transition duration-500 shadow-xl">
                        <div class="relative overflow-hidden aspect-[16/10]">
                            <img src="{{ $car->image ?? 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $car->marque }} {{ $car->modele }}" class="object-cover w-full h-full transition duration-700 group-hover:scale-110">
                            <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-slate-950/90 to-transparent">
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-amber-500 text-slate-950 rounded-full">Nouveau</span>
                                    <span class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-white/10 text-white backdrop-blur rounded-full">{{ $car->annee }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-white group-hover:text-amber-500 transition">{{ $car->marque }} {{ $car->modele }}</h3>
                                    <div class="flex items-center gap-2 mt-1 text-slate-500 text-sm">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i> {{ $car->ville_origine }}, {{ $car->pays_origine }}
                                    </div>
                                </div>
                                <div class="text-2xl font-black text-white">
                                    {{ number_format($car->prix, 0, ',', ' ') }} <span class="text-xs text-amber-500">€</span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 pb-6 mt-6 border-b border-white/5">
                                <div class="flex items-center gap-2 text-xs text-slate-400">
                                    <i data-lucide="gauge-circle" class="w-4 h-4 text-amber-500"></i>
                                    {{ number_format($car->kilometrage, 0, ',', ' ') }} km
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-400">
                                    <i data-lucide="fuel" class="w-4 h-4 text-amber-500"></i>
                                    {{ $car->carburant }}
                                </div>
                            </div>

                            <a href="{{ route('cars.show', $car->id) }}" class="flex items-center justify-center w-full py-4 mt-6 text-sm font-bold transition border border-slate-800 bg-slate-900 text-white rounded-xl hover:bg-amber-500 hover:text-slate-950 group/btn">
                                Voir les détails
                                <i data-lucide="chevron-right" class="w-4 h-4 ml-1 transition group-hover/btn:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-slate-500 italic">Aucun véhicule disponible pour le moment.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0) translateX(2rem); }
        50% { transform: translateY(-1rem) translateX(2rem); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 4s ease-in-out infinite;
    }
</style>
@endsection
