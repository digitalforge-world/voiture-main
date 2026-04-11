@extends('layouts.admin')

@section('title', 'Gestion Financière - AutoImport Hub')

@section('content')
<div class="space-y-6">
 <!-- Header Area -->
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
  <div>
   <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Journal Financier</h1>
   <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Traçabilité des transactions et flux de trésorerie</p>
  </div>
  <div class="flex items-center gap-4">
   <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">
    <i data-lucide="download" class="w-4 h-4 text-amber-500"></i>
    <span>Exporter PDF</span>
   </button>
  </div>
 </div>

 <!-- Payments Table -->
 <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
     <tr>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">ID / Date</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Client</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Mode & Type</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Statut</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Montant</th>
      <th class="px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider text-right">Actions</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
     @forelse($payments as $payment)
     <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
      <td class="px-6 py-4">
       <div class="flex flex-col gap-1">
        <span class="text-xs font-medium text-amber-600 dark:text-amber-500 bg-amber-50 dark:bg-amber-500/10 px-2 py-1 rounded inline-block w-fit mb-1">
         #PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
        </span>
        <div class="text-xs text-slate-500">{{ $payment->date_paiement?->format('d/m/Y H:i') ?? 'N/A' }}</div>
       </div>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $payment->user->prenom }} {{ $payment->user->nom }}</div>
      </td>
      <td class="px-6 py-4">
       <div class="text-sm text-slate-700 dark:text-slate-300 capitalize">{{ $payment->mode_paiement }}</div>
       <div class="text-xs text-slate-500 mt-1 capitalize">{{ $payment->type_paiement }}</div>
      </td>
      <td class="px-6 py-4">
       @php
        $statusColors = [
         'valide' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-400',
         'en_attente' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-400',
         'echoue' => 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-400',
        ];
        $colorClass = $statusColors[$payment->statut] ?? 'bg-slate-100 text-slate-800';
       @endphp
       <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $colorClass }} capitalize">
        {{ str_replace('_', ' ', $payment->statut) }}
       </span>
      </td>
      <td class="px-6 py-4 text-right">
       <div class="text-sm font-medium text-slate-900 dark:text-white">{{ number_format($payment->montant, 0, ',', ' ') }} <span class="text-xs text-slate-500">FCFA</span></div>
      </td>
      <td class="px-6 py-4 text-right">
       <div class="flex items-center justify-end gap-2">
        <button onclick="openShowPaymentModal({{ json_encode($payment->load('user')) }})" class="p-2 text-slate-400 hover:text-blue-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="eye" class="w-4 h-4"></i>
        </button>
        <button onclick="openEditPaymentModal({{ json_encode($payment) }})" class="p-2 text-slate-400 hover:text-amber-500 transition rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
         <i data-lucide="edit-3" class="w-4 h-4"></i>
        </button>
       </div>
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="6" class="px-6 py-8 text-center text-slate-500 text-sm">
       Aucune transaction trouvée.
      </td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
  @if($payments->hasPages())
  <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
   {{ $payments->links() }}
  </div>
  @endif
 </div>
</div>

<!-- Edit Payment Modal -->
<div id="editPaymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editPaymentModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
    <div>
     <h2 class="text-lg font-medium text-slate-900 dark:text-white">Audit de Paiement</h2>
     <p class="text-xs text-slate-500 mt-0.5">Validation manuelle des écritures</p>
    </div>
    <button onclick="closeModal('editPaymentModal')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
     <i data-lucide="x" class="w-5 h-5"></i>
    </button>
   </div>

   <form id="editPaymentForm" method="POST" class="p-6 space-y-4">
    @csrf
    @method('PUT')
    
    <div>
     <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Décision Administrative</label>
     <select name="statut" id="edit_pay_statut" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-1 focus:ring-amber-500 outline-none">
      <option value="en_attente">Attente Confirmation</option>
      <option value="valide">Compte Crédité</option>
      <option value="echoue">Transaction Rejetée</option>
     </select>
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
     <button type="button" onclick="closeModal('editPaymentModal')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">Annuler</button>
     <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 transition shadow-sm">Appliquer la Décision</button>
    </div>
   </form>
  </div>
 </div>
</div>

