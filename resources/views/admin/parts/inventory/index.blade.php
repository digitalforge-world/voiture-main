@extends('layouts.admin')

@section('title', 'Gestion du Stock Pièces - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Catalogue Pièces</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Inventaire des pièces détachées et accessoires</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openModal('createPartModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-[1.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 hover:scale-105 duration-300 shadow-xl shadow-amber-900/10 transition-colors">
                <i data-lucide="package-plus" class="w-4 h-4"></i> Ajouter une pièce
            </button>
        </div>
    </div>

    <!-- Parts Table -->
    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-lg dark:shadow-2xl backdrop-blur-sm transition-colors">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5 transition-colors">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors transition-colors">Désignation</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors transition-colors">Compatibilité</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors transition-colors">Prix & État</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors transition-colors">Stock</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic transition-colors transition-colors">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                @forelse($pieces as $piece)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300 transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4 transition-colors">
                            <div class="w-14 h-14 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/5 flex items-center justify-center overflow-hidden shadow-inner p-2 transition-colors">
                                @if($piece->photo)
                                    <img src="{{ $piece->photo }}" class="w-full h-full object-contain">
                                @else
                                    <i data-lucide="settings" class="w-6 h-6 text-slate-300 dark:text-slate-700 transition-colors"></i>
                                @endif
                            </div>
                            <div class="transition-colors">
                                <div class="text-sm font-black text-slate-900 dark:text-white tracking-tight italic uppercase transition-colors">{{ $piece->nom }}</div>
                                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest italic mt-1 transition-colors">{{ $piece->categorie }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-[10px] text-slate-400 dark:text-slate-400 font-bold leading-relaxed max-w-[200px] italic transition-colors">
                           {{ $piece->compatibilite ?? 'Universelle' }}
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-slate-900 dark:text-white italic tracking-tight transition-colors">{{ number_format($piece->prix, 0, ',', ' ') }} <span class="text-[10px] transition-colors">FCFA</span></div>
                        <div class="text-[9px] text-slate-400 dark:text-slate-600 font-bold uppercase tracking-widest italic mt-1 transition-colors">{{ $piece->etat }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3 transition-colors">
                            <div class="flex-grow w-24 h-1.5 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-200 dark:border-white/5 shadow-inner transition-colors">
                                <div class="h-full {{ $piece->stock <= 2 ? 'bg-rose-500' : 'bg-amber-500' }}" style="width: {{ min(($piece->stock / 10) * 100, 100) }}%"></div>
                            </div>
                            <span class="text-sm font-black {{ $piece->stock <= 2 ? 'text-rose-500 animate-pulse' : 'text-slate-900 dark:text-white' }} italic transition-colors">{{ $piece->stock }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-3 transition-colors">
                            <button onclick="openShowPartModal({{ json_encode($piece) }})" class="p-3 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition rounded-[1.2rem] border border-slate-200 dark:border-white/5 shadow-sm transition-colors">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button onclick="openEditPartModal({{ json_encode($piece) }})" class="p-3 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-500 hover:text-white hover:bg-amber-500 transition rounded-[1.2rem] border border-slate-200 dark:border-white/5 shadow-sm transition-colors">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('admin.parts-inventory.destroy', $piece->id) }}" method="POST" onsubmit="return confirm('Retirer cette pièce du catalogue ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-500 hover:text-white hover:bg-rose-500 transition rounded-[1.2rem] border border-slate-200 dark:border-white/5 shadow-sm transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-400 dark:text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs transition-colors">Aucune pièce en stock.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($pieces->hasPages())
        <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-white/5 transition-colors">
            {{ $pieces->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Part Modal -->
<div id="createPartModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/60 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('createPartModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-2xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300 transition-colors">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter mb-2 transition-colors">Nouvel Article</h2>
            <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4 transition-colors">Ajout au catalogue des pièces détachées</p>

            <form action="{{ route('admin.parts-inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 transition-colors">
                    <div class="md:col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Référence</label>
                        <input type="text" name="reference" required placeholder="Ex: BRK-782" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black uppercase transition-colors">
                    </div>
                    <div class="md:col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Désignation</label>
                        <input type="text" name="nom" required placeholder="Ex: Plaquettes de frein" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black italic transition-colors">
                    </div>
                    <div class="md:col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Catégorie</label>
                        <select name="categorie" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition appearance-none font-black uppercase tracking-widest italic transition-colors">
                            <option value="moteur">Moteur</option>
                            <option value="transmission">Transmission</option>
                            <option value="suspension">Suspension</option>
                            <option value="freinage">Freinage</option>
                            <option value="carrosserie">Carrosserie</option>
                            <option value="electricite">Electricité</option>
                            <option value="interieur">Intérieur</option>
                            <option value="pneumatique">Pneumatique</option>
                            <option value="optique_eclairage">Optique & Éclairage</option>
                            <option value="echappement">Échappement</option>
                            <option value="refroidissement">Refroidissement</option>
                            <option value="filtration">Filtration</option>
                            <option value="embrayage">Embrayage</option>
                            <option value="direction">Direction</option>
                            <option value="climatisation">Climatisation</option>
                            <option value="vitrage">Vitrage</option>
                            <option value="accessoires">Accessoires</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 transition-colors">
                    <div class="space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Marque Compatible</label>
                        <input type="text" name="marque_compatible" required placeholder="Ex: Toyota, Mercedes..." class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black uppercase transition-colors">
                    </div>
                    <div class="space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Modèles compatibles</label>
                        <input type="text" name="modele_compatible" placeholder="Ex: Corolla, Hilux, E-Class..." class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black uppercase transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-8 transition-colors">
                    <div class="col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Prix (FCFA)</label>
                        <input type="number" name="prix" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-amber-600 dark:text-amber-500 text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black transition-colors">
                    </div>
                    <div class="col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Stock Initial</label>
                        <input type="number" name="stock" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black transition-colors">
                    </div>
                    <div class="col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">État</label>
                        <select name="etat" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black uppercase italic tracking-widest transition-colors">
                            <option value="neuf" class="bg-white dark:bg-slate-950">Neuf</option>
                            <option value="reconditionne" class="bg-white dark:bg-slate-950">Reconditionné</option>
                            <option value="occasion" class="bg-white dark:bg-slate-950">Occasion</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 transition-colors">
                    <div class="space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Photos de l'article</label>
                        <input type="file" name="photos[]" multiple onchange="handlePartFilePreview(this, 'create_part_media_preview')" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-amber-500 file:text-slate-950 hover:file:bg-slate-900 hover:file:text-white dark:hover:file:bg-white dark:hover:file:text-slate-950 transition transition-colors">
                    </div>
                    <div class="space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Vidéos de l'article</label>
                        <input type="file" name="videos[]" multiple accept="video/*" onchange="handlePartFilePreview(this, 'create_part_media_preview')" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-rose-500 file:text-white hover:file:bg-slate-900 dark:hover:file:bg-white dark:hover:file:text-slate-950 transition transition-colors">
                    </div>
                </div>

                <div id="create_part_media_preview" class="flex flex-wrap gap-4 empty:hidden transition-colors"></div>

                <div class="pt-10 flex gap-6 transition-colors">
                    <button type="button" onclick="closeModal('createPartModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-white dark:hover:bg-slate-900 transition font-black italic transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Inscrire au catalogue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Part Modal -->
<div id="editPartModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/60 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('editPartModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-2xl p-12 shadow-2xl rounded-[4rem] rounded-tl-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300 transition-colors">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter mb-2 transition-colors">Modifier Article</h2>
            <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4 transition-colors">Mise à jour des spécifications techniques</p>

            <form id="editPartForm" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 transition-colors">
                    <div class="md:col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Référence</label>
                        <input type="text" name="reference" id="edit_part_ref" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black uppercase transition-colors">
                    </div>
                    <div class="md:col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Désignation</label>
                        <input type="text" name="nom" id="edit_part_nom" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black italic transition-colors">
                    </div>
                    <div class="md:col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Catégorie</label>
                        <select name="categorie" id="edit_part_cat" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition appearance-none font-black uppercase tracking-widest italic transition-colors">
                            <option value="moteur">Moteur</option>
                            <option value="transmission">Transmission</option>
                            <option value="suspension">Suspension</option>
                            <option value="freinage">Freinage</option>
                            <option value="carrosserie">Carrosserie</option>
                            <option value="electricite">Electricité</option>
                            <option value="interieur">Intérieur</option>
                            <option value="pneumatique">Pneumatique</option>
                            <option value="optique_eclairage">Optique & Éclairage</option>
                            <option value="echappement">Échappement</option>
                            <option value="refroidissement">Refroidissement</option>
                            <option value="filtration">Filtration</option>
                            <option value="embrayage">Embrayage</option>
                            <option value="direction">Direction</option>
                            <option value="climatisation">Climatisation</option>
                            <option value="vitrage">Vitrage</option>
                            <option value="accessoires">Accessoires</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 transition-colors">
                    <div class="space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Marque Compatible</label>
                        <input type="text" name="marque_compatible" id="edit_part_marque" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black uppercase transition-colors">
                    </div>
                    <div class="space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Modèles compatibles</label>
                        <input type="text" name="modele_compatible" id="edit_part_modele" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black uppercase transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-8 transition-colors">
                    <div class="col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Prix (FCFA)</label>
                        <input type="number" name="prix" id="edit_part_prix" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-amber-600 dark:text-amber-500 text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black transition-colors">
                    </div>
                    <div class="col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Stock</label>
                        <input type="number" name="stock" id="edit_part_stock" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black transition-colors">
                    </div>
                    <div class="col-span-1 space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">État</label>
                        <select name="etat" id="edit_part_etat" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black uppercase italic tracking-widest transition-colors">
                            <option value="neuf" class="bg-white dark:bg-slate-950">Neuf</option>
                            <option value="reconditionne" class="bg-white dark:bg-slate-950">Reconditionné</option>
                            <option value="occasion" class="bg-white dark:bg-slate-950">Occasion</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 transition-colors">
                    <div class="space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Ajouter des photos</label>
                        <input type="file" name="photos[]" multiple onchange="handlePartFilePreview(this, 'edit_part_media_preview')" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-amber-500 file:text-slate-950 hover:file:bg-slate-900 hover:file:text-white dark:hover:file:bg-white dark:hover:file:text-slate-950 transition transition-colors">
                    </div>
                    <div class="space-y-2 transition-colors">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Ajouter des vidéos</label>
                        <input type="file" name="videos[]" multiple accept="video/*" onchange="handlePartFilePreview(this, 'edit_part_media_preview')" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-rose-500 file:text-white hover:file:bg-slate-900 dark:hover:file:bg-white dark:hover:file:text-slate-950 transition transition-colors">
                    </div>
                </div>

                <div id="edit_part_media_preview" class="flex flex-wrap gap-4 empty:hidden transition-colors"></div>

                <div class="pt-10 flex gap-6 transition-colors">
                    <button type="button" onclick="closeModal('editPartModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-white dark:hover:bg-slate-900 transition font-black italic transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Part Modal -->
<div id="showPartModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/80 dark:bg-slate-950/95 backdrop-blur-2xl transition-colors" onclick="closeModal('showPartModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-4xl shadow-2xl rounded-[5rem] rounded-tl-xl rounded-br-xl overflow-hidden flex flex-col md:flex-row animate-in fade-in slide-in-from-bottom duration-500 transition-colors">
             <!-- Part Visual Side -->
             <div class="w-full md:w-2/5 p-16 bg-slate-50 dark:bg-slate-950 border-r border-slate-100 dark:border-white/5 flex flex-col items-center justify-center transition-colors">
                <div class="relative group">
                    <div class="absolute inset-0 bg-amber-500/20 blur-[100px] opacity-20 group-hover:opacity-40 transition transition-colors"></div>
                    <img id="show_part_img" src="" class="relative w-64 h-64 object-contain drop-shadow-[0_20px_50px_rgba(251,191,36,0.1)] transition-all">
                    <video id="show_part_vid" class="hidden relative w-64 h-64 object-contain rounded-3xl" controls></video>
                </div>
                <!-- Gallery Thumbnails -->
                <div id="show_part_gallery" class="flex flex-wrap items-center justify-center gap-2 mt-8 transition-colors">
                    <!-- Dynamic thumbs -->
                </div>
                <div class="mt-8 text-center transition-colors">
                    <span id="show_part_cat" class="px-6 py-2 rounded-full bg-white dark:bg-slate-900 text-slate-400 dark:text-slate-500 border border-slate-100 dark:border-white/5 text-[9px] font-black uppercase tracking-[0.2em] italic mb-4 inline-block transition-colors"></span>
                    <h3 id="show_part_nom" class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter uppercase leading-tight transition-colors"></h3>
                </div>
             </div>

             <!-- Part Specs Side -->
             <div class="w-full md:w-3/5 p-16 bg-white dark:bg-slate-900 relative transition-colors">
                <button onclick="closeModal('showPartModal')" class="absolute top-10 right-10 p-4 bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-2xl transition transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="space-y-12 transition-colors">
                    <div class="transition-colors">
                        <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-6 italic italic transition-colors">Configuration Technique</h4>
                        <div class="grid grid-cols-2 gap-10 transition-colors">
                            <div class="transition-colors">
                                <span class="text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase italic block mb-2 transition-colors">Marque / Modèle</span>
                                <div id="show_part_comp_brand" class="text-lg font-black text-slate-900 dark:text-white italic tracking-tighter uppercase leading-tight transition-colors"></div>
                                <div id="show_part_comp_model" class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1 transition-colors"></div>
                            </div>
                            <div class="transition-colors">
                                <span class="text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase italic block mb-2 transition-colors">État de l'article</span>
                                <div id="show_part_etat" class="text-lg font-black text-amber-500 italic uppercase transition-colors"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-slate-50 dark:border-white/5 transition-colors">
                        <div class="grid grid-cols-2 gap-10 mb-12 transition-colors">
                            <div class="p-8 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-xl transition-colors">
                                <span class="text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase italic block mb-2 transition-colors">Prix Public</span>
                                <div id="show_part_prix" class="text-2xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors"></div>
                            </div>
                            <div class="p-8 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 shadow-xl transition-colors">
                                <span class="text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase italic block mb-2 transition-colors">Disponibilité</span>
                                <div id="show_part_stock" class="text-2xl font-black italic tracking-tighter transition-colors"></div>
                            </div>
                        </div>

                        <div class="flex gap-4 transition-colors">
                            <button onclick="closeModal('showPartModal')" class="flex-1 py-5 bg-slate-900 dark:bg-white text-white dark:text-slate-950 rounded-[2rem] text-[10px] font-black uppercase tracking-widest italic hover:bg-amber-500 dark:hover:bg-amber-500 transition shadow-2xl transition-colors">Fermer la fiche</button>
                        </div>
                    </div>
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

    function openEditPartModal(piece) {
        const form = document.getElementById('editPartForm');
        form.action = `/admin/parts-inventory/${piece.id}`;
        
        document.getElementById('edit_part_ref').value = piece.reference;
        document.getElementById('edit_part_nom').value = piece.nom;
        document.getElementById('edit_part_cat').value = piece.categorie;
        document.getElementById('edit_part_marque').value = piece.marque_compatible || '';
        document.getElementById('edit_part_modele').value = piece.modele_compatible || '';
        document.getElementById('edit_part_prix').value = piece.prix;
        document.getElementById('edit_part_stock').value = piece.stock;
        document.getElementById('edit_part_etat').value = piece.etat;

        openModal('editPartModal');
    }

    function openShowPartModal(piece) {
        document.getElementById('show_part_nom').innerText = piece.nom;
        document.getElementById('show_part_cat').innerText = piece.categorie.replace('_', ' ').toUpperCase();
        document.getElementById('show_part_comp_brand').innerText = piece.marque_compatible || 'UNIVERSELLE';
        document.getElementById('show_part_comp_model').innerText = piece.modele_compatible || '-';
        document.getElementById('show_part_etat').innerText = piece.etat.toUpperCase();
        document.getElementById('show_part_prix').innerText = new Intl.NumberFormat('fr-FR').format(piece.prix) + ' FCFA';
        
        const stockEl = document.getElementById('show_part_stock');
        stockEl.innerText = piece.stock;
        stockEl.className = 'text-2xl font-black italic tracking-tighter ' + (piece.stock <= 2 ? 'text-rose-500' : 'text-emerald-500');
        
        const mainImg = document.getElementById('show_part_img');
        const mainVid = document.getElementById('show_part_vid');
        
        mainImg.src = piece.image || '/images/placeholder-part.jpg';
        mainImg.classList.remove('hidden');
        mainVid.classList.add('hidden');
        mainVid.pause();

        // Load Gallery
        const gallery = document.getElementById('show_part_gallery');
        gallery.innerHTML = '';
        
        // Photos
        if (piece.photos && piece.photos.length > 0) {
            piece.photos.forEach(photo => {
                const thumb = document.createElement('div');
                thumb.className = 'w-12 h-12 rounded-lg bg-slate-900 border border-white/10 overflow-hidden cursor-pointer hover:border-amber-500 transition p-1';
                thumb.innerHTML = `<img src="${photo.url}" class="w-full h-full object-contain">`;
                thumb.onclick = () => { 
                    mainImg.src = photo.url; 
                    mainImg.classList.remove('hidden');
                    mainVid.classList.add('hidden');
                    mainVid.pause();
                };
                gallery.appendChild(thumb);
            });
        }

        // Videos
        if (piece.videos && piece.videos.length > 0) {
            piece.videos.forEach(video => {
                const thumb = document.createElement('div');
                thumb.className = 'w-12 h-12 rounded-lg bg-slate-900 border border-white/10 overflow-hidden cursor-pointer hover:border-amber-500 transition p-1 relative';
                thumb.innerHTML = `
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40">
                        <i data-lucide="play" class="w-4 h-4 text-white"></i>
                    </div>
                `;
                thumb.onclick = () => { 
                    mainVid.src = video.url;
                    mainVid.classList.remove('hidden');
                    mainImg.classList.add('hidden');
                    mainVid.play();
                };
                gallery.appendChild(thumb);
            });
        }
        
        lucide.createIcons();
        
        openModal('showPartModal');
    }

    function handlePartFilePreview(input, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                const div = document.createElement('div');
                div.className = 'relative w-20 h-20 rounded-xl overflow-hidden border border-white/10 bg-slate-950 group';
                
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    } else if (file.type.startsWith('video/')) {
                        div.innerHTML = `
                            <div class="absolute inset-0 flex items-center justify-center bg-black/40">
                                <i data-lucide="video" class="w-6 h-6 text-white"></i>
                            </div>
                        `;
                    }
                    container.appendChild(div);
                    lucide.createIcons();
                }
                reader.readAsDataURL(file);
            });
        }
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('createPartModal');
            closeModal('editPartModal');
            closeModal('showPartModal');
        }
    });
</script>
@endsection
@endsection
