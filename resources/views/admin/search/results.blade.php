@extends('layouts.admin')
@section('title', 'Recherche — ' . $query)
@section('content')

@php
 $totalResults = $results['orders']->count() + $results['cars']->count() + $results['parts']->count() + $results['users']->count() + $results['rentals']->count() + $results['part_orders']->count() + $results['revisions']->count();

 $categories = [
  ['key' => 'all', 'label' => 'Tout', 'count' => $totalResults, 'icon' => 'layers'],
  ['key' => 'orders', 'label' => 'Commandes', 'count' => $results['orders']->count(), 'icon' => 'shopping-bag'],
  ['key' => 'cars', 'label' => 'Véhicules', 'count' => $results['cars']->count(), 'icon' => 'car-front'],
  ['key' => 'rentals', 'label' => 'Locations', 'count' => $results['rentals']->count(), 'icon' => 'calendar-check'],
  ['key' => 'parts', 'label' => 'Pièces', 'count' => $results['parts']->count(), 'icon' => 'package'],
  ['key' => 'part_orders', 'label' => 'Cmd. Pièces', 'count' => $results['part_orders']->count(), 'icon' => 'package-search'],
  ['key' => 'users', 'label' => 'Utilisateurs', 'count' => $results['users']->count(), 'icon' => 'users'],
  ['key' => 'revisions', 'label' => 'Révisions', 'count' => $results['revisions']->count(), 'icon' => 'wrench'],
 ];
@endphp

