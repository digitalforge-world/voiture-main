@extends('layouts.app')

@section('title', 'Catalogue des Véhicules')

@section('content')
<div class="bg-white dark:bg-slate-950 min-h-screen transition-colors duration-500">
    <!-- Header Section -->
    <div class="relative py-20 overflow-hidden bg-slate-50 dark:bg-slate-900/50 transition-colors">
        <div class="absolute inset-0 bg-gradient-to-b from-amber-500/5 to-transparent"></div>
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="max-w-3xl">
                <nav class="flex mb-8 space-x-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                    <a href="{{ url('/') }}" class="hover:text-amber-500 transition">Accueil</a>
                    <span>/</span>
                    <span class="text-amber-500">Stock & Importation</span>
                </nav>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white lg:text-5xl transition-colors">Trouvez Votre <span class="text-amber-500">Pépite</span>.</h1>
                <p class="mt-4 text-lg text-slate-600 dark:text-slate-400 transition-colors">Explorez notre sélection de véhicules premium disponibles à l'importation directe.</p>
            </div>
        </div>
    </div>

    <div class="container px-4 py-12 mx-auto lg:px-8">
        <div class="flex flex-col gap-12 lg:flex-row">
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-80 shrink-0">
                <form action="{{ route('cars.index') }}" method="GET" class="sticky top-28 space-y-8">
                    <div class="p-8 border bg-white dark:bg-slate-900/50 border-slate-200 dark:border-slate-800 rounded-[2rem] shadow-sm dark:shadow-none transition-all">
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
                                        <span class="absolute right-3.5 top-3.5 text-xs font-bold text-amber-500">€</span>
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

            <!-- Main Listing Grid -->
            <div class="flex-grow">
                <div class="flex items-center justify-between mb-8">
                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">
                        <span class="text-slate-900 dark:text-white font-bold transition-colors">{{ $voitures->count() }}</span> véhicules trouvés
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold tracking-widest uppercase text-slate-500">Trier par:</span>
                        <select class="py-1 px-3 text-xs bg-transparent border-none text-white focus:ring-0 cursor-pointer hover:text-amber-500 transition">
                            <option>Plus récents</option>
                            <option>Prix croissant</option>
                            <option>Prix décroissant</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    @forelse($voitures as $car)
                        <div class="group bg-slate-950 border border-slate-800 rounded-[2rem] overflow-hidden hover:border-amber-500/30 transition duration-500 flex flex-col shadow-xl">
                            <div class="relative overflow-hidden aspect-[4/3]">
                                <img src="{{ $car->image ?? 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $car->marque }} {{ $car->modele }}" class="object-cover w-full h-full transition duration-700 group-hover:scale-105">
                                <div class="absolute p-4 flex gap-2 top-0 left-0">
                                    <span class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-amber-500 text-slate-950 rounded-full shadow-lg">{{ $car->etat }}</span>
                                </div>
                                <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-slate-950/90 to-transparent">
                                    <div class="text-3xl font-black text-white">
                                        {{ number_format($car->prix, 0, ',', ' ') }} <span class="text-xs text-amber-500">€</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-8 flex-grow flex flex-col bg-white dark:bg-slate-950 transition-colors">
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition tracking-tight">{{ $car->marque }} {{ $car->modele }}</h3>
                                    <div class="flex items-center gap-2 mt-2 text-slate-500 text-sm font-medium">
                                        <div class="flex items-center gap-1.5 px-2 py-0.5 bg-slate-50 dark:bg-white/5 rounded-md border border-slate-200 dark:border-white/5 uppercase tracking-tighter text-[10px] transition-colors">{{ $car->annee }}</div>
                                        <span class="w-1 h-1 bg-slate-300 dark:bg-slate-700 rounded-full transition-colors"></span>
                                        <span class="transition-colors">{{ $car->ville_origine }}, {{ $car->pays_origine }}</span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 pb-6 mt-4 border-b border-slate-100 dark:border-white/5 transition-colors">
                                    <div class="flex items-center gap-2.5 text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">
                                        <div class="p-2 bg-slate-50 dark:bg-slate-900 rounded-lg transition-colors"><i data-lucide="gauge-circle" class="w-4 h-4 text-amber-500"></i></div>
                                        {{ number_format($car->kilometrage, 0, ',', ' ') }} km
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">
                                        <div class="p-2 bg-slate-50 dark:bg-slate-900 rounded-lg transition-colors"><i data-lucide="fuel" class="w-4 h-4 text-amber-500"></i></div>
                                        {{ $car->carburant }}
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">
                                        <div class="p-2 bg-slate-50 dark:bg-slate-900 rounded-lg transition-colors"><i data-lucide="settings-2" class="w-4 h-4 text-amber-500"></i></div>
                                        {{ $car->transmission }}
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">
                                        <div class="p-2 bg-slate-50 dark:bg-slate-900 rounded-lg transition-colors"><i data-lucide="ship" class="w-4 h-4 text-amber-500"></i></div>
                                        {{ $car->portRecommande->nom ?? 'Port Togo' }}
                                    </div>
                                </div>

                                <div class="mt-auto pt-6 flex gap-3">
                                    <a href="{{ route('cars.show', $car->id) }}" class="flex-grow flex items-center justify-center py-4 text-sm font-bold transition border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-700">
                                        Détails
                                    </a>
                                    <form action="{{ route('cars.order', $car->id) }}" method="POST" class="flex-grow">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 text-sm font-bold bg-amber-500 text-slate-950 rounded-xl hover:bg-amber-400 transition shadow-lg shadow-amber-900/10">
                                            Acheter <i data-lucide="zap" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-32 text-center rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-900 transition-colors">
                            <i data-lucide="car-front" class="w-16 h-16 mx-auto mb-4 text-slate-200 dark:text-slate-800 transition-colors"></i>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Ours ! Aucun trésor ici.</h3>
                            <p class="text-slate-500 mt-2 transition-colors">Essayez de modifier vos filtres pour trouver votre bonheur.</p>
                            <a href="{{ route('cars.index') }}" class="inline-block mt-8 px-6 py-2.5 bg-amber-500 text-slate-950 font-bold rounded-xl hover:bg-amber-400 transition">Voir tout le stock</a>
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