<!-- Show Payment Modal -->
<div id="showPaymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('showPaymentModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
    <!-- Financial Side -->
    <div class="w-full md:w-1/2 p-6 bg-slate-50 dark:bg-slate-800/50 border-r border-slate-200 dark:border-slate-800">
     <div class="mb-6">
      <span id="show_pay_ref" class="px-2.5 py-1 rounded-md bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500 text-xs font-medium mb-3 inline-block"></span>
      <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Détails de la Transaction</h3>
     </div>

     <div class="space-y-6">
      <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
       <div class="text-xs font-medium text-slate-500 uppercase mb-2">Montant de l'opération</div>
       <div id="show_pay_amount" class="text-2xl font-bold text-slate-900 dark:text-white"></div>
       <div id="show_pay_type" class="mt-2 text-xs font-medium text-amber-600 dark:text-amber-500 capitalize"></div>
      </div>

      <div>
       <div class="text-xs font-medium text-slate-500 uppercase mb-3">Données de règlement</div>
       <div class="space-y-3">
        <div class="flex justify-between items-center text-sm">
         <span class="text-slate-500">Moyen de paiement</span>
         <span id="show_pay_mode" class="font-medium text-slate-900 dark:text-white capitalize"></span>
        </div>
        <div class="flex justify-between items-center text-sm">
         <span class="text-slate-500">Horodatage</span>
         <span id="show_pay_date" class="font-medium text-slate-900 dark:text-white"></span>
        </div>
       </div>
      </div>
     </div>
    </div>

    <!-- Auditor Side -->
    <div class="w-full md:w-1/2 p-6 relative">
     <button onclick="closeModal('showPaymentModal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
      <i data-lucide="x" class="w-5 h-5"></i>
     </button>

     <div class="mt-2 mb-8">
      <h4 class="text-xs font-medium text-slate-500 uppercase mb-4">Informations Porteur</h4>
      <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800">
        <div id="show_pay_user_init" class="w-12 h-12 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-lg font-medium text-slate-900 dark:text-white uppercase"></div>
        <div>
         <div id="show_pay_user_name" class="text-sm font-medium text-slate-900 dark:text-white"></div>
         <div id="show_pay_user_id" class="text-xs text-slate-500 mt-1 uppercase"></div>
        </div>
      </div>
     </div>

     <div>
      <h4 class="text-xs font-medium text-slate-500 uppercase mb-4">État de l'audit financier</h4>
      <div id="show_pay_status_banner" class="p-4 rounded-xl border flex items-center justify-between">
       <div>
        <div id="show_pay_status_txt" class="text-sm font-semibold capitalize"></div>
       </div>
       <i data-lucide="shield-check" class="w-5 h-5 opacity-50"></i>
      </div>
     </div>

     <div class="mt-8 flex gap-3">
      <button onclick="closeModal('showPaymentModal')" class="flex-1 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 transition">
       Fermer
      </button>
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

 function openEditPaymentModal(payment) {
  const form = document.getElementById('editPaymentForm');
  form.action = `/admin/payments/${payment.id}`;
  document.getElementById('edit_pay_statut').value = payment.statut;
  openModal('editPaymentModal');
 }

 function openShowPaymentModal(payment) {
  document.getElementById('show_pay_ref').innerText = `#PAY-${payment.id.toString().padStart(6, '0')}`;
  document.getElementById('show_pay_amount').innerText = new Intl.NumberFormat('fr-FR').format(payment.montant) + ' FCFA';
  document.getElementById('show_pay_type').innerText = payment.type_paiement.replace('_', ' ');
  document.getElementById('show_pay_mode').innerText = payment.mode_paiement;
  
  const dateObj = new Date(payment.date_paiement);
  document.getElementById('show_pay_date').innerText = dateObj.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
  
  document.getElementById('show_pay_user_name').innerText = `${payment.user.prenom} ${payment.user.nom}`;
  document.getElementById('show_pay_user_id').innerText = `CLIENT_ID_REF: USER-${payment.user.id.toString().padStart(4, '0')}`;
  document.getElementById('show_pay_user_init').innerText = payment.user.prenom[0] + payment.user.nom[0];
  
  const banner = document.getElementById('show_pay_status_banner');
  const txt = document.getElementById('show_pay_status_txt');
  txt.innerText = payment.statut.replace('_', ' ');
  
  const styles = {
   'valide': 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400',
   'en_attente': 'bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20 text-amber-700 dark:text-amber-400',
   'echoue': 'bg-rose-50 dark:bg-rose-500/10 border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400'
  };
  banner.className = `p-4 flex items-center justify-between rounded-xl border ${styles[payment.statut] || 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200'}`;

  openModal('showPaymentModal');
 }

 window.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
   closeModal('editPaymentModal');
   closeModal('showPaymentModal');
  }
 });
</script>
@endsection
@endsection
