@extends('layouts.admin')

@section('title', 'Gestion de l\'Équipe Chauffeurs - AutoImport Hub')

@section('content')
<div class="space-y-6">
  <!-- Header Area -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Équipe de Chauffeurs</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gérez vos chauffeurs de transport et leurs véhicules associés</p>
    </div>
    <div class="flex items-center gap-4">
      <button onclick="openModal('createDriverModal')" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-amber-500 rounded-lg hover:bg-amber-600 shadow-sm">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        <span>Ajouter un chauffeur</span>
      </button>
    </div>
  </div>

  @if(session('success'))
  <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 flex items-center gap-3 text-emerald-600 dark:text-emerald-400 text-sm">
    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
    <span class="font-medium">{{ session('success') }}</span>
  </div>
  @endif

  <!-- Filters & Search -->
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-4">
    <form action="{{ route('admin.drivers.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
      <div class="flex-grow min-w-[300px] relative w-full">
        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
        <input 
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Rechercher par nom, téléphone, marque de voiture ou immatriculation..."
          class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none transition"
        >
      </div>

      <div class="flex items-center gap-3 w-full md:w-auto">
        <select name="statut" onchange="this.form.submit()" class="py-2 pl-3 pr-8 w-full md:w-auto bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none">
          <option value="">Tous les statuts</option>
          <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actifs</option>
          <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactifs</option>
        </select>
      </div>

      @if(request()->anyFilled(['search', 'statut']))
        <a href="{{ route('admin.drivers.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg whitespace-nowrap">
          Effacer
        </a>
      @endif
    </form>
  </div>

  <!-- Drivers Table -->
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
          <tr>
            <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Identité</th>
            <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Téléphone</th>
            <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Véhicule</th>
            <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Statut</th>
            <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
          @forelse($drivers as $driver)
          <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                  @if($driver->photo)
                    <img src="{{ $driver->photo }}" class="w-full h-full object-cover">
                  @else
                    <span class="font-medium text-slate-600 dark:text-slate-400 text-sm uppercase">{{ substr($driver->prenom, 0, 1) }}{{ substr($driver->nom, 0, 1) }}</span>
                  @endif
                </div>
                <div>
                  <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $driver->prenom }} {{ $driver->nom }}</div>
                  <div class="text-xs text-slate-500 mt-0.5">Enregistré le {{ $driver->created_at?->format('d/m/Y') ?? 'N/A' }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <a href="tel:{{ $driver->telephone }}" class="text-sm text-slate-700 dark:text-slate-300 font-semibold hover:text-amber-500 transition flex items-center gap-1.5">
                <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i>
                {{ $driver->telephone }}
              </a>
            </td>
            <td class="px-6 py-4">
              <div class="flex flex-col">
                <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $driver->vehicule_marque }} {{ $driver->vehicule_modele }}</span>
                <span class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                  <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-mono text-[10px] text-slate-700 dark:text-slate-300 uppercase">{{ $driver->vehicule_immatriculation }}</span>
                  @if($driver->vehicule_couleur)
                    <span>• {{ $driver->vehicule_couleur }}</span>
                  @endif
                </span>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $driver->statut === 'actif' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                <span class="text-sm font-medium {{ $driver->statut === 'actif' ? 'text-slate-900 dark:text-white' : 'text-slate-500' }}">
                  {{ $driver->statut === 'actif' ? 'Actif' : 'Inactif' }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button 
                  onclick="openEditDriverModal({{ json_encode($driver) }})"
                  class="p-2 text-slate-400 hover:text-amber-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"
                  title="Modifier"
                >
                  <i data-lucide="edit-3" class="w-4 h-4"></i>
                </button>
                <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment retirer ce chauffeur de l\'équipe ?')" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" title="Supprimer">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-sm">
              <div class="flex flex-col items-center justify-center gap-2">
                <i data-lucide="user-x" class="w-8 h-8 text-slate-400"></i>
                <span>Aucun chauffeur trouvé dans l'équipe.</span>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($drivers->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
      {{ $drivers->links() }}
    </div>
    @endif
  </div>
</div>

