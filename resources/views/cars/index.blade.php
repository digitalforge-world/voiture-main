@extends('layouts.app')

@section('title', 'Catalogue des Véhicules')

@section('content')
<div class="bg-white dark:bg-slate-950 min-h-screen transition-colors duration-500">
    <!-- Header Section -->
    {{-- <div class="relative py-20 overflow-hidden bg-slate-50 dark:bg-slate-900/50 transition-colors">
        <div class="absolute inset-0 bg-gradient-to-b from-amber-500/5 to-transparent"></div>
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="max-w-3xl">
                <nav class="flex mb-8 space-x-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                    <a href="{{ url('/') }}" class="hover:text-amber-500 transition">Accueil</a>
                    <span>/</span>
                    <span class="text-amber-500">Stock & Importation</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white lg:text-4xl transition-colors">Trouvez Votre <span class="text-amber-500">Pépite</span>.</h1>
                <p class="mt-4 text-base text-slate-600 dark:text-slate-400 transition-colors">Explorez notre sélection de véhicules premium disponibles à l'importation directe.</p>
            </div>
        </div>
    </div> --}}

    <div class="container px-4 py-8 mx-auto lg:px-8">
        {{-- Mobile Search Trigger (Hidden on Desktop) --}}
        <div class="lg:hidden mb-8">
            <div onclick="document.getElementById('search-modal').classList.remove('hidden'); document.body.style.overflow = 'hidden';" 
                 class="relative cursor-pointer group">
                <div class="w-full py-4 pl-12 pr-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm text-slate-500 transition-all group-hover:border-amber-500 shadow-sm flex items-center">
                    Rechercher une marque, un modèle...
                </div>
                <i data-lucide="search" class="absolute left-4 top-4 w-5 h-5 text-amber-500"></i>
                <div class="absolute right-4 top-3.5 bg-slate-200 dark:bg-slate-800 p-1.5 rounded-lg">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4 text-slate-500"></i>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-12 lg:flex-row">
            <!-- Sidebar Filters (Desktop Only) -->
            <aside class="hidden lg:block w-72 shrink-0">
                <form action="{{ route('cars.index') }}" method="GET" class="sticky top-28 space-y-6">
                    <div class="p-6 border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm dark:shadow-none transition-all">
                        <h3 class="flex items-center gap-2 mb-6 text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tighter transition-colors">
                            <i data-lucide="sliders-horizontal" class="w-5 h-5 text-amber-500"></i>
                            Filtres
                        </h3>

                        <div class="space-y-6">
                            <!-- Search -->
                            <div class="space-y-2">
                                <label class="text-xs font-black tracking-widest uppercase text-slate-500">Recherche</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Modèle, marque..." class="w-full py-3 pl-10 pr-4 text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600">
                                    <i data-lucide="search" class="absolute left-3.5 top-3.5 w-4 h-4 text-slate-400 dark:text-slate-600 transition-colors"></i>
                                </div>
                            </div>

                            <!-- Brand -->
                            <div class="space-y-2">
                                <label class="text-xs font-black tracking-widest uppercase text-slate-500">Marque</label>
                                <select name="marque" class="w-full py-3 px-4 text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-slate-900 dark:text-white">
                                    <option value="">Toutes les marques</option>
                                    @foreach($marques as $marque)
                                        <option value="{{ $marque }}" class="bg-white dark:bg-slate-950" {{ request('marque') == $marque ? 'selected' : '' }}>{{ $marque }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="space-y-2">
                                <label class="text-xs font-black tracking-widest uppercase text-slate-500">Budget Max</label>
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-grow">
                                        <input type="number" name="prix_max" value="{{ request('prix_max') }}" placeholder="Max" class="w-full py-3 px-4 text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-600">
                                        <span class="absolute right-3.5 top-3.5 text-[10px] font-bold text-amber-500">FCFA</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 text-sm font-bold text-slate-950 bg-amber-500 rounded-xl hover:bg-amber-400 transition shadow-lg shadow-amber-900/10"> Appliquer le filtre </button>
                            
                            @if(request()->anyFilled(['search', 'marque', 'prix_max']))
                                <a href="{{ route('cars.index') }}" class="block text-center text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition"> Réinitialiser </a>
                            @endif
                        </div>
                    </div>
                </form>
            </aside>

            {{-- Search Modal (Mobile Only) --}}
            <div id="search-modal" class="fixed inset-0 z-[100] hidden lg:hidden">
                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" onclick="document.getElementById('search-modal').classList.add('hidden'); document.body.style.overflow = 'auto';"></div>
                <div class="absolute inset-x-0 bottom-0 max-h-[90vh] bg-white dark:bg-slate-900 rounded-t-[2.5rem] p-8 pb-12 overflow-y-auto animate-in slide-in-from-bottom duration-300 ring-1 ring-white/10">
                    <div class="flex justify-between items-center mb-8">
                        <div class="space-y-1">
                            <h2 class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white uppercase leading-none">Recherche</h2>
                            <p class="text-xs font-bold text-slate-500 tracking-widest uppercase">Affinez vos résultats</p>
                        </div>
                        <button onclick="document.getElementById('search-modal').classList.add('hidden'); document.body.style.overflow = 'auto';" 
                                class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-white rounded-2xl hover:bg-amber-500 hover:text-slate-950 transition-all">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('cars.index') }}" method="GET" class="space-y-8">
                        <div class="space-y-6">
                            <!-- Search -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black tracking-[0.2em] uppercase text-slate-400">Par mots-clés</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ex: BMW M4, Toyota..." class="w-full py-4 pl-12 pr-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-slate-900 dark:text-white text-base">
                                    <i data-lucide="search" class="absolute left-4 top-4.5 w-5 h-5 text-slate-400"></i>
                                </div>
                            </div>

                            <!-- Brand -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black tracking-[0.2em] uppercase text-slate-400">Marque favorite</label>
                                <select name="marque" class="w-full py-4 px-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-slate-900 dark:text-white text-base">
                                    <option value="">Toutes les marques</option>
                                    @foreach($marques as $marque)
                                        <option value="{{ $marque }}" {{ request('marque') == $marque ? 'selected' : '' }}>{{ $marque }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price -->
                            <div class="space-y-3">
                                <label class="text-[10px] font-black tracking-[0.2em] uppercase text-slate-400">Budget Maximum</label>
                                <div class="relative">
                                    <input type="number" name="prix_max" value="{{ request('prix_max') }}" placeholder="Ex: 15000000" class="w-full py-4 px-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-slate-900 dark:text-white text-base">
                                    <span class="absolute right-4 top-4 text-xs font-black text-amber-500">FCFA</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 space-y-4">
                            <button type="submit" class="w-full py-5 bg-amber-500 text-slate-950 text-sm font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-amber-500/20 hover:bg-amber-400 transition-all">
                                Afficher les résultats
                            </button>
                            @if(request()->anyFilled(['search', 'marque', 'prix_max']))
                                <a href="{{ route('cars.index') }}" class="block text-center py-4 text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Effacer les filtres
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <!-- Main Listing Grid -->
            <div class="flex-grow min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        Total: <span class="text-slate-900 dark:text-white font-black">{{ $voitures->count() }}</span> véhicules premium
                    </div>
                    <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900/50 p-1.5 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase ml-2">Trier par</span>
                        <select class="py-1.5 px-3 text-[10px] font-bold bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-900 dark:text-white focus:ring-0 focus:border-amber-500 cursor-pointer appearance-none transition-all">
                            <option>Plus récents</option>
                            <option>Prix croissant</option>
                            <option>Prix décroissant</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-6 lg:gap-8">
                    @forelse($voitures as $car)
                        <div class="group relative bg-white dark:bg-slate-950 border border-slate-200 dark:border-white/5 rounded-xl sm:rounded-2xl overflow-hidden hover:border-amber-500/30 transition-all duration-700 shadow-sm hover:shadow-2xl hover:shadow-amber-500/5 flex flex-col h-full">
                            {{-- Image Section --}}
                            <div class="relative aspect-[16/11] overflow-hidden bg-slate-100 dark:bg-slate-900">
                                <img src="{{ $car->photo_principale ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=800' }}" 
                                     alt="{{ $car->marque }} {{ $car->modele }}" 
                                     class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                                
                                {{-- Price Badge --}}
                                <div class="absolute bottom-2 left-2 sm:bottom-4 sm:left-4">
                                    <div class="px-2 py-1 sm:px-4 sm:py-2 bg-slate-950/80 backdrop-blur-xl rounded-lg sm:rounded-2xl border border-white/10 flex items-baseline gap-1">
                                        <span class="text-[10px] sm:text-lg font-black text-white tracking-tighter">{{ number_format($car->prix, 0, ',', ' ') }}</span>
                                        <span class="text-[6px] sm:text-[9px] font-black text-amber-500 uppercase">FCFA</span>
                                    </div>
                                </div>

                                {{-- Badges --}}
                                <div class="absolute top-2 left-2 sm:top-4 sm:left-4">
                                    <span class="px-1.5 py-0.5 sm:px-3 sm:py-1.5 bg-white/10 backdrop-blur-md border border-white/20 text-[8px] sm:text-[10px] font-black text-white rounded-lg sm:rounded-xl">
                                        {{ $car->annee }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content Section --}}
                            <div class="p-3 sm:p-6 flex-grow flex flex-col justify-between space-y-3 sm:space-y-6">
                                <div>
                                    <div class="flex items-center gap-1.5 mb-1 sm:mb-2">
                                        <span class="text-[7px] sm:text-[9px] font-black text-amber-500 uppercase tracking-[0.1em] sm:tracking-[0.2em]">{{ $car->marque }}</span>
                                        <div class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></div>
                                        <span class="text-[7px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-widest hidden sm:inline">{{ $car->transmission ?? 'AUTO' }}</span>
                                    </div>
                                    <h3 class="text-sm sm:text-2xl font-black text-slate-900 dark:text-white tracking-tighter leading-none group-hover:text-amber-500 transition-colors line-clamp-1">
                                        {{ $car->modele }}
                                    </h3>
                                    <div class="flex items-center gap-1.5 mt-2 text-slate-400">
                                        <i data-lucide="map-pin" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                                        <span class="text-[7px] sm:text-[10px] font-bold tracking-widest uppercase italic line-clamp-1">{{ $car->ville_origine }}</span>
                                    </div>
                                </div>

                                {{-- Technical Specs (Hidden on very small screens or simplified) --}}
                                <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-1.5 sm:p-3 rounded-lg sm:rounded-2xl border border-slate-100 dark:border-white/5">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="gauge" class="w-3 h-3 sm:w-4 sm:h-4 text-amber-500"></i>
                                            <span class="text-[8px] sm:text-xs font-black text-slate-800 dark:text-slate-200">{{ round($car->kilometrage/1000) }}k km</span>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-1.5 sm:p-3 rounded-lg sm:rounded-2xl border border-slate-100 dark:border-white/5">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="fuel" class="w-3 h-3 sm:w-4 sm:h-4 text-amber-500"></i>
                                            <span class="text-[8px] sm:text-xs font-black text-slate-800 dark:text-slate-200 truncate">{{ $car->carburant }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 sm:pt-2">
                                    <a href="{{ route('cars.show', $car) }}" class="flex items-center justify-center py-2 sm:py-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg sm:rounded-2xl text-[8px] sm:text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                        Voir
                                    </a>
                                    <button type="button" 
                                            onclick="openOrderModal({
                                                id: {{ $car->id }},
                                                marque: '{{ addslashes($car->marque) }}',
                                                modele: '{{ addslashes($car->modele) }}',
                                                prix: {{ $car->prix }},
                                                photo: '{{ $car->photo_principale ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=800' }}',
                                                slug: '{{ $car->slug }}'
                                            })"
                                            class="hidden sm:block w-full py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-amber-500/20 transition-all active:scale-95">
                                        Commander
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                            <i data-lucide="car-front" class="w-12 h-12 mx-auto mb-4 text-slate-200 dark:text-slate-800"></i>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Aucun véhicule trouvé</h3>
                            <p class="text-slate-500 mt-2 text-xs">Essayez de modifier vos filtres pour trouver votre bonheur.</p>
                            <a href="{{ route('cars.index') }}" class="inline-block mt-8 px-6 py-2.5 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-400 transition">Voir tout le stock</a>
                        </div>
                    @endforelse
                </div>
                
                @if($voitures->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="bg-slate-900/50 border border-white/5 rounded-[2rem] p-2 backdrop-blur-sm">
                            {{ $voitures->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
