@extends('layouts.admin')

@section('title', 'Ventes de Pièces - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Ventes de Pièces</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Gestion des commandes et expéditions de pièces détachées</p>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="border overflow-hidden bg-slate-950/50 border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left">
            <thead class="bg-slate-900/50 border-b border-white/5">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">ID Commande / Client</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Détails Pièce</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Statut</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Total</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($orders as $order)
                <tr class="group hover:bg-white/[0.02] transition duration-300">
                    <td class="px-8 py-6">
                        <div class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1 italic">#PART-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-sm font-black text-white tracking-tight italic">{{ $order->user->prenom }} {{ $order->user->nom }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-[11px] font-black text-white uppercase italic tracking-tight">{{ $order->pieceDetachee->nom }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5 italic">Quantité: {{ $order->quantite }}</div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $statusColor = match($order->statut) {
                                'en_attente' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                'valide' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'en_expedition' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                'livre' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                'annule' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border {{ $statusColor }} text-[9px] font-black uppercase tracking-widest italic leading-none">
                            {{ str_replace('_', ' ', $order->statut) }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-sm font-black text-white italic tracking-tight">{{ number_format($order->montant_total, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button onclick="openShowPartOrderModal({{ json_encode($order->load(['user', 'pieceDetachee'])) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-[1.2rem] border border-white/5 shadow-xl">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button onclick="openEditPartOrderModal({{ json_encode($order) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-amber-500 transition rounded-[1.2rem] border border-white/5 shadow-xl">
                                <i data-lucide="package-check" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucune commande de pièce.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-8 py-6 bg-slate-900/30 border-t border-white/5">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Part Order Modal -->
<div id="editPartOrderModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('editPartOrderModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-2">Statut Vente</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4">Fulfillment & expédition des articles</p>

            <form id="editPartOrderForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Progression de la vente</label>
                    <select name="statut" id="edit_per_statut" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition appearance-none font-black uppercase italic tracking-widest">
                        <option value="en_attente">En Attente de Paiement</option>
                        <option value="valide">Paiement Validé</option>
                        <option value="en_expedition">Expédition en Cours</option>
                        <option value="livre">Colis Livré</option>
                        <option value="annule">Vente Annulée</option>
                    </select>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editPartOrderModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2.5rem] border border-white/5 hover:bg-slate-900 transition font-black italic">Fermer</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-white transition shadow-xl shadow-amber-500/20 font-black italic">Actualiser le Statut</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Part Order Modal -->
<div id="showPartOrderModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/95 backdrop-blur-2xl" onclick="closeModal('showPartOrderModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-4xl shadow-2xl rounded-[5rem] rounded-tl-xl rounded-br-xl overflow-hidden flex flex-col md:flex-row animate-in fade-in slide-in-from-bottom duration-500">
             <!-- Summary Side -->
             <div class="w-full md:w-2/5 p-16 bg-slate-950 border-r border-white/5">
                <div class="mb-12">
                    <span id="show_per_ref" class="px-4 py-2 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-black uppercase tracking-[0.2em] italic mb-6 inline-block"></span>
                    <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase leading-tight border-b border-amber-500/30 pb-6">Détails <br> Vente Pièce</h3>
                </div>

                <div class="space-y-12">
                    <div>
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Client Acheteur</div>
                        <div class="flex items-center gap-4">
                            <div id="show_per_user_init" class="w-12 h-12 rounded-2xl bg-slate-900 border border-white/5 flex items-center justify-center font-black text-white uppercase italic shadow-inner"></div>
                            <div>
                                <div id="show_per_user_name" class="text-sm font-black text-white italic"></div>
                                <div id="show_per_user_email" class="text-[10px] text-slate-500 font-bold italic mt-0.5"></div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-900/50 rounded-[2.5rem] border border-white/5 shadow-inner">
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Total de la transaction</div>
                        <div id="show_per_total" class="text-3xl font-black text-amber-500 italic tracking-tighter"></div>
                        <div class="mt-4 flex items-center gap-2">
                             <div id="show_per_status_dot" class="w-2 h-2 rounded-full"></div>
                             <span id="show_per_status_txt" class="text-[10px] font-black text-slate-400 uppercase italic"></span>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Logistics Side -->
             <div class="w-full md:w-3/5 p-16 bg-slate-900 relative">
                <button onclick="closeModal('showPartOrderModal')" class="absolute top-10 right-10 p-4 bg-white/5 text-slate-400 hover:text-white rounded-2xl transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="space-y-12 mt-4">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-8 italic italic">L'Article Commandé</h4>
                        <div class="flex gap-10 items-center p-8 bg-slate-950/50 border border-white/5 rounded-[3rem] shadow-xl relative overflow-hidden group">
                             <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-500 opacity-0 group-hover:opacity-10 blur-3xl transition duration-500"></div>
                             <img id="show_per_img" src="" class="w-32 h-32 object-contain drop-shadow-2xl">
                             <div>
                                <div id="show_per_part_nom" class="text-2xl font-black text-white italic uppercase tracking-tighter"></div>
                                <div id="show_per_part_cat" class="text-[10px] font-black text-slate-500 italic uppercase mt-2"></div>
                                <div id="show_per_qty" class="mt-4 px-4 py-1.5 bg-slate-900 border border-white/5 rounded-xl text-[10px] font-black text-emerald-500 uppercase w-fit italic"></div>
                             </div>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-white/5">
                         <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6 italic italic">Étapes de Fulfillment</h4>
                         <div class="grid grid-cols-2 gap-6">
                             <div class="flex items-center gap-4 p-5 bg-slate-950 rounded-2xl border border-white/5">
                                 <i data-lucide="shopping-cart" class="w-5 h-5 text-slate-700"></i>
                                 <span class="text-[10px] font-black text-slate-300 uppercase italic">Commande Créée</span>
                             </div>
                             <div class="flex items-center gap-4 p-5 bg-slate-950 rounded-2xl border border-white/5">
                                 <i data-lucide="credit-card" class="w-5 h-5 text-slate-700"></i>
                                 <span class="text-[10px] font-black text-slate-300 uppercase italic">Paiement Partiel</span>
                             </div>
                             <div class="flex items-center gap-4 p-5 bg-slate-950 rounded-2xl border border-white/5">
                                 <i data-lucide="truck" class="w-5 h-5 text-slate-700"></i>
                                 <span class="text-[10px] font-black text-slate-300 uppercase italic">Expédition Planifiée</span>
                             </div>
                             <div class="flex items-center gap-4 p-5 bg-slate-950 rounded-2xl border border-white/5">
                                 <i data-lucide="check-circle-2" class="w-5 h-5 text-slate-700"></i>
                                 <span class="text-[10px] font-black text-slate-300 uppercase italic">Fin du dossier</span>
                             </div>
                         </div>
                    </div>
                </div>

                <div class="pt-16 text-center">
                    <button onclick="closeModal('showPartOrderModal')" class="px-12 py-5 bg-white text-slate-950 rounded-[2rem] text-[10px] font-black uppercase tracking-widest italic hover:bg-amber-500 transition duration-300 shadow-2xl">Fermer le Dossier</button>
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
@endsection
