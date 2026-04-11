@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs - AutoImport Hub')

@section('content')
<div class="space-y-6">
 <!-- Header Area -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Clients & Staff</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Contrôle total des accès et des rôles système</p>
  </div>
  <div class="flex items-center gap-4">
   <button onclick="openModal('createUserModal')" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-amber-500 rounded-lg hover:bg-amber-600 shadow-sm">
    <i data-lucide="user-plus" class="w-4 h-4"></i>
    <span>Ajouter un compte</span>
   </button>
  </div>
 </div>

 <!-- Filters & Search -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-4">
  <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
   <div class="flex-grow min-w-[300px] relative">
    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
    <input 
     type="text"
     name="search"
     value="{{ request('search') }}"
     placeholder="Rechercher par nom, email ou téléphone..."
     class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none transition"
    >
   </div>

   <div class="flex items-center gap-3 w-full md:w-auto">
    <select name="role" onchange="this.form.submit()" class="py-2 pl-3 pr-8 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none">
     <option value="">Tous les rôles</option>
     <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
     <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
     <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
    </select>
   </div>

   <div class="flex items-center gap-3 w-full md:w-auto">
    <select name="status" onchange="this.form.submit()" class="py-2 pl-3 pr-8 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none">
     <option value="">Tous les statuts</option>
     <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
     <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendus</option>
    </select>
   </div>

   @if(request()->anyFilled(['search', 'role', 'status']))
    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg whitespace-nowrap">
     Effacer
    </a>
   @endif
  </form>
 </div>

 <!-- Users Table -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
     <tr>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Identité</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Rôle</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Coordonnées</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Statut</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
     @forelse($users as $user)
     <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
      <td class="px-6 py-4">
       <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden flex-shrink-0">
         @if($user->photo_profil)
          <img src="{{ $user->photo_profil }}" class="w-full h-full object-cover">
         @else
          <span class="font-medium text-slate-600 dark:text-slate-400 text-sm uppercase">{{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}</span>
         @endif
        </div>
        <div>
         <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->prenom }} {{ $user->nom }}</div>
         <div class="text-xs text-slate-500 mt-0.5">Inscrit le {{ $user->date_creation?->format('d/m/Y') ?? 'N/A' }}</div>
        </div>
       </div>
      </td>
      <td class="px-6 py-4">
       @php
        $roleColors = [
         'admin' => 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-400',
         'agent' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400',
         'client' => 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400',
        ];
        $roleColor = $roleColors[$user->role] ?? 'bg-slate-100 text-slate-800';
       @endphp
       <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $roleColor }} capitalize">
        {{ $user->role }}
       </span>
      </td>
      <td class="px-6 py-4">
       <div class="flex flex-col gap-1">
        <div class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
         <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i> {{ $user->email }}
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
         <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i> {{ $user->telephone }}
        </div>
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full {{ $user->actif ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
        <span class="text-sm font-medium {{ $user->actif ? 'text-slate-900 dark:text-white' : 'text-slate-500' }}">
         {{ $user->actif ? 'Actif' : 'Suspendu' }}
        </span>
       </div>
      </td>
      <td class="px-6 py-4 text-right">
       <div class="flex items-center justify-end gap-2">
        <button 
         onclick="openEditUserModal({{ json_encode($user) }})"
         class="p-2 text-slate-400 hover:text-amber-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"
        >
         <i data-lucide="edit-3" class="w-4 h-4"></i>
        </button>
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de cet utilisateur ?')" class="inline">
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
       Aucun utilisateur trouvé.
      </td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
  @if($users->hasPages())
  <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
   {{ $users->links() }}
  </div>
  @endif
 </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('createUserModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <div>
     <h2 class="text-lg font-medium text-slate-900 dark:text-white">Nouveau Compte</h2>
     <p class="text-sm text-slate-500 mt-0.5">Création d'un accès collaborateur ou client</p>
    </div>
    <button onclick="closeModal('createUserModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
    @csrf
    
    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Prénom <span class="text-rose-500">*</span></label>
      <input type="text" name="prenom" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nom <span class="text-rose-500">*</span></label>
      <input type="text" name="nom" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
    </div>

    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email <span class="text-rose-500">*</span></label>
     <input type="email" name="email" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
    </div>

    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Téléphone <span class="text-rose-500">*</span></label>
      <input type="text" name="telephone" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Rôle Système</label>
      <select name="role" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
       <option value="client">Client</option>
       <option value="agent">Agent / Staff</option>
       <option value="admin">Administrateur</option>
      </select>
     </div>
    </div>

    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mot de passe temporaire <span class="text-rose-500">*</span></label>
     <input type="password" name="mot_de_passe" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('createUserModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">Créer le compte</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editUserModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <div>
     <h2 class="text-lg font-medium text-slate-900 dark:text-white">Modifier l'accès</h2>
     <p class="text-sm text-slate-500 mt-0.5">Mise à jour des privilèges et données client</p>
    </div>
    <button onclick="closeModal('editUserModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editUserForm" method="POST" class="p-6 space-y-4">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Prénom <span class="text-rose-500">*</span></label>
      <input type="text" name="prenom" id="edit_prenom" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nom <span class="text-rose-500">*</span></label>
      <input type="text" name="nom" id="edit_nom" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
    </div>

    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email <span class="text-rose-500">*</span></label>
     <input type="email" name="email" id="edit_email" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
    </div>

    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Téléphone <span class="text-rose-500">*</span></label>
      <input type="text" name="telephone" id="edit_telephone" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Rôle</label>
      <select name="role" id="edit_role" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
       <option value="client">Client</option>
       <option value="agent">Agent / Staff</option>
       <option value="admin">Administrateur</option>
      </select>
     </div>
    </div>

    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800">
     <div>
      <div class="text-sm font-medium text-slate-900 dark:text-white">Statut du compte</div>
      <div class="text-xs text-slate-500">Autoriser ou révoquer l'accès système</div>
     </div>
     <div class="flex items-center">
      <select name="actif" id="edit_actif" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-sm font-medium text-slate-900 dark:text-white rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-amber-500 outline-none">
       <option value="1">Actif</option>
       <option value="0">Suspendu</option>
      </select>
     </div>
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('editUserModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
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

 function openEditUserModal(user) {
  const form = document.getElementById('editUserForm');
  form.action = `/admin/users/${user.id}`;
  
  document.getElementById('edit_prenom').value = user.prenom;
  document.getElementById('edit_nom').value = user.nom;
  document.getElementById('edit_email').value = user.email;
  document.getElementById('edit_telephone').value = user.telephone;
  document.getElementById('edit_role').value = user.role;
  document.getElementById('edit_actif').value = user.actif ? "1" : "0";

  openModal('editUserModal');
 }

 window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
   closeModal('createUserModal');
   closeModal('editUserModal');
  }
 });
</script>
@endsection
@endsection
