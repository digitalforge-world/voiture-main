@extends('layouts.admin')

@section('title', 'Gestion du Stock Pièces - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Catalogue Pièces</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Inventaire des pièces détachées et accessoires</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openModal('createPartModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-[1.5rem] hover:bg-white hover:scale-105 duration-300 shadow-xl shadow-amber-900/10">
                <i data-lucide="package-plus" class="w-4 h-4"></i> Ajouter une pièce
            </button>
        </div>
    </div>

    <!-- Parts Table -->
    <div class="border overflow-hidden bg-slate-950/50 border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left">
            <thead class="bg-slate-900/50 border-b border-white/5">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Désignation</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Compatibilité</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Prix & État</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Stock</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($pieces as $piece)
                <tr class="group hover:bg-white/[0.02] transition duration-300">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-slate-900 border border-white/5 flex items-center justify-center overflow-hidden shadow-inner p-2">
                                @if($piece->photo)
                                    <img src="{{ $piece->photo }}" class="w-full h-full object-contain">
                                @else
                                    <i data-lucide="settings" class="w-6 h-6 text-slate-700"></i>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-black text-white tracking-tight italic uppercase">{{ $piece->nom }}</div>
                                <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest italic mt-1">{{ $piece->categorie }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-[10px] text-slate-400 font-bold leading-relaxed max-w-[200px] italic">
                           {{ $piece->compatibilite ?? 'Universelle' }}
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-white italic tracking-tight">{{ number_format($piece->prix, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></div>
                        <div class="text-[9px] text-slate-600 font-bold uppercase tracking-widest italic mt-1">{{ $piece->etat }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="flex-grow w-24 h-1.5 bg-slate-900 rounded-full overflow-hidden border border-white/5 shadow-inner">
                                <div class="h-full {{ $piece->stock <= 2 ? 'bg-rose-500' : 'bg-amber-500' }}" style="width: {{ min(($piece->stock / 10) * 100, 100) }}%"></div>
                            </div>
                            <span class="text-sm font-black {{ $piece->stock <= 2 ? 'text-rose-500 animate-pulse' : 'text-white' }} italic">{{ $piece->stock }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button onclick="openShowPartModal({{ json_encode($piece) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-[1.2rem] border border-white/5 shadow-xl">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button onclick="openEditPartModal({{ json_encode($piece) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-amber-500 transition rounded-[1.2rem] border border-white/5 shadow-xl">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('admin.parts-inventory.destroy', $piece->id) }}" method="POST" onsubmit="return confirm('Retirer cette pièce du catalogue ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-rose-500 transition rounded-[1.2rem] border border-white/5 shadow-xl">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucune pièce en stock.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($pieces->hasPages())
        <div class="px-8 py-6 bg-slate-900/30 border-t border-white/5">
            {{ $pieces->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Part Modal -->
<div id="createPartModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('createPartModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-2xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-2">Nouvel Article</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4">Ajout au catalogue des pièces détachées</p>

            <form action="{{ route('admin.parts-inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Désignation</label>
                        <input type="text" name="nom" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black italic">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Catégorie</label>
                        <select name="categorie" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition appearance-none font-black uppercase tracking-widest italic">
                            <option value="Moteur">Moteur</option>
                            <option value="Freinage">Freinage</option>
                            <option value="Suspension">Suspension</option>
                            <option value="Éclairage">Éclairage</option>
                            <option value="Carrosserie">Carrosserie</option>
                            <option value="Accessoires">Accessoires</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Compatibilité (Marques/Modèles)</label>
                    <input type="text" name="compatibilite" placeholder="Ex: Toyota Hilux 2018-2022" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner italic font-medium">
                </div>

                <div class="grid grid-cols-3 gap-8">
                    <div class="col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Prix (FCFA)</label>
                        <input type="number" name="prix" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black text-amber-500">
                    </div>
                    <div class="col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Stock Initial</label>
                        <input type="number" name="stock" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black">
                    </div>
                    <div class="col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">État</label>
                        <select name="etat" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black uppercase italic tracking-widest">
                            <option value="neuf">Neuf</option>
                            <option value="reconditionne">Reconditionné</option>
                            <option value="occasion">Occasion</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Photo de l'article</label>
                    <input type="file" name="photo" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-amber-500 file:text-slate-950 hover:file:bg-white transition">
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('createPartModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2.5rem] border border-white/5 hover:bg-slate-900 transition font-black italic">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-white transition shadow-xl shadow-amber-500/20 font-black italic">Inscrire au catalogue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Part Modal -->
<div id="editPartModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('editPartModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-2xl p-12 shadow-2xl rounded-[4rem] rounded-tl-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-2">Modifier Article</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4">Mise à jour des spécifications techniques</p>

            <form id="editPartForm" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Désignation</label>
                        <input type="text" name="nom" id="edit_part_nom" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black italic">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Catégorie</label>
                        <select name="categorie" id="edit_part_cat" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition appearance-none font-black uppercase tracking-widest italic">
                            <option value="Moteur">Moteur</option>
                            <option value="Freinage">Freinage</option>
                            <option value="Suspension">Suspension</option>
                            <option value="Éclairage">Éclairage</option>
                            <option value="Carrosserie">Carrosserie</option>
                            <option value="Accessoires">Accessoires</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Compatibilité</label>
                    <input type="text" name="compatibilite" id="edit_part_comp" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner italic font-medium">
                </div>

                <div class="grid grid-cols-3 gap-8">
                    <div class="col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Prix (FCFA)</label>
                        <input type="number" name="prix" id="edit_part_prix" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black text-amber-500">
                    </div>
                    <div class="col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Stock</label>
                        <input type="number" name="stock" id="edit_part_stock" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black">
                    </div>
                    <div class="col-span-1 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">État</label>
                        <select name="etat" id="edit_part_etat" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black uppercase italic tracking-widest">
                            <option value="neuf">Neuf</option>
                            <option value="reconditionne">Reconditionné</option>
                            <option value="occasion">Occasion</option>
                        </select>
                    </div>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editPartModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2.5rem] border border-white/5 hover:bg-slate-900 transition font-black italic">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-white transition shadow-xl shadow-amber-500/20 font-black italic">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Part Modal -->
<div id="showPartModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/95 backdrop-blur-2xl" onclick="closeModal('showPartModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-4xl shadow-2xl rounded-[5rem] rounded-tl-xl rounded-br-xl overflow-hidden flex flex-col md:flex-row animate-in fade-in slide-in-from-bottom duration-500">
             <!-- Part Visual Side -->
             <div class="w-full md:w-2/5 p-16 bg-slate-950 border-r border-white/5 flex flex-col items-center justify-center">
                <div class="relative group">
                    <div class="absolute inset-0 bg-amber-500/20 blur-[100px] opacity-20 group-hover:opacity-40 transition"></div>
                    <img id="show_part_img" src="" class="relative w-64 h-64 object-contain drop-shadow-[0_20px_50px_rgba(251,191,36,0.1)]">
                </div>
                <div class="mt-12 text-center">
                    <span id="show_part_cat" class="px-6 py-2 rounded-full bg-slate-900 text-slate-500 border border-white/5 text-[9px] font-black uppercase tracking-[0.2em] italic mb-4 inline-block"></span>
                    <h3 id="show_part_nom" class="text-3xl font-black text-white italic tracking-tighter uppercase leading-tight"></h3>
                </div>
             </div>

             <!-- Part Specs Side -->
             <div class="w-full md:w-3/5 p-16 bg-slate-900 relative">
                <button onclick="closeModal('showPartModal')" class="absolute top-10 right-10 p-4 bg-white/5 text-slate-400 hover:text-white rounded-2xl transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="space-y-12">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6 italic italic">Configuration Technique</h4>
                        <div class="grid grid-cols-2 gap-10">
                            <div>
                                <span class="text-[9px] font-black text-slate-600 uppercase italic block mb-2">Compatibilité</span>
                                <div id="show_part_comp" class="text-lg font-black text-white italic tracking-tighter uppercase leading-tight"></div>
                            </div>
                            <div>
                                <span class="text-[9px] font-black text-slate-600 uppercase italic block mb-2">État de l'article</span>
                                <div id="show_part_etat" class="text-lg font-black text-amber-500 italic uppercase"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-white/5">
                        <div class="grid grid-cols-2 gap-10 mb-12">
                            <div class="p-8 bg-slate-950 rounded-[2.5rem] border border-white/5 shadow-xl">
                                <span class="text-[9px] font-black text-slate-600 uppercase italic block mb-2">Prix Public</span>
                                <div id="show_part_prix" class="text-2xl font-black text-white italic tracking-tighter"></div>
                            </div>
                            <div class="p-8 bg-slate-950 rounded-[2.5rem] border border-white/5 shadow-xl">
                                <span class="text-[9px] font-black text-slate-600 uppercase italic block mb-2">Disponibilité</span>
                                <div id="show_part_stock" class="text-2xl font-black italic tracking-tighter"></div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button onclick="closeModal('showPartModal')" class="flex-1 py-5 bg-white text-slate-950 rounded-[2rem] text-[10px] font-black uppercase tracking-widest italic hover:bg-amber-500 transition shadow-2xl">Fermer la fiche</button>
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
        
        document.getElementById('edit_part_nom').value = piece.nom;
        document.getElementById('edit_part_cat').value = piece.categorie;
        document.getElementById('edit_part_comp').value = piece.compatibilite || '';
        document.getElementById('edit_part_prix').value = piece.prix;
        document.getElementById('edit_part_stock').value = piece.stock;
        document.getElementById('edit_part_etat').value = piece.etat;

        openModal('editPartModal');
    }

    function openShowPartModal(piece) {
        document.getElementById('show_part_nom').innerText = piece.nom;
        document.getElementById('show_part_cat').innerText = piece.categorie;
        document.getElementById('show_part_comp').innerText = piece.compatibilite || 'UNIVERSELLE';
        document.getElementById('show_part_etat').innerText = piece.etat.toUpperCase();
        document.getElementById('show_part_prix').innerText = new Intl.NumberFormat('fr-FR').format(piece.prix) + ' FCFA';
        
        const stockEl = document.getElementById('show_part_stock');
        stockEl.innerText = `${piece.stock} UNITÉS`;
        stockEl.className = `text-2xl font-black italic tracking-tighter ${piece.stock <= 2 ? 'text-rose-500' : 'text-emerald-500'}`;
        
        document.getElementById('show_part_img').src = piece.photo || '/images/placeholder-part.png';

        openModal('showPartModal');
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
