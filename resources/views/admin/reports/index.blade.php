@extends('layouts.admin')
@section('title', 'Rapports & Statistiques - AutoImport Hub')
@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Rapports & Analytics</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Oversight stratégique et performance multi-dimensionnelle</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-white rounded-2xl hover:bg-amber-500 hover:scale-105 duration-300 shadow-xl">
                <i data-lucide="printer" class="w-4 h-4"></i> Imprimer Rapport
            </button>
        </div>
    </div>

    <!-- Top Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-8 bg-slate-950 border border-slate-900 rounded-[3rem] rounded-tr-xl shadow-2xl relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-amber-500 opacity-5 blur-3xl group-hover:opacity-10 transition duration-700"></div>
            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic italic">Volume Inventaire</div>
            <div class="text-3xl font-black text-white italic tracking-tighter">{{ number_format($totalInventoryValue, 0, ',', ' ') }} <span class="text-xs">FCFA</span></div>
            <div class="mt-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                <span class="text-[9px] font-black text-slate-500 uppercase italic">Valeur Marchande Totale</span>
            </div>
        </div>

        <div class="p-8 bg-slate-950 border border-slate-900 rounded-[3rem] shadow-2xl relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-500 opacity-5 blur-3xl group-hover:opacity-10 transition duration-700"></div>
            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic italic">Taux de Service</div>
            <div class="text-3xl font-black text-white italic tracking-tighter">94.8 <span class="text-xs font-bold">%</span></div>
            <div class="mt-4 flex items-center gap-2 text-emerald-400">
                <i data-lucide="trending-up" class="w-3 h-3"></i>
                <span class="text-[9px] font-black uppercase italic">+2.4% ce mois</span>
            </div>
        </div>

        <div class="p-8 bg-slate-950 border border-slate-900 rounded-[3rem] shadow-2xl relative overflow-hidden group">
             <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-500 opacity-5 blur-3xl group-hover:opacity-10 transition duration-700"></div>
            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic italic">Panier Moyen Plafond</div>
            <div class="text-3xl font-black text-white italic tracking-tighter">{{ number_format($revenueData->avg('total'), 0, ',', ' ') }} <span class="text-xs">FCFA</span></div>
            <div class="mt-4 text-[9px] font-black text-slate-500 uppercase italic italic">Calculé sur 6 mois glissants</div>
        </div>

        <div class="p-8 bg-slate-950 border border-slate-900 rounded-[3rem] rounded-bl-xl shadow-2xl relative overflow-hidden group">
             <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500 opacity-5 blur-3xl group-hover:opacity-10 transition duration-700"></div>
            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic italic">Status Plateforme</div>
            <div class="flex items-center gap-3">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-2xl font-black text-white italic tracking-tighter italic">OPÉRATIONNEL</span>
            </div>
            <div class="mt-4 text-[9px] font-black text-slate-500 uppercase italic">Node: AU-HUB-LOG-01</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Velocity Chart -->
        <div class="lg:col-span-2 p-12 bg-slate-950 border border-slate-900 rounded-[4rem] rounded-tl-xl shadow-2xl relative overflow-hidden group">
            <div class="flex justify-between items-center mb-12 relative z-10">
                <div>
                   <h2 class="text-xl font-black text-white italic uppercase tracking-tighter italic">Vitesse de Revenus</h2>
                   <p class="text-[9px] text-slate-600 font-black uppercase tracking-widest mt-1 italic italic">Performance financière du semestre</p>
                </div>
                <div class="px-4 py-2 bg-slate-900 rounded-xl border border-white/5 text-[9px] font-black text-slate-400 uppercase italic">Source: Ledger CMS</div>
            </div>

            <div class="flex items-end gap-6 h-64 relative z-10">
                 @php
                    $maxTotal = $revenueData->max('total') ?: 1;
                 @endphp
                 @foreach($revenueData as $data)
                 <div class="flex-1 flex flex-col items-center gap-4 group/bar">
                     <div class="relative w-full bg-slate-900 rounded-t-2xl rounded-b-lg overflow-hidden transition-all duration-500 hover:bg-slate-800" style="height: {{ ($data->total / $maxTotal) * 100 }}%">
                        <div class="absolute inset-0 bg-gradient-to-t from-amber-500/20 to-transparent opacity-0 group-hover/bar:opacity-100 transition duration-500"></div>
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.5)]"></div>
                        <!-- Tooltip -->
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-white text-slate-950 px-3 py-1 rounded-md text-[9px] font-black opacity-0 group-hover/bar:opacity-100 transition whitespace-nowrap z-20">
                            {{ number_format($data->total, 0, ',', ' ') }} FCFA
                        </div>
                     </div>
                     <span class="text-[9px] font-black text-slate-500 uppercase italic tracking-widest">{{ substr($data->month, 0, 3) }}</span>
                 </div>
                 @endforeach
            </div>

            <!-- Chart Legend/BG Decoration -->
            <div class="absolute inset-0 flex flex-col justify-between py-12 px-12 pointer-events-none opacity-20">
                <div class="border-t border-slate-900 w-full"></div>
                <div class="border-t border-slate-900 w-full"></div>
                <div class="border-t border-slate-900 w-full"></div>
                <div class="border-t border-slate-900 w-full"></div>
            </div>
        </div>

        <!-- Logistical Throughput -->
        <div class="p-12 bg-slate-950 border border-slate-900 rounded-[4rem] rounded-tr-xl shadow-2xl relative">
            <h2 class="text-xl font-black text-white italic uppercase tracking-tighter mb-10 italic">Performance Logistique</h2>
            
            <div class="space-y-10">
                <!-- Orders Part Break -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[10px] font-black text-slate-400 uppercase italic">Commandes Véhicules</span>
                        <span class="text-[10px] font-black text-white italic">{{ $carOrdersStats->sum('count') }} dossiers</span>
                    </div>
                    <div class="flex h-3 w-full bg-slate-900 rounded-full overflow-hidden border border-white/5 shadow-inner p-0.5">
                         @foreach($carOrdersStats as $stat)
                            @php
                                $colors = [
                                    'en_attente' => 'bg-slate-700',
                                    'valide' => 'bg-emerald-500',
                                    'en_expedition' => 'bg-blue-500',
                                    'livre' => 'bg-indigo-500',
                                    'annule' => 'bg-rose-500'
                                ];
                            @endphp
                            <div class="{{ $colors[$stat->statut] ?? 'bg-slate-500' }} h-full first:rounded-l-full last:rounded-r-full" style="width: {{ ($stat->count / max($carOrdersStats->sum('count'), 1)) * 100 }}%"></div>
                         @endforeach
                    </div>
                </div>

                <!-- Rentals Break -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[10px] font-black text-slate-400 uppercase italic">Exploitation Parc</span>
                        <span class="text-[10px] font-black text-white italic">{{ $rentalStats->sum('count') }} contrats</span>
                    </div>
                    <div class="flex h-3 w-full bg-slate-900 rounded-full overflow-hidden border border-white/5 shadow-inner p-0.5">
                         @foreach($rentalStats as $stat)
                            @php
                                $relColors = [
                                    'confirme' => 'bg-blue-500',
                                    'en_cours' => 'bg-amber-500',
                                    'termine' => 'bg-emerald-500',
                                    'annule' => 'bg-rose-500'
                                ];
                            @endphp
                            <div class="{{ $relColors[$stat->statut] ?? 'bg-slate-500' }} h-full first:rounded-l-full last:rounded-r-full" style="width: {{ ($stat->count / max($rentalStats->sum('count'), 1)) * 100 }}%"></div>
                         @endforeach
                    </div>
                </div>

                <!-- Part Orders Break -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[10px] font-black text-slate-400 uppercase italic">Ventes Pièces</span>
                        <span class="text-[10px] font-black text-white italic">{{ $partOrdersStats->sum('count') }} ventes</span>
                    </div>
                    <div class="flex h-3 w-full bg-slate-900 rounded-full overflow-hidden border border-white/5 shadow-inner p-0.5">
                         @foreach($partOrdersStats as $stat)
                             @php
                                $pColors = [
                                    'en_attente' => 'bg-slate-700',
                                    'valide' => 'bg-emerald-500',
                                    'en_expedition' => 'bg-blue-500',
                                    'livre' => 'bg-indigo-500',
                                    'annule' => 'bg-rose-500'
                                ];
                            @endphp
                            <div class="{{ $pColors[$stat->statut] ?? 'bg-slate-500' }} h-full first:rounded-l-full last:rounded-r-full" style="width: {{ ($stat->count / max($partOrdersStats->sum('count'), 1)) * 100 }}%"></div>
                         @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-white/5 grid grid-cols-2 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                    <span class="text-[8px] font-black text-slate-500 uppercase italic">Finalisé</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                    <span class="text-[8px] font-black text-slate-500 uppercase italic">Logistique</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></div>
                    <span class="text-[8px] font-black text-slate-500 uppercase italic">Actif / En cours</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-slate-700"></div>
                    <span class="text-[8px] font-black text-slate-500 uppercase italic">Dossier Initial</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory / Content Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="p-12 bg-slate-950 border border-slate-900 rounded-[4rem] rounded-bl-xl shadow-2xl overflow-hidden group">
            <div class="flex items-center gap-8">
                <div class="w-24 h-24 rounded-[2rem] bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                    <i data-lucide="package" class="w-10 h-10 text-amber-500"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter italic">Optimisation Stock</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1 italic italic">Analyse des flux d'entrepôt</p>
                </div>
            </div>
            <div class="mt-12 grid grid-cols-3 gap-6">
                <div class="p-6 bg-slate-900/50 rounded-3xl border border-white/5 text-center">
                    <span class="text-[8px] font-black text-slate-600 uppercase block mb-2 italic">Alertes Rupture</span>
                    <span class="text-xl font-black text-rose-500 italic">04</span>
                </div>
                <div class="p-6 bg-slate-900/50 rounded-3xl border border-white/5 text-center">
                    <span class="text-[8px] font-black text-slate-600 uppercase block mb-2 italic">Rotation Moy.</span>
                    <span class="text-xl font-black text-white italic">12j</span>
                </div>
                <div class="p-6 bg-slate-900/50 rounded-3xl border border-white/5 text-center">
                    <span class="text-[8px] font-black text-slate-600 uppercase block mb-2 italic">SKU Actifs</span>
                    <span class="text-xl font-black text-amber-500 italic">142</span>
                </div>
            </div>
        </div>

        <div class="p-12 bg-slate-950 border border-slate-900 rounded-[4rem] rounded-br-xl shadow-2xl overflow-hidden group">
             <div class="flex items-center gap-8">
                <div class="w-24 h-24 rounded-[2rem] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-10 h-10 text-indigo-500"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter italic">Intégrité Système</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1 italic italic">Security & Ops Audit</p>
                </div>
            </div>
            <div class="mt-12 space-y-6">
                <div class="flex items-center justify-between p-6 bg-slate-900/50 rounded-3xl border border-white/5">
                    <span class="text-[10px] font-black text-slate-400 uppercase italic">Temps de réponse API</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-black text-white italic">24 ms</span>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between p-6 bg-slate-900/50 rounded-3xl border border-white/5">
                    <span class="text-[10px] font-black text-slate-400 uppercase italic">Uptime Mensuel</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-black text-white italic">99.98%</span>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
