@extends('layouts.admin')

@section('title', 'Gestion des Ports - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Ports & Logistique</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Configuration des points d'entrée maritime et frais associés</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openModal('createPortModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-white hover:scale-105 duration-300 shadow-xl shadow-amber-900/10">
                <i data-lucide="anchor" class="w-4 h-4"></i> Nouveau Port
            </button>
        </div>
    </div>

    <!-- Ports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($ports as $port)
        <div class="group p-10 border bg-slate-900/30 border-slate-900 rounded-[4rem] rounded-tr-xl rounded-bl-xl hover:border-amber-500/30 transition shadow-2xl relative overflow-hidden backdrop-blur-sm">
             <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-500 opacity-[0.03] rounded-full blur-3xl group-hover:opacity-10 transition"></div>
             
             <div class="flex justify-between items-start mb-8">
                <div class="p-4 bg-slate-950 rounded-[1.5rem] text-amber-500 border border-white/5 shadow-inner">
                    <i data-lucide="ship" class="w-8 h-8"></i>
                </div>
                <div class="flex gap-4">
                    <button onclick="openEditPortModal({{ json_encode($port) }})" class="p-3 bg-slate-950 text-slate-500 hover:text-white hover:bg-amber-500 rounded-xl transition border border-white/5 shadow-xl">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </button>
                    <form action="{{ route('admin.ports.destroy', $port->id) }}" method="POST" onsubmit="return confirm('Supprimer ce point d\'entrée portuaire ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-3 bg-slate-950 text-slate-500 hover:text-white hover:bg-rose-500 rounded-xl transition border border-white/5 shadow-xl">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
             </div>

             <div class="mb-8">
                <h3 class="text-2xl font-black text-white italic tracking-tighter uppercase">{{ $port->nom }}</h3>
                <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1 italic">{{ $port->pays }}</div>
             </div>

             <div class="space-y-5 pt-8 border-t border-white/5 relative">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest italic">Frais Opérationnels</span>
                    <span class="text-lg font-black text-amber-500 italic">{{ number_format($port->frais_portuaires, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest italic">Transit Moyen</span>
                    <span class="text-lg font-black text-white italic">{{ $port->delai_estime }} <span class="text-[10px] font-bold text-slate-500">JOURS</span></span>
                </div>
             </div>
        </div>
        @empty
        <div class="col-span-full py-24 text-center">
            <div class="inline-block p-10 bg-slate-900/30 rounded-[3rem] border border-slate-900 mb-6">
                <i data-lucide="anchor" class="w-16 h-16 text-slate-800"></i>
            </div>
            <p class="text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucun port configuré dans le système.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Create Port Modal -->
<div id="createPortModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('createPortModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-2">Nouveau Hub</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4">Ajout d'un point logistique maritime</p>

            <form action="{{ route('admin.ports.store') }}" method="POST" class="space-y-8 relative">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Nom du Port</label>
                    <input type="text" name="nom" required placeholder="Ex: Port d'Anvers" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Pays</label>
                    <input type="text" name="pays" required placeholder="Ex: Belgique" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Frais (FCFA)</label>
                        <input type="number" name="frais_portuaires" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black text-amber-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Délai (Jours)</label>
                        <input type="number" name="delai_estime" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black">
                    </div>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('createPortModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2.5rem] border border-white/5 hover:bg-slate-900 transition font-black italic">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-white transition shadow-xl shadow-amber-500/20 font-black italic">Enregistrer le Hub</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Port Modal -->
<div id="editPortModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('editPortModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tl-xl rounded-bl-xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-2">Modifier Hub</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4">Mise à jour des paramètres logistiques</p>

            <form id="editPortForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Nom du Port</label>
                    <input type="text" name="nom" id="edit_port_nom" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Pays</label>
                    <input type="text" name="pays" id="edit_port_pays" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner">
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Frais (FCFA)</label>
                        <input type="number" name="frais_portuaires" id="edit_port_frais" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black text-amber-500">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Délai (Jours)</label>
                        <input type="number" name="delai_estime" id="edit_port_delai" required class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black">
                    </div>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editPortModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2.5rem] border border-white/5 hover:bg-slate-900 transition font-black italic">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-white transition shadow-xl shadow-amber-500/20 font-black italic">Mettre à jour</button>
                </div>
            </form>
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

    function openEditPortModal(port) {
        const form = document.getElementById('editPortForm');
        form.action = `/admin/ports/${port.id}`;
        
        document.getElementById('edit_port_nom').value = port.nom;
        document.getElementById('edit_port_pays').value = port.pays;
        document.getElementById('edit_port_frais').value = port.frais_portuaires;
        document.getElementById('edit_port_delai').value = port.delai_estime;

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
@endsection
