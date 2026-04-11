@php
 $users = $users ?? \App\Models\User::all();
 $voitures = $voitures ?? \App\Models\VoitureLocation::where('disponible', true)->get();
@endphp

<!-- Create Rental Modal -->
<div id="createRentalModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('createRentalModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-2xl shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <div>
     <h2 class="text-lg font-medium text-slate-900 dark:text-white">Nouveau Contrat</h2>
     <p class="text-xs text-slate-500 mt-0.5">Ouverture d'un dossier de location</p>
    </div>
    <button onclick="closeModal('createRentalModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form action="{{ route('admin.rentals.store') }}" method="POST" class="p-6 space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Client Enregistré</label>
      <select name="user_id" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
       <option value="">Sélectionnez un client...</option>
       @foreach($users as $user)
       <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }} ({{ $user->telephone }})</option>
       @endforeach
      </select>
     </div>

     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Véhicule <span class="text-rose-500">*</span></label>
      <select name="voiture_location_id" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
       @foreach($voitures as $voiture)
       <option value="{{ $voiture->id }}">{{ $voiture->marque }} {{ $voiture->modele }} - {{ number_format($voiture->prix_jour, 0, ',', ' ') }} FCFA/J</option>
       @endforeach
      </select>
     </div>
    </div>

    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700 space-y-4">
     <p class="text-sm font-medium text-slate-900 dark:text-white">Ou saisir un Client Invité</p>
     <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <input type="text" name="client_nom" placeholder="Nom Complet" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
      <input type="tel" name="client_telephone" placeholder="Téléphone" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
      <input type="email" name="client_email" placeholder="Email" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
     </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Date Début <span class="text-rose-500">*</span></label>
      <input type="date" name="date_debut" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Date Fin <span class="text-rose-500">*</span></label>
      <input type="date" name="date_fin" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
     </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Statut Initial</label>
      <select name="statut" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
       <option value="reserve">Réservé</option>
       <option value="confirme">Confirmé</option>
       <option value="en_cours">Sortie immédiate (En Cours)</option>
      </select>
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Montant Total Estimé (FCFA) <span class="text-rose-500">*</span></label>
      <input type="number" name="montant_total" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
     </div>
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('createRentalModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">Créer le contrat</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Edit Rental Modal -->
<div id="editRentalModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editRentalModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <div>
     <h2 class="text-lg font-medium text-slate-900 dark:text-white">Ajuster Contrat</h2>
     <p class="text-xs text-slate-500 mt-0.5">Mise à jour des paramètres du contrat</p>
    </div>
    <button onclick="closeModal('editRentalModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editRentalForm" method="POST" class="p-6 space-y-4">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Date Début <span class="text-rose-500">*</span></label>
      <input type="date" name="date_debut" id="edit_rent_start" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-blue-500 outline-none transition">
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Date Fin <span class="text-rose-500">*</span></label>
      <input type="date" name="date_fin" id="edit_rent_end" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-blue-500 outline-none transition">
     </div>
    </div>

    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Statut de la Location</label>
     <select name="statut" id="edit_rent_statut" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-blue-500 outline-none transition">
      <option value="reserve">Réservé</option>
      <option value="confirme">Confirmé</option>
      <option value="en_cours">En Cours (Véhicule sorti)</option>
      <option value="termine">Terminé (Véhicule rendu)</option>
      <option value="annule">Annulé</option>
     </select>
    </div>

    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Coût Total (FCFA) <span class="text-rose-500">*</span></label>
     <input type="number" name="montant_total" id="edit_rent_total" required class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-blue-500 outline-none transition">
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('editRentalModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">Sauvegarder</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Show Rental Modal -->
<div id="showRentalModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('showRentalModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-4xl shadow-xl overflow-hidden flex flex-col md:flex-row">
    
    <!-- Left: Identity & Details -->
    <div class="w-full md:w-1/2 p-6 bg-slate-50 dark:bg-slate-800/50 border-r border-slate-200 dark:border-slate-800">
     <div class="mb-6">
      <span id="show_rent_id" class="px-2.5 py-1 rounded-md bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500 text-xs font-medium mb-3 inline-block"></span>
      <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Détails du Contrat</h3>
     </div>

     <div class="space-y-6">
      <div>
       <h4 class="text-xs font-medium text-slate-500 uppercase mb-4">Client</h4>
       <div class="flex items-center gap-4 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div id="show_rent_user_initials" class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-lg font-medium text-slate-600 dark:text-slate-400 uppercase"></div>
        <div>
         <div id="show_rent_user_name" class="text-sm font-medium text-slate-900 dark:text-white"></div>
         <div id="show_rent_user_email" class="text-xs text-slate-500 mt-1"></div>
        </div>
       </div>
      </div>

      <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 space-y-4">
       <div class="flex justify-between items-center text-sm">
        <span class="text-slate-500">Date Départ</span>
        <span id="show_rent_start" class="font-medium text-slate-900 dark:text-white"></span>
       </div>
       <div class="flex justify-between items-center text-sm">
        <span class="text-slate-500">Date Retour</span>
        <span id="show_rent_end" class="font-medium text-slate-900 dark:text-white"></span>
       </div>
       <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
        <span class="text-sm font-medium text-slate-900 dark:text-white">Montant Total</span>
        <span id="show_rent_amount" class="text-lg font-bold text-slate-900 dark:text-white"></span>
       </div>
      </div>
     </div>
    </div>

    <!-- Right: Vehicle & Status -->
    <div class="w-full md:w-1/2 p-6 relative bg-white dark:bg-slate-900">
     <button onclick="closeModal('showRentalModal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
      <i data-lucide="x" class="w-5 h-5"></i>
     </button>

     <div class="space-y-6">
      <div>
       <div class="flex items-center gap-2 mb-4">
        <i data-lucide="car-front" class="w-5 h-5 text-slate-400"></i>
        <h4 class="text-xs font-medium text-slate-500 uppercase">Spécifications Flotte</h4>
       </div>

       <div class="relative aspect-video rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 mb-4">
        <img id="show_rent_car_photo" src="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute bottom-4 left-4 right-4">
         <div id="show_rent_car_title" class="text-lg font-semibold text-white mb-1 drop-shadow-md"></div>
         <div id="show_rent_car_plate" class="text-xs font-medium text-amber-400 bg-black/50 backdrop-blur px-2 py-1 rounded inline-block"></div>
        </div>
       </div>
      </div>

      <div>
       <h4 class="text-xs font-medium text-slate-500 uppercase mb-3">Statut</h4>
       <div id="show_rent_status_badge" class="mb-6"></div>
      </div>
      
      <div class="flex gap-3">
        <button onclick="closeModal('showRentalModal')" class="flex-1 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">
         Fermer
        </button>
      </div>

     </div>
    </div>
  </div>
 </div>
</div>
