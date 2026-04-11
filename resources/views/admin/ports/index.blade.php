@extends('layouts.admin')

@section('title', 'Gestion des Ports - AutoImport Hub')

@section('content')
<div class="space-y-6">
 <!-- Header Area -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Ports & Logistique</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Configuration des points d'entrée maritime et frais logistiques</p>
  </div>
  <div class="flex items-center gap-4">
   <button onclick="openModal('createPortModal')" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-amber-500 rounded-lg hover:bg-amber-600 shadow-sm">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span>Nouveau Port</span>
   </button>
  </div>
 </div>

 <!-- Ports Grid -->
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  @forelse($ports as $port)
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition">
    <div class="flex justify-between items-start mb-6">
     <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-500 flex items-center justify-center">
      <i data-lucide="anchor" class="w-6 h-6"></i>
     </div>
     <div class="flex items-center gap-2">
      <button onclick="openEditPortModal({{ json_encode($port) }})" class="p-2 text-slate-400 hover:text-amber-500 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition">
       <i data-lucide="edit-3" class="w-4 h-4"></i>
      </button>
      <form action="{{ route('admin.ports.destroy', $port->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce port ? Cela pourrait affecter les calculs logistiques en cours.')" class="inline-block">
       @csrf
       @method('DELETE')
       <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition">
        <i data-lucide="trash-2" class="w-4 h-4"></i>
       </button>
      </form>
     </div>
    </div>

    <div>
     <h3 class="text-lg font-semibold text-slate-900 dark:text-white uppercase">{{ $port->nom }}</h3>
     <p class="text-sm text-slate-500 font-medium tracking-wide mt-1">{{ $port->ville }}, {{ $port->pays }}</p>
    </div>

    <div class="space-y-3 pt-6 mt-6 border-t border-slate-200 dark:border-slate-800">
     <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
      <span class="text-sm text-slate-500">Frais Opérationnels</span>
      <span class="text-sm font-semibold text-amber-600 dark:text-amber-500">{{ number_format($port->frais_base, 0, ',', ' ') }} FCFA</span>
     </div>
     <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
      <span class="text-sm text-slate-500">Délai Transit</span>
      <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $port->delai_moyen_jours }} jours</span>
     </div>
    </div>
  </div>
  @empty
  <div class="col-span-full py-16 px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-center">
   <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
    <i data-lucide="anchor" class="w-8 h-8 text-slate-400"></i>
   </div>
   <p class="text-slate-500 text-sm">Aucun port configuré dans le système.</p>
  </div>
  @endforelse
 </div>
</div>

<!-- Create Port Modal -->
<div id="createPortModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('createPortModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <h2 class="text-lg font-medium text-slate-900 dark:text-white">Nouveau Port</h2>
    <button onclick="closeModal('createPortModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form action="{{ route('admin.ports.store') }}" method="POST" class="p-6 space-y-4">
    @csrf
    
    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nom du Port <span class="text-rose-500">*</span></label>
     <input type="text" name="nom" required placeholder="Ex: Port d'Anvers" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pays <span class="text-rose-500">*</span></label>
      <input type="text" name="pays" required placeholder="Ex: Belgique" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ville <span class="text-rose-500">*</span></label>
      <input type="text" name="ville" required placeholder="Ex: Anvers" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
    </div>

    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Type de Hub</label>
     <select name="type" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
      <option value="maritime">Maritime</option>
      <option value="terrestre">Terrestre</option>
      <option value="mixte">Mixte / Multimodal</option>
     </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Frais Opérationnels (FCFA) <span class="text-rose-500">*</span></label>
      <input type="number" name="frais_portuaires" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Délai Estimé (Jours) <span class="text-rose-500">*</span></label>
      <input type="number" name="delai_estime" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('createPortModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">Créer le port</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Edit Port Modal -->
<div id="editPortModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editPortModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <h2 class="text-lg font-medium text-slate-900 dark:text-white">Modifier Port</h2>
    <button onclick="closeModal('editPortModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editPortForm" method="POST" class="p-6 space-y-4">
    @csrf
    @method('PUT')
    
    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nom du Port <span class="text-rose-500">*</span></label>
     <input type="text" name="nom" id="edit_port_nom" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pays <span class="text-rose-500">*</span></label>
      <input type="text" name="pays" id="edit_port_pays" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ville <span class="text-rose-500">*</span></label>
      <input type="text" name="ville" id="edit_port_ville" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
    </div>

    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Type de Hub</label>
     <select name="type" id="edit_port_type" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
      <option value="maritime">Maritime</option>
      <option value="terrestre">Terrestre</option>
      <option value="mixte">Mixte / Multimodal</option>
     </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Frais Opérationnels (FCFA) <span class="text-rose-500">*</span></label>
      <input type="number" name="frais_portuaires" id="edit_port_frais" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Délai Estimé (Jours) <span class="text-rose-500">*</span></label>
      <input type="number" name="delai_estime" id="edit_port_delai" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('editPortModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">Enregistrer</button>
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

 window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
   closeModal('createPortModal');
   closeModal('editPortModal');
  }
 });
</script>
@endsection