<!-- Create Driver Modal -->
<div id="createDriverModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
  <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('createDriverModal')"></div>
  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800">
      <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
        <div>
          <h2 class="text-lg font-medium text-slate-900 dark:text-white">Nouveau Chauffeur</h2>
          <p class="text-xs text-slate-500 mt-0.5">Enregistrer un chauffeur dans l'équipe et ses détails de véhicule</p>
        </div>
        <button onclick="closeModal('createDriverModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
        @csrf
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Prénom <span class="text-rose-500">*</span></label>
            <input type="text" name="prenom" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nom <span class="text-rose-500">*</span></label>
            <input type="text" name="nom" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Téléphone <span class="text-rose-500">*</span></label>
            <input type="text" name="telephone" required placeholder="+225 07 00 00 00 00" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Statut</label>
            <select name="statut" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
              <option value="actif">Actif</option>
              <option value="inactif">Inactif</option>
            </select>
          </div>
        </div>

        <div class="border-t border-slate-100 dark:border-slate-800 my-4 pt-4">
          <h3 class="text-xs font-bold text-amber-500 uppercase tracking-widest mb-3">Informations du Véhicule</h3>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Marque <span class="text-rose-500">*</span></label>
              <input type="text" name="vehicule_marque" required placeholder="Ex: Toyota" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Modèle <span class="text-rose-500">*</span></label>
              <input type="text" name="vehicule_modele" required placeholder="Ex: Prado" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-3">
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Immatriculation <span class="text-rose-500">*</span></label>
              <input type="text" name="vehicule_immatriculation" required placeholder="Ex: 1234-AB-01" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Couleur</label>
              <input type="text" name="vehicule_couleur" placeholder="Ex: Noir métallisé" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
            </div>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Photo de Profil</label>
          <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-500 hover:file:bg-amber-500/20">
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
          <button type="button" onclick="closeModal('createDriverModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium transition">Annuler</button>
          <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-lg text-sm font-bold shadow-sm transition">Sauvegarder</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Driver Modal -->
<div id="editDriverModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
  <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('editDriverModal')"></div>
  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800">
      <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
        <div>
          <h2 class="text-lg font-medium text-slate-900 dark:text-white">Modifier le Chauffeur</h2>
          <p class="text-xs text-slate-500 mt-0.5">Mettre à jour les informations du chauffeur et du véhicule</p>
        </div>
        <button onclick="closeModal('editDriverModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="editDriverForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Prénom <span class="text-rose-500">*</span></label>
            <input type="text" name="prenom" id="edit_prenom" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nom <span class="text-rose-500">*</span></label>
            <input type="text" name="nom" id="edit_nom" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Téléphone <span class="text-rose-500">*</span></label>
            <input type="text" name="telephone" id="edit_telephone" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Statut</label>
            <select name="statut" id="edit_statut" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
              <option value="actif">Actif</option>
              <option value="inactif">Inactif</option>
            </select>
          </div>
        </div>

        <div class="border-t border-slate-100 dark:border-slate-800 my-4 pt-4">
          <h3 class="text-xs font-bold text-amber-500 uppercase tracking-widest mb-3">Informations du Véhicule</h3>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Marque <span class="text-rose-500">*</span></label>
              <input type="text" name="vehicule_marque" id="edit_vehicule_marque" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Modèle <span class="text-rose-500">*</span></label>
              <input type="text" name="vehicule_modele" id="edit_vehicule_modele" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-3">
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Immatriculation <span class="text-rose-500">*</span></label>
              <input type="text" name="vehicule_immatriculation" id="edit_vehicule_immatriculation" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Couleur</label>
              <input type="text" name="vehicule_couleur" id="edit_vehicule_couleur" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
            </div>
          </div>
        </div>

        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden flex-shrink-0">
            <img id="edit_photo_preview" src="" class="w-full h-full object-cover hidden">
            <div id="edit_photo_placeholder" class="w-full h-full flex items-center justify-center text-slate-400 text-xs">Photo</div>
          </div>
          <div class="flex-grow">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Remplacer la Photo</label>
            <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-500 hover:file:bg-amber-500/20">
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
          <button type="button" onclick="closeModal('editDriverModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium transition">Annuler</button>
          <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-lg text-sm font-bold shadow-sm transition">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    document.body.style.overflow = '';
  }

  function openEditDriverModal(driver) {
    document.getElementById('editDriverForm').action = `/admin/drivers/${driver.id}`;
    document.getElementById('edit_prenom').value = driver.prenom;
    document.getElementById('edit_nom').value = driver.nom;
    document.getElementById('edit_telephone').value = driver.telephone;
    document.getElementById('edit_statut').value = driver.statut;
    
    document.getElementById('edit_vehicule_marque').value = driver.vehicule_marque;
    document.getElementById('edit_vehicule_modele').value = driver.vehicule_modele;
    document.getElementById('edit_vehicule_immatriculation').value = driver.vehicule_immatriculation;
    document.getElementById('edit_vehicule_couleur').value = driver.vehicule_couleur || '';

    const preview = document.getElementById('edit_photo_preview');
    const placeholder = document.getElementById('edit_photo_placeholder');
    if (driver.photo) {
      preview.src = driver.photo;
      preview.classList.remove('hidden');
      placeholder.classList.add('hidden');
    } else {
      preview.classList.add('hidden');
      placeholder.classList.remove('hidden');
    }

    openModal('editDriverModal');
  }
</script>
@endsection
