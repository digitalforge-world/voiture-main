@extends('layouts.admin')
@section('title', 'Résultats de recherche - ' . $query)
@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}" class="p-2 bg-slate-100 dark:bg-slate-900 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 transition">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i>
        </a>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white uppercase italic">
            Résultats pour "{{ $query }}"
        </h1>
    </div>

    @if($results['orders']->isEmpty() && $results['cars']->isEmpty() && $results['parts']->isEmpty() && $results['users']->isEmpty())
        <div class="text-center py-20">
            <div class="inline-flex p-6 rounded-full bg-slate-50 dark:bg-slate-900 mb-4">
                <i data-lucide="search-x" class="w-10 h-10 text-slate-400"></i>
            </div>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Aucun résultat trouvé dans la base de données.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Orders -->
        @if($results['orders']->isNotEmpty())
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-3xl p-6 shadow-sm">
            <h3 class="text-xs font-black uppercase text-slate-400 mb-6 tracking-widest flex items-center gap-2 border-b border-slate-100 dark:border-white/5 pb-2">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Commandes Véhicules
            </h3>
            <div class="space-y-3">
                @foreach($results['orders'] as $order)
                <a href="{{ route('admin.orders-cars.index', ['search' => $order->reference]) }}" class="block p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-white/5 hover:border-amber-500/50 transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-sm font-black text-slate-900 dark:text-white group-hover:text-amber-500 transition uppercase italic">{{ $order->reference }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ $order->client_nom }}</div>
                        </div>
                        <div class="flex flex-col items-end">
                             <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">{{ $order->statut }}</span>
                             <span class="text-xs font-mono text-slate-600 dark:text-slate-400 mt-1 font-bold">{{ number_format($order->montant_total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Cars -->
        @if($results['cars']->isNotEmpty())
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-3xl p-6 shadow-sm">
             <h3 class="text-xs font-black uppercase text-slate-400 mb-6 tracking-widest flex items-center gap-2 border-b border-slate-100 dark:border-white/5 pb-2">
                <i data-lucide="car-front" class="w-4 h-4"></i> Véhicules
            </h3>
            <div class="space-y-3">
                @foreach($results['cars'] as $car)
                <a href="{{ route('admin.cars.index', ['search' => $car->numero_chassis]) }}" class="block p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-white/5 hover:border-amber-500/50 transition group">
                     <div class="flex gap-4 items-center">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl overflow-hidden flex items-center justify-center border border-slate-200 dark:border-white/5">
                             @if($car->photo_principale)
                             <img src="{{ $car->photo_principale }}" class="w-full h-full object-cover">
                             @else
                             <i data-lucide="car" class="w-5 h-5 text-slate-300"></i>
                             @endif
                        </div>
                        <div>
                            <div class="text-sm font-black text-slate-900 dark:text-white group-hover:text-amber-500 transition italic">{{ $car->marque }} {{ $car->modele }}</div>
                            <div class="text-[10px] text-slate-500 mt-1 font-mono uppercase">CHASSIS: {{ $car->numero_chassis }}</div>
                        </div>
                     </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Parts -->
        @if($results['parts']->isNotEmpty())
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-3xl p-6 shadow-sm">
             <h3 class="text-xs font-black uppercase text-slate-400 mb-6 tracking-widest flex items-center gap-2 border-b border-slate-100 dark:border-white/5 pb-2">
                <i data-lucide="package" class="w-4 h-4"></i> Catalogue Pièces
            </h3>
            <div class="space-y-3">
                @foreach($results['parts'] as $part)
                <a href="{{ route('admin.parts-inventory.index', ['search' => $part->reference]) }}" class="block p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-white/5 hover:border-amber-500/50 transition group">
                     <div class="flex justify-between items-center">
                        <div>
                             <div class="text-sm font-black text-slate-900 dark:text-white group-hover:text-amber-500 transition italic">{{ $part->nom }}</div>
                             <div class="text-[10px] text-slate-500 mt-1 uppercase tracking-wider font-bold">REF: {{ $part->reference }}</div>
                        </div>
                        <div class="px-2 py-1 bg-slate-200 dark:bg-slate-800 rounded text-[10px] font-bold text-slate-600 dark:text-slate-400">Stock: {{ $part->stock }}</div>
                     </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Users -->
        @if($results['users']->isNotEmpty())
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-3xl p-6 shadow-sm">
             <h3 class="text-xs font-black uppercase text-slate-400 mb-6 tracking-widest flex items-center gap-2 border-b border-slate-100 dark:border-white/5 pb-2">
                <i data-lucide="users" class="w-4 h-4"></i> Utilisateurs
            </h3>
            <div class="space-y-3">
                @foreach($results['users'] as $user)
                <a href="{{ route('admin.users.index', ['search' => $user->email]) }}" class="block p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-white/5 hover:border-amber-500/50 transition group">
                     <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-500 border-2 border-white dark:border-slate-700">
                             {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                        </div>
                        <div>
                             <div class="text-sm font-black text-slate-900 dark:text-white group-hover:text-amber-500 transition italic">{{ $user->prenom }} {{ $user->nom }}</div>
                             <div class="text-[10px] text-slate-500 mt-1">{{ $user->email }}</div>
                        </div>
                     </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
