@extends('layouts.admin')

@section('title', 'Gestion des Locations - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Parc Locatif</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Suivi des réservations et état des véhicules de location</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openModal('createRentalModal')" class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-white hover:scale-105 duration-300 shadow-xl shadow-amber-900/10">
                <i data-lucide="plus" class="w-4 h-4"></i> Nouvelle Location
            </button>
        </div>
    </div>

    <!-- Rentals Table -->
    <div class="border overflow-hidden bg-slate-950/50 border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left">
            <thead class="bg-slate-900/50 border-b border-white/5">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Client</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Véhicule</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Période</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Statut</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($locations as $rental)
                <tr class="group hover:bg-white/[0.02] transition duration-300">
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-white tracking-tight italic">{{ $rental->user->prenom }} {{ $rental->user->nom }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest italic mt-1">{{ $rental->user->telephone }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-[11px] font-black text-white uppercase italic tracking-tight">{{ $rental->voiture->marque }} {{ $rental->voiture->modele }}</div>
                        <div class="text-[9px] text-amber-500 font-bold uppercase tracking-widest mt-0.5 italic">{{ $rental->voiture->immatriculation ?? 'PROVISOIRE' }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] text-slate-300 font-black italic uppercase">DU {{ $rental->date_debut->format('d/m/Y') }}</span>
                            <span class="text-[10px] text-slate-500 font-black italic uppercase">AU {{ $rental->date_fin->format('d/m/Y') }}</span>
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
                        <span class="px-3 py-1.5 rounded-lg border {{ $statusColor }} text-[9px] font-black uppercase tracking-widest italic leading-none">
                            {{ $rental->statut }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <button onclick="openShowRentalModal({{ json_encode($rental->load(['user', 'voiture'])) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-[1.2rem] border border-white/5 shadow-xl">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button onclick="openEditRentalModal({{ json_encode($rental) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-amber-500 transition rounded-[1.2rem] border border-white/5 shadow-xl">
                                <i data-lucide="calendar-days" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucune location enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($locations->hasPages())
        <div class="px-8 py-6 bg-slate-900/30 border-t border-white/5">
            {{ $locations->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Rental Modal -->
<div id="editRentalModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('editRentalModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-2">Ajuster Location</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4">Modification des paramètres de réservation</p>

            <form id="editRentalForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Date Début</label>
                        <input type="date" name="date_debut" id="edit_rent_start" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Date Fin</label>
                        <input type="date" name="date_fin" id="edit_rent_end" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Statut Actuel</label>
                    <select name="statut" id="edit_rent_statut" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-1 focus:ring-amber-500 transition appearance-none font-black uppercase italic tracking-widest">
                        <option value="confirme">Confirmée</option>
                        <option value="en_cours">Véhicule Sorti</option>
                        <option value="termine">Contrat Clôturé</option>
                        <option value="annule">Annulée</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Coût Total (FCFA)</label>
                    <input type="number" name="montant_total" id="edit_rent_total" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black text-amber-500">
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editRentalModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2.5rem] border border-white/5 hover:bg-slate-900 transition font-black italic">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-white transition shadow-xl shadow-amber-500/20 font-black italic">Mettre à jour le contrat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Rental Modal -->
<div id="showRentalModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/95 backdrop-blur-2xl" onclick="closeModal('showRentalModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-4xl shadow-2xl rounded-[5rem] rounded-tl-xl rounded-br-xl overflow-hidden flex flex-col md:flex-row animate-in fade-in slide-in-from-bottom duration-500">
             <!-- Rental Summary Side -->
             <div class="w-full md:w-2/5 p-16 bg-slate-950 border-r border-white/5">
                <div class="mb-12">
                    <span id="show_rent_id" class="px-4 py-2 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-black uppercase tracking-[0.2em] italic mb-6 inline-block"></span>
                    <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase leading-tight">Contrat de <br> Location</h3>
                </div>

                <div class="space-y-12">
                    <div>
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Locataire</div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-white/5 flex items-center justify-center font-black text-white uppercase italic" id="show_rent_user_initials"></div>
                            <div>
                                <div id="show_rent_user_name" class="text-sm font-black text-white italic"></div>
                                <div id="show_rent_user_email" class="text-[10px] text-slate-500 font-bold italic"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic italic">Période d'Exploitation</div>
                        <div class="p-6 bg-slate-900/50 rounded-[2rem] border border-white/5 space-y-4">
                            <div class="flex justify-between items-center text-[10px] font-black uppercase italic">
                                <span class="text-slate-500">Départ</span>
                                <span id="show_rent_start" class="text-white"></span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] font-black uppercase italic">
                                <span class="text-slate-500">Retour</span>
                                <span id="show_rent_end" class="text-white"></span>
                            </div>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Car Detail Side -->
             <div class="w-full md:w-3/5 p-16 bg-slate-900 relative">
                <button onclick="closeModal('showRentalModal')" class="absolute top-10 right-10 p-4 bg-white/5 text-slate-400 hover:text-white rounded-2xl transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="space-y-12 mt-4">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6 italic italic">Le Véhicule</h4>
                        <div class="flex gap-10 items-center p-8 bg-slate-950/50 border border-white/5 rounded-[3rem] shadow-xl">
                            <img id="show_rent_car_photo" src="" class="w-40 h-28 object-cover rounded-2xl shadow-2xl">
                            <div>
                                <div id="show_rent_car_title" class="text-2xl font-black text-white italic uppercase tracking-tighter"></div>
                                <div id="show_rent_car_plate" class="text-lg font-black text-amber-500 mt-2 italic border-l-2 border-amber-500 pl-4 bg-amber-500/5 pr-4 rounded-r-lg"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-white/5">
                        <div class="flex justify-between items-center p-10 bg-slate-950 rounded-[3rem] border border-white/10 shadow-2xl relative overflow-hidden">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-500 opacity-5 blur-3xl"></div>
                            <div>
                                <div class="text-[9px] font-black text-slate-500 uppercase italic mb-1">Total Facturé</div>
                                <div id="show_rent_amount" class="text-4xl font-black text-white italic tracking-tighter"></div>
                            </div>
                            <div class="text-right">
                                <div id="show_rent_status_badge" class="px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest italic border mb-4 inline-block"></div>
                                <div class="text-[9px] text-slate-600 font-black italic uppercase">Facturation Finale</div>
                            </div>
                        </div>
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

    function openEditRentalModal(rental) {
        const form = document.getElementById('editRentalForm');
        form.action = `/admin/rentals/${rental.id}`;
        
        // Format dates for input YYYY-MM-DD
        const formatDate = (d) => new Date(d).toISOString().split('T')[0];
        document.getElementById('edit_rent_start').value = formatDate(rental.date_debut);
        document.getElementById('edit_rent_end').value = formatDate(rental.date_fin);
        document.getElementById('edit_rent_statut').value = rental.statut;
        document.getElementById('edit_rent_total').value = rental.montant_total;

        openModal('editRentalModal');
    }

    function openShowRentalModal(rental) {
        document.getElementById('show_rent_id').innerText = `CONTRAT-LOC-HU #${rental.id.toString().padStart(4, '0')}`;
        document.getElementById('show_rent_user_name').innerText = `${rental.user.prenom} ${rental.user.nom}`;
        document.getElementById('show_rent_user_email').innerText = rental.user.email;
        document.getElementById('show_rent_user_initials').innerText = rental.user.prenom[0] + rental.user.nom[0];
        
        const fmtDate = (d) => new Date(d).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' }).toUpperCase();
        document.getElementById('show_rent_start').innerText = fmtDate(rental.date_debut);
        document.getElementById('show_rent_end').innerText = fmtDate(rental.date_fin);

        document.getElementById('show_rent_car_title').innerText = `${rental.voiture.marque} ${rental.voiture.modele}`;
        document.getElementById('show_rent_car_plate').innerText = rental.voiture.immatriculation || 'IMM-PROVISOIRE';
        document.getElementById('show_rent_car_photo').src = rental.voiture.photo_principale || '/images/placeholder-car.jpg';
        
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
            closeModal('editRentalModal');
            closeModal('showRentalModal');
        }
    });
</script>
@endsection
@endsection
