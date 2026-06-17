@extends('layouts.admin')

@section('title', 'Gestion du Catalogue - AutoImport Hub')

@section('content')
<div class="space-y-6">
 <!-- Header Area -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Inventaire Véhicules</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gestion du stock local et des importations en cours</p>
  </div>
  <div class="flex items-center gap-3">
   <button onclick="openModal('createCarModal')" class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 text-slate-900 font-medium rounded-lg hover:bg-amber-600 transition shadow-sm">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span>Nouveau véhicule</span>
   </button>
  </div>
 </div>

  <!-- Session Messages & Validation Errors -->
  @if($errors->any())
  <div class="p-4 bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20 rounded-xl text-rose-600 dark:text-rose-400 text-sm">
    <div class="font-bold mb-2 uppercase tracking-wide text-xs">Erreurs lors de l'enregistrement :</div>
    <ul class="list-disc pl-5 space-y-1">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  @if(session('success'))
  <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl text-emerald-600 dark:text-emerald-400 text-sm font-semibold transition-colors">
    {{ session('success') }}
  </div>
  @endif

  <!-- Filters & Search -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-4">
  <form action="{{ route('admin.cars.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
   
   <div class="flex-grow min-w-[250px] relative">
    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
    <input 
     type="text"
     name="search"
     value="{{ request('search') }}"
     placeholder="Rechercher par marque, modèle..."
     class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition"
    >
   </div>

   <div class="flex items-center gap-3 w-full md:w-auto">
    <select name="availability" onchange="this.form.submit()" class="py-2 pl-3 pr-8 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none">
     <option value="">Tous les statuts</option>
     <option value="disponible" {{ request('availability') == 'disponible' ? 'selected' : '' }}>En Stock</option>
     <option value="importation" {{ request('availability') == 'importation' ? 'selected' : '' }}>En Importation</option>
     <option value="reserve" {{ request('availability') == 'reserve' ? 'selected' : '' }}>Réservé</option>
     <option value="vendu" {{ request('availability') == 'vendu' ? 'selected' : '' }}>Vendu</option>
    </select>
   </div>

   <div class="flex items-center gap-3 w-full md:w-auto">
    <select name="condition" onchange="this.form.submit()" class="py-2 pl-3 pr-8 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none">
     <option value="">Tous les états</option>
     <option value="neuf" {{ request('condition') == 'neuf' ? 'selected' : '' }}>Neuf</option>
     <option value="occasion" {{ request('condition') == 'occasion' ? 'selected' : '' }}>Occasion</option>
     <option value="reconditionne" {{ request('condition') == 'reconditionne' ? 'selected' : '' }}>Reconditionné</option>
    </select>
   </div>

   <div class="flex items-center gap-3 w-full md:w-auto">
    <select name="categorie" onchange="this.form.submit()" class="py-2 pl-3 pr-8 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none">
     <option value="">Toutes catégories</option>
     <option value="voiture" {{ request('categorie') == 'voiture' ? 'selected' : '' }}>🚗 Voiture</option>
     <option value="scooter" {{ request('categorie') == 'scooter' ? 'selected' : '' }}>🛵 Scooter</option>
    </select>
   </div>

   @if(request()->anyFilled(['search', 'availability', 'condition', 'categorie']))
   <a href="{{ route('admin.cars.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg whitespace-nowrap">
    Effacer filtres
   </a>
   @endif
  </form>
 </div>

 <!-- Cars Table -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
     <tr>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Véhicule</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Informations</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Statut</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Prix</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
     @forelse($voitures as $car)
     <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
      <td class="px-6 py-4">
       <div class="flex items-center gap-4">
        <div class="w-16 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 overflow-hidden flex-shrink-0 flex items-center justify-center">
         @if($car->photo_principale)
          <img src="{{ $car->photo_principale }}" class="w-full h-full object-cover">
         @elseif(($car->categorie ?? 'voiture') === 'scooter')
          <span class="text-2xl">🛵</span>
         @else
          <i data-lucide="car" class="w-5 h-5 text-slate-400"></i>
         @endif
        </div>
        <div>
         <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $car->marque }} {{ $car->modele }}</div>
         <div class="text-xs text-slate-500">{{ $car->annee }} • {{ $car->numero_chassis ?? 'Sans Châssis' }}</div>
        </div>
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm text-slate-900 dark:text-white">{{ number_format($car->kilometrage, 0, ',', ' ') }} km</div>
       <div class="text-xs text-slate-500 capitalize">{{ $car->etat }}</div>
      </td>
      <td class="px-6 py-4">
       @php
        $statusColors = [
         'disponible' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-500',
         'importation' => 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-500',
         'reserve' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500',
         'vendu' => 'bg-slate-100 text-slate-800 dark:bg-slate-500/10 dark:text-slate-500',
        ];
        $colorClass = $statusColors[$car->disponibilite] ?? $statusColors['vendu'];
       @endphp
       <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize {{ $colorClass }}">
        {{ str_replace('_', ' ', $car->disponibilite) }}
       </span>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm font-medium text-slate-900 dark:text-white">{{ number_format($car->prix, 0, ',', ' ') }} <span class="text-xs text-slate-500">FCFA</span></div>
      </td>
      <td class="px-6 py-4 text-right">
       <div class="flex items-center justify-end gap-2">
        <button onclick="openShowCarModal({{ json_encode($car) }})" class="p-2 text-slate-400 hover:text-blue-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="eye" class="w-4 h-4"></i>
        </button>
        <button onclick="openEditCarModal({{ json_encode($car) }})" class="p-2 text-slate-400 hover:text-amber-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="edit-2" class="w-4 h-4"></i>
        </button>
        <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')" class="inline">
         @csrf
         @method('DELETE')
         <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
         </button>
        </form>
       </div>
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
       Aucun véhicule trouvé.
      </td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
  @if($voitures->hasPages())
  <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
   {{ $voitures->links() }}
  </div>
  @endif
 </div>
