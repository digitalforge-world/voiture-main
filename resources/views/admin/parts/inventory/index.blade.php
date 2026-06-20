@extends('layouts.admin')

@section('title', 'Gestion du Stock Pièces - AutoImport Hub')

@section('content')
<div class="space-y-6">
 <!-- Header Area -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Catalogue Pièces</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Inventaire des pièces détachées et accessoires</p>
  </div>
  <div class="flex items-center gap-4">
   <button onclick="openModal('createPartModal')" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-amber-500 rounded-lg hover:bg-amber-600 shadow-sm">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span>Ajouter une pièce</span>
   </button>
  </div>
 </div>

 <!-- Parts Table -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
     <tr>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Désignation</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Compatibilité</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Prix & État</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Stock</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
     @forelse($pieces as $piece)
     <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150">
      <td class="px-6 py-4">
       <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 border-none flex items-center justify-center overflow-hidden flex-shrink-0">
         @if($piece->photo)
          <img src="{{ $piece->photo }}" class="w-full h-full object-cover">
         @else
          <i data-lucide="settings" class="w-5 h-5 text-slate-400"></i>
         @endif
        </div>
        <div>
         <div class="text-sm font-medium text-slate-900 dark:text-white capitalize">{{ $piece->nom }}</div>
         <div class="text-xs text-slate-500">{{ str_replace('_', ' ', ucfirst($piece->categorie)) }}</div>
        </div>
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm text-slate-700 dark:text-slate-300 max-w-xs truncate">
        {{ $piece->compatibilite ?? 'Universelle' }}
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm font-medium text-slate-900 dark:text-white">{{ number_format($piece->prix, 0, ',', ' ') }} <span class="text-xs text-slate-500">FCFA</span></div>
       <div class="text-xs text-slate-500 mt-1 capitalize">{{ $piece->etat }}</div>
      </td>
      <td class="px-6 py-4">
       <div class="flex items-center gap-3">
        @php
            $stockColor = $piece->stock <= 2 ? 'bg-rose-500' : 'bg-emerald-500';
            $stockText = $piece->stock <= 2 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white';
            $stockWidth = min(($piece->stock / 20) * 100, 100);
        @endphp
        <div class="flex-grow w-16 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
         <div class="h-full {{ $stockColor }}" style="width: {{ $stockWidth }}%"></div>
        </div>
        <span class="text-sm font-medium {{ $stockText }}">{{ $piece->stock }}</span>
       </div>
      </td>
      <td class="px-6 py-4 text-right">
       <div class="flex items-center justify-end gap-2">
        <button onclick="openShowPartModal({{ json_encode($piece) }})" class="p-2 text-slate-400 hover:text-blue-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="eye" class="w-4 h-4"></i>
        </button>
        <button onclick="openEditPartModal({{ json_encode($piece) }})" class="p-2 text-slate-400 hover:text-amber-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="edit-3" class="w-4 h-4"></i>
        </button>
        <form action="{{ route('admin.parts-inventory.destroy', $piece->id) }}" method="POST" onsubmit="return confirm('Retirer cette pièce du catalogue ?')" class="inline-block">
         @csrf
         @method('DELETE')
         <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
         </button>
        </form>
       </div>
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
       Aucune pièce en stock.
      </td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
  @if($pieces->hasPages())
  <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
   {{ $pieces->links() }}
  </div>
  @endif
 </div>
</div>

