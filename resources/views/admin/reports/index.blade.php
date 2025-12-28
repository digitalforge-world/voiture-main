@extends('layouts.admin')
@section('title', 'Rapports & Statistiques - AutoImport Hub')
@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Rapports & Analytics</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Oversight stratégique et performance multi-dimensionnelle</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/5 text-slate-900 dark:text-white rounded-2xl hover:bg-amber-500 dark:hover:bg-amber-500 hover:text-slate-950 dark:hover:text-slate-950 hover:scale-105 duration-300 shadow-xl transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i> Imprimer Rapport
            </button>
        </div>
    </div>

    <!-- Top Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-8 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[3rem] rounded-tr-xl shadow-lg dark:shadow-2xl relative overflow-hidden group transition-colors">
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-amber-500 opacity-[0.03] dark:opacity-5 blur-3xl group-hover:opacity-10 transition duration-700"></div>
            <div class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 italic transition-colors">Volume Inventaire</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ number_format($totalInventoryValue, 0, ',', ' ') }} <span class="text-xs">FCFA</span></div>
            <div class="mt-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Valeur Marchande Totale</span>
            </div>
        </div>

        <div class="p-8 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[3rem] shadow-lg dark:shadow-2xl relative overflow-hidden group transition-colors">
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-500 opacity-[0.03] dark:opacity-5 blur-3xl group-hover:opacity-10 transition duration-700"></div>
            <div class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 italic transition-colors">Taux de Service</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">94.8 <span class="text-xs font-bold">%</span></div>
            <div class="mt-4 flex items-center gap-2 text-emerald-600 dark:text-emerald-400 transition-colors">
                <i data-lucide="trending-up" class="w-3 h-3"></i>
                <span class="text-[9px] font-black uppercase italic">+2.4% ce mois</span>
            </div>
        </div>

        <div class="p-8 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[3rem] shadow-lg dark:shadow-2xl relative overflow-hidden group transition-colors">
             <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-500 opacity-[0.03] dark:opacity-5 blur-3xl group-hover:opacity-10 transition duration-700"></div>
            <div class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 italic transition-colors">Panier Moyen Plafond</div>
            <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ number_format($revenueData->avg('total'), 0, ',', ' ') }} <span class="text-xs">FCFA</span></div>
            <div class="mt-4 text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Calculé sur 6 mois glissants</div>
        </div>

        <div class="p-8 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[3rem] rounded-bl-xl shadow-lg dark:shadow-2xl relative overflow-hidden group transition-colors">
             <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500 opacity-[0.03] dark:opacity-5 blur-3xl group-hover:opacity-10 transition duration-700"></div>
            <div class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 italic transition-colors">Status Plateforme</div>
            <div class="flex items-center gap-3">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-2xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">OPÉRATIONNEL</span>
            </div>
            <div class="mt-4 text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Node: AU-HUB-LOG-01</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Velocity Chart -->
        <div class="lg:col-span-2 p-12 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tl-xl shadow-lg dark:shadow-2xl relative overflow-hidden group transition-colors">
            <div class="flex justify-between items-center mb-12 relative z-10 transition-colors">
                <div>
                   <h2 class="text-xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Vitesse de Revenus</h2>
                   <p class="text-[9px] text-slate-400 dark:text-slate-600 font-black uppercase tracking-widest mt-1 italic transition-colors">Performance financière du semestre</p>
                </div>
                <div class="px-4 py-2 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-white/5 text-[9px] font-black text-slate-400 uppercase italic transition-colors">Source: Ledger CMS</div>
            </div>

            <div class="flex items-end gap-6 h-64 relative z-10 transition-colors">
                 @php
                    $maxTotal = $revenueData->max('total') ?: 1;
                 @endphp
                 @foreach($revenueData as $data)
                 <div class="flex-1 flex flex-col items-center gap-4 group/bar transition-colors">
                     <div class="relative w-full bg-slate-50 dark:bg-slate-900 rounded-t-2xl rounded-b-lg overflow-hidden transition-all duration-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" style="height: {{ ($data->total / $maxTotal) * 100 }}%">
                        <div class="absolute inset-0 bg-gradient-to-t from-amber-500/20 to-transparent opacity-0 group-hover/bar:opacity-100 transition duration-500"></div>
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.5)]"></div>
                        <!-- Tooltip -->
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-900 dark:bg-white text-white dark:text-slate-950 px-3 py-1 rounded-md text-[9px] font-black opacity-0 group-hover/bar:opacity-100 transition whitespace-nowrap z-20 transition-colors">
                            {{ number_format($data->total, 0, ',', ' ') }} FCFA
                        </div>
                     </div>
                     <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase italic tracking-widest transition-colors">{{ substr($data->month, 0, 3) }}</span>
                 </div>
                 @endforeach
            </div>

            <!-- Chart Legend/BG Decoration -->
            <div class="absolute inset-0 flex flex-col justify-between py-12 px-12 pointer-events-none opacity-[0.05] dark:opacity-20 transition-colors">
                <div class="border-t border-slate-900 dark:border-slate-900 w-full"></div>
                <div class="border-t border-slate-900 dark:border-slate-900 w-full"></div>
                <div class="border-t border-slate-900 dark:border-slate-900 w-full"></div>
                <div class="border-t border-slate-900 dark:border-slate-900 w-full"></div>
            </div>
        </div>

        <!-- Logistical Throughput -->
        <div class="p-12 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tr-xl shadow-lg dark:shadow-2xl relative transition-colors">
            <h2 class="text-xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter mb-10 transition-colors">Performance Logistique</h2>
            
            <div class="space-y-10">
                <!-- Orders Part Break -->
                <div>
                    <div class="flex justify-between items-center mb-4 transition-colors">
                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Commandes Véhicules</span>
                        <span class="text-[10px] font-black text-slate-900 dark:text-white italic transition-colors">{{ $carOrdersStats->sum('count') }} dossiers</span>
                    </div>
                    <div class="flex h-3 w-full bg-slate-50 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-100 dark:border-white/5 shadow-inner p-0.5 transition-colors">
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
                    <div class="flex justify-between items-center mb-4 transition-colors">
                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Exploitation Parc</span>
                        <span class="text-[10px] font-black text-slate-900 dark:text-white italic transition-colors">{{ $rentalStats->sum('count') }} contrats</span>
                    </div>
                    <div class="flex h-3 w-full bg-slate-50 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-100 dark:border-white/5 shadow-inner p-0.5 transition-colors">
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
                    <div class="flex justify-between items-center mb-4 transition-colors">
                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Ventes Pièces</span>
                        <span class="text-[10px] font-black text-slate-900 dark:text-white italic transition-colors">{{ $partOrdersStats->sum('count') }} ventes</span>
                    </div>
                    <div class="flex h-3 w-full bg-slate-50 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-100 dark:border-white/5 shadow-inner p-0.5 transition-colors">
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

            <div class="mt-16 pt-8 border-t border-slate-50 dark:border-white/5 grid grid-cols-2 gap-4 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                    <span class="text-[8px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Finalisé</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                    <span class="text-[8px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Logistique</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></div>
                    <span class="text-[8px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Actif / En cours</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors"></div>
                    <span class="text-[8px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Dossier Initial</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory / Content Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="p-12 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-bl-xl shadow-lg dark:shadow-2xl overflow-hidden group transition-colors">
            <div class="flex items-center gap-8 transition-colors">
                <div class="w-24 h-24 rounded-[2rem] bg-amber-500/10 border border-amber-500/20 flex items-center justify-center transition-colors">
                    <i data-lucide="package" class="w-10 h-10 text-amber-500"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Optimisation Stock</h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-1 italic transition-colors">Analyse des flux d'entrepôt</p>
                </div>
            </div>
            <div class="mt-12 grid grid-cols-3 gap-6 transition-colors">
                <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-white/5 text-center transition-colors">
                    <span class="text-[8px] font-black text-slate-400 dark:text-slate-600 uppercase block mb-2 italic transition-colors">Alertes Rupture</span>
                    <span class="text-xl font-black text-rose-500 italic">04</span>
                </div>
                <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-white/5 text-center transition-colors">
                    <span class="text-[8px] font-black text-slate-400 dark:text-slate-600 uppercase block mb-2 italic transition-colors">Rotation Moy.</span>
                    <span class="text-xl font-black text-slate-900 dark:text-white italic transition-colors">12j</span>
                </div>
                <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-white/5 text-center transition-colors">
                    <span class="text-[8px] font-black text-slate-400 dark:text-slate-600 uppercase block mb-2 italic transition-colors">SKU Actifs</span>
                    <span class="text-xl font-black text-amber-500 italic">142</span>
                </div>
            </div>
        </div>

        <div class="p-12 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-br-xl shadow-lg dark:shadow-2xl overflow-hidden group transition-colors">
             <div class="flex items-center gap-8 transition-colors">
                <div class="w-24 h-24 rounded-[2rem] bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center transition-colors">
                    <i data-lucide="shield-check" class="w-10 h-10 text-indigo-500"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Intégrité Système</h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-1 italic transition-colors">Security & Ops Audit</p>
                </div>
            </div>
            <div class="mt-12 space-y-6 transition-colors">
                <div class="flex items-center justify-between p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase italic transition-colors">Temps de réponse API</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-black text-slate-900 dark:text-white italic transition-colors">24 ms</span>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase italic transition-colors">Uptime Mensuel</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-black text-slate-900 dark:text-white italic transition-colors">99.98%</span>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
