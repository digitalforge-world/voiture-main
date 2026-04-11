@extends('layouts.admin')

@section('title', 'Ventes de Pièces - AutoImport Hub')

@section('content')
<div class="space-y-6">
 <!-- Header Area -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Ventes de Pièces</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gestion des commandes et expéditions de pièces détachées</p>
  </div>
 </div>

 <!-- Orders Table -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
     <tr>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">ID Commande / Client</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Détails Pièce</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Statut</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
     @forelse($orders as $order)
     <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
      <td class="px-6 py-4">
       <div class="flex flex-col gap-1">
        <span class="text-xs font-medium text-amber-600 dark:text-amber-500 bg-amber-50 dark:bg-amber-500/10 px-2 py-1 rounded inline-block w-fit mb-1">
         #PART-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
        </span>
        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $order->user->prenom }} {{ $order->user->nom }}</div>
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $order->pieceDetachee->nom }}</div>
       <div class="text-xs text-slate-500 mt-1">Quantité: {{ $order->quantite }}</div>
      </td>
      <td class="px-6 py-4">
       @php
        $statusColors = [
         'en_attente' => 'bg-slate-100 text-slate-800 dark:bg-slate-500/10 dark:text-slate-400',
         'valide' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400',
         'en_expedition' => 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400',
         'livre' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-400',
         'annule' => 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-400',
        ];
        $colorClass = $statusColors[$order->statut] ?? 'bg-slate-100 text-slate-800';
       @endphp
       <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $colorClass }} capitalize">
        {{ str_replace('_', ' ', $order->statut) }}
       </span>
      </td>
      <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
       {{ number_format($order->montant_total, 0, ',', ' ') }} <span class="text-xs text-slate-500">FCFA</span>
      </td>
      <td class="px-6 py-4 text-right">
       <div class="flex items-center justify-end gap-2">
        <button onclick="openShowPartOrderModal({{ json_encode($order->load(['user', 'pieceDetachee'])) }})" class="p-2 text-slate-400 hover:text-blue-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="eye" class="w-4 h-4"></i>
        </button>
        <button onclick="openEditPartOrderModal({{ json_encode($order) }})" class="p-2 text-slate-400 hover:text-amber-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="edit-3" class="w-4 h-4"></i>
        </button>
       </div>
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
       Aucune commande de pièce.
      </td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>

  <!-- Pagination -->
  @if($orders->hasPages())
  <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
   {{ $orders->links() }}
  </div>
  @endif
 </div>
</div>

<!-- Edit Part Order Modal -->
<div id="editPartOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editPartOrderModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <h2 class="text-lg font-medium text-slate-900 dark:text-white">Statut de la Vente</h2>
    <button onclick="closeModal('editPartOrderModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editPartOrderForm" method="POST" class="p-6">
    @csrf
    @method('PUT')
    
    <div class="space-y-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Progression de la vente</label>
      <select name="statut" id="edit_per_statut" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
       <option value="en_attente">En Attente de Paiement</option>
       <option value="valide">Paiement Validé</option>
       <option value="en_expedition">Expédition en Cours</option>
       <option value="livre">Colis Livré</option>
       <option value="annule">Vente Annulée</option>
      </select>
     </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
     <button type="button" onclick="closeModal('editPartOrderModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Fermer</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition">Actualiser</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Show Part Order Modal -->
<div id="showPartOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('showPartOrderModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
    <!-- Summary Side -->
    <div class="w-full md:w-1/2 p-6 bg-slate-50 dark:bg-slate-800/50 border-r border-slate-200 dark:border-slate-800">
     <div class="mb-6">
      <span id="show_per_ref" class="px-2.5 py-1 rounded-md bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500 text-xs font-medium mb-3 inline-block"></span>
      <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Détails Vente Pièce</h3>
     </div>

     <div class="space-y-6">
      <div>
       <div class="text-xs font-medium text-slate-500 uppercase mb-2">Client Acheteur</div>
       <div class="flex items-center gap-3">
        <div id="show_per_user_init" class="w-10 h-10 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center font-medium text-slate-900 dark:text-white uppercase"></div>
        <div>
         <div id="show_per_user_name" class="text-sm font-medium text-slate-900 dark:text-white"></div>
         <div id="show_per_user_email" class="text-xs text-slate-500"></div>
        </div>
       </div>
      </div>

      <div>
       <div class="text-xs font-medium text-slate-500 uppercase mb-2">Total de la transaction</div>
       <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
        <div id="show_per_total" class="text-base font-semibold text-amber-600 dark:text-amber-500"></div>
        <div class="flex items-center gap-2">
          <div id="show_per_status_dot" class="w-2 h-2 rounded-full"></div>
          <span id="show_per_status_txt" class="text-xs font-medium text-slate-500 uppercase"></span>
        </div>
       </div>
      </div>
     </div>
    </div>

    <!-- Logistics Side -->
    <div class="w-full md:w-1/2 p-6 relative">
     <button onclick="closeModal('showPartOrderModal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
      <i data-lucide="x" class="w-5 h-5"></i>
     </button>

     <div class="mt-4 mb-6">
      <h4 class="text-xs font-medium text-slate-500 uppercase mb-4">L'Article Commandé</h4>
      <div class="flex gap-4 items-center p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-xl">
       <img id="show_per_img" src="" class="w-16 h-16 object-contain">
       <div>
        <div id="show_per_part_nom" class="text-sm font-medium text-slate-900 dark:text-white"></div>
        <div id="show_per_part_cat" class="text-xs text-slate-500 mt-1"></div>
        <div id="show_per_qty" class="mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-500"></div>
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

 function openEditPartOrderModal(order) {
  const form = document.getElementById('editPartOrderForm');
  form.action = `/admin/orders-parts/${order.id}`;
  document.getElementById('edit_per_statut').value = order.statut;
  openModal('editPartOrderModal');
 }

 function openShowPartOrderModal(order) {
  document.getElementById('show_per_ref').innerText = `#PART-${order.id.toString().padStart(5, '0')}`;
  document.getElementById('show_per_user_name').innerText = `${order.user.prenom} ${order.user.nom}`;
  document.getElementById('show_per_user_email').innerText = order.user.email;
  document.getElementById('show_per_user_init').innerText = order.user.prenom[0] + order.user.nom[0];
  
  document.getElementById('show_per_total').innerText = new Intl.NumberFormat('fr-FR').format(order.montant_total) + ' FCFA';
  document.getElementById('show_per_status_txt').innerText = order.statut.replace('_', ' ').toUpperCase();
  
  const dot = document.getElementById('show_per_status_dot');
  const colors = {
   'en_attente': 'bg-slate-500',
   'valide': 'bg-emerald-500',
   'en_expedition': 'bg-blue-500',
   'livre': 'bg-indigo-500',
   'annule': 'bg-rose-500'
  };
  dot.className = `w-2 h-2 rounded-full ${colors[order.statut] || 'bg-slate-500'}`;
  
  document.getElementById('show_per_part_nom').innerText = order.piece_detachee.nom;
  document.getElementById('show_per_part_cat').innerText = order.piece_detachee.categorie;
  document.getElementById('show_per_qty').innerText = `${order.quantite} UNITÉS`;
  document.getElementById('show_per_img').src = order.piece_detachee.photo || '/images/placeholder-part.png';

  openModal('showPartOrderModal');
 }

 window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
   closeModal('editPartOrderModal');
   closeModal('showPartOrderModal');
  }
 });
</script>
@endsection
