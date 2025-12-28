@extends('layouts.admin')

@section('title', 'Gestion Financière - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Journal Financier</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Traçabilité des transactions et flux de trésorerie</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white transition bg-slate-900 rounded-2xl hover:bg-slate-800 duration-300 border border-white/5 shadow-xl">
                <i data-lucide="download" class="w-4 h-4 text-amber-500"></i> Exporter PDF
            </button>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="border overflow-hidden bg-slate-950/50 border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left">
            <thead class="bg-slate-900/50 border-b border-white/5">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">ID / Date</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Client</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Mode & Type</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Statut</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right italic">Montant</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right italic uppercase italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($payments as $payment)
                <tr class="group hover:bg-white/[0.02] transition duration-300">
                    <td class="px-8 py-6">
                        <div class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1 italic">#PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest italic mt-1">{{ $payment->date_paiement->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-white tracking-tight italic">{{ $payment->user->prenom }} {{ $payment->user->nom }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">{{ $payment->mode_paiement }}</span>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest italic">{{ $payment->type_paiement }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $statusColor = match($payment->statut) {
                                'valide' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'en_attente' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'echoue' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border {{ $statusColor }} text-[9px] font-black uppercase tracking-widest italic leading-none">
                            {{ $payment->statut }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="text-sm font-black text-white italic tracking-tight">{{ number_format($payment->montant, 0, ',', ' ') }} <span class="text-[9px] font-black">FCFA</span></div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button onclick="openShowPaymentModal({{ json_encode($payment->load('user')) }})" class="p-3 bg-slate-900 border border-white/5 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-[1.2rem] shadow-xl">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button onclick="openEditPaymentModal({{ json_encode($payment) }})" class="p-3 bg-slate-900 border border-white/5 text-slate-400 hover:text-white hover:bg-amber-500 transition rounded-[1.2rem] shadow-xl">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucune transaction trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="px-8 py-6 bg-slate-900/30 border-t border-white/5">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Payment Modal -->
<div id="editPaymentModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('editPaymentModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-2">Audit de Paiement</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4">Validation manuelle des écritures</p>

            <form id="editPaymentForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Décision Administrative</label>
                    <select name="statut" id="edit_pay_statut" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition appearance-none font-black uppercase italic tracking-widest">
                        <option value="en_attente">Attente Confirmation</option>
                        <option value="valide">Compte Crédité</option>
                        <option value="echoue">Transaction Rejetée</option>
                    </select>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editPaymentModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2.5rem] border border-white/5 hover:bg-slate-900 transition font-black italic">Sortie</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-white transition shadow-xl shadow-amber-500/20 font-black italic">Appliquer la Décision</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Payment Modal -->
<div id="showPaymentModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/95 backdrop-blur-2xl" onclick="closeModal('showPaymentModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-4xl shadow-2xl rounded-[5rem] rounded-tl-xl rounded-br-xl overflow-hidden flex flex-col md:flex-row animate-in fade-in slide-in-from-bottom duration-500">
             <!-- Financial Side -->
             <div class="w-full md:w-2/5 p-16 bg-slate-950 border-r border-white/5">
                <div class="mb-12">
                    <span id="show_pay_ref" class="px-4 py-2 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-black uppercase tracking-[0.2em] italic mb-6 inline-block"></span>
                    <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase leading-tight">Pièce <br> Comptable</h3>
                </div>

                <div class="space-y-12">
                    <div class="p-10 bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-inner">
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Montant de l'opération</div>
                        <div id="show_pay_amount" class="text-4xl font-black text-white italic tracking-tighter"></div>
                        <div id="show_pay_type" class="mt-4 text-[10px] font-black text-amber-500 uppercase tracking-widest italic"></div>
                    </div>

                    <div>
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-6 italic">Données de règlement</div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-[10px] font-black uppercase italic">
                                <span class="text-slate-500">Moyen de paiement</span>
                                <span id="show_pay_mode" class="text-white"></span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] font-black uppercase italic">
                                <span class="text-slate-500">Horodatage</span>
                                <span id="show_pay_date" class="text-white"></span>
                            </div>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Auditor Side -->
             <div class="w-full md:w-3/5 p-16 bg-slate-900 relative">
                <button onclick="closeModal('showPaymentModal')" class="absolute top-10 right-10 p-4 bg-white/5 text-slate-400 hover:text-white rounded-2xl transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="space-y-12 mt-4">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-8 italic">Informations Porteur</h4>
                        <div class="flex items-center gap-6 p-10 bg-slate-950 rounded-[3rem] border border-white/5 shadow-2xl">
                             <div id="show_pay_user_init" class="w-20 h-20 rounded-3xl bg-slate-900 border border-white/5 flex items-center justify-center text-2xl font-black text-white uppercase italic shadow-inner"></div>
                             <div>
                                <div id="show_pay_user_name" class="text-2xl font-black text-white italic uppercase tracking-tighter"></div>
                                <div id="show_pay_user_id" class="text-[10px] font-black text-slate-500 italic mt-2 uppercase tracking-widest"></div>
                             </div>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-white/5">
                        <div id="show_pay_status_banner" class="p-10 rounded-[3rem] border flex items-center justify-between">
                            <div>
                                <div class="text-[9px] font-black uppercase italic opacity-60 mb-1">État de l'audit financier</div>
                                <div id="show_pay_status_txt" class="text-2xl font-black italic uppercase tracking-tighter"></div>
                            </div>
                            <div class="p-6 bg-white/5 rounded-2xl">
                                <i data-lucide="shield-check" class="w-8 h-8 text-white/20"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-16 flex gap-6">
                    <button class="flex-1 py-6 bg-slate-950 border border-white/5 text-slate-400 rounded-[2.5rem] text-[10px] font-black uppercase tracking-widest italic hover:text-white transition">
                        Générer Reçu
                    </button>
                    <button onclick="closeModal('showPaymentModal')" class="flex-[2] py-6 bg-white text-slate-950 rounded-[2.5rem] text-[10px] font-black uppercase tracking-widest italic hover:bg-amber-500 transition duration-300 shadow-2xl">
                        Certifier Audit
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
        document.getElementById('show_pay_type').innerText = payment.type_paiement.toUpperCase();
        document.getElementById('show_pay_mode').innerText = payment.mode_paiement.toUpperCase();
        
        const dateObj = new Date(payment.date_paiement);
        document.getElementById('show_pay_date').innerText = dateObj.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }).toUpperCase();
        
        document.getElementById('show_pay_user_name').innerText = `${payment.user.prenom} ${payment.user.nom}`;
        document.getElementById('show_pay_user_id').innerText = `CLIENT_ID_REF: USER-${payment.user.id.toString().padStart(4, '0')}`;
        document.getElementById('show_pay_user_init').innerText = payment.user.prenom[0] + payment.user.nom[0];
        
        const banner = document.getElementById('show_pay_status_banner');
        const txt = document.getElementById('show_pay_status_txt');
        txt.innerText = payment.statut.toUpperCase();
        
        const styles = {
            'valide': 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500',
            'en_attente': 'bg-amber-500/10 border-amber-500/20 text-amber-500',
            'echoue': 'bg-rose-500/10 border-rose-500/20 text-rose-500'
        };
        banner.className = `p-10 rounded-[3rem] border flex items-center justify-between ${styles[payment.statut] || 'bg-slate-900 border-white/5 text-white'}`;

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
