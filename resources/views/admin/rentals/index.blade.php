@extends('layouts.admin')

@section('title', 'Gestion de la Flotte & Locations - AutoImport Hub')

@section('content')
<div class="space-y-6">
 <!-- Header & Stats -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Location de Véhicules</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Tableau de bord de gestion de la flotte et des contrats</p>
  </div>
  <div class="flex items-center gap-4">
   <button onclick="openModal('createRentalModal')" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-amber-500 rounded-lg hover:bg-amber-600 shadow-sm">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span>Nouveau Contrat</span>
   </button>
  </div>
 </div>

 <!-- Quick Stats Grid -->
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
   <div class="flex items-center gap-4">
    <div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-lg">
     <i data-lucide="key" class="w-6 h-6 text-slate-600 dark:text-slate-400"></i>
    </div>
    <div>
     <div class="text-sm font-medium text-slate-500">Total Contrats</div>
     <div class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $stats['total'] }}</div>
    </div>
   </div>
  </div>

  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm border-l-4 border-l-blue-500">
   <div class="flex items-center gap-4">
    <div class="p-3 bg-blue-50 dark:bg-blue-500/10 rounded-lg">
     <i data-lucide="play" class="w-6 h-6 text-blue-600 dark:text-blue-500"></i>
    </div>
    <div>
     <div class="text-sm font-medium text-slate-500">En Cours</div>
     <div class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $stats['active'] }}</div>
    </div>
   </div>
  </div>

  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm border-l-4 border-l-amber-500">
   <div class="flex items-center gap-4">
    <div class="p-3 bg-amber-50 dark:bg-amber-500/10 rounded-lg">
     <i data-lucide="clock" class="w-6 h-6 text-amber-600 dark:text-amber-500"></i>
    </div>
    <div>
     <div class="text-sm font-medium text-slate-500">En Attente</div>
     <div class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $stats['pending'] }}</div>
    </div>
   </div>
  </div>

  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm border-l-4 border-l-emerald-500">
   <div class="flex items-center gap-4">
    <div class="p-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg">
     <i data-lucide="banknote" class="w-6 h-6 text-emerald-600 dark:text-emerald-500"></i>
    </div>
    <div>
     <div class="text-sm font-medium text-slate-500">Revenus</div>
     <div class="text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['revenue'], 0, ',', ' ') }} <span class="text-sm font-medium text-slate-500">FCFA</span></div>
    </div>
   </div>
  </div>
 </div>

 <!-- Main Content Table -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
     <tr>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Référence / Client</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Véhicule</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Période & Tarifs</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">État</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
     @forelse($locations as $rental)
     <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
      <td class="px-6 py-4">
       <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-sm font-medium text-slate-600 dark:text-slate-400 uppercase flex-shrink-0">
         {{ substr($rental->client_nom ?? ($rental->user ? $rental->user->prenom : 'G'), 0, 1) }}{{ substr($rental->client_nom ?? ($rental->user ? $rental->user->nom : 'U'), -1, 1) }}
        </div>
        <div>
         <div class="mb-0.5 mt-0">
          <span class="text-xs font-medium text-amber-600 dark:text-amber-500 bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded leading-none">
           {{ $rental->reference ?? '#LOC-' . strtoupper(substr(md5($rental->id), 0, 8)) }}
          </span>
         </div>
         <div class="text-sm font-medium text-slate-900 dark:text-white leading-tight">
          @if($rental->user)
           {{ $rental->user->prenom }} {{ $rental->user->nom }}
          @else
           {{ $rental->client_nom }} <span class="text-xs text-slate-500 font-normal">(Invité)</span>
          @endif
         </div>
        </div>
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm font-medium text-slate-900 dark:text-white leading-tight mb-1">{{ $rental->voiture->marque }} {{ $rental->voiture->modele }}</div>
       <div class="flex items-center gap-2">
        <span class="text-xs text-slate-500 leading-none">{{ $rental->voiture->immatriculation ?? 'PROVISOIRE' }}</span>
        <span class="w-1 h-1 bg-slate-300 dark:bg-slate-600 rounded-full"></span>
        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 capitalize leading-none">{{ $rental->voiture->categorie }}</span>
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm text-slate-600 dark:text-slate-300 leading-tight mb-1">
        {{ $rental->date_debut?->format('d/m/Y') ?? 'N/A' }} <span class="text-slate-400 text-xs mx-1">à</span> {{ $rental->date_fin?->format('d/m/Y') ?? 'N/A' }}
       </div>
       <div class="text-sm font-medium text-slate-900 dark:text-white leading-none">
        {{ number_format($rental->montant_total, 0, ',', ' ') }} FCFA
       </div>
      </td>
      <td class="px-6 py-4">
       @php
        $statusColors = [
         'confirme' => 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400',
         'en_cours' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400',
         'termine' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400',
         'annule' => 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-400',
        ];
        $colorClass = $statusColors[$rental->statut] ?? 'bg-slate-100 text-slate-800';
       @endphp
       <span class="px-2.5 py-1 rounded-full text-xs font-medium capitalize {{ $colorClass }}">
        {{ str_replace('_', ' ', $rental->statut) }}
       </span>
      </td>
      <td class="px-6 py-4 text-right">
       <div class="flex items-center justify-end gap-2">
        <button onclick="openShowRentalModal({{ json_encode($rental->load(['user', 'voiture'])) }})" class="p-2 text-slate-400 hover:text-blue-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="eye" class="w-4 h-4"></i>
        </button>
        <button onclick="openEditRentalModal({{ json_encode($rental) }})" class="p-2 text-slate-400 hover:text-amber-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="edit-3" class="w-4 h-4"></i>
        </button>
        <form action="{{ route('admin.rentals.destroy', $rental->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de ce contrat ?')" class="inline">
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
       Aucun contrat de location trouvé.
      </td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>

  @if($locations->hasPages())
  <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
   {{ $locations->links() }}
  </div>
  @endif
 </div>
