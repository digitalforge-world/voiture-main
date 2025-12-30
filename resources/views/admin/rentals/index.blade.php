@extends('layouts.admin')

@section('title', 'Gestion de la Flotte & Locations - AutoImport Hub')

@section('content')
<div class="space-y-10">
    <!-- Header & Stats -->
    <div class="space-y-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Location de Véhicules</h1>
                <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Tableau de bord de gestion de la flotte et des contrats</p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="openModal('createRentalModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 hover:scale-105 duration-300 shadow-xl shadow-amber-900/10 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i> Nouveau Contrat
                </button>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-8 bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-[2.5rem] shadow-xl transition-colors group relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-amber-500 opacity-5 blur-3xl group-hover:opacity-10 transition-opacity"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-amber-500/10 rounded-2xl transition-colors"><i data-lucide="key" class="w-5 h-5 text-amber-500"></i></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic transition-colors">Total</span>
                </div>
                <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $stats['total'] }}</div>
                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1 tracking-widest transition-colors">Contrats enregistrés</div>
            </div>

            <div class="p-8 bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-[2.5rem] shadow-xl transition-colors group relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-500 opacity-5 blur-3xl group-hover:opacity-10 transition-opacity"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-500/10 rounded-2xl transition-colors"><i data-lucide="play" class="w-5 h-5 text-blue-500"></i></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic transition-colors">En Cours</span>
                </div>
                <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors text-blue-500">{{ $stats['active'] }}</div>
                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1 tracking-widest transition-colors">Véhicules sur la route</div>
            </div>

            <div class="p-8 bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-[2.5rem] shadow-xl transition-colors group relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-rose-500 opacity-5 blur-3xl group-hover:opacity-10 transition-opacity"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-rose-500/10 rounded-2xl transition-colors"><i data-lucide="clock" class="w-5 h-5 text-rose-500"></i></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic transition-colors">Attente</span>
                </div>
                <div class="text-4xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors text-rose-500">{{ $stats['pending'] }}</div>
                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1 tracking-widest transition-colors">Réservations à valider</div>
            </div>

            <div class="p-8 bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-[2.5rem] shadow-xl transition-colors group relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500 opacity-5 blur-3xl group-hover:opacity-10 transition-opacity"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-500/10 rounded-2xl transition-colors"><i data-lucide="banknote" class="w-5 h-5 text-emerald-500"></i></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic transition-colors">Chiffre</span>
                </div>
                <div class="text-2xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors text-emerald-500 whitespace-nowrap">{{ number_format($stats['revenue'], 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></div>
                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1 tracking-widest transition-colors">Revenus terminés</div>
            </div>
        </div>
    </div>

    <!-- Main Content Table -->
    <div class="bg-white dark:bg-slate-950/50 border border-slate-100 dark:border-white/5 rounded-[3rem] shadow-2xl overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5 transition-colors">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Référence / Client</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Véhicule</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">Période & Tarifs</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic transition-colors">État</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic transition-colors">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                    @forelse($locations as $rental)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-white/5 flex items-center justify-center font-black text-slate-900 dark:text-white uppercase italic text-[10px] transition-colors shrink-0">
                                    {{ substr($rental->client_nom ?? ($rental->user ? $rental->user->prenom : 'G'), 0, 1) }}{{ substr($rental->client_nom ?? ($rental->user ? $rental->user->nom : 'U'), -1, 1) }}
                                </div>
                                <div>
                                    <div class="text-[9px] font-black text-amber-500 uppercase tracking-widest mb-1 italic transition-colors">#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-sm font-black text-slate-900 dark:text-white tracking-tight italic transition-colors">
                                        @if($rental->user)
                                            {{ $rental->user->prenom }} {{ $rental->user->nom }}
                                        @else
                                            {{ $rental->client_nom }} <span class="text-[10px] text-slate-400 dark:text-slate-600">(INVITÉ)</span>
                                        @endif
                                    </div>
                                    <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5 italic transition-colors">
                                        {{ $rental->client_telephone ?? ($rental->user ? $rental->user->telephone : 'N/A') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-[11px] font-black text-slate-900 dark:text-white uppercase italic tracking-tight transition-colors">{{ $rental->voiture->marque }} {{ $rental->voiture->modele }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[9px] text-slate-400 dark:text-slate-600 font-bold uppercase tracking-widest italic transition-colors">{{ $rental->voiture->immatriculation ?? 'PROVISOIRE' }}</span>
                                <span class="w-1 h-1 bg-amber-500 rounded-full"></span>
                                <span class="text-[9px] text-amber-500 font-bold uppercase tracking-widest italic transition-colors">{{ $rental->voiture->categorie }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-[10px] font-black italic uppercase transition-colors">
                                    <span class="text-slate-400 dark:text-slate-600 transition-colors">DU</span>
                                    <span class="text-slate-900 dark:text-white transition-colors">{{ $rental->date_debut?->format('d M Y') ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-[10px] font-black italic uppercase transition-colors">
                                    <span class="text-slate-400 dark:text-slate-600 transition-colors">AU</span>
                                    <span class="text-slate-900 dark:text-white transition-colors">{{ $rental->date_fin?->format('d M Y') ?? 'N/A' }}</span>
                                </div>
                                <div class="text-[11px] font-black text-emerald-500 mt-2 italic transition-colors">
                                    {{ number_format($rental->montant_total, 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $statusColor = match($rental->statut) {
                                    'confirme' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'en_cours' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'termine' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'annule' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                    default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                };
                            @endphp
                            <span class="px-4 py-2 rounded-xl border {{ $statusColor }} text-[9px] font-black uppercase tracking-[0.2em] italic leading-none transition-all group-hover:px-6">
                                {{ $rental->statut }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3 translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
                                <button onclick="openShowRentalModal({{ json_encode($rental->load(['user', 'voiture'])) }})" class="p-3 bg-white dark:bg-slate-900 text-slate-400 hover:text-amber-500 rounded-2xl border border-slate-100 dark:border-white/5 shadow-xl transition-all hover:scale-110">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button onclick="openEditRentalModal({{ json_encode($rental) }})" class="p-3 bg-white dark:bg-slate-900 text-slate-400 hover:text-blue-500 rounded-2xl border border-slate-100 dark:border-white/5 shadow-xl transition-all hover:scale-110">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <button onclick="confirmDeletion('{{ route('admin.rentals.destroy', $rental->id) }}', 'Archiver ce contrat de location ?')" class="p-3 bg-white dark:bg-slate-900 text-slate-400 hover:text-rose-500 rounded-2xl border border-slate-100 dark:border-white/5 shadow-xl transition-all hover:scale-110">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-[2rem] flex items-center justify-center mx-auto mb-6 transition-colors">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-200 dark:text-slate-800 transition-colors"></i>
                            </div>
                            <div class="text-xs font-black uppercase tracking-[0.3em] text-slate-300 italic transition-colors">Aucun contrat actif</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($locations->hasPages())
        <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-white/5 transition-all">
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
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openEditRentalModal(rental) {
        const form = document.getElementById('editRentalForm');
        form.action = `/admin/rentals/${rental.id}`;
        
        const formatDate = (d) => d ? new Date(d).toISOString().split('T')[0] : '';
        document.getElementById('edit_rent_start').value = formatDate(rental.date_debut);
        document.getElementById('edit_rent_end').value = formatDate(rental.date_fin);
        document.getElementById('edit_rent_statut').value = rental.statut;
        document.getElementById('edit_rent_total').value = rental.montant_total;

        openModal('editRentalModal');
    }

    function openShowRentalModal(rental) {
        document.getElementById('show_rent_id').innerText = `CONTRAT-LOC #${rental.id.toString().padStart(5, '0')}`;
        
        const clientName = rental.user ? `${rental.user.prenom} ${rental.user.nom}` : rental.client_nom;
        const clientEmail = rental.user ? rental.user.email : (rental.client_email || 'N/A');
        
        document.getElementById('show_rent_user_name').innerText = clientName.toUpperCase();
        document.getElementById('show_rent_user_email').innerText = clientEmail;
        document.getElementById('show_rent_user_initials').innerText = clientName[0].toUpperCase() + (clientName.includes(' ') ? clientName.split(' ')[1][0] : clientName[1]).toUpperCase();
        
        const fmt = (d) => d ? new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' }).toUpperCase() : 'N/A';
        document.getElementById('show_rent_start').innerText = fmt(rental.date_debut);
        document.getElementById('show_rent_end').innerText = fmt(rental.date_fin);

        document.getElementById('show_rent_car_title').innerText = `${rental.voiture.marque} ${rental.voiture.modele}`;
        document.getElementById('show_rent_car_plate').innerText = rental.voiture.immatriculation || 'IMM-PROVISOIRE';
        document.getElementById('show_rent_car_photo').src = rental.voiture.photo_principale || 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1000';
        
        document.getElementById('show_rent_amount').innerText = new Intl.NumberFormat('fr-FR').format(rental.montant_total) + ' FCFA';
        
        const badge = document.getElementById('show_rent_status_badge');
        badge.innerText = rental.statut.toUpperCase();
        const colors = {
            'confirme': 'bg-blue-500/10 text-blue-500 border-blue-500/20',
            'en_cours': 'bg-amber-500/10 text-amber-500 border-amber-500/20',
            'termine': 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
            'annule': 'bg-rose-500/10 text-rose-500 border-rose-500/20'
        };
        badge.className = `px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest italic border mb-4 inline-block ${colors[rental.statut] || ''}`;

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
        const start = document.querySelector('input[name="date_debut"]').value;
        const end = document.querySelector('input[name="date_fin"]').value;
        const totalInput = document.querySelector('input[name="montant_total"]');

        const selectedText = voiture.options[voiture.selectedIndex].text;
        const priceMatch = selectedText.match(/(\d[\d\s]*)\sFCFA/);
        
        if (priceMatch && start && end) {
            const price = parseInt(priceMatch[1].replace(/\s/g, ''));
            const d1 = new Date(start);
            const d2 = new Date(end);
            const days = Math.ceil((d2 - d1) / (1000 * 3600 * 24));
            
            if (days > 0) {
                totalInput.value = (days * price) + 50000; // Adding dummy 50k caution
            }
        }
    }

    document.querySelector('input[name="date_debut"]')?.addEventListener('change', updateCreateEstimate);
    document.querySelector('input[name="date_fin"]')?.addEventListener('change', updateCreateEstimate);
    document.querySelector('select[name="voiture_location_id"]')?.addEventListener('change', updateCreateEstimate);
</script>
@endsection
@endsection

