@extends('layouts.app')

@section('title', 'AutoImport Hub - Votre partenaire automobile en Afrique de l\'Ouest')

@section('content')
<div class="min-h-screen">
    {{-- Hero Section --}}
    <section class="relative pt-24 pb-20 overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-20 left-10 w-72 h-72 bg-amber-500/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-20 w-96 h-96 bg-amber-600/20 rounded-full blur-3xl"></div>
        </div>

        <div class="container relative px-4 mx-auto max-w-7xl">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
                <div class="space-y-7">
                    <span class="inline-block px-4 py-1.5 text-sm font-semibold bg-amber-500/10 text-amber-500 border border-amber-500/20 rounded-full">
                        🚗 Leader en Afrique de l'Ouest
                    </span>
                    
                    <h1 class="text-4xl font-extrabold leading-tight text-white lg:text-5xl">
                        Importation, Location et <br class="hidden sm:block">
                        Pièces Automobiles
                    </h1>
                    
                    <p class="text-lg leading-relaxed text-slate-300 max-w-xl">
                        Simplifiez vos besoins automobiles. Importez depuis l'Europe ou l'Asie, 
                        louez un véhicule ou trouvez la pièce parfaite - tout ça sur une seule plateforme.
                    </p>
                    
                    {{-- CTA Buttons --}}
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ route('cars.index') }}" class="inline-flex items-center px-6 py-3.5 text-base font-semibold text-slate-950 bg-amber-500 rounded-lg hover:bg-amber-400 transition-colors shadow-lg shadow-amber-500/25">
                            <i data-lucide="car" class="w-5 h-5 mr-2"></i>
                            Commander une voiture
                        </a>
                        
                        <a href="{{ route('rental.index') }}" class="inline-flex items-center px-6 py-3.5 text-base font-semibold text-white bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 transition-colors">
                            <i data-lucide="key" class="w-5 h-5 mr-2"></i>
                            Louer un véhicule
                        </a>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('parts.index') }}" class="text-sm font-medium text-slate-400 hover:text-amber-500 transition-colors underline underline-offset-4">
                            Trouver une pièce détachée
                        </a>
                        <span class="text-slate-700">•</span>
                        <a href="{{ route('revisions.create') }}" class="text-sm font-medium text-slate-400 hover:text-amber-500 transition-colors underline underline-offset-4">
                            Demander une révision
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="flex gap-8 pt-6 border-t border-slate-800">
                        <div>
                            <div class="text-2xl font-bold text-white">500+</div>
                            <div class="text-sm text-slate-500">Voitures livrées</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-white">12</div>
                            <div class="text-sm text-slate-500">Ports couverts</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-white">4.9★</div>
                            <div class="text-sm text-slate-500">Note moyenne</div>
                        </div>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=900" 
                         alt="Voiture de luxe" 
                         class="rounded-2xl shadow-2xl"
                         style="mask-image: radial-gradient(ellipse 85% 80% at 50% 50%, black 30%, transparent 95%);">
                    
                    {{-- Floating badge --}}
                    <div class="absolute top-8 -right-4 bg-slate-900/90 backdrop-blur border border-amber-500/30 rounded-xl p-4 shadow-xl">
                        <div class="flex items-center gap-3">
                            <div class="bg-amber-500 rounded-lg p-2">
                                <i data-lucide="percent" class="w-6 h-6 text-slate-950"></i>
                            </div>
                            <div>
                                <div class="text-xs text-slate-400">Économisez</div>
                                <div class="text-xl font-bold text-amber-500">jusqu'à 25%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Overview --}}
    <section class="py-16 bg-slate-900/50">
        <div class="container px-4 mx-auto max-w-7xl">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-bold text-white mb-3">Nos Services</h2>
                <p class="text-slate-400 max-w-2xl mx-auto">
                    Une plateforme complète pour gérer tous vos besoins automobiles
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @php
                    $services = [
                        ['icon' => 'ship', 'title' => 'Importation', 'desc' => 'Catalogues Europe & Asie, expédition directe'],
                        ['icon' => 'anchor', 'title' => 'Choix du port', 'desc' => 'Lomé, Cotonou, Tema, Abidjan...'],
                        ['icon' => 'key', 'title' => 'Location', 'desc' => 'Courte ou longue durée, tarifs flexibles'],
                        ['icon' => 'package', 'title' => 'Pièces détachées', 'desc' => 'Achat, échange, compatibilité garantie'],
                        ['icon' => 'cpu', 'title' => 'Compatibilité auto', 'desc' => 'Trouvez les pièces pour votre modèle'],
                        ['icon' => 'wrench', 'title' => 'Révision', 'desc' => 'Diagnostic complet et devis transparent'],
                        ['icon' => 'clipboard-list', 'title' => 'Suivi commandes', 'desc' => 'Tableau de bord unifié temps réel'],
                        ['icon' => 'shield-check', 'title' => 'Traçabilité', 'desc' => 'Chaque action référencée et traçable'],
                    ];
                @endphp

                @foreach($services as $service)
                <div class="p-6 bg-slate-900/60 border border-slate-800 rounded-xl hover:border-amber-500/40 transition-all group">
                    <div class="mb-4 inline-flex p-3 bg-amber-500/10 text-amber-500 rounded-lg group-hover:bg-amber-500 group-hover:text-slate-950 transition-colors">
                        <i data-lucide="{{ $service['icon'] }}" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $service['title'] }}</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">{{ $service['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Cars --}}
    <section class="py-16 bg-slate-950">
        <div class="container px-4 mx-auto max-w-7xl">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Véhicules Disponibles</h2>
                    <p class="text-slate-400">Nouveaux arrivages et coups de cœur</p>
                </div>
                
                <div class="hidden md:flex gap-3">
                    <select class="px-4 py-2 bg-slate-900 border border-slate-800 text-slate-300 rounded-lg text-sm focus:border-amber-500 focus:outline-none">
                        <option>Marque</option>
                        <option>Toyota</option>
                        <option>Mercedes</option>
                        <option>BMW</option>
                    </select>
                    <select class="px-4 py-2 bg-slate-900 border border-slate-800 text-slate-300 rounded-lg text-sm focus:border-amber-500 focus:outline-none">
                        <option>Pays</option>
                        <option>Allemagne</option>
                        <option>France</option>
                        <option>Japon</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse($featuredCars ?? [] as $car)
                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden hover:border-amber-500/30 transition-all group">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $car->image ?? 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&q=80&w=600' }}" 
                                 alt="{{ ($car->marque ?? '') . ' ' . ($car->modele ?? '') }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-amber-500 text-slate-950 text-xs font-bold rounded">{{ $car->annee ?? '2024' }}</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-white text-lg mb-1">{{ ($car->marque ?? '') . ' ' . ($car->modele ?? '') }}</h3>
                            <p class="text-sm text-slate-500 mb-4">
                                <i data-lucide="map-pin" class="w-3 h-3 inline"></i>
                                {{ ($car->ville_origine ?? '') . ', ' . ($car->pays_origine ?? '') }}
                            </p>
                            
                            <div class="flex items-baseline justify-between mb-4">
                                <div class="text-2xl font-bold text-white">
                                    {{ number_format($car->prix ?? 0, 0, ',', ' ') }}<span class="text-sm text-amber-500">€</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('cars.show', $car->id ?? 1) }}" class="block w-full py-2.5 text-center text-sm font-semibold bg-slate-800 text-white rounded-lg hover:bg-amber-500 hover:text-slate-950 transition-colors">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                @empty
                    @for($i = 0; $i < 4; $i++)
                    @php
                        $demos = [
                            ['name' => 'Toyota Camry 2023', 'img' => '1583121274602-3e2820c69888', 'loc' => 'Hambourg, Allemagne', 'price' => 25000],
                            ['name' => 'Mercedes C-Class', 'img' => '1494976388531-d1058494cdd8', 'loc' => 'Paris, France', 'price' => 35000],
                            ['name' => 'BMW X5 2024', 'img' => '1617531653332-bd46c24f2068', 'loc' => 'Munich, Allemagne', 'price' => 48000],
                            ['name' => 'Audi A6 Sportline', 'img' => '1605559424843-9e4c2c43d7d0', 'loc' => 'Tokyo, Japon', 'price' => 38000],
                        ];
                        $demo = $demos[$i];
                    @endphp
                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden hover:border-amber-500/30 transition-all group">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="https://images.unsplash.com/photo-{{ $demo['img'] }}?auto=format&fit=crop&q=80&w=600" 
                                 alt="{{ $demo['name'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-amber-500 text-slate-950 text-xs font-bold rounded">2024</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-white text-lg mb-1">{{ $demo['name'] }}</h3>
                            <p class="text-sm text-slate-500 mb-4">
                                <i data-lucide="map-pin" class="w-3 h-3 inline"></i>
                                {{ $demo['loc'] }}
                            </p>
                            
                            <div class="flex items-baseline justify-between mb-4">
                                <div class="text-2xl font-bold text-white">
                                    {{ number_format($demo['price'], 0, ',', ' ') }}<span class="text-sm text-amber-500">€</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('cars.index') }}" class="block w-full py-2.5 text-center text-sm font-semibold bg-slate-800 text-white rounded-lg hover:bg-amber-500 hover:text-slate-950 transition-colors">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                    @endfor
                @endforelse
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('cars.index') }}" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-amber-500 border border-amber-500/30 rounded-lg hover:bg-amber-500/10 transition-colors">
                    Voir tout le catalogue
                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Ports Section --}}
    <section class="py-16 bg-slate-900/30">
        <div class="container px-4 mx-auto max-w-7xl">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-bold text-white mb-3">Ports d'Arrivée Disponibles</h2>
                <p class="text-slate-400 max-w-2xl mx-auto">
                    Choisissez le port le plus proche de chez vous. Les frais et délais varient selon la destination.
                </p>
            </div>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $ports = [
                        ['name' => 'Lomé', 'country' => 'Togo', 'flag' => '🇹🇬', 'delay' => '25-35j', 'popular' => true],
                        ['name' => 'Cotonou', 'country' => 'Bénin', 'flag' => '🇧🇯', 'delay' => '28-38j', 'popular' => true],
                        ['name' => 'Tema', 'country' => 'Ghana', 'flag' => '🇬🇭', 'delay' => '30-40j', 'popular' => false],
                        ['name' => 'Abidjan', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮', 'delay' => '32-42j', 'popular' => false],
                        ['name' => 'Dakar', 'country' => 'Sénégal', 'flag' => '🇸🇳', 'delay' => '28-38j', 'popular' => false],
                        ['name' => 'Ouagadougou', 'country' => 'Burkina Faso', 'flag' => '🇧🇫', 'delay' => '35-45j', 'popular' => false],
                    ];
                @endphp

                @foreach($ports as $port)
                <div class="relative p-5 bg-slate-900/60 border border-slate-800 rounded-xl hover:border-amber-500/30 transition-colors {{ $port['popular'] ? 'ring-2 ring-amber-500/20' : '' }}">
                    @if($port['popular'])
                    <span class="absolute -top-2 right-4 px-2 py-0.5 bg-amber-500 text-slate-950 text-xs font-bold rounded">Populaire</span>
                    @endif
                    
                    <div class="flex items-start gap-3">
                        <span class="text-3xl">{{ $port['flag'] }}</span>
                        <div class="flex-1">
                            <h3 class="font-semibold text-white text-lg">{{ $port['name'] }}</h3>
                            <p class="text-sm text-slate-500 mb-3">{{ $port['country'] }}</p>
                            <div class="text-sm text-slate-400">
                                <i data-lucide="clock" class="w-4 h-4 inline text-amber-500"></i>
                                Délai: <strong class="text-white">{{ $port['delay'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <p class="mt-8 text-center text-sm text-slate-500">
                <i data-lucide="info" class="w-4 h-4 inline text-amber-500"></i>
                Les délais sont estimatifs et peuvent varier selon les formalités douanières
            </p>
        </div>
    </section>

    {{-- Location Vehicles --}}
    <section class="py-16 bg-slate-950">
        <div class="container px-4 mx-auto max-w-7xl">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-white mb-2">Location de Véhicules</h2>
                <p class="text-slate-400">Durée flexible • Paiement sécurisé • Véhicules récents</p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @php
                    $rentals = [
                        ['name' => 'Toyota Corolla', 'cat' => 'Berline économique', 'price' => 35000, 'img' => '1621007947622-8c22e2ac881f'],
                        ['name' => 'Honda CR-V', 'cat' => 'SUV familial', 'price' => 55000, 'img' => '1519641471654-76ce0107ad1b'],
                        ['name' => 'Mercedes E-Class', 'cat' => 'Berline de luxe', 'price' => 85000, 'img' => '1617531653332-bd46c24f2068'],
                    ];
                @endphp

                @foreach($rentals as $rental)
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden hover:border-amber-500/30 transition-all">
                    <div class="relative aspect-video overflow-hidden">
                        <img src="https://images.unsplash.com/photo-{{ $rental['img'] }}?auto=format&fit=crop&q=80&w=600" 
                             alt="{{ $rental['name'] }}" 
                             class="w-full h-full object-cover">
                        <span class="absolute top-3 right-3 px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded">
                            Disponible
                        </span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-white text-lg mb-1">{{ $rental['name'] }}</h3>
                        <p class="text-sm text-slate-500 mb-4">{{ $rental['cat'] }}</p>
                        
                        <div class="mb-4 pb-4 border-b border-slate-800">
                            <span class="text-2xl font-bold text-white">{{ number_format($rental['price'], 0, ' ', ' ') }}</span>
                            <span class="text-sm text-slate-400"> FCFA/jour</span>
                        </div>

                        <div class="space-y-2 mb-5 text-sm text-slate-400">
                            <div class="flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                Assurance tous risques
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                Kilométrage illimité
                            </div>
                        </div>
                        
                        <a href="{{ route('rental.index') }}" class="block w-full py-2.5 text-center text-sm font-semibold bg-amber-500 text-slate-950 rounded-lg hover:bg-amber-400 transition-colors">
                            Réserver
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('rental.index') }}" class="text-sm text-amber-500 hover:text-amber-400 underline underline-offset-4">
                    Voir tous les véhicules de location →
                </a>
            </div>
        </div>
    </section>

    {{-- Parts & Compatibility --}}
    <section class="py-16 bg-slate-900/50">
        <div class="container px-4 mx-auto max-w-7xl">
            <div class="grid gap-12 lg:grid-cols-2 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-4">
                        Pièces Détachées avec Compatibilité Intelligente
                    </h2>
                    <p class="text-lg text-slate-300 mb-8">
                        Notre système trouve automatiquement les pièces compatibles avec votre véhicule. 
                        Recherche par marque, modèle ou numéro de châssis.
                    </p>

                    <div class="space-y-4">
                        <div class="flex gap-4 p-4 bg-slate-900/60 border border-slate-800 rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-amber-500/20 text-amber-500 rounded-lg">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white mb-1">Recherche par modèle</h4>
                                <p class="text-sm text-slate-400">Entrez marque, modèle et année</p>
                            </div>
                        </div>

                        <div class="flex gap-4 p-4 bg-slate-900/60 border border-slate-800 rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-amber-500/20 text-amber-500 rounded-lg">
                                <i data-lucide="scan" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white mb-1">Numéro VIN</h4>
                                <p class="text-sm text-slate-400">Précision maximale avec le châssis</p>
                            </div>
                        </div>

                        <div class="flex gap-4 p-4 bg-slate-900/60 border border-slate-800 rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-amber-500/20 text-amber-500 rounded-lg">
                                <i data-lucide="repeat" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white mb-1">Échange possible</h4>
                                <p class="text-sm text-slate-400">Valorisez vos pièces usagées</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('parts.index') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-amber-500 text-slate-950 font-semibold rounded-lg hover:bg-amber-400 transition-colors shadow-lg">
                        Rechercher des pièces
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6">Recherche rapide</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Marque du véhicule</label>
                            <select class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-white rounded-lg focus:border-amber-500 focus:outline-none">
                                <option>Sélectionnez...</option>
                                <option>Toyota</option>
                                <option>Mercedes-Benz</option>
                                <option>BMW</option>
                                <option>Volkswagen</option>
                                <option>Peugeot</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Modèle</label>
                            <input type="text" placeholder="Ex: Corolla, C-Class..." 
                                   class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-white rounded-lg focus:border-amber-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Année</label>
                            <input type="number" placeholder="2020" 
                                   class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-white rounded-lg focus:border-amber-500 focus:outline-none">
                        </div>

                        <button class="w-full py-3 bg-amber-500 text-slate-950 font-semibold rounded-lg hover:bg-amber-400 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            Lancer la recherche
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Revision Service --}}
    <section class="py-16 bg-slate-950">
        <div class="container px-4 mx-auto max-w-7xl">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-bold text-white mb-3">Service de Révision</h2>
                <p class="text-slate-400">Diagnostic • Devis • Suivi en temps réel</p>
            </div>

            <div class="grid gap-6 md:grid-cols-4">
                @php
                    $steps = [
                        ['icon' => 'file-text', 'title' => 'Demande', 'desc' => 'Remplissez le formulaire en ligne'],
                        ['icon' => 'stethoscope', 'title' => 'Diagnostic', 'desc' => 'Analyse complète du véhicule'],
                        ['icon' => 'calculator', 'title' => 'Devis', 'desc' => 'Tarifs transparents détaillés'],
                        ['icon' => 'check-circle', 'title' => 'Suivi', 'desc' => 'État d\'avancement en direct'],
                    ];
                @endphp

                @foreach($steps as $index => $step)
                <div class="relative">
                    <div class="absolute -top-3 left-4 w-8 h-8 bg-amber-500 text-slate-950 rounded-full flex items-center justify-center font-bold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="pt-6 p-5 bg-slate-900/60 border border-slate-800 rounded-xl">
                        <div class="mb-4 inline-flex p-2.5 bg-amber-500/10 text-amber-500 rounded-lg">
                            <i data-lucide="{{ $step['icon'] }}" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-semibold text-white mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-slate-400">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('revisions.create') }}" class="inline-flex items-center px-6 py-3 bg-amber-500 text-slate-950 font-semibold rounded-lg hover:bg-amber-400 transition-colors">
                    Demander une révision
                </a>
            </div>
        </div>
    </section>

    {{-- Trust Section --}}
    <section class="py-16 bg-slate-900/30">
        <div class="container px-4 mx-auto max-w-7xl">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-bold text-white mb-3">Pourquoi Nous Choisir ?</h2>
                <p class="text-slate-400">Transparence et professionnalisme à chaque étape</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $points = [
                        ['icon' => 'map', 'title' => 'Processus Transparent', 'desc' => 'Suivi en temps réel de votre commande'],
                        ['icon' => 'hash', 'title' => 'Références Uniques', 'desc' => 'Chaque commande a un ID unique'],
                        ['icon' => 'shield', 'title' => 'Paiements Sécurisés', 'desc' => 'Plateformes certifiées et cryptées'],
                        ['icon' => 'users', 'title' => 'Équipe Disponible', 'desc' => 'Support client réactif et professionnel'],
                        ['icon' => 'file-check', 'title' => 'Documentation Claire', 'desc' => 'Tous les documents fournis'],
                        ['icon' => 'trending-up', 'title' => 'Meilleurs Prix', 'desc' => 'Tarifs compétitifs garantis'],
                    ];
                @endphp

                @foreach($points as $point)
                <div class="p-5 bg-slate-900/60 border border-slate-800 rounded-xl hover:border-amber-500/30 transition-colors">
                    <div class="mb-3 inline-flex p-2.5 bg-amber-500/10 text-amber-500 rounded-lg">
                        <i data-lucide="{{ $point['icon'] }}" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-semibold text-white mb-2">{{ $point['title'] }}</h3>
                    <p class="text-sm text-slate-400">{{ $point['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Simple Tracking Preview --}}
    <section class="py-16 bg-slate-950">
        <div class="container px-4 mx-auto max-w-7xl">
            <div class="grid gap-12 lg:grid-cols-2 items-center">
                <div class="order-2 lg:order-1">
                    <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-8 relative overflow-hidden">
                        {{-- Decorative elements --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl"></div>
                        
                        <div class="relative z-10">
                            <div class="bg-slate-950 border border-slate-800 rounded-lg p-6 shadow-xl mb-6">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-10 h-10 bg-amber-500/20 rounded-full flex items-center justify-center text-amber-500">
                                        <i data-lucide="package-search" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-400">Numéro de Suivi</div>
                                        <div class="text-xl font-mono font-bold text-white tracking-wider">CAR-2024-X8Y9</div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="text-white">État de la commande</span>
                                            <span class="text-amber-500 font-bold">En transit</span>
                                        </div>
                                        <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-amber-500 w-2/3"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-xs text-slate-500">
                                        <span>Départ: Hambourg</span>
                                        <span>Arrivée: Lomé (Est. 12 Jours)</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('tracking.index') }}" class="block w-full py-3 text-center bg-amber-500 text-slate-950 font-bold rounded-lg hover:bg-amber-400 transition-colors">
                                Vérifier mon statut
                            </a>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2">
                    <h2 class="text-3xl font-bold text-white mb-4">Sérénité Totale, Sans Inscription</h2>
                    <p class="text-lg text-slate-300 mb-6">
                        Pas besoin de créer de compte. À chaque commande, vous recevez un code de suivi unique pour suivre votre véhicule ou vos pièces en temps réel.
                    </p>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3 text-slate-300">
                            <div class="mt-1 bg-amber-500/10 p-1 rounded">
                                <i data-lucide="shield-check" class="w-4 h-4 text-amber-500"></i>
                            </div>
                            <div>
                                <strong class="text-white block">Accès Sécurisé</strong>
                                Votre numéro de suivi est votre clé personnelle.
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-slate-300">
                            <div class="mt-1 bg-amber-500/10 p-1 rounded">
                                <i data-lucide="zap" class="w-4 h-4 text-amber-500"></i>
                            </div>
                            <div>
                                <strong class="text-white block">Instantané</strong>
                                Aucune procédure d'inscription longue et fastidieuse.
                            </div>
                        </li>
                        <li class="flex items-start gap-3 text-slate-300">
                            <div class="mt-1 bg-amber-500/10 p-1 rounded">
                                <i data-lucide="bell" class="w-4 h-4 text-amber-500"></i>
                            </div>
                            <div>
                                <strong class="text-white block">Notifications</strong>
                                Mises à jour en temps réel sur l'avancement.
                            </div>
                        </li>
                    </ul>

                    <a href="{{ route('tracking.index') }}" class="inline-flex items-center text-amber-500 font-semibold hover:text-amber-400 transition-colors">
                        Accéder au suivi
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-20 bg-gradient-to-br from-amber-500/10 to-slate-950">
        <div class="container px-4 mx-auto max-w-4xl text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Prêt à Commander ?</h2>
            <p class="text-xl text-slate-300 mb-8">
                Trouvez votre véhicule idéal ou la pièce qu'il vous faut dès maintenant.
            </p>

            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('cars.index') }}" class="inline-flex items-center px-8 py-4 bg-amber-500 text-slate-950 font-semibold rounded-lg hover:bg-amber-400 transition-colors shadow-lg shadow-amber-500/20">
                    <i data-lucide="car" class="w-5 h-5 mr-2"></i>
                    Explorer les véhicules
                </a>
                
                <a href="{{ route('tracking.index') }}" class="inline-flex items-center px-8 py-4 border-2 border-amber-500/50 text-white font-semibold rounded-lg hover:bg-amber-500/10 transition-colors">
                    <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                    Suivre une commande
                </a>
            </div>

            <div class="flex gap-6 justify-center mt-8 text-sm text-slate-400">
                <span class="flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                    Devis gratuit
                </span>
                <span class="flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                    Support 24/7
                </span>
                <span class="flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                    Garantie qualité
                </span>
            </div>
        </div>
    </section>
</div>
@endsection
