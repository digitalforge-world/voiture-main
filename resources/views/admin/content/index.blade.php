@extends('layouts.admin')
@section('title', 'Gestion du Contenu - AutoImport Hub')
@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Identité & Contenu</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Configuration des paramètres globaux et messaging plateforme</p>
        </div>
        <div class="flex items-center gap-4">
             <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-white/5 flex items-center gap-3 transition-colors">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[9px] font-black text-slate-900 dark:text-white uppercase italic tracking-widest transition-colors">Variable Sync Active</span>
             </div>
        </div>
    </div>

    <!-- Parameters Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($parameters as $param)
        <div class="p-10 bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tr-xl shadow-lg dark:shadow-2xl relative overflow-hidden group hover:border-amber-500/30 transition-colors duration-500">
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-amber-500 opacity-0 group-hover:opacity-5 blur-3xl transition duration-700"></div>
            
            <div class="flex justify-between items-start mb-8">
                <span class="px-4 py-1.5 bg-slate-50 dark:bg-slate-900 rounded-lg text-[8px] font-black text-slate-400 dark:text-slate-500 uppercase italic tracking-widest border border-slate-100 dark:border-white/5 transition-colors">{{ $param->type }}</span>
                <button onclick="openEditParamModal({{ json_encode($param) }})" class="p-3 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-400 hover:text-white hover:bg-amber-500 transition rounded-2xl shadow-xl transition-colors">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                </button>
            </div>

            <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] mb-4 italic transition-colors">{{ str_replace('_', ' ', $param->cle) }}</h3>
            <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-[2rem] border border-slate-100 dark:border-white/5 min-h-[100px] flex items-center shadow-inner transition-colors">
                <p class="text-xs text-slate-900 dark:text-white font-medium leading-relaxed italic line-clamp-3 transition-colors">
                    {{ $param->valeur }}
                </p>
            </div>
            
            @if($param->description)
            <p class="mt-6 text-[9px] text-slate-400 dark:text-slate-600 font-bold uppercase tracking-widest italic line-clamp-1 border-l-2 border-slate-200 dark:border-slate-800 pl-4 transition-colors">{{ $param->description }}</p>
            @endif
        </div>
        @empty
        <div class="col-span-full py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucun paramètre système trouvé.</div>
        @endforelse
    </div>
</div>

<!-- Edit Parameter Modal -->
<div id="editParamModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('editParamModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tl-xl rounded-br-xl overflow-hidden animate-in zoom-in duration-300 transition-colors">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter mb-2 transition-colors">Ajuster la Variable</h2>
            <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4 transition-colors">Définition des constantes de l'écosystème</p>

            <form id="editParamForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Clé (Immuable)</label>
                    <input type="text" id="edit_param_cle" readonly class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-400 dark:text-slate-600 text-sm font-black italic shadow-inner outline-none opacity-50 transition-colors">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Valeur de la Variable</label>
                    <textarea name="valeur" id="edit_param_valeur" rows="4" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition shadow-inner font-black italic transition-colors"></textarea>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editParamModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-900 transition font-black italic transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Appliquer le Changement</button>
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

    function openEditParamModal(param) {
        const form = document.getElementById('editParamForm');
        // Logic for update route would ideally be /admin/content/{id} but let's assume standard resource
        form.action = `/admin/settings/${param.id}`; 
        
        document.getElementById('edit_param_cle').value = param.cle.toUpperCase();
        document.getElementById('edit_param_valeur').value = param.valeur;

        openModal('editParamModal');
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('editParamModal');
        }
    });
</script>
@endsection
@endsection