</div>

@include('admin.cars.create-modal')

<!-- Edit Car Modal -->
<div id="editCarModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editCarModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-3xl shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <h3 class="text-lg font-medium text-slate-900 dark:text-white">Modifier le Véhicule</h3>
    <button onclick="closeModal('editCarModal')" class="text-slate-400 hover:text-slate-500 transition">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editCarForm" method="POST" enctype="multipart/form-data" class="p-6 max-h-[75vh] overflow-y-auto">
    @csrf
    @method('PUT')
    
    <div class="space-y-6">
     <!-- Pricing and Mileage -->
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
       <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Prix de vente (FCFA)</label>
       <input type="number" name="prix" id="edit_prix" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
      </div>
      <div>
       <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kilométrage</label>
       <input type="number" name="kilometrage" id="edit_kilometrage" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
      </div>
     </div>

     <!-- Status and Origin -->
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
       <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">État</label>
       <select name="etat" id="edit_etat" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
        <option value="neuf">Neuf</option>
        <option value="occasion">Occasion</option>
        <option value="reconditionne">Reconditionné</option>
       </select>
      </div>
      <div>
       <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Disponibilité</label>
       <select name="disponibilite" id="edit_disponibilite" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
        <option value="disponible">En Stock</option>
        <option value="importation">En Importation</option>
        <option value="reserve">Réservé</option>
        <option value="vendu">Vendu</option>
       </select>
      </div>
      <div>
       <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pays d'origine</label>
       <input type="text" name="pays_origine" id="edit_pays_origine" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
      </div>
     </div>

     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label>
      <textarea name="description" id="edit_description" rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none"></textarea>
     </div>

     <!-- Visuels -->
     <div class="space-y-6 border-t border-slate-200 dark:border-slate-800 pt-6">
      <h3 class="text-[10px] font-semibold text-amber-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Images & Vidéos</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
       <div class="space-y-2">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Photo principale</label>
        <div class="relative group">
         <input type="file" name="photo_principale" accept="image/*" onchange="handleFilePreview(this, 'edit_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
         <div class="w-full py-4 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:border-amber-500 transition-colors">
          <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
          <span class="text-sm text-slate-500 dark:text-slate-400">Remplacer la photo principale</span>
         </div>
        </div>
       </div>
       <div class="space-y-2">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Galerie photos</label>
        <div class="relative group">
         <input type="file" name="photos[]" accept="image/*" multiple onchange="handleFilePreview(this, 'edit_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
         <div class="w-full py-4 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:border-amber-500 transition-colors">
          <i data-lucide="images" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
          <span class="text-sm text-slate-500 dark:text-slate-400">Ajouter des images supplémentaires</span>
         </div>
        </div>
       </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
       <div class="space-y-2">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Vidéos</label>
        <div class="relative group">
         <input type="file" name="videos[]" accept="video/*" multiple onchange="handleFilePreview(this, 'edit_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
         <div class="w-full py-4 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-3xl flex flex-col items-center justify-center gap-3 group-hover:border-amber-500 transition-colors">
          <i data-lucide="video" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
          <span class="text-sm text-slate-500 dark:text-slate-400">Ajouter des vidéos</span>
         </div>
        </div>
       </div>
       <div class="space-y-2">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Médias existants</label>
        <div id="edit_existing_media" class="grid grid-cols-2 gap-3"></div>
       </div>
      </div>
      <div id="edit_media_preview" class="flex flex-wrap gap-4"></div>
     </div>
    </div>

    <!-- Submit buttons -->
    <div class="mt-8 flex justify-end gap-3">
     <button type="button" onclick="closeModal('editCarModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-slate-900 bg-amber-500 rounded-lg hover:bg-amber-600 transition">Enregistrer</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Show Car Modal -->
