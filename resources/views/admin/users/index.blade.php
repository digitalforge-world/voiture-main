@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Clients & Staff</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Contrôle total des accès et des rôles système</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openModal('createUserModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 hover:scale-105 duration-300 shadow-xl shadow-amber-900/10 transition-colors">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Ajouter un compte
            </button>
        </div>
    </div>

    <!-- Filters & Search -->
    <form action="{{ route('admin.users.index') }}" method="GET" class="p-6 bg-white dark:bg-slate-900/30 border border-slate-100 dark:border-slate-900 rounded-[3rem] rounded-tr-lg rounded-bl-lg flex flex-wrap items-center gap-6 shadow-lg dark:shadow-2xl transition-colors">
        <div class="flex-grow min-w-[300px] relative group">
            <i data-lucide="search" class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="RECHERCHER UN NOM, EMAIL OU TÉLÉPHONE..." 
                class="w-full py-4 pl-14 pr-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-300 dark:placeholder:text-slate-700 focus:ring-1 focus:ring-amber-500 transition shadow-inner outline-none transition-colors"
            >
        </div>

        <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-white/5 transition-colors">
            <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest transition-colors">Rôle :</span>
            <select name="role" onchange="this.form.submit()" class="bg-transparent border-none text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest focus:ring-0 cursor-pointer transition-colors">
                <option value="" class="bg-white dark:bg-slate-950">Tous</option>
                <option value="admin" class="bg-white dark:bg-slate-950" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="agent" class="bg-white dark:bg-slate-950" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                <option value="client" class="bg-white dark:bg-slate-950" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
            </select>
        </div>

        <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-white/5 transition-colors">
            <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest transition-colors">Statut :</span>
            <select name="status" onchange="this.form.submit()" class="bg-transparent border-none text-[10px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-widest focus:ring-0 cursor-pointer transition-colors">
                <option value="" class="bg-white dark:bg-slate-950">Tous</option>
                <option value="active" class="bg-white dark:bg-slate-950" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="suspended" class="bg-white dark:bg-slate-950" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendus</option>
            </select>
        </div>

        <button type="submit" class="p-4 bg-amber-500 text-slate-950 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/10 transition-colors">
            <i data-lucide="filter" class="w-4 h-4"></i>
        </button>

        @if(request()->anyFilled(['search', 'role', 'status']))
            <a href="{{ route('admin.users.index') }}" class="p-4 bg-slate-50 dark:bg-slate-800 text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-2xl transition border border-slate-100 dark:border-white/5 transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
        @endif
    </form>

    <!-- Users Table -->
    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-[3rem] rounded-tl-lg rounded-br-lg shadow-lg dark:shadow-2xl backdrop-blur-sm transition-colors">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5 transition-colors">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Identité</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Rôle</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Coordonnées</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Statut</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic transition-colors">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                @forelse($users as $user)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300 transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-white/5 flex items-center justify-center overflow-hidden shadow-inner transition-colors">
                                @if($user->photo_profil)
                                    <img src="{{ $user->photo_profil }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-black text-slate-400 dark:text-slate-600 text-lg uppercase transition-colors">{{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900 dark:text-white tracking-tight italic transition-colors">{{ $user->prenom }} {{ $user->nom }}</div>
                                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest italic mt-1 transition-colors">Inscrit le {{ $user->date_creation?->format('d/m/Y') ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $roleColor = match($user->role) {
                                'admin' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                'agent' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                default => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border {{ $roleColor }} text-[10px] font-black uppercase tracking-widest italic leading-none">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                                <i data-lucide="mail" class="w-3 h-3 text-slate-400 dark:text-slate-500 transition-colors"></i> {{ $user->email }}
                            </div>
                            <div class="flex items-center gap-2 text-[11px] text-slate-700 dark:text-slate-300 font-medium transition-colors">
                                <i data-lucide="phone" class="w-3 h-3 text-slate-400 dark:text-slate-500 transition-colors"></i> {{ $user->telephone }}
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $user->actif ? 'bg-emerald-500 shadow-sm shadow-emerald-500/50' : 'bg-rose-500' }}"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest italic {{ $user->actif ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $user->actif ? 'Actif' : 'Suspendu' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-2 text-right">
                            <button 
                                onclick="openEditUserModal({{ json_encode($user) }})"
                                class="p-2.5 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-400 hover:text-white hover:bg-amber-500 transition rounded-xl group/btn border border-slate-100 dark:border-white/5 transition-colors"
                            >
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-400 hover:text-white hover:bg-rose-500 transition rounded-xl group/btn border border-slate-100 dark:border-white/5 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucun utilisateur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-white/5 transition-colors">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/80 backdrop-blur-lg transition-colors" onclick="closeModal('createUserModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-2xl p-10 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300 transition-colors">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center justify-between mb-10 relative">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Nouveau Compte</h2>
                    <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[9px] mt-1 italic transition-colors">Création d'un accès collaborateur ou client</p>
                </div>
                <button onclick="closeModal('createUserModal')" class="p-4 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-2xl border border-slate-100 dark:border-white/5 transition transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6 relative">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Prénom</label>
                        <input type="text" name="prenom" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Nom</label>
                        <input type="text" name="nom" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Email Professionnel</label>
                    <input type="email" name="email" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Téléphone</label>
                        <input type="text" name="telephone" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Rôle Système</label>
                        <select name="role" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none transition-colors">
                            <option value="client">Client</option>
                            <option value="agent">Agent / Staff</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Mot de passe temporaire</label>
                    <input type="password" name="mot_de_passe" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="button" onclick="closeModal('createUserModal')" class="flex-1 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-800 transition mt-auto transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-5 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/10 transition-colors">Initialiser le compte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/80 backdrop-blur-lg transition-colors" onclick="closeModal('editUserModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-2xl p-10 shadow-2xl rounded-[4rem] rounded-tl-xl rounded-br-xl overflow-hidden animate-in zoom-in duration-300 transition-colors">
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center justify-between mb-10 relative">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Modifier l'accès</h2>
                    <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[9px] mt-1 italic transition-colors">Mise à jour des privilèges et données client</p>
                </div>
                <button onclick="closeModal('editUserModal')" class="p-4 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-2xl border border-slate-100 dark:border-white/5 transition transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="editUserForm" method="POST" class="space-y-6 relative">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Prénom</label>
                        <input type="text" name="prenom" id="edit_prenom" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Nom</label>
                        <input type="text" name="nom" id="edit_nom" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Email</label>
                    <input type="email" name="email" id="edit_email" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Téléphone</label>
                        <input type="text" name="telephone" id="edit_telephone" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 transition-colors">Rôle</label>
                        <select name="role" id="edit_role" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none transition-colors">
                            <option value="client">Client</option>
                            <option value="agent">Agent / Staff</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-white/5 transition-colors">
                    <div class="flex-grow">
                        <div class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-wider italic transition-colors">Statut du compte</div>
                        <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-1 italic transition-colors">Autoriser ou révoquer l'accès système</div>
                    </div>
                    <select name="actif" id="edit_actif" class="bg-white dark:bg-slate-900 border-none text-[10px] font-black uppercase tracking-widest text-amber-600 dark:text-amber-500 focus:ring-0 rounded-xl px-4 py-2 transition-colors">
                        <option value="1">Actif</option>
                        <option value="0">Compte Suspendu</option>
                    </select>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="button" onclick="closeModal('editUserModal')" class="flex-1 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-900 transition mt-auto transition-colors">Fermer</button>
                    <button type="submit" class="flex-[2] py-5 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/10 transition-colors">Enregistrer les changements</button>
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

    // Close on escape
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('createUserModal');
            closeModal('editUserModal');
        }
    });
</script>
@endsection
@endsection