<!-- Create Part Modal -->
<div id="createPartModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('createPartModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-3xl shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <h2 class="text-lg font-medium text-slate-900 dark:text-white">Nouvel Article</h2>
    <button onclick="closeModal('createPartModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form action="{{ route('admin.parts-inventory.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
    @csrf
    
    <!-- Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Référence <span class="text-rose-500">*</span></label>
      <input type="text" name="reference" required placeholder="Ex: BRK-782" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Désignation <span class="text-rose-500">*</span></label>
      <input type="text" name="nom" required placeholder="Ex: Plaquettes de frein" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catégorie <span class="text-rose-500">*</span></label>
      <select name="categorie" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
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

    <!-- Row 2 -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Marque Compatible <span class="text-rose-500">*</span></label>
      <input type="text" name="marque_compatible" required placeholder="Ex: Toyota, Mercedes..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Modèles compatibles</label>
      <input type="text" name="modele_compatible" placeholder="Ex: Corolla, Hilux, E-Class..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
    </div>

    <!-- Row 3 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Prix (FCFA) <span class="text-rose-500">*</span></label>
      <input type="number" name="prix" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Stock Initial <span class="text-rose-500">*</span></label>
      <input type="number" name="stock" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">État</label>
      <select name="etat" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
       <option value="neuf">Neuf</option>
       <option value="reconditionne">Reconditionné</option>
       <option value="occasion">Occasion</option>
      </select>
     </div>
    </div>

    <!-- Media Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Photos</label>
      <input type="file" name="photos[]" multiple onchange="handlePartFilePreview(this, 'create_part_media_preview')" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-amber-500/10 dark:file:text-amber-500">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Vidéos</label>
      <input type="file" name="videos[]" multiple accept="video/*" onchange="handlePartFilePreview(this, 'create_part_media_preview')" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-300">
     </div>
    </div>
    <div id="create_part_media_preview" class="flex flex-wrap gap-2 mt-2 empty:hidden"></div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('createPartModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">Créer l'article</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Edit Part Modal -->
<div id="editPartModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editPartModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-3xl shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <h2 class="text-lg font-medium text-slate-900 dark:text-white">Modifier Article</h2>
    <button onclick="closeModal('editPartModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editPartForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Référence <span class="text-rose-500">*</span></label>
      <input type="text" name="reference" id="edit_part_ref" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Désignation <span class="text-rose-500">*</span></label>
      <input type="text" name="nom" id="edit_part_nom" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catégorie <span class="text-rose-500">*</span></label>
      <select name="categorie" id="edit_part_cat" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
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

    <!-- Row 2 -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Marque Compatible <span class="text-rose-500">*</span></label>
      <input type="text" name="marque_compatible" id="edit_part_marque" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Modèles compatibles</label>
      <input type="text" name="modele_compatible" id="edit_part_modele" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
    </div>

    <!-- Row 3 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Prix (FCFA) <span class="text-rose-500">*</span></label>
      <input type="number" name="prix" id="edit_part_prix" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Stock <span class="text-rose-500">*</span></label>
      <input type="number" name="stock" id="edit_part_stock" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">État</label>
      <select name="etat" id="edit_part_etat" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
       <option value="neuf">Neuf</option>
       <option value="reconditionne">Reconditionné</option>
       <option value="occasion">Occasion</option>
      </select>
     </div>
    </div>

    <!-- Media Update Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ajouter des photos</label>
      <input type="file" name="photos[]" multiple onchange="handlePartFilePreview(this, 'edit_part_media_preview')" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-amber-500/10 dark:file:text-amber-500">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ajouter des vidéos</label>
      <input type="file" name="videos[]" multiple accept="video/*" onchange="handlePartFilePreview(this, 'edit_part_media_preview')" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-300">
     </div>
    </div>
    
    <div id="edit_part_media_preview" class="flex flex-wrap gap-2 mt-2 empty:hidden"></div>

    <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Médias Actuels (Cliquer pour supprimer)</label>
     <div id="existing_part_media_container" class="flex flex-wrap gap-2 empty:hidden"></div>
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4">
     <button type="button" onclick="closeModal('editPartModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">Enregistrer</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Show Part Modal -->
<div id="showPartModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('showPartModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-4xl shadow-xl overflow-hidden flex flex-col md:flex-row">
    <!-- Part Visual Side -->
    <div class="w-full md:w-1/2 p-6 bg-slate-50 dark:bg-slate-800/50 border-r border-slate-200 dark:border-slate-800 flex flex-col">
     <div class="flex-grow flex items-center justify-center bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden min-h-[300px]">
      <img id="show_part_img" src="" class="w-full h-full object-contain">
      <video id="show_part_vid" class="hidden w-full h-full object-contain" controls></video>
     </div>
     
     <!-- Gallery Thumbnails -->
     <div id="show_part_gallery" class="flex items-center gap-2 mt-4 overflow-x-auto pb-2 scrollbar-thin">
      <!-- Dynamic thumbs -->
     </div>
    </div>

    <!-- Part Specs Side -->
    <div class="w-full md:w-1/2 p-6 relative">
     <button onclick="closeModal('showPartModal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
      <i data-lucide="x" class="w-5 h-5"></i>
     </button>

     <div class="mb-6">
      <span id="show_part_cat" class="px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium mb-3 inline-block"></span>
      <h3 id="show_part_nom" class="text-xl font-semibold text-slate-900 dark:text-white capitalize"></h3>
     </div>

     <div class="space-y-6">
      <div>
       <h4 class="text-xs font-medium text-slate-500 uppercase mb-3">Spécifications</h4>
       <div class="grid grid-cols-2 gap-4">
        <div>
         <span class="text-xs text-slate-500 block mb-1">Marque / Modèle</span>
         <div id="show_part_comp_brand" class="text-sm font-medium text-slate-900 dark:text-white"></div>
         <div id="show_part_comp_model" class="text-xs text-slate-500"></div>
        </div>
        <div>
         <span class="text-xs text-slate-500 block mb-1">État</span>
         <div id="show_part_etat" class="text-sm font-medium text-slate-900 dark:text-white capitalize"></div>
        </div>
       </div>
      </div>

      <div class="pt-6 border-t border-slate-200 dark:border-slate-800">
       <div class="grid grid-cols-2 gap-4">
        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700">
         <span class="text-xs text-slate-500 block mb-1">Prix Public</span>
         <div id="show_part_prix" class="text-lg font-semibold text-amber-600 dark:text-amber-500"></div>
        </div>
        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700">
         <span class="text-xs text-slate-500 block mb-1">Disponibilité</span>
         <div id="show_part_stock" class="text-lg font-semibold"></div>
        </div>
       </div>
      </div>
     </div>
    </div>
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

  const mediaContainer = document.getElementById('existing_part_media_container');
  mediaContainer.innerHTML = '';
  
  if (piece.image) {
   addMediaThumb(mediaContainer, 'photo', piece.image, 'principale', 'part');
  }
  
  if (piece.photos) {
   piece.photos.forEach(p => {
    if (p.url !== piece.image) {
     addMediaThumb(mediaContainer, 'photo', p.url, p.id, 'part');
    }
   });
  }
  
  if (piece.videos) {
   piece.videos.forEach(v => {
    addMediaThumb(mediaContainer, 'video', v.url, v.id, 'part');
   });
  }

  openModal('editPartModal');
  lucide.createIcons();
 }

 function addMediaThumb(container, type, url, id, entity) {
  const div = document.createElement('div');
  div.id = `media-${type}-${id}`;
  div.className = 'relative w-16 h-16 rounded-lg overflow-hidden group cursor-pointer border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800';
  
  if (type === 'photo') {
   div.innerHTML = `<img src="${url}" class="w-full h-full object-cover">`;
  } else {
   div.innerHTML = `
    <div class="absolute inset-0 flex items-center justify-center">
     <i data-lucide="video" class="w-5 h-5 text-slate-400"></i>
    </div>
   `;
  }
  
  const overlay = document.createElement('div');
  overlay.className = 'absolute inset-0 bg-rose-500/90 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity';
  overlay.innerHTML = '<i data-lucide="trash-2" class="w-4 h-4 text-white"></i>';
  overlay.onclick = () => deleteMedia(type, id, entity);
  
  div.appendChild(overlay);
  container.appendChild(div);
 }

 async function deleteMedia(type, id, entity) {
  if (id === 'principale') {
   alert("L'image principale ne peut pas être supprimée seule. Téléchargez une nouvelle photo pour la remplacer.");
   return;
  }

  if (!confirm('Supprimer définitivement cet élément ?')) return;
  
  const basePath = entity === 'car' ? '/admin/cars' : '/admin/parts-inventory';
  const url = `${basePath}/${type}s/${id}`;
  
  try {
   const response = await fetch(url, {
    method: 'DELETE',
    headers: {
     'X-CSRF-TOKEN': '{{ csrf_token() }}',
     'Accept': 'application/json'
    }
   });
   
   const result = await response.json();
   if (result.success) {
    document.getElementById(`media-${type}-${id}`).remove();
   } else {
    alert('Erreur: ' + (result.message || 'Inconnue'));
   }
  } catch (e) {
   console.error(e);
   alert('Erreur réseau.');
  }
 }

 function openShowPartModal(piece) {
  document.getElementById('show_part_nom').innerText = piece.nom;
  document.getElementById('show_part_cat').innerText = piece.categorie.replace('_', ' ');
  document.getElementById('show_part_comp_brand').innerText = piece.marque_compatible || 'UNIVERSELLE';
  document.getElementById('show_part_comp_model').innerText = piece.modele_compatible || '-';
  document.getElementById('show_part_etat').innerText = piece.etat;
  document.getElementById('show_part_prix').innerText = new Intl.NumberFormat('fr-FR').format(piece.prix) + ' FCFA';
  
  const stockEl = document.getElementById('show_part_stock');
  stockEl.innerText = piece.stock;
  stockEl.className = 'text-lg font-semibold ' + (piece.stock <= 2 ? 'text-rose-600 dark:text-rose-500' : 'text-emerald-600 dark:text-emerald-500');
  
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
    thumb.className = 'w-14 h-14 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 overflow-hidden cursor-pointer hover:border-amber-500 transition p-1 flex-shrink-0';
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
    thumb.className = 'w-14 h-14 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden cursor-pointer hover:border-amber-500 transition relative flex-shrink-0';
    thumb.innerHTML = `
     <div class="absolute inset-0 flex items-center justify-center">
      <i data-lucide="play" class="w-4 h-4 text-slate-500"></i>
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
    div.className = 'relative w-12 h-12 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800';
    
    reader.onload = function(e) {
     if (file.type.startsWith('image/')) {
      div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
     } else if (file.type.startsWith('video/')) {
      div.innerHTML = `
       <div class="absolute inset-0 flex items-center justify-center">
        <i data-lucide="video" class="w-4 h-4 text-slate-400"></i>
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
