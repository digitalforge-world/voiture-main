@extends('layouts.admin')

@section('title', 'Commandes Véhicules - AutoImport Hub')

@section('content')
<div class="space-y-6">
 <!-- Header Area -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Suivi des Commandes</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gestion des flux d'importation et livraisons clients</p>
  </div>
 </div>

 <!-- Filters & Search -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm p-4">
  <form action="{{ route('admin.orders-cars.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
   <div class="flex-grow min-w-[300px] relative">
    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
    <input 
     type="text"
     name="search"
     value="{{ request('search') }}"
     placeholder="Rechercher par n° de suivi ou client..."
     class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none transition"
    >
   </div>

   <div class="flex items-center gap-3 w-full md:w-auto">
    <select name="status" onchange="this.form.submit()" class="py-2 pl-3 pr-8 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/50 outline-none">
     <option value="">Tous les flux</option>
     <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
     <option value="paye" {{ request('status') == 'paye' ? 'selected' : '' }}>Acompte Reçu</option>
     <option value="importation" {{ request('status') == 'importation' ? 'selected' : '' }}>En Transit</option>
     <option value="arrive" {{ request('status') == 'arrive' ? 'selected' : '' }}>Arrivé Port</option>
     <option value="livre" {{ request('status') == 'livre' ? 'selected' : '' }}>Livré</option>
     <option value="annule" {{ request('status') == 'annule' ? 'selected' : '' }}>Annulé</option>
    </select>
   </div>

   @if(request()->anyFilled(['search', 'status']))
    <a href="{{ route('admin.orders-cars.index') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-lg whitespace-nowrap">
     Effacer filtres
    </a>
   @endif
  </form>
 </div>

 <!-- Orders Table -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
     <tr>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Référence / Client</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Véhicule</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Statut Suivi</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Montant</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
     @forelse($orders as $order)
     <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150">
      <td class="px-6 py-4">
       <div class="flex flex-col gap-1">
        
        <div class="text-sm font-medium text-slate-900 dark:text-white">
            {{ $order->user ? $order->user->prenom . ' ' . $order->user->nom : $order->client_nom }}
            <span class="text-xs font-medium text-amber-600 dark:text-amber-500 bg-amber-50 dark:bg-amber-500/10 px-2 py-1 rounded inline-block w-fit mb-1">
            #{{ $order->numero_suivi ?? 'SANS-REF' }}
            </span>
        </div>
        <div class="text-xs text-slate-500">{{ $order->date_commande?->format('d/m/Y') ?? 'N/A' }}</div>
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="flex items-center gap-3">
        <div class="w-12 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border-none flex items-center justify-center overflow-hidden flex-shrink-0">
         @if($order->voiture->photo_principale)
          <img src="{{ $order->voiture->photo_principale }}" class="w-full h-full object-cover">
         @else
          <i data-lucide="car" class="w-4 h-4 text-slate-400"></i>
         @endif
        </div>
        <div>
         <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $order->voiture->marque }} {{ $order->voiture->modele }}</div>
         <div class="text-xs text-slate-500">{{ $order->voiture->annee }}</div>
        </div>
       </div>
      </td>
      <td class="px-6 py-4">
       @php
        $statusColors = [
         'en_attente' => 'bg-slate-100 text-slate-800 dark:bg-slate-500/10 dark:text-slate-400',
         'paye' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400',
         'importation' => 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400',
         'arrive' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-400',
         'livre' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400',
         'annule' => 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-400',
        ];
        $colorClass = $statusColors[$order->statut] ?? 'bg-slate-100 text-slate-800';
       @endphp
       <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $colorClass }} capitalize">
        {{ str_replace('_', ' ', $order->statut) }}
       </span>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm font-medium text-slate-900 dark:text-white">{{ number_format($order->montant_total, 0, ',', ' ') }} <span class="text-xs text-slate-500">FCFA</span></div>
       <div class="text-xs text-slate-500 mt-1">Acompte: {{ number_format($order->acompte_paye, 0, ',', ' ') }}</div>
      </td>
      <td class="px-6 py-4 text-right">
       <div class="flex items-center justify-end gap-2">
        <button onclick="openShowOrderModal({{ json_encode($order->load(['user', 'voiture'])) }})" class="p-2 text-slate-400 hover:text-blue-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="eye" class="w-4 h-4"></i>
        </button>
        <button onclick="openEditOrderModal({{ json_encode($order) }})" class="p-2 text-slate-400 hover:text-amber-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="edit-3" class="w-4 h-4"></i>
        </button>
       </div>
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
       Aucune commande de véhicule enregistrée.
      </td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
  @if($orders->hasPages())
  <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
   {{ $orders->links() }}
  </div>
  @endif
 </div>
