@extends('layouts.admin')

@section('title', 'Gestion du Catalogue - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Inventaire Véhicules</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Gestion du stock local et des importations en cours</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openModal('createCarModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 hover:scale-105 duration-300 shadow-xl shadow-amber-900/10 transition-colors">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Ajouter un véhicule
            </button>
        </div>
    </div>

    <!-- Filters & Search -->
    <form action="{{ route('admin.cars.index') }}" method="GET" class="p-8 bg-white dark:bg-slate-900/30 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tr-xl rounded-bl-xl flex flex-wrap items-center gap-8 shadow-lg dark:shadow-2xl backdrop-blur-sm transition-colors">
        <div class="flex-grow min-w-[350px] relative group">
            <i data-lucide="search" class="absolute left-8 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="RECHERCHER PAR MARQUE, MODÈLE OU CHÂSSIS..." 
                class="w-full py-6 pl-20 pr-10 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-3xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-300 dark:placeholder:text-slate-700 focus:ring-1 focus:ring-amber-500 transition shadow-inner outline-none transition-colors"
            >
        </div>

        <div class="flex items-center gap-4 px-6 py-3 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-white/5 transition-colors">
            <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest transition-colors">Disponibilité :</span>
            <select name="availability" onchange="this.form.submit()" class="bg-transparent border-none text-[10px] font-black text-amber-500 uppercase tracking-widest focus:ring-0 cursor-pointer p-0">
                <option value="">Tous</option>
                <option value="disponible" {{ request('availability') == 'disponible' ? 'selected' : '' }}>En Stock</option>
                <option value="importation" {{ request('availability') == 'importation' ? 'selected' : '' }}>En Importation</option>
                <option value="reserve" {{ request('availability') == 'reserve' ? 'selected' : '' }}>Réservé</option>
                <option value="vendu" {{ request('availability') == 'vendu' ? 'selected' : '' }}>Vendu</option>
            </select>
        </div>

        <div class="flex items-center gap-4 px-6 py-3 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-white/5 transition-colors">
            <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest transition-colors">État :</span>
            <select name="condition" onchange="this.form.submit()" class="bg-transparent border-none text-[10px] font-black text-emerald-500 uppercase tracking-widest focus:ring-0 cursor-pointer p-0">
                <option value="">Tous</option>
                <option value="neuf" {{ request('condition') == 'neuf' ? 'selected' : '' }}>Neuf</option>
                <option value="occasion" {{ request('condition') == 'occasion' ? 'selected' : '' }}>Occasion</option>
                <option value="reconditionne" {{ request('condition') == 'reconditionne' ? 'selected' : '' }}>Reconditionné</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="p-6 bg-amber-500 text-slate-950 rounded-3xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 transition-colors">
                <i data-lucide="filter" class="w-5 h-5"></i>
            </button>
            @if(request()->anyFilled(['search', 'availability', 'condition']))
                <a href="{{ route('admin.cars.index') }}" class="p-6 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-3xl transition border border-slate-100 dark:border-white/5 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </a>
            @endif
        </div>
    </form>

    <!-- Cars Table -->
    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-lg dark:shadow-2xl backdrop-blur-sm transition-colors">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5 transition-colors">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Véhicule</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Détails techniques</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Prix & État</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Statut</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic transition-colors">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                @forelse($voitures as $car)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-14 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 flex items-center justify-center overflow-hidden shadow-inner p-1 transition-colors">
                                @if($car->photo_principale)
                                    <img src="{{ $car->photo_principale }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i data-lucide="car" class="w-6 h-6 text-slate-700"></i>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900 dark:text-white tracking-tight italic uppercase transition-colors">{{ $car->marque }} {{ $car->modele }}</div>
                                <div class="text-[9px] text-amber-500 font-bold uppercase tracking-widest italic mt-1 transition-colors">{{ $car->numero_chassis ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="space-y-1">
                            <div class="text-[11px] text-slate-600 dark:text-slate-300 font-bold italic transition-colors">
                                <span class="text-slate-400 dark:text-slate-500 uppercase text-[9px] transition-colors">Année:</span> {{ $car->annee }}
                            </div>
                            <div class="text-[11px] text-slate-600 dark:text-slate-300 font-bold italic transition-colors">
                                <span class="text-slate-400 dark:text-slate-500 uppercase text-[9px] transition-colors">KM:</span> {{ number_format($car->kilometrage, 0, ',', ' ') }}
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-slate-900 dark:text-white italic tracking-tight transition-colors">{{ number_format($car->prix, 0, ',', ' ') }} <span class="text-[10px] transition-colors">FCFA</span></div>
                        <div class="text-[9px] text-slate-400 dark:text-slate-600 font-bold uppercase tracking-widest italic mt-1 transition-colors">{{ $car->etat }}</div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $statusColor = match($car->disponibilite) {
                                'disponible' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'importation' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'reserve' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                'vendu' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border {{ $statusColor }} text-[9px] font-black uppercase tracking-widest italic leading-none">
                            {{ str_replace('_', ' ', $car->disponibilite) }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openShowCarModal({{ json_encode($car) }})" class="p-2.5 bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition rounded-xl group/btn border border-slate-100 dark:border-white/5 transition-colors">
                                <i data-lucide="eye" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                            <button onclick="openEditCarModal({{ json_encode($car) }})" class="p-2.5 bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-amber-100 dark:hover:bg-amber-500 transition rounded-xl group/btn border border-slate-100 dark:border-white/5 transition-colors">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                            <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Supprimer ce véhicule du catalogue ?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-white hover:bg-rose-500 transition rounded-xl group/btn border border-slate-100 dark:border-white/5 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucun véhicule au catalogue.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($voitures->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-white/5 transition-colors">
            {{ $voitures->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Car Modal -->
<div id="createCarModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('createCarModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-4xl p-12 shadow-2xl rounded-[5rem] rounded-tr-2xl rounded-bl-2xl overflow-hidden animate-in fade-in zoom-in duration-300 transition-colors">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center justify-between mb-12 relative">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Référencer un Véhicule</h2>
                    <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mt-1 italic transition-colors">Ajout d'une nouvelle unité au stock ou en importation</p>
                </div>
                <button onclick="closeModal('createCarModal')" class="p-5 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-[2rem] border border-slate-100 dark:border-white/5 transition hover:scale-110 duration-300 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12 relative max-h-[70vh] overflow-y-auto px-4 custom-scrollbar">
                @csrf
                
                <!-- Section 1: Informations Générales -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Identité & Statut</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Marque</label>
                            <input type="text" name="marque" required placeholder="Ex: Toyota" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Modèle</label>
                            <input type="text" name="modele" required placeholder="Ex: RAV4" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Année</label>
                            <input type="number" name="annee" required placeholder="2023" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Prix de vente (FCFA)</label>
                            <input type="number" name="prix" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Kilométrage</label>
                            <input type="number" name="kilometrage" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">État général</label>
                            <select name="etat" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none transition-colors">
                                <option value="neuf">Neuf</option>
                                <option value="occasion">Occasion</option>
                                <option value="excellent">Excellent</option>
                                <option value="bon">Bon état</option>
                                <option value="reconditionne">Reconditionné</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Spécifications Techniques -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Technique & Performance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Puissance (CH)</label>
                            <input type="text" name="puissance" placeholder="Ex: 150 ch" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Moteur / Cylindrée</label>
                            <input type="text" name="moteur" placeholder="Ex: 2.0L V6" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Transmission</label>
                            <select name="transmission" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="automatique">Automatique</option>
                                <option value="manuelle">Manuelle</option>
                                <option value="semi-automatique">Semi-Auto</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Carburant</label>
                            <select name="carburant" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="essence">Essence</option>
                                <option value="diesel">Diesel</option>
                                <option value="hybride">Hybride</option>
                                <option value="electrique">Electrique</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Consommation Mixte</label>
                            <input type="text" name="consommation_mixte" placeholder="Ex: 6.5 L/100" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Vitesse Max</label>
                            <input type="text" name="vitesse_max" placeholder="Ex: 210 km/h" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">0-100 km/h</label>
                            <input type="text" name="acceleration_0_100" placeholder="Ex: 8.2s" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Type de Véhicule</label>
                            <select name="type_vehicule" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="berline">Berline</option>
                                <option value="suv">SUV / Crossover</option>
                                <option value="4x4">4x4 / Tout-terrain</option>
                                <option value="pickup">Pick-up</option>
                                <option value="coupe">Coupé</option>
                                <option value="utilitaire">Utilitaire</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Marché & Historique -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Origine & Historique</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Marché d'Origine</label>
                            <select name="origine_marche" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="europe">Europe</option>
                                <option value="usa">USA / Canada</option>
                                <option value="gcc">GCC (Dubaï, etc.)</option>
                                <option value="asie">Asie (Japon, Corée)</option>
                                <option value="local">Local Africa</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Nbre de Propriétaires</label>
                            <input type="number" name="nombre_proprietaires" value="1" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-4 pt-8">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="carnet_entretien_ajour" value="1" class="w-5 h-5 rounded border-slate-200 dark:border-white/10 text-amber-500 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-950 transition-colors">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors italic">Carnet d'entretien à jour</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="non_fumeur" value="1" class="w-5 h-5 rounded border-slate-200 dark:border-white/10 text-amber-500 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-950 transition-colors">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors italic">Véhicule Non-fumeur</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Équipements & Options (JSON Structure) -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Session des Équipements</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Confort -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-amber-500 pl-3">Confort & Intérieur</h4>
                            <div class="space-y-3">
                                @foreach(['Climatisation Bi-zone', 'Sièges Cuir', 'Sièges Chauffants', 'Toit Ouvrant/Pano', 'Régulateur Adaptatif', 'Démarrage sans clé'] as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[confort][]" value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-emerald-500 focus:ring-emerald-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sécurité -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-rose-500 pl-3">Sécurité & Aide</h4>
                            <div class="space-y-3">
                                @foreach(['ABS / ESP', 'Airbags Front/Lat', 'Caméra 360°', 'Capteurs de stationnement', 'Aide au maintien de voie', 'Freinage d\'urgence'] as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[securite][]" value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-rose-500 focus:ring-rose-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Multimédia -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-blue-500 pl-3">Tech & Multimédia</h4>
                            <div class="space-y-3">
                                @foreach(['Écran Tactile', 'Système Navigation GPS', 'Apple CarPlay / Android Auto', 'Système Audio Premium', 'Chargeur Induction', 'Bluetooth / USB'] as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[tech][]" value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-blue-500 focus:ring-blue-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Extérieur -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-slate-500 pl-3">Design & Extérieur</h4>
                            <div class="space-y-3">
                                @foreach(['Jantes Alliage', 'Feux LED / Matrix', 'Pack Chrome', 'Rétros Électriques', 'Peinture Métallisée', 'Attelage Remorque'] as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[exterieur][]" value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-slate-500 focus:ring-slate-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Visuels -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Assets & Visuels</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Photo Principale</label>
                            <div class="relative group">
                                <input type="file" name="photo_principale" onchange="handleFilePreview(this, 'create_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full py-8 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] bg-slate-50/50 dark:bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-950 group-hover:border-amber-500/50 transition transition-colors">
                                    <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 dark:text-slate-600 group-hover:text-amber-500 transition transition-colors"></i>
                                    <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">Image de couverture</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Galerie Photos (Multiple)</label>
                            <div class="relative group">
                                <input type="file" name="photos[]" multiple onchange="handleFilePreview(this, 'create_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full py-8 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] bg-slate-50/50 dark:bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-950 group-hover:border-amber-500/50 transition transition-colors">
                                    <i data-lucide="images" class="w-8 h-8 text-slate-400 dark:text-slate-600 group-hover:text-amber-500 transition transition-colors"></i>
                                    <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">Vues secondaires</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="create_media_preview" class="flex flex-wrap gap-4 empty:hidden"></div>
                </div>

                <div class="pt-10 flex gap-6 pb-4">
                    <button type="button" onclick="closeModal('createCarModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-900 transition transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Confirmer l'inscription Catalogue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Car Modal -->
<div id="editCarModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('editCarModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-4xl p-12 shadow-2xl rounded-[5rem] rounded-tl-2xl rounded-br-2xl overflow-hidden animate-in fade-in zoom-in duration-300 transition-colors">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center justify-between mb-12 relative">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Mettre à Jour</h2>
                    <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mt-1 italic transition-colors">Modification des données catalogue</p>
                </div>
                <button onclick="closeModal('editCarModal')" class="p-5 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-[2rem] border border-slate-100 dark:border-white/5 transition hover:scale-110 duration-300 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="editCarForm" method="POST" enctype="multipart/form-data" class="space-y-12 relative max-h-[70vh] overflow-y-auto px-4 custom-scrollbar">
                @csrf
                @method('PUT')
                
                <!-- Identité & Statut -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Identité & Statut Révisés</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Prix de vente (FCFA)</label>
                            <input type="number" name="prix" id="edit_prix" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner font-black transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Kilométrage Actuel</label>
                            <input type="number" name="kilometrage" id="edit_kilometrage" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Statut Stock</label>
                            <select name="disponibilite" id="edit_disponibilite" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none font-black uppercase transition-colors">
                                <option value="disponible">En Stock</option>
                                <option value="importation">En Importation</option>
                                <option value="reserve">Réservé</option>
                                <option value="vendu">Vendu</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">État général</label>
                            <select name="etat" id="edit_etat" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="neuf">Neuf</option>
                                <option value="occasion">Occasion</option>
                                <option value="excellent">Excellent</option>
                                <option value="bon">Bon état</option>
                                <option value="reconditionne">Reconditionné</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Pays d'Origine</label>
                            <input type="text" name="pays_origine" id="edit_pays_origine" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner uppercase transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Technique & Performance -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Technique & Performance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Puissance (CH)</label>
                            <input type="text" name="puissance" id="edit_puissance" placeholder="Ex: 150 ch" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Moteur</label>
                            <input type="text" name="moteur" id="edit_moteur" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Transmission</label>
                            <select name="transmission" id="edit_transmission" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="automatique">Automatique</option>
                                <option value="manuelle">Manuelle</option>
                                <option value="semi-automatique">Semi-Auto</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Carburant</label>
                            <select name="carburant" id="edit_carburant" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="essence">Essence</option>
                                <option value="diesel">Diesel</option>
                                <option value="hybride">Hybride</option>
                                <option value="electrique">Electrique</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Marché & Historique -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Origine & Historique</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Marché d'Origine</label>
                            <select name="origine_marche" id="edit_origine_marche" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="europe">Europe</option>
                                <option value="usa">USA / Canada</option>
                                <option value="gcc">GCC (Dubaï, etc.)</option>
                                <option value="asie">Asie (Japon, Corée)</option>
                                <option value="local">Local Africa</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Nbre de Propriétaires</label>
                            <input type="number" name="nombre_proprietaires" id="edit_nombre_proprietaires" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition transition-colors">
                        </div>
                        <div class="space-y-4 pt-8">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="carnet_entretien_ajour" id="edit_carnet_entretien" value="1" class="w-5 h-5 rounded border-slate-200 dark:border-white/10 text-amber-500 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-950 transition-colors">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors italic">Entretien à jour</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="non_fumeur" id="edit_non_fumeur" value="1" class="w-5 h-5 rounded border-slate-200 dark:border-white/10 text-amber-500 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-950 transition-colors">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors italic">Non-fumeur</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Équipements Session -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Session des Équipements</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach(['confort' => ['Climatisation Bi-zone', 'Sièges Cuir', 'Sièges Chauffants', 'Toit Ouvrant/Pano', 'Régulateur Adaptatif', 'Démarrage sans clé'], 
                                  'securite' => ['ABS / ESP', 'Airbags Front/Lat', 'Caméra 360°', 'Capteurs de stationnement', 'Aide au maintien de voie', 'Freinage d\'urgence'],
                                  'tech' => ['Écran Tactile', 'Système Navigation GPS', 'Apple CarPlay / Android Auto', 'Système Audio Premium', 'Chargeur Induction', 'Bluetooth / USB'],
                                  'exterieur' => ['Jantes Alliage', 'Feux LED / Matrix', 'Pack Chrome', 'Rétros Électriques', 'Peinture Métallisée', 'Attelage Remorque']] as $cat => $opts)
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-{{ $cat == 'confort' ? 'amber' : ($cat == 'securite' ? 'rose' : ($cat == 'tech' ? 'blue' : 'slate')) }}-500 pl-3 uppercase">{{ $cat }}</h4>
                            <div class="space-y-3">
                                @foreach($opts as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[{{ $cat }}][]" value="{{ $opt }}" class="equipement-checkbox w-4 h-4 rounded border-slate-200 dark:border-white/10 text-emerald-500 focus:ring-emerald-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Description additionnelle</label>
                    <textarea name="description" id="edit_description" rows="3" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner resize-none transition-colors"></textarea>
                </div>

                <!-- Visuels Assets -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Mise à jour Visuelle</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Nouvelle Couverture</label>
                            <div class="relative group">
                                <input type="file" name="photo_principale" onchange="handleFilePreview(this, 'edit_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full py-8 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] bg-slate-50/50 dark:bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-950 transition transition-colors">
                                    <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 dark:text-slate-600 transition-colors"></i>
                                    <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic transition-colors">Changer l'image principale</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Ajouter des Vues</label>
                            <div class="relative group">
                                <input type="file" name="photos[]" multiple onchange="handleFilePreview(this, 'edit_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full py-8 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] bg-slate-50/50 dark:bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-950 transition transition-colors">
                                    <i data-lucide="images" class="w-8 h-8 text-slate-400 dark:text-slate-600 transition-colors"></i>
                                    <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic transition-colors">Enrichir la galerie</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="edit_media_preview" class="flex flex-wrap gap-4 empty:hidden"></div>
                </div>

                <div class="pt-10 flex gap-6 pb-6 mt-auto">
                    <button type="button" onclick="closeModal('editCarModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-900 transition transition-colors">Sortir</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Sauvegarder les Données</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Car Modal: Premium Experience -->
<div id="showCarModal" class="fixed inset-0 z-[100] hidden overflow-hidden">
    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-xl transition-all duration-500" onclick="closeModal('showCarModal')"></div>
    <div class="absolute inset-y-0 right-0 w-full lg:w-[85%] bg-white dark:bg-slate-950 shadow-2xl flex flex-col transform transition-transform duration-500 translate-x-full overflow-hidden border-l border-slate-100 dark:border-white/5" id="showCarModalContent">
        <!-- Header section with car brand/model and big status -->
        <div class="p-8 lg:p-12 flex flex-col lg:flex-row justify-between items-start gap-8 bg-slate-50/50 dark:bg-slate-900/30 border-b border-slate-100 dark:border-white/5 transition-colors">
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <span id="show_marque" class="px-5 py-2.5 bg-amber-500/10 text-amber-600 dark:text-amber-500 rounded-xl text-xs font-black uppercase tracking-[0.2em] italic transition-colors"></span>
                    <span id="show_annee_val" class="text-slate-400 dark:text-slate-600 font-black italic tracking-widest transition-colors"></span>
                </div>
                <h2 id="show_modele_val" class="text-5xl lg:text-7xl font-black text-slate-950 dark:text-white uppercase tracking-tighter transition-colors leading-none"></h2>
                <div class="flex items-center gap-4 transition-colors">
                    <p id="show_prix_val" class="text-3xl font-black text-amber-500 italic transition-colors"></p>
                    <span id="show_badge_val" class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors"></span>
                </div>
            </div>
            <button onclick="closeModal('showCarModal')" class="p-4 hover:bg-slate-100 dark:hover:bg-white/5 rounded-full transition-colors group">
                <i data-lucide="x" class="w-8 h-8 text-slate-400 group-hover:text-slate-950 dark:group-hover:text-white transition-colors"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-8 lg:px-12 py-12 custom-scrollbar space-y-20">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-16">
                <!-- Technical Specs Column -->
                <div class="xl:col-span-4 space-y-12">
                    <section class="space-y-8">
                        <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.3em] italic border-l-4 border-amber-500 pl-4">Fiche Technique</h3>
                        <div class="space-y-4">
                            @foreach([
                                ['label' => 'Motorisation', 'id' => 'show_moteur_val', 'icon' => 'zap'],
                                ['label' => 'Puissance', 'id' => 'show_puissance_val', 'icon' => 'gauge'],
                                ['label' => 'Transmission', 'id' => 'show_transmission_val', 'icon' => 'settings-2'],
                                ['label' => 'Carburant', 'id' => 'show_carburant_val', 'icon' => 'fuel'],
                                ['label' => 'Kilométrage', 'id' => 'show_km_val', 'icon' => 'milestone'],
                                ['label' => 'Consommation', 'id' => 'show_conso_val', 'icon' => 'droplet'],
                                ['label' => '0-100 km/h', 'id' => 'show_acceleration_val', 'icon' => 'timer'],
                                ['label' => 'Vitesse Max', 'id' => 'show_vmax_val', 'icon' => 'fast-forward'],
                            ] as $spec)
                            <div class="flex items-center justify-between py-5 border-b border-slate-100 dark:border-white/5 group hover:border-amber-500/30 transition-colors">
                                <div class="flex items-center gap-4 transition-colors">
                                    <i data-lucide="{{ $spec['icon'] }}" class="w-4 h-4 text-slate-400 group-hover:text-amber-500 transition-colors"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 transition-colors">{{ $spec['label'] }}</span>
                                </div>
                                <span id="{{ $spec['id'] }}" class="text-sm font-black text-slate-900 dark:text-white transition-colors"></span>
                            </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="space-y-8">
                        <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.3em] italic border-l-4 border-amber-500 pl-4">Historique & État</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 italic">État</span>
                                <span id="show_etat_val" class="text-xs font-black text-slate-950 dark:text-white uppercase italic"></span>
                            </div>
                            <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 italic">Origine</span>
                                <span id="show_pays_val" class="text-xs font-black text-slate-950 dark:text-white uppercase italic"></span>
                            </div>
                            <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 italic">Propriétaires</span>
                                <span id="show_owners_val" class="text-xs font-black text-slate-950 dark:text-white uppercase italic"></span>
                            </div>
                            <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 italic">Marché</span>
                                <span id="show_marche_val" class="text-xs font-black text-slate-950 dark:text-white uppercase italic"></span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div id="badge_entretien_val" class="hidden flex items-center gap-3 px-4 py-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 rounded-2xl border border-emerald-500/20 transition-colors">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest italic">Carnet d'entretien à jour</span>
                            </div>
                            <div id="badge_nonfumeur_val" class="hidden flex items-center gap-3 px-4 py-3 bg-blue-500/10 text-blue-600 dark:text-blue-500 rounded-2xl border border-blue-500/20 transition-colors">
                                <i data-lucide="wind" class="w-4 h-4"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest italic">Véhicule Non-fumeur</span>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Equipment & Gallery Column -->
                <div class="xl:col-span-8 space-y-16">
                    <section class="space-y-8">
                        <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.3em] italic border-l-4 border-emerald-500 pl-4">Équipements & Options</h3>
                        <div id="equipment_container_val" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Groups will be injected by JS -->
                        </div>
                    </section>

                    <section class="space-y-8">
                        <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.3em] italic border-l-4 border-amber-500 pl-4">Galerie Haute Définition</h3>
                        <div id="show_gallery_val" class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <!-- Media items will be injected by JS -->
                        </div>
                    </section>

                    <section class="space-y-8">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic border-l-4 border-slate-500 pl-4">Description Détaillée</h3>
                        <div class="p-10 bg-slate-50 dark:bg-slate-950 rounded-[3rem] border border-slate-100 dark:border-white/5 transition-colors">
                            <p id="show_desc_val" class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm transition-colors whitespace-pre-wrap italic font-medium"></p>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="p-8 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/30 flex justify-end transition-colors">
            <button onclick="closeModal('showCarModal')" class="px-12 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-950 bg-amber-500 rounded-3xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 italic transition-colors">Fermer la Fiche</button>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openShowCarModal(car) {
        // Basic Info
        document.getElementById('show_marque').innerText = car.marque;
        document.getElementById('show_annee_val').innerText = car.annee;
        document.getElementById('show_modele_val').innerText = car.modele;
        document.getElementById('show_prix_val').innerText = new Intl.NumberFormat('fr-FR').format(car.prix) + ' FCFA';
        document.getElementById('show_km_val').innerText = (car.kilometrage || 0).toLocaleString() + ' KM';
        document.getElementById('show_etat_val').innerText = (car.etat || '-').toUpperCase();
        document.getElementById('show_pays_val').innerText = (car.pays_origine || '-').toUpperCase();
        document.getElementById('show_desc_val').innerText = car.description || 'AUCUNE DESCRIPTION DISPONIBLE';

        // Tech specs
        document.getElementById('show_moteur_val').innerText = car.moteur || '-';
        document.getElementById('show_puissance_val').innerText = car.puissance || '-';
        document.getElementById('show_transmission_val').innerText = (car.transmission || '-').toUpperCase();
        document.getElementById('show_carburant_val').innerText = (car.carburant || '-').toUpperCase();
        document.getElementById('show_conso_val').innerText = car.consommation_mixte || 'N/C';
        document.getElementById('show_acceleration_val').innerText = car.acceleration_0_100 || 'N/C';
        document.getElementById('show_vmax_val').innerText = car.vitesse_max || 'N/C';
        
        // Market & History
        document.getElementById('show_owners_val').innerText = car.nombre_proprietaires || 1;
        document.getElementById('show_marche_val').innerText = (car.origine_marche || 'N/C').toUpperCase();
        
        document.getElementById('badge_entretien_val').classList.toggle('hidden', !car.carnet_entretien_ajour);
        document.getElementById('badge_nonfumeur_val').classList.toggle('hidden', !car.non_fumeur);

        // Status Badge
        const statusEl = document.getElementById('show_badge_val');
        statusEl.innerText = car.disponibilite.toUpperCase();
        const colors = {
            'disponible': 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-500',
            'importation': 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
            'reserve': 'bg-rose-500/10 text-rose-600 dark:text-rose-400',
            'vendu': 'bg-slate-500/10 text-slate-600'
        };
        statusEl.className = `px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest ${colors[car.disponibilite] || colors['vendu']}`;

        // Equipment Container
        const eqContainer = document.getElementById('equipment_container_val');
        eqContainer.innerHTML = '';
        
        if (car.equipements_details) {
            let details = car.equipements_details;
            if (typeof details === 'string') try { details = JSON.parse(details); } catch(e) { details = {}; }

            const categories = {
                'confort': { label: 'Confort & Intérieur', color: 'amber' },
                'securite': { label: 'Sécurité & Aide', color: 'rose' },
                'tech': { label: 'Tech & Multimédia', color: 'blue' },
                'exterieur': { label: 'Design & Extérieur', color: 'slate' }
            };

            Object.keys(details).forEach(catKey => {
                if (details[catKey] && details[catKey].length > 0) {
                    const cat = categories[catKey] || { label: catKey.toUpperCase(), color: 'slate' };
                    const group = document.createElement('div');
                    group.className = 'p-8 bg-slate-50 dark:bg-slate-900/50 rounded-[2.5rem] border border-slate-100 dark:border-white/5 transition-colors';
                    group.innerHTML = `
                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-${cat.color}-500 pl-4">${cat.label}</h4>
                        <div class="flex flex-wrap gap-4">
                            ${details[catKey].map(opt => `
                                <span class="px-4 py-2 bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-full text-[9px] font-bold text-slate-600 dark:text-slate-300 shadow-sm transition-colors">${opt}</span>
                            `).join('')}
                        </div>
                    `;
                    eqContainer.appendChild(group);
                }
            });
        }

        // Gallery HD
        const gallery = document.getElementById('show_gallery_val');
        gallery.innerHTML = '';
        
        const allMedia = [];
        if (car.photo_principale) allMedia.push({type: 'photo', url: car.photo_principale});
        if (car.photos) car.photos.forEach(p => allMedia.push({type: 'photo', url: p.url}));
        if (car.videos) car.videos.forEach(v => allMedia.push({type: 'video', url: v.url}));

        allMedia.forEach(media => {
            const div = document.createElement('div');
            div.className = 'aspect-square rounded-[2rem] overflow-hidden group border border-slate-100 dark:border-white/5 transition-colors relative';
            if (media.type === 'photo') {
                div.innerHTML = `<img src="${media.url}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">`;
            } else {
                div.innerHTML = `
                    <video src="${media.url}" class="w-full h-full object-cover"></video>
                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/40 transition-colors cursor-pointer" onclick="this.previousElementSibling.play(); this.classList.add('hidden')">
                        <i data-lucide="play" class="w-10 h-10 text-white fill-white"></i>
                    </div>
                `;
            }
            gallery.appendChild(div);
        });

        openModal('showCarModal');
        // Trigger slide-in animation
        setTimeout(() => {
            document.getElementById('showCarModalContent').classList.remove('translate-x-full');
        }, 10);
        lucide.createIcons();
    }

    function openEditCarModal(car) {
        const form = document.getElementById('editCarForm');
        form.action = `/admin/cars/${car.id}`;
        
        document.getElementById('edit_prix').value = car.prix;
        document.getElementById('edit_kilometrage').value = car.kilometrage;
        document.getElementById('edit_etat').value = car.etat;
        document.getElementById('edit_disponibilite').value = car.disponibilite;
        document.getElementById('edit_pays_origine').value = car.pays_origine;
        document.getElementById('edit_description').value = car.description || '';

        // New technical & market fields
        if(document.getElementById('edit_puissance')) document.getElementById('edit_puissance').value = car.puissance || '';
        if(document.getElementById('edit_moteur')) document.getElementById('edit_moteur').value = car.moteur || '';
        if(document.getElementById('edit_transmission')) document.getElementById('edit_transmission').value = car.transmission || 'automatique';
        if(document.getElementById('edit_carburant')) document.getElementById('edit_carburant').value = car.carburant || 'essence';
        if(document.getElementById('edit_origine_marche')) document.getElementById('edit_origine_marche').value = car.origine_marche || 'europe';
        if(document.getElementById('edit_nombre_proprietaires')) document.getElementById('edit_nombre_proprietaires').value = car.nombre_proprietaires || 1;
        
        if(document.getElementById('edit_carnet_entretien')) document.getElementById('edit_carnet_entretien').checked = !!car.carnet_entretien_ajour;
        if(document.getElementById('edit_non_fumeur')) document.getElementById('edit_non_fumeur').checked = !!car.non_fumeur;

        // Structured Equipment JSON
        const checkboxes = document.querySelectorAll('.equipement-checkbox');
        checkboxes.forEach(cb => cb.checked = false); // Reset

        if (car.equipements_details) {
            let details = car.equipements_details;
            // Handle stringified JSON if needed (though Eloquent should have cast it to array/obj)
            if (typeof details === 'string') {
                try { details = JSON.parse(details); } catch(e) { details = {}; }
            }
            
            Object.keys(details).forEach(cat => {
                if (Array.isArray(details[cat])) {
                    details[cat].forEach(opt => {
                        const cb = document.querySelector(`.equipement-checkbox[name="equipements_details[${cat}][]"][value="${opt}"]`);
                        if (cb) cb.checked = true;
                    });
                }
            });
        }

        openModal('editCarModal');
    }

    function handleFilePreview(input, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                const div = document.createElement('div');
                div.className = 'relative w-24 h-24 rounded-2xl overflow-hidden border border-white/10 bg-slate-950 group';
                
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    } else if (file.type.startsWith('video/')) {
                        div.innerHTML = `
                            <div class="absolute inset-0 flex items-center justify-center bg-black/40">
                                <i data-lucide="video" class="w-6 h-6 text-white"></i>
                            </div>
                            <video src="${e.target.result}" class="w-full h-full object-cover"></video>
                        `;
                    }
                    container.appendChild(div);
                    lucide.createIcons();
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // Close on escape
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('createCarModal');
            closeModal('editCarModal');
            closeModal('showCarModal');
        }
    });
</script>
@endsection
@endsection
