@extends('layouts.app')

@section('title', 'Administration - AutoImport Hub')

@section('content')
<div class="min-h-screen bg-slate-950 flex flex-col lg:flex-row">
    <!-- Sidebar Navigation -->
    <aside class="w-full lg:w-72 lg:fixed h-full border-r border-slate-900 bg-slate-950 z-20 overflow-y-auto">
        <div class="p-8">
            <div class="mb-12">
                <span class="text-[10px] font-black tracking-[0.2em] uppercase text-amber-500 mb-2 block">Admin Panel</span>
                <div class="text-xl font-black text-white tracking-widest uppercase italic">Console</div>
            </div>
            
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-5 py-4 text-sm font-bold rounded-2xl transition {{ Request::is('admin') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-900/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="{{ route('cars.index') }}" class="flex items-center gap-4 px-5 py-4 text-sm font-bold rounded-2xl text-slate-400 hover:bg-slate-900 hover:text-white transition">
                    <i data-lucide="car-front" class="w-5 h-5"></i> Inventaire Voitures
                </a>
                <a href="{{ route('parts.index') }}" class="flex items-center gap-4 px-5 py-4 text-sm font-bold rounded-2xl text-slate-400 hover:bg-slate-900 hover:text-white transition">
                    <i data-lucide="package" class="w-5 h-5"></i> Stock Pièces
                </a>
                <a href="#" class="flex items-center gap-4 px-5 py-4 text-sm font-bold rounded-2xl text-slate-400 hover:bg-slate-900 hover:text-white transition">
                    <i data-lucide="users" class="w-5 h-5"></i> Clients & Users
                </a>
                <div class="pt-8 mb-4">
                    <h4 class="px-5 text-[10px] font-black tracking-widest uppercase text-slate-600">Opérations</h4>
                </div>
                <a href="#" class="flex items-center gap-4 px-5 py-4 text-sm font-bold rounded-2xl text-slate-400 hover:bg-slate-900 hover:text-white transition">
                    <i data-lucide="credit-card" class="w-5 h-5"></i> Comptabilité
                </a>
                <a href="#" class="flex items-center gap-4 px-5 py-4 text-sm font-bold rounded-2xl text-slate-400 hover:bg-slate-900 hover:text-white transition">
                    <i data-lucide="settings" class="w-5 h-5"></i> Configuration
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-grow lg:ml-72 p-8 lg:p-12 space-y-12">
        <!-- Dashboard Header -->
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">Tableau de Bord <span class="text-amber-500">Gérant</span></h1>
                <p class="text-slate-500 font-medium mt-1 uppercase tracking-tighter italic">{{ now()->translatedFormat('l d F Y') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <button class="flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition border border-slate-800 bg-slate-900/50 rounded-2xl hover:bg-slate-800">
                    <i data-lucide="download" class="w-5 h-5"></i> Rapport mensuel
                </button>
                <div class="w-12 h-12 overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 p-1">
                    <img src="https://ui-avatars.com/api/?name=Admin+Hub&background=fbbf24&color=000" alt="Admin" class="object-cover w-full h-full rounded-xl uppercase tracking-tighter">
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group p-8 border bg-slate-900/30 border-slate-900 rounded-[2.5rem] hover:border-amber-500/30 transition shadow-xl">
                 <div class="p-3 bg-amber-500/10 rounded-2xl text-amber-500 w-fit mb-6 group-hover:bg-amber-500 group-hover:text-slate-950 transition duration-500 shadow-inner"><i data-lucide="car-front" class="w-6 h-6"></i></div>
                 <div class="text-4xl font-black text-white">{{ $totalCars }}</div>
                 <div class="text-xs font-black text-slate-500 uppercase tracking-widest mt-2">Voitures en stock</div>
            </div>
            <div class="group p-8 border bg-slate-900/30 border-slate-900 rounded-[2.5rem] hover:border-emerald-500/30 transition shadow-xl">
                 <div class="p-3 bg-emerald-500/10 rounded-2xl text-emerald-500 w-fit mb-6 group-hover:bg-emerald-500 group-hover:text-slate-950 transition duration-500 shadow-inner"><i data-lucide="shopping-cart" class="w-6 h-6"></i></div>
                 <div class="text-4xl font-black text-white">{{ $pendingOrders }}</div>
                 <div class="text-xs font-black text-slate-500 uppercase tracking-widest mt-2">Ventes en attente</div>
            </div>
            <div class="group p-8 border bg-slate-900/30 border-slate-900 rounded-[2.5rem] hover:border-blue-500/30 transition shadow-xl">
                 <div class="p-3 bg-blue-500/10 rounded-2xl text-blue-500 w-fit mb-6 group-hover:bg-blue-500 group-hover:text-slate-950 transition duration-500 shadow-inner"><i data-lucide="users-round" class="w-6 h-6"></i></div>
                 <div class="text-4xl font-black text-white">{{ $totalUsers }}</div>
                 <div class="text-xs font-black text-slate-500 uppercase tracking-widest mt-2">Total Utilisateurs</div>
            </div>
            <div class="group p-8 border bg-slate-900/30 border-slate-900 rounded-[2.5rem] hover:border-purple-500/30 transition shadow-xl">
                 <div class="p-3 bg-purple-500/10 rounded-2xl text-purple-500 w-fit mb-6 group-hover:bg-purple-500 group-hover:text-slate-950 transition duration-500 shadow-inner"><i data-lucide="wrench" class="w-6 h-6"></i></div>
                 <div class="text-4xl font-black text-white">{{ $pendingRevisions }}</div>
                 <div class="text-xs font-black text-slate-500 uppercase tracking-widest mt-2">Révisions à traiter</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
            <!-- Recent Orders (Left) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Ventes Récentes</h2>
                    <a href="#" class="text-xs font-black text-slate-500 hover:text-amber-500 transition tracking-widest uppercase">Voir plus</a>
                </div>
                <div class="border overflow-hidden bg-slate-950/50 border-slate-900 rounded-[2.5rem] shadow-2xl">
                    <table class="w-full text-left">
                        <thead class="bg-slate-900 border-b border-white/5">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-500">Client</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-500">Véhicule</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-500">Date</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($recentOrders as $order)
                            <tr class="group hover:bg-white/[0.02] transition duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center font-black text-slate-500">{{ substr($order->user->prenom, 0, 1) }}</div>
                                        <div>
                                            <div class="text-sm font-black text-white tracking-tight">{{ $order->user->prenom }} {{ $order->user->nom }}</div>
                                            <div class="text-[10px] text-slate-600 font-bold uppercase tracking-tighter">{{ $order->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-white">{{ $order->voiture->marque }} {{ $order->voiture->modele }}</div>
                                    <div class="text-[10px] font-bold text-amber-500/60 uppercase">{{ $order->reference }}</div>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-500 font-medium italic">{{ $order->date_commande->format('d/m/Y') }}</td>
                                <td class="px-8 py-6 text-right">
                                    <div class="text-sm font-black text-white">{{ number_format($order->montant_total, 0, ',', ' ') }} €</div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-8 py-16 text-center text-slate-700 italic font-medium">Aucune commande enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Center (Right) -->
            <div class="space-y-8">
                <h2 class="text-2xl font-black text-white tracking-tight uppercase italic">Gestion Rapide</h2>
                <div class="p-10 border bg-amber-500 text-slate-950 rounded-[2.5rem] shadow-2xl shadow-amber-900/20">
                    <h3 class="text-xl font-black uppercase mb-6 leading-none tracking-tighter italic">Ajouter Nouveau Véhicule</h3>
                    <p class="text-xs font-bold mb-8 leading-relaxed opacity-80 uppercase tracking-tighter italic">Mettez à jour le catalogue pour attirer de nouveaux clients.</p>
                    <a href="#" class="flex items-center justify-center w-full py-4 bg-slate-950 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-slate-900 transition">Action Panel</a>
                </div>
                
                <div class="p-10 border border-slate-900 bg-slate-900/40 rounded-[2.5rem] space-y-6">
                    <h3 class="text-lg font-black text-white uppercase tracking-tighter italic mb-4">Stock de Pièces</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-slate-950 rounded-2xl border border-white/5">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest italic leading-none">Alerte Stock</span>
                            <span class="px-3 py-1 bg-rose-500 text-[9px] font-black text-white rounded-full uppercase tracking-widest italic leading-none">5 alertes</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-950 rounded-2xl border border-white/5">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest italic leading-none">Référence la plus vendue</span>
                            <span class="text-xs font-black text-white uppercase tracking-tighter italic leading-none">Amortisseur X4</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
