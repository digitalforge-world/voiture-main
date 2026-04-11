@extends('layouts.admin')
@section('title', 'Rapports & Statistiques - AutoImport Hub')
@section('content')
<div class="space-y-6">
 <!-- Header -->
 <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white transition-colors">Rapports & Analytics</h1>
   <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm transition-colors">Oversight stratégique et performance multi-dimensionnelle</p>
  </div>
  <div class="flex items-center gap-4">
   <button class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-900 dark:text-white transition bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-amber-600 dark:hover:text-amber-500 shadow-sm transition-colors">
    <i data-lucide="printer" class="w-4 h-4"></i> Imprimer Rapport
   </button>
  </div>
 </div>

 <!-- Top Metrics Row -->
 <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
  <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm relative overflow-hidden group transition-colors">
   <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2 transition-colors">Volume Inventaire</div>
   <div class="text-2xl font-semibold text-slate-900 dark:text-white transition-colors">{{ number_format($totalInventoryValue, 0, ',', ' ') }} <span class="text-sm font-normal text-slate-500">FCFA</span></div>
   <div class="mt-4 flex items-center gap-2">
    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
    <span class="text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">Valeur Marchande Totale</span>
   </div>
  </div>

  <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm relative overflow-hidden group transition-colors">
   <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2 transition-colors">Taux de Service</div>
   <div class="text-2xl font-semibold text-slate-900 dark:text-white transition-colors">94.8 <span class="text-sm font-normal text-slate-500">%</span></div>
   <div class="mt-4 flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 transition-colors">
    <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
    <span class="text-xs font-medium">+2.4% ce mois</span>
   </div>
  </div>

  <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm relative overflow-hidden group transition-colors">
   <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2 transition-colors">Panier Moyen Plafond</div>
   <div class="text-2xl font-semibold text-slate-900 dark:text-white transition-colors">{{ number_format($revenueData->avg('total'), 0, ',', ' ') }} <span class="text-sm font-normal text-slate-500">FCFA</span></div>
   <div class="mt-4 text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">Calculé sur 6 mois glissants</div>
  </div>

  <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm relative overflow-hidden group transition-colors">
   <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2 transition-colors">Statut Plateforme</div>
   <div class="flex items-center gap-3">
    <span class="flex h-2.5 w-2.5 relative">
     <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
     <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
    </span>
    <span class="text-lg font-semibold text-slate-900 dark:text-white transition-colors">Opérationnel</span>
   </div>
   <div class="mt-4 text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">Node: AU-HUB-LOG-01</div>
  </div>
 </div>

 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- Revenue Velocity Chart -->
  <div class="lg:col-span-2 p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm relative overflow-hidden group transition-colors">
   <div class="flex justify-between items-center mb-8 relative z-10 transition-colors">
    <div>
     <h2 class="text-lg font-semibold text-slate-900 dark:text-white transition-colors">Vitesse de Revenus</h2>
     <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1 transition-colors">Performance financière du semestre</p>
    </div>
    <div class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-500 transition-colors">Source: CMS</div>
   </div>

   <div class="flex items-end gap-4 h-64 relative z-10 transition-colors">
     @php
     $maxTotal = $revenueData->max('total') ?: 1;
     @endphp
     @foreach($revenueData as $data)
     <div class="flex-1 flex flex-col items-center gap-3 group/bar transition-colors">
      <div class="relative w-full bg-slate-100 dark:bg-slate-800 rounded-t-xl rounded-b-sm overflow-hidden transition-all duration-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" style="height: {{ ($data->total / $maxTotal) * 100 }}%">
      <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500"></div>
      <!-- Tooltip -->
      <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 dark:bg-white text-white dark:text-slate-900 px-2 py-1 rounded text-xs font-medium opacity-0 group-hover/bar:opacity-100 transition whitespace-nowrap z-20 transition-colors shadow-lg">
       {{ number_format($data->total, 0, ',', ' ') }} FCFA
      </div>
      </div>
      <span class="text-xs font-medium text-slate-500 dark:text-slate-400 capitalize transition-colors">{{ substr($data->month, 0, 3) }}</span>
     </div>
     @endforeach
   </div>
  </div>

  <!-- Logistical Throughput -->
  <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm relative transition-colors">
   <div class="flex items-center justify-between mb-8">
       <h2 class="text-lg font-semibold text-slate-900 dark:text-white transition-colors">Performance Logistique</h2>
   </div>
   
   <div class="space-y-8">
    <!-- Orders Break -->
    <div>
     <div class="flex justify-between items-center mb-2 transition-colors">
      <span class="text-sm font-medium text-slate-600 dark:text-slate-400 transition-colors">Commandes Véhicules</span>
      <span class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">{{ $carOrdersStats->sum('count') }} dossiers</span>
     </div>
     <div class="flex h-2.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden transition-colors">
       @foreach($carOrdersStats as $stat)
       @php
        $colors = [
         'en_attente' => 'bg-slate-400',
         'valide' => 'bg-emerald-500',
         'en_expedition' => 'bg-blue-500',
         'livre' => 'bg-indigo-500',
         'annule' => 'bg-rose-500'
        ];
       @endphp
       <div class="{{ $colors[$stat->statut] ?? 'bg-slate-400' }} h-full" style="width: {{ ($stat->count / max($carOrdersStats->sum('count'), 1)) * 100 }}%"></div>
       @endforeach
     </div>
    </div>

    <!-- Rentals Break -->
    <div>
     <div class="flex justify-between items-center mb-2 transition-colors">
      <span class="text-sm font-medium text-slate-600 dark:text-slate-400 transition-colors">Exploitation Parc</span>
      <span class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">{{ $rentalStats->sum('count') }} contrats</span>
     </div>
     <div class="flex h-2.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden transition-colors">
       @foreach($rentalStats as $stat)
       @php
        $relColors = [
         'confirme' => 'bg-blue-500',
         'en_cours' => 'bg-amber-500',
         'termine' => 'bg-emerald-500',
         'annule' => 'bg-rose-500'
        ];
       @endphp
       <div class="{{ $relColors[$stat->statut] ?? 'bg-slate-400' }} h-full" style="width: {{ ($stat->count / max($rentalStats->sum('count'), 1)) * 100 }}%"></div>
       @endforeach
     </div>
    </div>

    <!-- Part Orders Break -->
    <div>
     <div class="flex justify-between items-center mb-2 transition-colors">
      <span class="text-sm font-medium text-slate-600 dark:text-slate-400 transition-colors">Ventes Pièces</span>
      <span class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">{{ $partOrdersStats->sum('count') }} ventes</span>
     </div>
     <div class="flex h-2.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden transition-colors">
       @foreach($partOrdersStats as $stat)
        @php
        $pColors = [
         'en_attente' => 'bg-slate-400',
         'valide' => 'bg-emerald-500',
         'en_expedition' => 'bg-blue-500',
         'livre' => 'bg-indigo-500',
         'annule' => 'bg-rose-500'
        ];
       @endphp
       <div class="{{ $pColors[$stat->statut] ?? 'bg-slate-400' }} h-full" style="width: {{ ($stat->count / max($partOrdersStats->sum('count'), 1)) * 100 }}%"></div>
       @endforeach
     </div>
    </div>
   </div>

   <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 grid grid-cols-2 gap-y-3 gap-x-2 transition-colors">
    <div class="flex items-center gap-2">
     <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
     <span class="text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">Finalisé</span>
    </div>
    <div class="flex items-center gap-2">
     <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
     <span class="text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">Logistique</span>
    </div>
    <div class="flex items-center gap-2">
     <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
     <span class="text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">En cours</span>
    </div>
    <div class="flex items-center gap-2">
     <div class="w-1.5 h-1.5 rounded-full bg-slate-400"></div>
     <span class="text-xs font-medium text-slate-500 dark:text-slate-400 transition-colors">Initial</span>
    </div>
   </div>
  </div>
 </div>

 <!-- Inventory Summary -->
 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden group transition-colors">
   <div class="flex items-center gap-4 transition-colors">
    <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-500 transition-colors">
     <i data-lucide="package" class="w-6 h-6"></i>
    </div>
    <div>
     <h3 class="text-lg font-semibold text-slate-900 dark:text-white transition-colors">Optimisation Stock</h3>
     <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Analyse des flux d'entrepôt</p>
    </div>
   </div>
   <div class="mt-8 grid grid-cols-3 gap-4 transition-colors">
    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700/50 text-center transition-colors">
     <span class="text-xs font-medium text-slate-500 dark:text-slate-400 block mb-1">Ruptures</span>
     <span class="text-lg font-semibold text-rose-500">04</span>
    </div>
    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700/50 text-center transition-colors">
     <span class="text-xs font-medium text-slate-500 dark:text-slate-400 block mb-1">Rotation</span>
     <span class="text-lg font-semibold text-slate-900 dark:text-white">12j</span>
    </div>
    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700/50 text-center transition-colors">
     <span class="text-xs font-medium text-slate-500 dark:text-slate-400 block mb-1">SKU</span>
     <span class="text-lg font-semibold text-amber-500">142</span>
    </div>
   </div>
  </div>

  <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden group transition-colors">
    <div class="flex items-center gap-4 transition-colors">
    <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-500 transition-colors">
     <i data-lucide="shield-check" class="w-6 h-6"></i>
    </div>
    <div>
     <h3 class="text-lg font-semibold text-slate-900 dark:text-white transition-colors">Intégrité Système</h3>
     <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">Security & Ops Audit</p>
    </div>
   </div>
   <div class="mt-8 space-y-4 transition-colors">
    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700/50 transition-colors">
     <span class="text-sm font-medium text-slate-600 dark:text-slate-400 transition-colors">Temps de réponse API</span>
     <div class="flex items-center gap-2">
      <span class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">24 ms</span>
      <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
     </div>
    </div>
    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700/50 transition-colors">
     <span class="text-sm font-medium text-slate-600 dark:text-slate-400 transition-colors">Uptime Mensuel</span>
     <div class="flex items-center gap-2">
      <span class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">99.98%</span>
      <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
@endsection
