@extends('layouts.app')

@section('title', 'Mon Tableau de Bord')

@section('content')
<div class="min-h-screen bg-slate-950">
    <!-- Dashboard Header -->
    <div class="relative pt-20 pb-12 overflow-hidden border-b border-slate-900 bg-slate-900/20">
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-slate-950 to-transparent"></div>
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="flex flex-col items-center justify-between gap-8 md:flex-row md:items-end">
                <div class="flex items-center gap-6">
                    <div class="relative group">
                        <div class="w-24 h-24 overflow-hidden rounded-[2rem] border-2 border-amber-500 p-1 bg-slate-900">
                            <img src="{{ Auth::user()->photo_profil ?? 'https://ui-avatars.com/api/?name='.Auth::user()->nom.'+'.Auth::user()->prenom.'&background=fbbf24&color=000' }}" alt="Avatar" class="object-cover w-full h-full rounded-[1.8rem]">
                        </div>
                        <div class="absolute -bottom-1 -right-1 p-1.5 bg-emerald-500 border-4 border-slate-950 rounded-full"></div>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                             <h1 class="text-3xl font-black text-white tracking-tight">Salut, {{ Auth::user()->prenom }} !</h1>
                             <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest bg-amber-500 text-slate-950 rounded-full">{{ Auth::user()->role }}</span>
                        </div>
                        <p class="text-slate-500 font-medium">Membre depuis {{ Auth::user()->date_creation->format('M Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="p-3 transition border rounded-2xl border-slate-800 hover:bg-slate-900 text-slate-400">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                    </a>
                    <form action="#" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-6 py-3 text-sm font-bold transition border title border-rose-500/30 bg-rose-500/5 text-rose-500 rounded-2xl hover:bg-rose-500/10">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="container px-4 py-12 mx-auto lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="p-8 border bg-slate-900/40 border-slate-900 rounded-[2rem]">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-amber-500/10 rounded-2xl text-amber-500"><i data-lucide="ship" class="w-6 h-6"></i></div>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest">En cours</span>
                </div>
                <div class="text-3xl font-black text-white">{{ $commandesVoitures->count() }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-tighter mt-1">Importations</div>
            </div>
            <div class="p-8 border bg-slate-900/40 border-slate-900 rounded-[2rem]">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-500/10 rounded-2xl text-blue-500"><i data-lucide="calendar" class="w-6 h-6"></i></div>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Actives</span>
                </div>
                <div class="text-3xl font-black text-white">{{ $locations->count() }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-tighter mt-1">Locations</div>
            </div>
            <div class="p-8 border bg-slate-900/40 border-slate-900 rounded-[2rem]">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-emerald-500/10 rounded-2xl text-emerald-500"><i data-lucide="package" class="w-6 h-6"></i></div>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Livrées</span>
                </div>
                <div class="text-3xl font-black text-white">{{ $commandesPieces->count() }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-tighter mt-1">Commandes Pièces</div>
            </div>
            <div class="p-8 border bg-slate-900/40 border-slate-900 rounded-[2rem]">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-500/10 rounded-2xl text-purple-500"><i data-lucide="wrench" class="w-6 h-6"></i></div>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest">En attente</span>
                </div>
                <div class="text-3xl font-black text-white">{{ $revisions->count() }}</div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-tighter mt-1">Révisions</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-12 mt-12 lg:grid-cols-12">
            <!-- Recent Activities (Left) -->
            <div class="lg:col-span-8 space-y-12">
                <!-- Section: Importations -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                         <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i data-lucide="ship-wheel" class="w-6 h-6 text-amber-500"></i>
                            Suivi Importations
                         </h2>
                         <a href="#" class="text-xs font-bold text-amber-500 uppercase tracking-widest hover:text-white transition">Tout voir</a>
                    </div>
                    
                    <div class="overflow-hidden border bg-slate-900/30 border-slate-900 rounded-[2.5rem]">
                        <table class="w-full text-left">
                            <thead class="bg-slate-950 border-b border-slate-900">
                                <tr>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Référence</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Véhicule</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Statut</th>
                                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-500">Montant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @forelse($commandesVoitures as $commande)
                                <tr class="group hover:bg-white/5 transition duration-300">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-white tracking-widest">{{ $commande->reference }}</div>
                                        <div class="text-[10px] font-bold text-slate-600 mt-1 uppercase">{{ $commande->date_commande->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-white tracking-tight">{{ $commande->voiture->marque }} {{ $commande->voiture->modele }}</div>
                                        <div class="text-xs text-slate-500 truncate max-w-[150px]">{{ $commande->port->nom }} ({{ $commande->port->pays }})</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statutClasses = [
                                                'en_attente' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                                'confirme' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                                'expedie' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                                'arrive' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20'
                                            ];
                                            $class = $statutClasses[$commande->statut] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full {{ $class }}">
                                            {{ str_replace('_', ' ', $commande->statut) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-white">{{ number_format($commande->montant_total, 0, ',', ' ') }} <span class="text-[10px] text-amber-500 uppercase">Eur</span></div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-slate-600 italic text-sm">Aucune importation en cours.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section: Pièces -->
                <div class="space-y-6">
                    <h2 class="text-xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                        <i data-lucide="box" class="w-6 h-6 text-amber-500"></i>
                        Commandes de Pièces
                    </h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @forelse($commandesPieces as $commande)
                        <div class="p-6 border bg-slate-900/30 border-slate-900 rounded-3xl hover:border-amber-500/30 transition group">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">{{ $commande->reference }}</div>
                                    <div class="text-sm font-bold text-white">{{ $commande->lignes->count() }} article(s)</div>
                                </div>
                                <span class="px-2 py-1 bg-white/5 border border-white/10 rounded-lg text-[9px] font-black text-slate-400 uppercase">{{ $commande->statut }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                <div class="text-[10px] font-bold text-slate-600 uppercase">{{ $commande->date_commande->format('d/m/Y') }}</div>
                                <div class="text-sm font-black text-white">{{ number_format($commande->montant_total, 0, ',', ' ') }} €</div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 p-10 text-center border border-dashed border-slate-900 rounded-[2rem] text-slate-600 text-sm italic">Aucune commande de pièce.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right) -->
            <div class="lg:col-span-4 space-y-12">
                <!-- Locations Actives -->
                <div class="p-1 p-px bg-gradient-to-br from-blue-500/30 to-slate-800 rounded-[2.5rem]">
                    <div class="p-10 bg-slate-950 rounded-[2.4rem] space-y-8">
                        <h3 class="text-lg font-black text-white uppercase tracking-tighter flex items-center gap-3">
                            <i data-lucide="key" class="w-5 h-5 text-blue-500"></i>
                            Locations en cours
                        </h3>
                        
                        <div class="space-y-6">
                            @forelse($locations as $loc)
                            <div class="space-y-3 pb-6 border-b border-white/5 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-bold text-white">{{ $loc->voitureLocation->marque }} {{ $loc->voitureLocation->modele }}</div>
                                    <span class="text-[10px] font-bold text-blue-500 uppercase">{{ $loc->statut }}</span>
                                </div>
                                <div class="flex items-center gap-4 text-[10px] font-bold text-slate-500 uppercase">
                                    <div class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5 italic"></i> {{ $loc->date_debut->format('d/m') }} - {{ $loc->date_fin->format('d/m') }}</div>
                                    <div class="flex items-center gap-1.5"><i data-lucide="credit-card" class="w-3.5 h-3.5 italic"></i> {{ number_format($loc->montant_total, 0, ',', ' ') }} €</div>
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-slate-600 italic">Aucune location active.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Révisions -->
                <div class="p-10 border bg-slate-950 border-slate-900 rounded-[2.5rem] space-y-8">
                     <h3 class="text-lg font-black text-white uppercase tracking-tighter flex items-center gap-3">
                        <i data-lucide="wrench" class="w-5 h-5 text-purple-500"></i>
                        Révisions techniques
                    </h3>
                    <div class="space-y-6">
                        @forelse($revisions as $rev)
                        <div class="p-6 bg-slate-900/50 border border-slate-800 rounded-3xl group transition">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-[10px] font-black text-slate-500 tracking-widest uppercase">{{ $rev->reference }}</div>
                                <span class="w-2 h-2 rounded-full {{ $rev->statut === 'termine' ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }}"></span>
                            </div>
                            <div class="text-sm font-bold text-white mb-1">{{ $rev->marque_vehicule }} {{ $rev->modele_vehicule }}</div>
                            <div class="text-[10px] font-black text-slate-600 uppercase">{{ str_replace('_', ' ', $rev->statut) }}</div>
                        </div>
                        @empty
                         <p class="text-sm text-slate-600 italic">Aucune demande de révision.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