</div>

<!-- Edit Order Modal -->
<div id="editOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editOrderModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <div>
     <h2 class="text-lg font-medium text-slate-900 dark:text-white">Suivi Commande</h2>
    </div>
    <button onclick="closeModal('editOrderModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editOrderForm" method="POST" class="p-6">
    @csrf
    @method('PUT')
    
    <div class="space-y-4">
     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Numéro de Suivi Tracker</label>
      <input type="text" name="numero_suivi" id="edit_numero_suivi" placeholder="Ex: TRX-9988-ABC" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
     </div>

     <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Étape Actuelle</label>
      <select name="statut" id="edit_order_statut" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
       <option value="en_attente">En Attente</option>
       <option value="paye">Acompte Reçu</option>
       <option value="importation">En Transit Maritime</option>
       <option value="arrive">Arrivé au Port</option>
       <option value="livre">Livré au Client</option>
       <option value="annule">Commande Annulée</option>
      </select>
     </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
     <button type="button" onclick="closeModal('editOrderModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition">Actualiser</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Show Order Modal -->
<div id="showOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('showOrderModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
    <!-- Left Side: Order Stats -->
    <div class="w-full md:w-1/2 p-6 bg-slate-50 dark:bg-slate-800/50 border-r border-slate-200 dark:border-slate-800">
     <div class="mb-6">
      <span id="show_order_ref" class="px-2.5 py-1 rounded-md bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500 text-xs font-medium mb-3 inline-block"></span>
      <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Détails de la Commande</h3>
     </div>

     <div class="space-y-6">
      <div>
       <div class="text-xs font-medium text-slate-500 uppercase mb-2">Informations Client</div>
       <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center font-medium text-slate-900 dark:text-white" id="show_user_initials"></div>
        <div>
         <div id="show_user_name" class="text-sm font-medium text-slate-900 dark:text-white"></div>
         <div id="show_user_email" class="text-xs text-slate-500"></div>
        </div>
       </div>
      </div>

      <div>
       <div class="text-xs font-medium text-slate-500 uppercase mb-2">Bilan Financier</div>
       <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 space-y-3">
        <div class="flex justify-between items-center text-sm">
         <span class="text-slate-500">Total</span>
         <span id="show_order_total" class="font-medium text-slate-900 dark:text-white"></span>
        </div>
        <div class="flex justify-between items-center text-sm">
         <span class="text-emerald-600 dark:text-emerald-500">Payé</span>
         <span id="show_order_paid" class="font-medium text-emerald-600 dark:text-emerald-500"></span>
        </div>
        <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
         <span class="text-sm text-slate-500">Reste à régler</span>
         <span id="show_order_rest" class="text-base font-semibold text-amber-600 dark:text-amber-500"></span>
        </div>
       </div>
      </div>
     </div>
    </div>

    <!-- Right Side: Logistics -->
    <div class="w-full md:w-1/2 p-6 relative">
     <button onclick="closeModal('showOrderModal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
      <i data-lucide="x" class="w-5 h-5"></i>
     </button>

     <div class="mb-8">
      <h4 class="text-xs font-medium text-slate-500 uppercase mb-4">Véhicule Commandé</h4>
      <div class="flex gap-4 items-center p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 rounded-xl">
       <img id="show_car_photo" src="" class="w-20 h-16 object-cover rounded-lg">
       <div>
        <div id="show_car_title" class="text-sm font-medium text-slate-900 dark:text-white"></div>
        <div class="text-xs text-slate-500 mt-1">
         <span id="show_car_annee"></span> • <span id="show_car_km"></span>
        </div>
       </div>
      </div>
     </div>

     <div>
      <h4 class="text-xs font-medium text-slate-500 uppercase mb-4">Chronologie de livraison</h4>
      <div id="timeline_status" class="space-y-3 relative before:absolute before:inset-0 before:ml-3 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800">
       <!-- JS Timeline -->
      </div>
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

 function openEditOrderModal(order) {
  const form = document.getElementById('editOrderForm');
  form.action = `/admin/orders-cars/${order.id}`;
  document.getElementById('edit_numero_suivi').value = order.numero_suivi || '';
  document.getElementById('edit_order_statut').value = order.statut;
  openModal('editOrderModal');
 }

 function openShowOrderModal(order) {
  document.getElementById('show_order_ref').innerText = `#${order.numero_suivi || 'SANS-REF'}`;
  const userName = order.user ? `${order.user.prenom} ${order.user.nom}` : (order.client_nom || 'Client Externe');
  const userEmail = order.user ? order.user.email : (order.client_email || 'N/A');
  const userInitials = order.user ? (order.user.prenom[0] + order.user.nom[0]) : (userName.substring(0, 2).toUpperCase());

  document.getElementById('show_user_name').innerText = userName;
  document.getElementById('show_user_email').innerText = userEmail;
  document.getElementById('show_user_initials').innerText = userInitials;
  
  const total = order.montant_total;
  const paid = order.acompte_paye;
  const fmt = (v) => new Intl.NumberFormat('fr-FR').format(v) + ' FCFA';
  
  document.getElementById('show_order_total').innerText = fmt(total);
  document.getElementById('show_order_paid').innerText = fmt(paid);
  document.getElementById('show_order_rest').innerText = fmt(total - paid);

  document.getElementById('show_car_title').innerText = `${order.voiture.marque} ${order.voiture.modele}`;
  document.getElementById('show_car_annee').innerText = order.voiture.annee;
  document.getElementById('show_car_km').innerText = new Intl.NumberFormat('fr-FR').format(order.voiture.kilometrage) + ' KM';
  document.getElementById('show_car_photo').src = order.voiture.photo_principale || '/images/placeholder-car.jpg';

  const statuses = ['en_attente', 'paye', 'importation', 'arrive', 'livre'];
  const container = document.getElementById('timeline_status');
  container.innerHTML = '';
  
  statuses.forEach((s, idx) => {
   const isActive = statuses.indexOf(order.statut) >= idx;
   const item = document.createElement('div');
   item.className = 'relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active';
   item.innerHTML = `
    <div class="flex items-center w-full">
     <div class="relative z-10 w-6 h-6 flex items-center justify-center bg-white dark:bg-slate-900 rounded-full border-2 ${isActive ? 'border-amber-500' : 'border-slate-200 dark:border-slate-700'}">
      ${isActive ? '<div class="w-2 h-2 bg-amber-500 rounded-full"></div>' : ''}
     </div>
     <div class="ml-4 ${isActive ? 'text-slate-900 dark:text-white font-medium' : 'text-slate-500'} text-xs capitalize">${s.replace('_', ' ')}</div>
    </div>
   `;
   container.appendChild(item);
  });
  
  if (typeof lucide !== 'undefined') lucide.createIcons();
  openModal('showOrderModal');
 }

 window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
   closeModal('editOrderModal');
   closeModal('showOrderModal');
  }
 });
</script>
@endsection
@endsection
