@extends('layouts.admin')

@section('title', 'Gestion des Ports - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Ports & Logistique</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Configuration des points d'entrée maritime et frais associés</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openModal('createPortModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 hover:scale-105 duration-300 shadow-xl shadow-amber-900/10 transition-colors">
                <i data-lucide="anchor" class="w-4 h-4"></i> Nouveau Port
            </button>
        </div>
    </div>

    <!-- Ports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($ports as $port)
        <div class="group p-10 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tr-xl rounded-bl-xl hover:border-amber-500/30 transition shadow-lg dark:shadow-2xl relative overflow-hidden backdrop-blur-sm transition-colors">
             <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-500 opacity-[0.03] rounded-full blur-3xl group-hover:opacity-10 transition duration-500"></div>
             
             <div class="flex justify-between items-start mb-8 relative z-10">
                <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-[1.5rem] text-amber-500 border border-slate-100 dark:border-white/5 shadow-inner transition-colors">
                    <i data-lucide="ship" class="w-8 h-8"></i>
                </div>
                <div class="flex gap-4">
                    <button onclick="openEditPortModal({{ json_encode($port) }})" class="p-3 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 hover:text-white hover:bg-amber-500 rounded-xl transition border border-slate-100 dark:border-white/5 shadow-xl transition-colors">
                        <i data-lucide="edit-3" class="w-4 h-4 transition-transform hover:scale-110"></i>
                    </button>
                    <button onclick="confirmDeletion('{{ route('admin.ports.destroy', $port->id) }}', 'Voulez-vous vraiment supprimer ce point d\'entrée portuaire ? Cela pourrait affecter les calculs logistiques en cours.')" class="p-3 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 hover:text-white hover:bg-rose-500 rounded-xl transition border border-slate-100 dark:border-white/5 shadow-xl transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4 transition-transform hover:scale-110"></i>
                    </button>
                </div>
             </div>

             <div class="mb-8">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white italic tracking-tighter uppercase transition-colors">{{ $port->nom }}</h3>
                <div class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-1 italic transition-colors">{{ $port->ville }}, {{ $port->pays }}</div>
             </div>

             <div class="space-y-5 pt-8 border-t border-slate-100 dark:border-white/5 relative transition-colors">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest italic transition-colors">Frais Opérationnels</span>
                    <span class="text-lg font-black text-amber-500 italic transition-colors">{{ number_format($port->frais_base, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest italic transition-colors">Transit Moyen</span>
                    <span class="text-lg font-black text-slate-900 dark:text-white italic transition-colors">{{ $port->delai_moyen_jours }} <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">JOURS</span></span>
                </div>
             </div>
        </div>
        @empty
        <div class="col-span-full py-24 text-center">
            <div class="inline-block p-10 bg-slate-50 dark:bg-slate-900/30 rounded-[3rem] border border-slate-100 dark:border-slate-900 mb-6 transition-colors">
                <i data-lucide="anchor" class="w-16 h-16 text-slate-200 dark:text-slate-800 transition-colors"></i>
            </div>
            <p class="text-slate-400 dark:text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs transition-colors">Aucun port configuré dans le système.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Create Port Modal -->
<div id="createPortModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('createPortModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in fade-in zoom-in duration-300 transition-colors">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter mb-2 transition-colors">Nouveau Hub</h2>
            <p class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4 transition-colors">Ajout d'un point logistique maritime</p>

            <form action="{{ route('admin.ports.store') }}" method="POST" class="space-y-8 relative">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Nom du Port</label>
                    <input type="text" name="nom" required placeholder="Ex: Port d'Anvers" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Pays</label>
                        <input type="text" name="pays" required placeholder="Ex: Belgique" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Ville</label>
                        <input type="text" name="ville" required placeholder="Ex: Anvers" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Type de Hub</label>
                    <select name="type" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition appearance-none font-black uppercase tracking-widest italic transition-colors">
                        <option value="maritime">Maritime</option>
                        <option value="terrestre">Terrestre</option>
                        <option value="mixte">Mixte / Multimodal</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Frais (FCFA)</label>
                        <input type="number" name="frais_portuaires" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black text-amber-500 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Délai (Jours)</label>
                        <input type="number" name="delai_estime" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black transition-colors">
                    </div>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('createPortModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-800 transition font-black italic transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Enregistrer le Hub</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Port Modal -->
<div id="editPortModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('editPortModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tl-xl rounded-bl-xl overflow-hidden animate-in fade-in zoom-in duration-300 transition-colors">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter mb-2 transition-colors">Modifier Hub</h2>
            <p class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4 transition-colors">Mise à jour des paramètres logistiques</p>

            <form id="editPortForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Nom du Port</label>
                    <input type="text" name="nom" id="edit_port_nom" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Pays</label>
                        <input type="text" name="pays" id="edit_port_pays" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Ville</label>
                        <input type="text" name="ville" id="edit_port_ville" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Type de Hub</label>
                    <select name="type" id="edit_port_type" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition appearance-none font-black uppercase tracking-widest italic transition-colors">
                        <option value="maritime">Maritime</option>
                        <option value="terrestre">Terrestre</option>
                        <option value="mixte">Mixte / Multimodal</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Frais (FCFA)</label>
                        <input type="number" name="frais_portuaires" id="edit_port_frais" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black text-amber-500 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Délai (Jours)</label>
                        <input type="number" name="delai_estime" id="edit_port_delai" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black transition-colors">
                    </div>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editPortModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-800 transition font-black italic transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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

    function openEditPortModal(port) {
        const form = document.getElementById('editPortForm');
        form.action = `/admin/ports/${port.id}`;
        
        document.getElementById('edit_port_nom').value = port.nom;
        document.getElementById('edit_port_pays').value = port.pays;
        document.getElementById('edit_port_ville').value = port.ville;
        document.getElementById('edit_port_type').value = port.type;
        document.getElementById('edit_port_frais').value = port.frais_base;
        document.getElementById('edit_port_delai').value = port.delai_moyen_jours;

        openModal('editPortModal');
    }

    // Close on escape
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('createPortModal');
            closeModal('editPortModal');
        }
    });
</script>
@endsection