<div id="showCarModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('showCarModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-2xl shadow-xl overflow-hidden">
   <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-start">
    <div class="flex items-center gap-4">
     <div class="w-24 h-24 rounded-lg bg-slate-100 dark:bg-slate-800 overflow-hidden">
      <img id="show_car_photo" src="" class="w-full h-full object-cover">
     </div>
     <div>
      <h3 id="show_car_title" class="text-xl font-bold text-slate-900 dark:text-white"></h3>
      <p id="show_car_subtitle" class="text-sm text-slate-500"></p>
      <div id="show_badge_val" class="mt-2 inline-block px-2.5 py-0.5 rounded-full text-xs font-medium"></div>
     </div>
    </div>
    <button onclick="closeModal('showCarModal')" class="text-slate-400 hover:text-slate-500 transition flex-shrink-0">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
     <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Détails de vente</h4>
     <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
      <li class="flex justify-between"><span class="text-slate-500">Prix:</span> <strong id="show_prix_val" class="text-amber-600 dark:text-amber-500 text-base"></strong></li>
      <li class="flex justify-between"><span class="text-slate-500">État:</span> <span id="show_etat_val" class="capitalize"></span></li>
      <li class="flex justify-between"><span class="text-slate-500">Pays d'origine:</span> <span id="show_pays_val" class="uppercase"></span></li>
     </ul>
    </div>
    <div>
     <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Fiche technique</h4>
     <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
      <li class="flex justify-between"><span class="text-slate-500">Moteur:</span> <span id="show_moteur_val"></span></li>
      <li class="flex justify-between"><span class="text-slate-500">Transmission:</span> <span id="show_transmission_val" class="capitalize"></span></li>
      <li class="flex justify-between"><span class="text-slate-500">Carburant:</span> <span id="show_carburant_val" class="capitalize"></span></li>
     </ul>
    </div>
    <div class="md:col-span-2">
     <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">Description</h4>
     <p id="show_desc_val" class="text-sm text-slate-600 dark:text-slate-400 p-3 bg-slate-50 dark:bg-slate-800 rounded-lg"></p>
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

 function openEditCarModal(car) {
  const form = document.getElementById('editCarForm');
  form.action = `/admin/cars/${car.id}`;
  
  document.getElementById('edit_prix').value = car.prix;
  document.getElementById('edit_kilometrage').value = car.kilometrage;
  document.getElementById('edit_etat').value = car.etat;
  document.getElementById('edit_disponibilite').value = car.disponibilite;
  document.getElementById('edit_pays_origine').value = car.pays_origine || '';
  document.getElementById('edit_description').value = car.description || '';

  const existingMedia = document.getElementById('edit_existing_media');
  existingMedia.innerHTML = '';

  if (car.photos && car.photos.length) {
    car.photos.forEach(photo => {
      const card = document.createElement('div');
      card.className = 'relative border border-slate-200 dark:border-white/10 rounded-3xl overflow-hidden';
      card.innerHTML = `
        <img src="${photo.url}" alt="Photo voiture" class="w-full h-24 object-cover">
        <button type="button" onclick="deleteExistingPhoto(${photo.id}, this)" class="absolute top-2 right-2 bg-slate-950/80 text-white rounded-full p-1 hover:bg-red-500 transition">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      `;
      existingMedia.appendChild(card);
    });
  }

  if (car.videos && car.videos.length) {
    car.videos.forEach(video => {
      const card = document.createElement('div');
      card.className = 'relative border border-slate-200 dark:border-white/10 rounded-3xl overflow-hidden bg-slate-950/10';
      card.innerHTML = `
        <div class="p-4 text-center text-slate-500">
          <i data-lucide="video" class="w-6 h-6 mx-auto mb-2"></i>
          <p class="text-[10px] truncate">${video.url.split('/').pop()}</p>
        </div>
        <button type="button" onclick="deleteExistingVideo(${video.id}, this)" class="absolute top-2 right-2 bg-slate-950/80 text-white rounded-full p-1 hover:bg-red-500 transition">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      `;
      existingMedia.appendChild(card);
    });
  }

  handleFilePreview(document.querySelector('input[name="photo_principale"]'), 'edit_media_preview');
  openModal('editCarModal');
 }

 function deleteExistingPhoto(photoId, button) {
  if (!confirm('Supprimer cette photo existante ?')) return;
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch(`/admin/cars/photos/${photoId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json'
    }
  }).then(res => res.json()).then(data => {
    if (data.success) {
      button.closest('div').remove();
    } else {
      alert(data.message || 'Impossible de supprimer la photo');
    }
  });
 }

 function deleteExistingVideo(videoId, button) {
  if (!confirm('Supprimer cette vidéo existante ?')) return;
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch(`/admin/cars/videos/${videoId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json'
    }
  }).then(res => res.json()).then(data => {
    if (data.success) {
      button.closest('div').remove();
    } else {
      alert(data.message || 'Impossible de supprimer la vidéo');
    }
  });
 }

 function openShowCarModal(car) {
  document.getElementById('show_car_title').innerText = `${car.marque} ${car.modele}`;
  document.getElementById('show_car_subtitle').innerText = `${car.annee} • ${car.kilometrage.toLocaleString()} км`;
  document.getElementById('show_car_photo').src = car.photo_principale || '/images/placeholder-car.jpg';
  
  document.getElementById('show_prix_val').innerText = new Intl.NumberFormat('fr-FR').format(car.prix) + ' FCFA';
  document.getElementById('show_etat_val').innerText = car.etat || '-';
  document.getElementById('show_pays_val').innerText = car.pays_origine || '-';
  document.getElementById('show_desc_val').innerText = car.description || 'Aucune description.';
  
  document.getElementById('show_moteur_val').innerText = car.moteur || 'N/A';
  document.getElementById('show_transmission_val').innerText = car.transmission || 'N/A';
  document.getElementById('show_carburant_val').innerText = car.carburant || 'N/A';

  const badge = document.getElementById('show_badge_val');
  badge.innerText = car.disponibilite.replace('_', ' ');
  
  const colors = {
   'disponible': 'bg-emerald-100 text-emerald-800',
   'importation': 'bg-blue-100 text-blue-800',
   'reserve': 'bg-amber-100 text-amber-800',
   'vendu': 'bg-slate-100 text-slate-800'
  };
  badge.className = `mt-2 inline-block px-2.5 py-0.5 rounded-full text-xs font-medium capitalize ${colors[car.disponibilite] || colors['vendu']}`;

  openModal('showCarModal');
 }

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