<div class="max-w-5xl mx-auto">

 {{-- ═══════════ SEARCH HEADER ═══════════ --}}
 <div class="mb-8">
  <div class="flex items-center gap-3 mb-6">
   <a href="{{ url()->previous() }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4 text-slate-500"></i>
   </a>
   <div>
    <h1 class="text-lg font-semibold text-slate-900 dark:text-white leading-tight">Résultats de recherche</h1>
    <p class="text-xs text-slate-500 mt-0.5">
     @if($totalResults > 0)
      <span class="text-amber-500 font-semibold">{{ $totalResults }} résultat{{ $totalResults > 1 ? 's' : '' }}</span> trouvé{{ $totalResults > 1 ? 's' : '' }} pour
     @else
      Aucun résultat pour
     @endif
     <span class="font-medium text-slate-700 dark:text-slate-300">« {{ $query }} »</span>
    </p>
   </div>
  </div>

  {{-- Inline search refinement --}}
  <form action="{{ route('admin.global-search') }}" method="GET" class="relative">
   <div class="flex items-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-3 shadow-sm focus-within:border-amber-500/60 focus-within:shadow-amber-500/5 focus-within:shadow-lg transition-all duration-300">
    <i data-lucide="search" class="w-5 h-5 text-slate-400 mr-3 flex-shrink-0"></i>
    <input type="text" name="q" value="{{ $query }}" placeholder="Affiner votre recherche..." class="flex-1 bg-transparent border-none text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:ring-0 focus:outline-none">
    <kbd class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] font-medium text-slate-400 border border-slate-200 dark:border-slate-700 ml-2">Entrée</kbd>
   </div>
  </form>
 </div>

 {{-- ═══════════ CATEGORY FILTER TABS ═══════════ --}}
 <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1 custom-scrollbar">
  @foreach($categories as $cat)
   @if($cat['count'] > 0 || $cat['key'] === 'all')
   <button onclick="filterCategory('{{ $cat['key'] }}')"
    data-filter="{{ $cat['key'] }}"
    class="search-filter-btn flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-medium whitespace-nowrap transition-all duration-200
    {{ $cat['key'] === 'all' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
    <i data-lucide="{{ $cat['icon'] }}" class="w-3.5 h-3.5"></i>
    {{ $cat['label'] }}
    @if($cat['count'] > 0)
     <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-md text-[10px] font-bold {{ $cat['key'] === 'all' ? 'bg-slate-950/20 text-slate-950' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400' }}">{{ $cat['count'] }}</span>
    @endif
   </button>
   @endif
  @endforeach
 </div>

 {{-- ═══════════ EMPTY STATE ═══════════ --}}
 @if($totalResults === 0)
 <div class="text-center py-24">
  <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-slate-100 dark:bg-slate-800/60 mb-6">
   <i data-lucide="search-x" class="w-9 h-9 text-slate-300 dark:text-slate-600"></i>
  </div>
  <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-2">Aucun résultat</h2>
  <p class="text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">
   Nous n'avons rien trouvé correspondant à <span class="font-medium text-slate-700 dark:text-slate-300">« {{ $query }} »</span>. Essayez avec un terme différent.
  </p>
  <div class="flex items-center justify-center gap-3 mt-6">
   <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition">
    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Tableau de bord
   </a>
  </div>
 </div>
 @endif

 {{-- ═══════════ RESULTS LIST ═══════════ --}}
 @if($totalResults > 0)
 <div class="space-y-3">

  {{-- ── Commandes Véhicules ── --}}
  @foreach($results['orders'] as $order)
  <a href="{{ route('admin.orders-cars.index', ['search' => $order->reference]) }}" data-category="orders" class="search-result-item group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-amber-500/40 hover:shadow-md hover:shadow-amber-500/5 transition-all duration-200">
   <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
    <i data-lucide="shopping-bag" class="w-4.5 h-4.5 text-blue-500"></i>
   </div>
   <div class="flex-1 min-w-0">
    <div class="flex items-center gap-2 mb-0.5">
     <span class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-amber-500 transition truncate">{{ $order->reference }}</span>
     <span class="flex-shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase tracking-wide
      {{ $order->statut === 'en_attente' ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400' : ($order->statut === 'livre' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400') }}">
      {{ str_replace('_', ' ', $order->statut) }}
     </span>
    </div>
    <div class="text-xs text-slate-500 truncate">{{ $order->client_nom }} · {{ number_format($order->montant_total, 0, ',', ' ') }} FCFA</div>
   </div>
   <div class="flex-shrink-0 flex items-center gap-2">
    <span class="hidden sm:block px-2 py-1 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-[10px] font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">Commande</span>
    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-500 transition"></i>
   </div>
  </a>
  @endforeach

  {{-- ── Véhicules ── --}}
  @foreach($results['cars'] as $car)
  <a href="{{ route('admin.cars.index', ['search' => $car->numero_chassis]) }}" data-category="cars" class="search-result-item group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-amber-500/40 hover:shadow-md hover:shadow-amber-500/5 transition-all duration-200">
   <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 overflow-hidden flex items-center justify-center">
    @if($car->photo_principale)
     <img src="{{ $car->photo_principale }}" class="w-full h-full object-cover">
    @else
     <i data-lucide="car-front" class="w-4.5 h-4.5 text-slate-400"></i>
    @endif
   </div>
   <div class="flex-1 min-w-0">
    <div class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-amber-500 transition truncate mb-0.5">{{ $car->marque }} {{ $car->modele }}</div>
    <div class="text-xs text-slate-500 truncate font-mono">{{ $car->numero_chassis }}</div>
   </div>
   <div class="flex-shrink-0 flex items-center gap-2">
    <span class="hidden sm:block px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Stock</span>
    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-500 transition"></i>
   </div>
  </a>
  @endforeach

  {{-- ── Locations ── --}}
  @foreach($results['rentals'] as $rental)
  <a href="{{ route('admin.rentals.index', ['search' => $rental->reference]) }}" data-category="rentals" class="search-result-item group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-amber-500/40 hover:shadow-md hover:shadow-amber-500/5 transition-all duration-200">
   <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
    <i data-lucide="calendar-check" class="w-4.5 h-4.5 text-emerald-500"></i>
   </div>
   <div class="flex-1 min-w-0">
    <div class="flex items-center gap-2 mb-0.5">
     <span class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-amber-500 transition truncate">{{ $rental->reference ?? '#LOC-'.$rental->id }}</span>
     <span class="flex-shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase tracking-wide
      {{ $rental->statut === 'en_cours' ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400' : ($rental->statut === 'termine' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400') }}">
      {{ str_replace('_', ' ', $rental->statut) }}
     </span>
    </div>
    <div class="text-xs text-slate-500 truncate">{{ $rental->client_nom ?? ($rental->user->nom ?? 'Client') }} · {{ $rental->voiture->marque ?? '' }} {{ $rental->voiture->modele ?? '' }}</div>
   </div>
   <div class="flex-shrink-0 flex items-center gap-2">
    <span class="hidden sm:block px-2 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-[10px] font-medium text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Location</span>
    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-500 transition"></i>
   </div>
  </a>
  @endforeach

  {{-- ── Pièces Détachées ── --}}
  @foreach($results['parts'] as $part)
  <a href="{{ route('admin.parts-inventory.index', ['search' => $part->reference]) }}" data-category="parts" class="search-result-item group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-amber-500/40 hover:shadow-md hover:shadow-amber-500/5 transition-all duration-200">
   <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center">
    <i data-lucide="package" class="w-4.5 h-4.5 text-violet-500"></i>
   </div>
   <div class="flex-1 min-w-0">
    <div class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-amber-500 transition truncate mb-0.5">{{ $part->nom }}</div>
    <div class="text-xs text-slate-500 truncate">Réf: {{ $part->reference }} · Stock: {{ $part->stock }}</div>
   </div>
   <div class="flex-shrink-0 flex items-center gap-2">
    <span class="hidden sm:block px-2 py-1 rounded-lg bg-violet-50 dark:bg-violet-500/10 text-[10px] font-medium text-violet-600 dark:text-violet-400 uppercase tracking-wider">Pièce</span>
    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-500 transition"></i>
   </div>
  </a>
  @endforeach

  {{-- ── Commandes Pièces ── --}}
  @foreach($results['part_orders'] as $p_order)
  <a href="{{ route('admin.orders-parts.index', ['search' => $p_order->reference]) }}" data-category="part_orders" class="search-result-item group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-amber-500/40 hover:shadow-md hover:shadow-amber-500/5 transition-all duration-200">
   <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center">
    <i data-lucide="package-search" class="w-4.5 h-4.5 text-indigo-500"></i>
   </div>
   <div class="flex-1 min-w-0">
    <div class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-amber-500 transition truncate mb-0.5">{{ $p_order->reference ?? '#CMD-P-'.$p_order->id }}</div>
    <div class="text-xs text-slate-500 truncate">{{ $p_order->client_nom ?? 'Client' }} · {{ $p_order->lignes?->first()?->piece?->nom ?? 'Plusieurs pièces' }}</div>
   </div>
   <div class="flex-shrink-0 flex items-center gap-2">
    <span class="hidden sm:block px-2 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-[10px] font-medium text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Cmd. Pièce</span>
    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-500 transition"></i>
   </div>
  </a>
  @endforeach

  {{-- ── Utilisateurs ── --}}
  @foreach($results['users'] as $user)
  <a href="{{ route('admin.users.index', ['search' => $user->email]) }}" data-category="users" class="search-result-item group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-amber-500/40 hover:shadow-md hover:shadow-amber-500/5 transition-all duration-200">
   <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-sm font-semibold text-rose-500">
    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
   </div>
   <div class="flex-1 min-w-0">
    <div class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-amber-500 transition truncate mb-0.5">{{ $user->prenom }} {{ $user->nom }}</div>
    <div class="text-xs text-slate-500 truncate">{{ $user->email }} · <span class="capitalize">{{ $user->role ?? 'client' }}</span></div>
   </div>
   <div class="flex-shrink-0 flex items-center gap-2">
    <span class="hidden sm:block px-2 py-1 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-[10px] font-medium text-rose-500 dark:text-rose-400 uppercase tracking-wider">Utilisateur</span>
    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-500 transition"></i>
   </div>
  </a>
  @endforeach

  {{-- ── Révisions ── --}}
  @foreach($results['revisions'] as $rev)
  <a href="{{ route('admin.revisions.index', ['search' => $rev->reference]) }}" data-category="revisions" class="search-result-item group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-amber-500/40 hover:shadow-md hover:shadow-amber-500/5 transition-all duration-200">
   <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center">
    <i data-lucide="wrench" class="w-4.5 h-4.5 text-orange-500"></i>
   </div>
   <div class="flex-1 min-w-0">
    <div class="flex items-center gap-2 mb-0.5">
     <span class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-amber-500 transition truncate">{{ $rev->reference ?? '#REV-'.$rev->id }}</span>
     <span class="flex-shrink-0 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase tracking-wide
      {{ $rev->statut === 'en_attente' ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400' : ($rev->statut === 'termine' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400') }}">
      {{ str_replace('_', ' ', $rev->statut) }}
     </span>
    </div>
    <div class="text-xs text-slate-500 truncate">{{ $rev->marque_vehicule }} · {{ Str::limit($rev->probleme_description, 40) }}</div>
   </div>
   <div class="flex-shrink-0 flex items-center gap-2">
    <span class="hidden sm:block px-2 py-1 rounded-lg bg-orange-50 dark:bg-orange-500/10 text-[10px] font-medium text-orange-600 dark:text-orange-400 uppercase tracking-wider">Révision</span>
    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-500 transition"></i>
   </div>
  </a>
  @endforeach

 </div>

 {{-- ── Footer hint ── --}}
 <div class="mt-8 text-center">
  <p class="text-[11px] text-slate-400">
   <i data-lucide="info" class="w-3 h-3 inline-block mr-1 -mt-0.5"></i>
   Les résultats sont limités à 5 éléments par catégorie. Utilisez les filtres de chaque module pour une recherche exhaustive.
  </p>
 </div>
 @endif

</div>
@endsection

@section('scripts')
<script>
 function filterCategory(category) {
  const items = document.querySelectorAll('.search-result-item');
  const buttons = document.querySelectorAll('.search-filter-btn');

  // Update active button
  buttons.forEach(btn => {
   const isActive = btn.dataset.filter === category;
   btn.classList.toggle('bg-amber-500', isActive);
   btn.classList.toggle('text-slate-950', isActive);
   btn.classList.toggle('shadow-sm', isActive);
   btn.classList.toggle('bg-slate-100', !isActive);
   btn.classList.toggle('dark:bg-slate-800/60', !isActive);
   btn.classList.toggle('text-slate-500', !isActive);
   btn.classList.toggle('dark:text-slate-400', !isActive);

   // Update the count badge colors
   const badge = btn.querySelector('span:last-child');
   if (badge) {
    badge.classList.toggle('bg-slate-950/20', isActive);
    badge.classList.toggle('text-slate-950', isActive);
    badge.classList.toggle('bg-slate-200', !isActive);
    badge.classList.toggle('dark:bg-slate-700', !isActive);
    badge.classList.toggle('text-slate-500', !isActive);
    badge.classList.toggle('dark:text-slate-400', !isActive);
   }
  });

  // Filter items with animation
  items.forEach(item => {
   if (category === 'all' || item.dataset.category === category) {
    item.style.display = '';
    item.style.opacity = '0';
    item.style.transform = 'translateY(8px)';
    requestAnimationFrame(() => {
     item.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
     item.style.opacity = '1';
     item.style.transform = 'translateY(0)';
    });
   } else {
    item.style.display = 'none';
   }
  });
 }
</script>
@endsection
