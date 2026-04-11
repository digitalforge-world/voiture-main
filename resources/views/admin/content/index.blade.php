@extends('layouts.admin')
@section('title', 'Gestion du Contenu - AutoImport Hub')
@section('content')
<div class="space-y-6">
 <!-- Header -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Identité & Contenu</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configuration des paramètres globaux et messaging plateforme</p>
  </div>
  <div class="flex items-center gap-4">
    <div class="px-4 py-2 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-2">
     <span class="relative flex h-2.5 w-2.5">
       <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
       <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
     </span>
     <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Variable Sync Active</span>
    </div>
  </div>
 </div>

 <!-- Parameters Grid -->
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  @forelse($parameters as $param)
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition">
   <div class="flex justify-between items-start mb-4">
    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded text-xs font-medium uppercase tracking-wider">{{ $param->type }}</span>
    <button onclick="openEditParamModal({{ json_encode($param) }})" class="text-slate-400 hover:text-amber-500 transition rounded p-1 hover:bg-slate-50 dark:hover:bg-slate-800">
     <i data-lucide="edit-3" class="w-4 h-4"></i>
    </button>
   </div>

   <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">{{ str_replace('_', ' ', $param->cle) }}</h3>
   <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-800 min-h-[80px] flex items-center">
    <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed line-clamp-3">
     {{ $param->valeur }}
    </p>
   </div>
   
   @if($param->description)
   <p class="mt-4 text-xs text-slate-500 line-clamp-2">{{ $param->description }}</p>
   @endif
  </div>
  @empty
  <div class="col-span-full py-12 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-center shadow-sm">
   <p class="text-slate-500 text-sm">Aucun paramètre système trouvé.</p>
  </div>
  @endforelse
 </div>
</div>

<!-- Edit Parameter Modal -->
<div id="editParamModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editParamModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <div>
     <h2 class="text-lg font-medium text-slate-900 dark:text-white">Ajuster la Variable</h2>
     <p class="text-xs text-slate-500 mt-0.5">Définition des constantes de l'écosystème</p>
    </div>
    <button onclick="closeModal('editParamModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editParamForm" method="POST" class="p-6 space-y-4">
    @csrf
    @method('PUT')
    
    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Clé (Immuable)</label>
     <input type="text" id="edit_param_cle" readonly class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-500 dark:text-slate-400 text-sm outline-none cursor-not-allowed">
    </div>

    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Valeur de la Variable</label>
     <textarea name="valeur" id="edit_param_valeur" rows="4" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none resize-none"></textarea>
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('editParamModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">Enregistrer</button>
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
  
  document.getElementById('edit_param_cle').value = param.cle;
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