</div>

<!-- All Modals (Create, Edit, Show) implemented with the same premium style -->
@include('admin.rentals.partials.modals')

@section('scripts')
<script>
 function openModal(id) {
  const modal = document.getElementById(id);
  if(modal) {
   modal.classList.remove('hidden');
   document.body.classList.add('overflow-hidden');
  }
 }

 function closeModal(id) {
  const modal = document.getElementById(id);
  if(modal) {
   modal.classList.add('hidden');
   document.body.classList.remove('overflow-hidden');
  }
 }

 function openEditRentalModal(rental) {
  const form = document.getElementById('editRentalForm');
  if(form) {
   form.action = `/admin/rentals/${rental.id}`;
   
   const formatDate = (d) => d ? new Date(d).toISOString().split('T')[0] : '';
   document.getElementById('edit_rent_start').value = formatDate(rental.date_debut);
   document.getElementById('edit_rent_end').value = formatDate(rental.date_fin);
   document.getElementById('edit_rent_statut').value = rental.statut;
   document.getElementById('edit_rent_total').value = rental.montant_total;

   openModal('editRentalModal');
  }
 }

 function openShowRentalModal(rental) {
  if(!document.getElementById('show_rent_id')) return; // Check if partial exists
  
  const refText = rental.reference ? rental.reference : ('#LOC-' + rental.id.toString().padStart(5, '0'));
  document.getElementById('show_rent_id').innerText = refText;
  
  const clientName = rental.user ? `${rental.user.prenom} ${rental.user.nom}` : rental.client_nom;
  const clientEmail = rental.user ? rental.user.email : (rental.client_email || 'N/A');
  
  document.getElementById('show_rent_user_name').innerText = clientName;
  document.getElementById('show_rent_user_email').innerText = clientEmail;
  document.getElementById('show_rent_user_initials').innerText = clientName[0].toUpperCase() + (clientName.includes(' ') ? clientName.split(' ')[1][0] : clientName[1]).toUpperCase();
  
  const fmt = (d) => d ? new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' }) : 'N/A';
  document.getElementById('show_rent_start').innerText = fmt(rental.date_debut);
  document.getElementById('show_rent_end').innerText = fmt(rental.date_fin);

  document.getElementById('show_rent_car_title').innerText = `${rental.voiture.marque} ${rental.voiture.modele}`;
  document.getElementById('show_rent_car_plate').innerText = rental.voiture.immatriculation || 'IMM-PROVISOIRE';
  
  if(document.getElementById('show_rent_car_photo') && rental.voiture.photo_principale) {
    document.getElementById('show_rent_car_photo').src = rental.voiture.photo_principale;
  }
  
  document.getElementById('show_rent_amount').innerText = new Intl.NumberFormat('fr-FR').format(rental.montant_total) + ' FCFA';
  
  const badge = document.getElementById('show_rent_status_badge');
  badge.innerText = rental.statut.replace('_', ' ').toUpperCase();
  const colors = {
   'confirme': 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400',
   'en_cours': 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400',
   'termine': 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400',
   'annule': 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-400'
  };
  badge.className = `px-2.5 py-1 rounded-full text-xs font-medium inline-flex ${colors[rental.statut] || 'bg-slate-100 text-slate-800'}`;

  openModal('showRentalModal');
 }

 window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
   closeModal('createRentalModal');
   closeModal('editRentalModal');
   closeModal('showRentalModal');
  }
 });

 // Auto-calculate estimate in create modal
 function updateCreateEstimate() {
  const voiture = document.querySelector('select[name="voiture_location_id"]');
  const start = document.querySelector('input[name="date_debut"]')?.value;
  const end = document.querySelector('input[name="date_fin"]')?.value;
  const totalInput = document.querySelector('input[name="montant_total"]');

  if(voiture && start && end && totalInput) {
    const selectedText = voiture.options[voiture.selectedIndex].text;
    const priceMatch = selectedText.match(/(\d[\d\s]*)\sFCFA/);
    
    if (priceMatch) {
     const price = parseInt(priceMatch[1].replace(/\s/g, ''));
     const d1 = new Date(start);
     const d2 = new Date(end);
     const days = Math.ceil((d2 - d1) / (1000 * 3600 * 24));
     
     if (days > 0) {
      totalInput.value = (days * price) + 50000; // Adding dummy 50k caution
     }
    }
  }
 }

 document.querySelector('input[name="date_debut"]')?.addEventListener('change', updateCreateEstimate);
 document.querySelector('input[name="date_fin"]')?.addEventListener('change', updateCreateEstimate);
 document.querySelector('select[name="voiture_location_id"]')?.addEventListener('change', updateCreateEstimate);
</script>
@endsection
@endsection
