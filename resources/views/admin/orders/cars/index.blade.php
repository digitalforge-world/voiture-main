@extends('layouts.admin')

@section('title', 'Commandes Véhicules - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Suivi des Commandes</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Gestion des flux d'importation et livraisons clients</p>
        </div>
    <!-- Filters & Search -->
    <form action="{{ route('admin.orders-cars.index') }}" method="GET" class="p-6 bg-white dark:bg-slate-900/30 border border-slate-100 dark:border-slate-900 rounded-[3rem] rounded-tr-lg rounded-bl-lg flex flex-wrap items-center gap-6 shadow-lg dark:shadow-2xl backdrop-blur-sm transition-colors">
        <div class="flex-grow min-w-[300px] relative group">
            <i data-lucide="search" class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500 group-focus-within:text-amber-500 transition-colors"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="RECHERCHER PAR N° SUIVI OU NOM CLIENT..." 
                class="w-full py-4 pl-14 pr-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-300 dark:placeholder:text-slate-700 focus:ring-1 focus:ring-amber-500 transition shadow-inner outline-none transition-colors"
            >
        </div>

        <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-white/5 transition-colors">
            <span class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest transition-colors tracking-widest uppercase">Étape :</span>
            <select name="status" onchange="this.form.submit()" class="bg-transparent border-none text-[10px] font-black text-amber-500 uppercase tracking-widest focus:ring-0 cursor-pointer p-0">
                <option value="">Tous les flux</option>
                <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                <option value="paye" {{ request('status') == 'paye' ? 'selected' : '' }}>Acompte Reçu</option>
                <option value="importation" {{ request('status') == 'importation' ? 'selected' : '' }}>En Transit</option>
                <option value="arrive" {{ request('status') == 'arrive' ? 'selected' : '' }}>Arrivé Port</option>
                <option value="livre" {{ request('status') == 'livre' ? 'selected' : '' }}>Livré</option>
                <option value="annule" {{ request('status') == 'annule' ? 'selected' : '' }}>Annulé</option>
            </select>
        </div>

        <button type="submit" class="p-4 bg-amber-500 text-slate-950 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/10 transition-colors">
            <i data-lucide="filter" class="w-4 h-4"></i>
        </button>

        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.orders-cars.index') }}" class="p-4 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-2xl transition border border-slate-100 dark:border-white/5 transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
        @endif
    </form>
    </div>

    <!-- Orders Table -->
    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-lg dark:shadow-2xl backdrop-blur-sm transition-colors">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5 transition-colors">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Référence / Client</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Véhicule</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Statut Suivi</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Montant</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic transition-colors">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                @forelse($orders as $order)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300 transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-amber-500 tracking-widest uppercase italic bg-amber-500/5 px-3 py-1.5 rounded-xl border border-amber-500/10 w-fit transition-colors">
                                #{{ $order->numero_suivi ?? 'SANS-REF' }}
                            </span>
                            <div class="text-sm font-black text-slate-900 dark:text-white tracking-tight italic mt-2 transition-colors">{{ $order->user->prenom }} {{ $order->user->nom }}</div>
                            <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest italic mt-0.5 transition-colors">{{ $order->date_commande->format('d M Y') }}</div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-12 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 flex items-center justify-center overflow-hidden shadow-inner transition-colors">
                                @if($order->voiture->photo_principale)
                                    <img src="{{ $order->voiture->photo_principale }}" class="w-full h-full object-cover">
                                @else
                                    <i data-lucide="car" class="w-4 h-4 text-slate-300 dark:text-slate-700 transition-colors"></i>
                                @endif
                            </div>
                            <div>
                                <div class="text-[11px] font-black text-slate-900 dark:text-white uppercase italic tracking-tight transition-colors">{{ $order->voiture->marque }} {{ $order->voiture->modele }}</div>
                                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5 italic transition-colors">{{ $order->voiture->annee }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $statusColor = match($order->statut) {
                                'en_attente' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                'paye' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'importation' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                'arrive' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                'livre' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'annule' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                'vendu' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                'disponible' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border {{ $statusColor }} text-[9px] font-black uppercase tracking-widest italic leading-none">
                            {{ str_replace('_', ' ', $order->statut) }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-slate-900 dark:text-white italic tracking-tight transition-colors">{{ number_format($order->montant_total, 0, ',', ' ') }} <span class="text-[10px] transition-colors">FCFA</span></div>
                        <div class="text-[8px] text-slate-400 dark:text-slate-600 font-bold uppercase tracking-widest italic mt-1 transition-colors">Acompte: {{ number_format($order->acompte_paye, 0, ',', ' ') }}</div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button onclick="openShowOrderModal({{ json_encode($order->load(['user', 'voiture'])) }})" class="p-3 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition rounded-[1.2rem] group/btn border border-slate-100 dark:border-white/5 shadow-lg dark:shadow-xl transition-colors">
                                <i data-lucide="eye" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                            <button onclick="openEditOrderModal({{ json_encode($order) }})" class="p-3 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-400 hover:text-white hover:bg-amber-100 dark:hover:bg-amber-500 transition rounded-[1.2rem] group/btn border border-slate-100 dark:border-white/5 shadow-lg dark:shadow-xl transition-colors">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucune commande de véhicule enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-white/5 transition-colors">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Order Modal -->
<div id="editOrderModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('editOrderModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300 transition-colors">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter mb-2 transition-colors">Suivi Commande</h2>
            <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4 transition-colors">Mise à jour logistique & statut</p>

            <form id="editOrderForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Numéro de Suivi Tracker</label>
                    <input type="text" name="numero_suivi" id="edit_numero_suivi" placeholder="Ex: TRX-9988-ABC" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black tracking-widest uppercase transition-colors">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Étape Actuelle</label>
                    <select name="statut" id="edit_order_statut" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none font-black uppercase tracking-wider transition-colors">
                        <option value="en_attente">En Attente</option>
                        <option value="paye">Acompte Reçu</option>
                        <option value="importation">En Transit Maritime</option>
                        <option value="arrive">Arrivé au Port</option>
                        <option value="livre">Livré au Client</option>
                        <option value="annule">Commande Annulée</option>
                    </select>
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editOrderModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-900 transition font-black italic transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Actualiser le flux</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Order Modal -->
<div id="showOrderModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/95 backdrop-blur-2xl transition-colors" onclick="closeModal('showOrderModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-5xl shadow-2xl rounded-[5rem] rounded-tl-xl rounded-br-xl overflow-hidden flex flex-col md:flex-row animate-in fade-in slide-in-from-bottom duration-500 transition-colors">
             <!-- Left Side: Order Stats -->
             <div class="w-full md:w-2/5 p-16 bg-slate-50 dark:bg-slate-950 border-r border-slate-100 dark:border-white/5 transition-colors">
                <div class="mb-12">
                    <span id="show_order_ref" class="px-4 py-2 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-black uppercase tracking-[0.2em] italic mb-6 inline-block transition-colors"></span>
                    <h3 class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter uppercase leading-tight transition-colors">Détails de la <br> Commande</h3>
                </div>

                <div class="space-y-10">
                    <div>
                        <div class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-3 italic transition-colors">Informations Client</div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-white/5 flex items-center justify-center font-black text-slate-900 dark:text-white uppercase italic transition-colors" id="show_user_initials"></div>
                            <div>
                                <div id="show_user_name" class="text-sm font-black text-slate-900 dark:text-white italic transition-colors"></div>
                                <div id="show_user_email" class="text-[10px] text-slate-400 dark:text-slate-500 font-bold italic transition-colors"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-3 italic transition-colors">Bilan Financier</div>
                        <div class="p-6 bg-slate-100/30 dark:bg-slate-900/50 rounded-[2rem] border border-slate-100 dark:border-white/5 space-y-4 shadow-inner transition-colors">
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Total</span>
                                <span id="show_order_total" class="text-sm font-black text-slate-900 dark:text-white italic transition-colors"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] font-black text-emerald-600 dark:text-emerald-500 uppercase italic transition-colors">Payé</span>
                                <span id="show_order_paid" class="text-sm font-black text-emerald-600 dark:text-emerald-500 italic transition-colors"></span>
                            </div>
                            <div class="pt-4 border-t border-slate-200 dark:border-white/5 flex justify-between items-center font-black italic transition-colors">
                                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase italic transition-colors">Reste à régler</span>
                                <span id="show_order_rest" class="text-lg text-amber-500 italic tracking-tight transition-colors"></span>
                            </div>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Right Side: Logistics -->
             <div class="w-full md:w-3/5 p-16 bg-white dark:bg-slate-900 relative transition-colors">
                <button onclick="closeModal('showOrderModal')" class="absolute top-10 right-10 p-4 bg-slate-50 dark:bg-white/5 text-slate-400 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-2xl transition transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="mb-12">
                    <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-6 italic transition-colors">Véhicule Commandé</h4>
                    <div class="flex gap-8 items-center p-8 bg-slate-50 dark:bg-slate-950/50 border border-slate-100 dark:border-white/5 rounded-[3rem] shadow-xl transition-colors">
                        <img id="show_car_photo" src="" class="w-32 h-24 object-cover rounded-2xl shadow-2xl">
                        <div>
                            <div id="show_car_title" class="text-2xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors"></div>
                            <div class="flex gap-6 mt-2">
                                <div>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-600 font-black uppercase block italic transition-colors">Année</span>
                                    <span id="show_car_annee" class="text-xs font-black text-slate-900 dark:text-white italic transition-colors"></span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-600 font-black uppercase block italic transition-colors">Kilométrage</span>
                                    <span id="show_car_km" class="text-xs font-black text-slate-900 dark:text-white italic transition-colors"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-6 italic transition-colors">Chronologie de livraison</h4>
                    <div class="space-y-4">
                        <div id="timeline_status" class="space-y-4">
                            <!-- JS will inject status pills -->
                        </div>
                    </div>
                </div>

                <div class="pt-12 text-center">
                    <button onclick="closeModal('showOrderModal')" class="px-12 py-5 bg-slate-900 dark:bg-white text-white dark:text-slate-950 rounded-[2rem] text-[10px] font-black uppercase tracking-widest italic hover:bg-amber-500 dark:hover:bg-amber-500 transition duration-300 shadow-xl transition-colors">Fermer le dossier</button>
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
        document.getElementById('show_user_name').innerText = `${order.user.prenom} ${order.user.nom}`;
        document.getElementById('show_user_email').innerText = order.user.email;
        document.getElementById('show_user_initials').innerText = order.user.prenom[0] + order.user.nom[0];
        
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

        // Timeline Logic (Conceptual)
        const statuses = ['en_attente', 'paye', 'importation', 'arrive', 'livre'];
        const container = document.getElementById('timeline_status');
        container.innerHTML = '';
        
        statuses.forEach((s, idx) => {
            const isActive = statuses.indexOf(order.statut) >= idx;
            const item = document.createElement('div');
            item.className = `flex items-center gap-4 p-4 rounded-2xl border transition-colors ${isActive ? 'bg-amber-500/5 border-amber-500/20 text-slate-900 dark:text-white' : 'bg-slate-50 dark:bg-slate-950/50 border-slate-100 dark:border-white/5 text-slate-300 dark:text-slate-600 opacity-50'}`;
            item.innerHTML = `
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-black italic text-[9px] border transition-colors ${isActive ? 'border-amber-500 text-amber-500 bg-amber-500/10' : 'border-slate-200 dark:border-slate-800 text-slate-300 dark:text-slate-700'}">
                    ${idx + 1}
                </div>
                <div class="text-[9px] font-black uppercase tracking-widest italic">${s.replace('_', ' ')}</div>
                ${isActive && s === order.statut ? '<i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 ml-auto"></i>' : ''}
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
