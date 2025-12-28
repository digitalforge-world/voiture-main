@extends('layouts.admin')

@section('title', 'Console de Gestion - AutoImport Hub')

@section('content')
<div class="space-y-12">
    <!-- Dashboard Header -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-8 underline-offset-[-2px] transition-colors">Tableau de Bord</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">{{ now()->translatedFormat('l d F Y') }} <span class="mx-2 text-slate-100 dark:text-slate-800 transition-colors">|</span> <span class="text-amber-500/80">Console de Commandement</span></p>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white transition border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 rounded-2xl hover:bg-amber-500 dark:hover:bg-amber-500 hover:text-white dark:hover:text-white hover:border-amber-500 group transition-colors">
                <i data-lucide="download" class="w-4 h-4 text-amber-500 group-hover:text-white transition"></i> Rapport mensuel
            </button>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4 px-2">
        <!-- Stock Voitures -->
        <div class="group p-8 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[2.5rem] hover:border-amber-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition transition-colors"></div>
             <div class="p-3 bg-amber-500/10 rounded-2xl text-amber-600 dark:text-amber-500 w-fit mb-6 group-hover:bg-amber-500 group-hover:text-slate-950 transition duration-500 shadow-inner transition-colors"><i data-lucide="car-front" class="w-6 h-6"></i></div>
             <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $totalCars }}</div>
             <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Voitures en stock</div>
        </div>

        <!-- Ventes en attente -->
        <div class="group p-8 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[2.5rem] hover:border-emerald-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition transition-colors"></div>
             <div class="p-3 bg-emerald-500/10 rounded-2xl text-emerald-600 dark:text-emerald-500 w-fit mb-6 group-hover:bg-emerald-500 group-hover:text-slate-950 transition duration-500 shadow-inner transition-colors"><i data-lucide="shopping-cart" class="w-6 h-6"></i></div>
             <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $pendingOrders }}</div>
             <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Ventes en attente</div>
        </div>

        <!-- Locations Actives -->
        <div class="group p-8 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[2.5rem] hover:border-blue-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition transition-colors"></div>
             <div class="p-3 bg-blue-500/10 rounded-2xl text-blue-600 dark:text-blue-500 w-fit mb-6 group-hover:bg-blue-500 group-hover:text-slate-950 transition duration-500 shadow-inner transition-colors"><i data-lucide="calendar-check" class="w-6 h-6"></i></div>
             <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $activeRentals }}</div>
             <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Locations actives</div>
        </div>

        <!-- Chiffre d'Affaires -->
        <div class="group p-8 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[2.5rem] hover:border-amber-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition transition-colors"></div>
             <div class="p-3 bg-amber-500/10 rounded-2xl text-amber-600 dark:text-amber-500 w-fit mb-6 group-hover:bg-amber-500 group-hover:text-slate-950 transition duration-500 shadow-inner transition-colors"><i data-lucide="trending-up" class="w-6 h-6"></i></div>
             <div class="text-2xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ number_format($totalRevenue, 0, ',', ' ') }} <span class="text-[8px] font-bold transition-colors">FCFA</span></div>
             <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Total Revenus</div>
        </div>

        <!-- Clients -->
        <div class="group p-8 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[2.5rem] hover:border-indigo-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition transition-colors"></div>
             <div class="p-3 bg-indigo-500/10 rounded-2xl text-indigo-600 dark:text-indigo-500 w-fit mb-6 group-hover:bg-indigo-500 group-hover:text-slate-950 transition duration-500 shadow-inner transition-colors"><i data-lucide="users" class="w-6 h-6"></i></div>
             <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $totalClients }}</div>
             <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Total Clients</div>
        </div>

        <!-- Pièces Disponibles -->
        <div class="group p-8 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[2.5rem] hover:border-orange-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition transition-colors"></div>
             <div class="p-3 bg-orange-500/10 rounded-2xl text-orange-600 dark:text-orange-500 w-fit mb-6 group-hover:bg-orange-500 group-hover:text-slate-950 transition duration-500 shadow-inner transition-colors"><i data-lucide="package" class="w-6 h-6"></i></div>
             <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $availableParts }}</div>
             <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Pièces en stock</div>
        </div>

        <!-- Révisions -->
        <div class="group p-8 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[2.5rem] hover:border-purple-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition transition-colors"></div>
             <div class="p-3 bg-purple-500/10 rounded-2xl text-purple-600 dark:text-purple-500 w-fit mb-6 group-hover:bg-purple-500 group-hover:text-slate-950 transition duration-500 shadow-inner transition-colors"><i data-lucide="wrench" class="w-6 h-6"></i></div>
             <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $pendingRevisions }}</div>
             <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Révisions à traiter</div>
        </div>

        <!-- Utilisateurs Staff -->
        <div class="group p-8 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[2.5rem] hover:border-slate-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition transition-colors"></div>
             <div class="p-3 bg-slate-100 dark:bg-slate-500/10 rounded-2xl text-slate-400 dark:text-slate-500 w-fit mb-6 group-hover:bg-slate-400 dark:group-hover:bg-slate-500 group-hover:text-slate-950 transition duration-500 shadow-inner transition-colors"><i data-lucide="user-cog" class="w-6 h-6"></i></div>
             <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $totalUsers }}</div>
             <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Total Comptes (Staff)</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
        <!-- Recent Orders (Left) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Ventes Récentes</h2>
                <a href="{{ route('admin.orders-cars.index') }}" class="group text-[10px] font-black text-slate-400 dark:text-slate-500 hover:text-amber-500 transition tracking-[0.2em] uppercase flex items-center gap-2 italic transition-colors">
                    Voir la liste complète <i data-lucide="arrow-right" class="w-3 h-3 group-hover:translate-x-1 transition"></i>
                </a>
            </div>
            <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-[2.5rem] shadow-sm dark:shadow-2xl backdrop-blur-sm transition-colors">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5 transition-colors">
                        <tr>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Client</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Véhicule</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Date</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                        @forelse($recentOrders as $order)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3 transition-colors">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center font-black text-slate-400 dark:text-slate-500 shadow-inner transition-colors">{{ substr($order->user->prenom ?? $order->client_nom ?? 'A', 0, 1) }}</div>
                                    <div class="transition-colors">
                                        <div class="text-sm font-black text-slate-900 dark:text-white tracking-tight italic transition-colors">{{ $order->user->prenom ?? $order->client_nom ?? 'Anonyme' }}</div>
                                        <div class="text-[9px] text-slate-400 dark:text-slate-600 font-bold uppercase tracking-widest italic transition-colors">{{ $order->user->email ?? $order->client_email ?? 'Pas d\'email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-slate-900 dark:text-white italic tracking-tight transition-colors">{{ $order->voiture->marque }} {{ $order->voiture->modele }}</div>
                                <div class="flex items-center gap-2 mt-1 transition-colors">
                                    <span class="text-[9px] font-black text-amber-500/60 uppercase tracking-widest italic border border-amber-500/20 px-1.5 rounded transition-colors">{{ $order->tracking_number ?? $order->reference ?? 'REF' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-xs text-slate-400 dark:text-slate-500 font-bold italic tracking-tighter transition-colors">{{ $order->date_commande?->format('d M Y') ?? 'N/A' }}</td>
                            <td class="px-8 py-6 text-right">
                                <div class="text-sm font-black text-slate-900 dark:text-white italic transition-colors">{{ number_format($order->montant_total, 0, ',', ' ') }} <span class="text-[10px] text-amber-600 dark:text-amber-500 font-medium transition-colors">FCFA</span></div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-8 py-20 text-center text-slate-400 dark:text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs transition-colors">Aucune vente enregistrée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Center (Right) -->
        <div class="space-y-12">
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic px-2 transition-colors">Raccourcis</h2>
            
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-amber-500 to-amber-700 rounded-[2.6rem] blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                <div class="relative p-10 border bg-amber-500 text-slate-950 rounded-[2.5rem] shadow-2xl">
                    <h3 class="text-2xl font-black uppercase mb-4 leading-none tracking-tighter italic font-display">Nouvelle Voiture</h3>
                    <p class="text-[10px] font-black mb-8 leading-relaxed opacity-80 uppercase tracking-widest italic leading-tight">Ajoutez un véhicule premium au catalogue global.</p>
                    <button onclick="openModal('createCarModal'); lucide.createIcons();" class="flex items-center justify-center gap-3 w-full py-5 bg-slate-950 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] hover:bg-slate-900 transition translate-y-0 hover:-translate-y-1 duration-300 shadow-xl active:scale-95 cursor-pointer">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Commencer
                    </button>
                </div>
            </div>
            
            <div class="p-10 border border-slate-100 dark:border-slate-900 bg-white dark:bg-slate-900/40 rounded-[2.5rem] space-y-8 backdrop-blur-sm transition-colors shadow-sm dark:shadow-none">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] italic transition-colors">Statut Logistique</h3>
                    <i data-lucide="activity" class="w-4 h-4 text-slate-300 dark:text-slate-700 transition-colors"></i>
                </div>
                <div class="space-y-5">
                    <div class="flex items-center justify-between p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-white/5 hover:border-rose-500/30 transition duration-300 group transition-colors">
                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Stock Critique</span>
                        <span class="px-3 py-1 bg-rose-500/10 text-rose-500 text-[10px] font-black rounded-full uppercase tracking-widest italic">5 alertes</span>
                    </div>
                    <div class="flex items-center justify-between p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-white/5 hover:border-amber-500/30 transition duration-300 group transition-colors">
                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Top Produit</span>
                        <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tighter italic transition-colors">Pneus Michelin X</span>
                    </div>
                </div>
                <a href="{{ route('admin.parts-inventory.index') }}" class="w-full block text-center py-4 text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.3em] hover:text-slate-900 dark:hover:text-white transition-colors italic">Consulter l'Inventaire Complet</a>
            </div>
        </div>
    </div>
</div>
@include('admin.cars.create-modal')
@endsection

