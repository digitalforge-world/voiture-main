@extends('layouts.admin')

@section('title', 'Gestion du Catalogue - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Inventaire Véhicules</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Gestion du stock local et des importations en cours</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openModal('createCarModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-white hover:scale-105 duration-300 shadow-xl shadow-amber-900/10">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Ajouter un véhicule
            </button>
        </div>
    </div>

    <!-- Filters & Search -->
    <form action="{{ route('admin.cars.index') }}" method="GET" class="p-8 bg-slate-900/30 border border-slate-900 rounded-[4rem] rounded-tr-xl rounded-bl-xl flex flex-wrap items-center gap-8 shadow-2xl backdrop-blur-sm">
        <div class="flex-grow min-w-[350px] relative group">
            <i data-lucide="search" class="absolute left-8 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500 group-focus-within:text-amber-500 transition-colors"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="RECHERCHER PAR MARQUE, MODÈLE OU VIN..." 
                class="w-full py-6 pl-20 pr-10 bg-slate-950 border border-white/5 rounded-3xl text-[10px] font-black uppercase tracking-widest text-white placeholder:text-slate-700 focus:ring-1 focus:ring-amber-500 transition shadow-inner outline-none"
            >
        </div>

        <div class="flex items-center gap-4 px-6 py-3 bg-slate-950 rounded-2xl border border-white/5">
            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Disponibilité :</span>
            <select name="availability" onchange="this.form.submit()" class="bg-transparent border-none text-[10px] font-black text-amber-500 uppercase tracking-widest focus:ring-0 cursor-pointer p-0">
                <option value="">Tous</option>
                <option value="disponible" {{ request('availability') == 'disponible' ? 'selected' : '' }}>En Stock</option>
                <option value="importation" {{ request('availability') == 'importation' ? 'selected' : '' }}>En Importation</option>
                <option value="reserve" {{ request('availability') == 'reserve' ? 'selected' : '' }}>Réservé</option>
                <option value="vendu" {{ request('availability') == 'vendu' ? 'selected' : '' }}>Vendu</option>
            </select>
        </div>

        <div class="flex items-center gap-4 px-6 py-3 bg-slate-950 rounded-2xl border border-white/5">
            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">État :</span>
            <select name="condition" onchange="this.form.submit()" class="bg-transparent border-none text-[10px] font-black text-emerald-500 uppercase tracking-widest focus:ring-0 cursor-pointer p-0">
                <option value="">Tous</option>
                <option value="neuf" {{ request('condition') == 'neuf' ? 'selected' : '' }}>Neuf</option>
                <option value="occasion" {{ request('condition') == 'occasion' ? 'selected' : '' }}>Occasion</option>
                <option value="reconditionne" {{ request('condition') == 'reconditionne' ? 'selected' : '' }}>Reconditionné</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="p-6 bg-amber-500 text-slate-950 rounded-3xl hover:bg-white transition shadow-xl shadow-amber-500/20">
                <i data-lucide="filter" class="w-5 h-5"></i>
            </button>
            @if(request()->anyFilled(['search', 'availability', 'condition']))
                <a href="{{ route('admin.cars.index') }}" class="p-6 bg-slate-950 text-slate-500 hover:text-white rounded-3xl transition border border-white/5">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </a>
            @endif
        </div>
    </form>

    <!-- Cars Table -->
    <div class="border overflow-hidden bg-slate-950/50 border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left">
            <thead class="bg-slate-900/50 border-b border-white/5">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Véhicule</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Détails techniques</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Prix & État</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Statut</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($voitures as $car)
                <tr class="group hover:bg-white/[0.02] transition duration-300">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-14 rounded-xl bg-slate-900 border border-white/5 flex items-center justify-center overflow-hidden shadow-inner p-1">
                                @if($car->photo_principale)
                                    <img src="{{ $car->photo_principale }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i data-lucide="car" class="w-6 h-6 text-slate-700"></i>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-black text-white tracking-tight italic uppercase">{{ $car->marque }} {{ $car->modele }}</div>
                                <div class="text-[9px] text-amber-500 font-bold uppercase tracking-widest italic mt-1">{{ $car->vin ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="space-y-1">
                            <div class="text-[11px] text-slate-300 font-bold italic">
                                <span class="text-slate-500 uppercase text-[9px]">Année:</span> {{ $car->annee }}
                            </div>
                            <div class="text-[11px] text-slate-300 font-bold italic">
                                <span class="text-slate-500 uppercase text-[9px]">KM:</span> {{ number_format($car->kilometrage, 0, ',', ' ') }}
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-white italic tracking-tight">{{ number_format($car->prix, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></div>
                        <div class="text-[9px] text-slate-600 font-bold uppercase tracking-widest italic mt-1">{{ $car->etat }}</div>
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
                            <button onclick="openShowCarModal({{ json_encode($car) }})" class="p-2.5 bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-xl group/btn border border-white/5">
                                <i data-lucide="eye" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                            <button onclick="openEditCarModal({{ json_encode($car) }})" class="p-2.5 bg-slate-900 text-slate-400 hover:text-white hover:bg-amber-500 transition rounded-xl group/btn border border-white/5">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                            <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Supprimer ce véhicule du catalogue ?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-slate-900 text-slate-400 hover:text-white hover:bg-rose-500 transition rounded-xl group/btn border border-white/5">
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
        <div class="px-8 py-6 bg-slate-900/30 border-t border-white/5">
            {{ $voitures->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Car Modal -->
<div id="createCarModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('createCarModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-4xl p-12 shadow-2xl rounded-[5rem] rounded-tr-2xl rounded-bl-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center justify-between mb-12 relative">
                <div>
                    <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">Référencer un Véhicule</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mt-1 italic">Ajout d'une nouvelle unité au stock ou en importation</p>
                </div>
                <button onclick="closeModal('createCarModal')" class="p-5 bg-slate-950 text-slate-500 hover:text-white rounded-[2rem] border border-white/5 transition hover:scale-110 duration-300">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Marque</label>
                        <input type="text" name="marque" required placeholder="Ex: Toyota" class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Modèle</label>
                        <input type="text" name="modele" required placeholder="Ex: RAV4" class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Année</label>
                        <input type="number" name="annee" required placeholder="2023" class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Prix de vente (FCFA)</label>
                        <input type="number" name="prix" required class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Kilométrage</label>
                        <input type="number" name="kilometrage" required class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">État</label>
                        <select name="etat" class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none">
                            <option value="neuf">Neuf</option>
                            <option value="occasion">Occasion</option>
                            <option value="reconditionne">Reconditionné</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">VIN (Châssis)</label>
                        <input type="text" name="vin" placeholder="Numéro d'identification" class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner uppercase tracking-wider">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Disponibilité</label>
                        <select name="disponibilite" class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none">
                            <option value="disponible">En Stock</option>
                            <option value="importation">En Importation</option>
                            <option value="reserve">Réservé</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Photo Principale</label>
                        <div class="relative group">
                            <input type="file" name="photo_principale" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full py-8 border-2 border-dashed border-white/5 rounded-[2.5rem] bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-950 group-hover:border-amber-500/50 transition">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-600 group-hover:text-amber-500 transition"></i>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic group-hover:text-slate-300">Image de couverture</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Galerie Photos (Multiple)</label>
                        <div class="relative group">
                            <input type="file" name="photos[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full py-8 border-2 border-dashed border-white/5 rounded-[2.5rem] bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-950 group-hover:border-amber-500/50 transition">
                                <i data-lucide="images" class="w-8 h-8 text-slate-600 group-hover:text-amber-500 transition"></i>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic group-hover:text-slate-300">Vues secondaires</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex gap-6">
                    <button type="button" onclick="closeModal('createCarModal')" class="flex-1 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2rem] border border-white/5 hover:bg-slate-900 transition">Annuler</button>
                    <button type="submit" class="flex-[2] py-5 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2rem] hover:bg-white transition shadow-xl shadow-amber-500/10">Inscrire au catalogue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Car Modal -->
<div id="editCarModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('editCarModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-4xl p-12 shadow-2xl rounded-[5rem] rounded-tl-2xl rounded-br-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center justify-between mb-12 relative">
                <div>
                    <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter">Mettre à Jour</h2>
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mt-1 italic">Modification des données catalogue</p>
                </div>
                <button onclick="closeModal('editCarModal')" class="p-5 bg-slate-950 text-slate-500 hover:text-white rounded-[2rem] border border-white/5 transition hover:scale-110 duration-300">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="editCarForm" method="POST" enctype="multipart/form-data" class="space-y-8 relative">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Prix de vente (FCFA)</label>
                        <input type="number" name="prix" id="edit_prix" required class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Disponibilité</label>
                        <select name="disponibilite" id="edit_disponibilite" class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none font-black uppercase">
                            <option value="disponible">En Stock</option>
                            <option value="importation">En Importation</option>
                            <option value="reserve">Réservé</option>
                            <option value="vendu">Vendu</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Kilométrage Actuel</label>
                        <input type="number" name="kilometrage" id="edit_kilometrage" required class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">État</label>
                        <select name="etat" id="edit_etat" class="w-full py-4 px-6 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none">
                            <option value="neuf">Neuf</option>
                            <option value="occasion">Occasion</option>
                            <option value="reconditionne">Reconditionné</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Ajouter des photos à la galerie (Sélection multiple)</label>
                    <div class="relative group">
                        <input type="file" name="photos[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="w-full py-8 border-2 border-dashed border-white/5 rounded-[2.5rem] bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-950 group-hover:border-amber-500/50 transition">
                            <i data-lucide="images" class="w-8 h-8 text-slate-600 group-hover:text-amber-500 transition"></i>
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic group-hover:text-slate-300">Vues additionnelles</span>
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex gap-6">
                    <button type="button" onclick="closeModal('editCarModal')" class="flex-1 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2rem] border border-white/5 hover:bg-slate-900 transition mt-auto">Fermer</button>
                    <button type="submit" class="flex-[2] py-5 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2rem] hover:bg-white transition shadow-xl shadow-amber-500/10">Valider les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Car Modal -->
<div id="showCarModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/95 backdrop-blur-2xl" onclick="closeModal('showCarModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-950 border border-white/5 w-full max-w-5xl rounded-[6rem] rounded-tl-xl rounded-br-xl overflow-hidden shadow-2xl flex flex-col md:flex-row animate-in fade-in slide-in-from-bottom duration-500">
             <!-- Media Side -->
              <div class="w-full md:w-1/2 h-[400px] md:h-auto relative bg-slate-900 overflow-hidden group">
                <img id="show_photo" src="" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                
                <!-- Gallery Overlay -->
                <div class="absolute bottom-32 inset-x-0 px-10">
                    <div id="show_car_gallery" class="flex items-center gap-3 overflow-x-auto pb-4 custom-scrollbar no-scrollbar">
                        <!-- Thumbs dynamic -->
                    </div>
                </div>

                <div class="absolute bottom-10 left-10">
                    <span id="show_badge" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest italic border list-none mb-3 inline-block"></span>
                    <h3 id="show_title" class="text-4xl font-black text-white italic uppercase tracking-tighter"></h3>
                </div>
              </div>

             <!-- Info Side -->
             <div class="w-full md:w-1/2 p-16 space-y-10 relative">
                <button onclick="closeModal('showCarModal')" class="absolute top-10 right-10 p-4 bg-white/5 text-slate-400 hover:text-white rounded-2xl transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div>
                    <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6 italic">Spécifications Techniques</h4>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-1 italic">Année de sortie</div>
                            <div id="show_annee" class="text-xl font-black text-white italic"></div>
                        </div>
                        <div>
                            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-1 italic">Kilométrage</div>
                            <div id="show_kilometrage" class="text-xl font-black text-white italic"></div>
                        </div>
                        <div>
                            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-1 italic">Châssis (VIN)</div>
                            <div id="show_vin" class="text-sm font-bold text-amber-500 tracking-widest"></div>
                        </div>
                        <div>
                            <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-1 italic">État Général</div>
                            <div id="show_etat" class="text-sm font-black text-white uppercase italic"></div>
                        </div>
                    </div>
                </div>

                <div class="pt-10 border-t border-white/5">
                    <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-2 italic">Prix Catalogue</div>
                    <div id="show_prix" class="text-5xl font-black text-white italic tracking-tighter"></div>
                </div>

                <div class="pt-6">
                    <button onclick="closeModal('showCarModal')" class="w-full py-5 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-white rounded-[2rem] hover:bg-amber-500 transition">Fermer la fiche</button>
                </div>
             </div>
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
        document.getElementById('show_title').innerText = `${car.marque} ${car.modele}`;
        document.getElementById('show_annee').innerText = car.annee;
        document.getElementById('show_kilometrage').innerText = new Intl.NumberFormat('fr-FR').format(car.kilometrage) + ' KM';
        document.getElementById('show_prix').innerText = new Intl.NumberFormat('fr-FR').format(car.prix) + ' FCFA';
        document.getElementById('show_vin').innerText = car.vin || 'NON SPÉCIFIÉ';
        document.getElementById('show_etat').innerText = car.etat;
        
        const mainImg = document.getElementById('show_photo');
        mainImg.src = car.photo_principale || '/images/placeholder-car.jpg';
        
        // Gallery
        const gallery = document.getElementById('show_car_gallery');
        gallery.innerHTML = '';
        if (car.photos && car.photos.length > 0) {
            car.photos.forEach(photo => {
                const thumb = document.createElement('div');
                thumb.className = 'flex-shrink-0 w-16 h-12 rounded-xl border border-white/20 overflow-hidden cursor-pointer hover:border-amber-500 transition-all p-0.5 bg-slate-900/50 backdrop-blur';
                thumb.innerHTML = `<img src="${photo.url}" class="w-full h-full object-cover rounded-lg">`;
                thumb.onclick = () => { mainImg.src = photo.url; };
                gallery.appendChild(thumb);
            });
        }

        const badge = document.getElementById('show_badge');
        badge.innerText = car.disponibilite.replace('_', ' ');
        badge.className = 'px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest italic border inline-block mb-3 ';
        
        const colors = {
            'disponible': 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
            'importation': 'bg-amber-500/10 text-amber-500 border-amber-500/20',
            'reserve': 'bg-blue-500/10 text-blue-500 border-blue-500/20',
            'vendu': 'bg-slate-500/10 text-slate-500 border-slate-500/20'
        };
        badge.classList.add(...(colors[car.disponibilite] || colors['vendu']).split(' '));

        openModal('showCarModal');
    }

    function openEditCarModal(car) {
        const form = document.getElementById('editCarForm');
        form.action = `/admin/cars/${car.id}`;
        
        document.getElementById('edit_prix').value = car.prix;
        document.getElementById('edit_kilometrage').value = car.kilometrage;
        document.getElementById('edit_etat').value = car.etat;
        document.getElementById('edit_disponibilite').value = car.disponibilite;

        openModal('editCarModal');
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
