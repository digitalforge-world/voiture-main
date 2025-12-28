@extends('layouts.app')

@section('title', 'Résultats de compatibilité')

@section('content')
<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-500">
    <!-- Hero/Header -->
    <div class="relative py-24 overflow-hidden border-b border-slate-100 dark:border-slate-900 transition-colors">
        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 via-slate-100/40 dark:via-slate-900/40 to-white dark:to-slate-950 transition-colors"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/3 transition-colors"></div>
        
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="breadcrumb mb-8">
                <a href="{{ route('parts.index') }}" class="inline-flex items-center text-xs font-black tracking-widest uppercase text-slate-500 dark:text-slate-400 hover:text-amber-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Retour au catalogue
                </a>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="p-2 bg-amber-500 rounded-lg text-slate-950 shadow-lg shadow-amber-900/20">
                            <i data-lucide="check-check" class="w-6 h-6"></i>
                        </span>
                        <span class="text-xs font-black tracking-[0.2em] uppercase text-amber-500">Résultats de Compatibilité</span>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 dark:text-white lg:text-5xl transition-colors">
                        Pièces <span class="italic font-serif text-amber-500">Compatibles</span>
                    </h1>
                    <p class="mt-4 text-slate-600 dark:text-slate-400 transition-colors">
                        Résultats pour : <strong class="text-slate-900 dark:text-white transition-colors">{{ $search['marque'] }} {{ $search['modele'] }} ({{ $search['annee'] }})</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container px-4 py-16 mx-auto lg:px-8">
        @if($pieces->count() > 0)
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($pieces as $piece)
                    <div class="group relative bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-900 rounded-[2rem] p-4 hover:border-amber-500/30 transition duration-500 flex flex-col h-full shadow-sm dark:shadow-lg">
                        <div class="relative overflow-hidden aspect-square bg-slate-50 dark:bg-slate-950 rounded-[1.5rem] mb-6 flex items-center justify-center transition-colors">
                            @if($piece->image)
                                <img src="{{ $piece->image }}" alt="{{ $piece->nom }}" class="object-cover w-full h-full transition duration-700 group-hover:scale-110 opacity-80 group-hover:opacity-100">
                            @else
                                <i data-lucide="package" class="w-16 h-16 text-slate-200 dark:text-slate-800 stroke-1 group-hover:scale-110 transition duration-700 transition-colors"></i>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 to-transparent"></div>
                            <div class="absolute p-3 top-2 right-2">
                                <span class="px-3 py-1 text-[9px] font-black tracking-widest uppercase bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 rounded-full">100% Compatible</span>
                            </div>
                        </div>

                        <div class="px-2 flex-grow flex flex-col">
                            <div class="flex items-center gap-2 mb-2 text-[10px] font-mono text-slate-400">
                                Ref: <span class="bg-slate-50 dark:bg-white/5 px-2 py-0.5 rounded text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/5 transition-colors">{{ $piece->reference }}</span>
                            </div>
                            <h3 class="mb-4 text-lg font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition tracking-tight leading-tight transition-colors">{{ $piece->nom }}</h3>
                            
                            <div class="mt-auto pt-4 border-t border-slate-100 dark:border-slate-800 transition-colors">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-xl font-black text-slate-900 dark:text-white transition-colors">{{ number_format($piece->prix, 0, ',', ' ') }} <span class="text-xs text-amber-500 font-bold">€</span></span>
                                    <form action="{{ route('parts.buy', $piece->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-3 bg-slate-900 dark:bg-white text-white dark:text-slate-950 rounded-xl hover:bg-amber-500 dark:hover:bg-amber-500 hover:text-white dark:hover:text-white transition-all shadow-lg active:scale-95">
                                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="max-w-2xl mx-auto py-32 text-center border-2 border-dashed border-slate-200 dark:border-slate-900 rounded-[3rem] transition-colors">
                <i data-lucide="search-x" class="w-20 h-20 mx-auto mb-6 text-slate-200 dark:text-slate-800 transition-colors"></i>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-4 uppercase tracking-tighter transition-colors">Aucune pièce exacte trouvée</h2>
                <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-10 transition-colors">Nos techniciens peuvent vous aider à trouver une pièce équivalente ou compatible avec votre véhicule.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#" class="w-full sm:w-auto px-8 py-4 bg-amber-500 text-slate-950 font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-amber-400 transition-all shadow-xl shadow-amber-900/10">Contacter un expert</a>
                    <a href="{{ route('parts.index') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white font-black uppercase tracking-widest text-xs rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-amber-500 transition-all">Parcourir tout le catalogue</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Printing support */
    @media print {
        .bg-white, .dark\:bg-slate-950 { background-color: white !important; }
        .text-slate-900, .dark\:text-white { color: black !important; }
        .shadow-sm, .shadow-lg, .shadow-xl, .shadow-2xl { shadow: none !important; }
        .hero-section { display: none !important; }
    }
</style>
@endsection
