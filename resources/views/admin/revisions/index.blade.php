@extends('layouts.admin')

@section('title', 'Demandes de Révision - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Demandes de Révision</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Planification et suivi des entretiens mécaniques</p>
        </div>
    </div>

    <!-- Revisions Table -->
    <div class="border overflow-hidden bg-slate-950/50 border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left">
            <thead class="bg-slate-900/50 border-b border-white/5">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Client</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Véhicule Concerné</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Type de Service</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Statut</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($revisions as $revision)
                <tr class="group hover:bg-white/[0.02] transition duration-300">
                    <td class="px-8 py-6">
                        <div class="text-sm font-black text-white tracking-tight italic">{{ $revision->user->prenom }} {{ $revision->user->nom }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest italic mt-1">{{ $revision->date_demande->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-[11px] font-black text-white uppercase italic tracking-tight">{{ $revision->marque_modele }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5 italic">{{ $revision->immatriculation ?? 'NON-RENSEIGNÉ' }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-[10px] text-slate-400 font-bold leading-relaxed max-w-[200px] italic">
                            {{ Str::limit($revision->description_probleme, 50) }}
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $statusColor = match($revision->statut) {
                                'en_attente' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                'en_diagnostic' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'devis_envoye' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                'termine' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'annule' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg border {{ $statusColor }} text-[9px] font-black uppercase tracking-widest italic leading-none">
                            {{ str_replace('_', ' ', $revision->statut) }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                             <button onclick="openShowRevisionModal({{ json_encode($revision->load('user')) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-[1.2rem] group/btn border border-white/5 shadow-xl">
                                <i data-lucide="eye" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                            <button onclick="openEditRevisionModal({{ json_encode($revision) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-amber-500 transition rounded-[1.2rem] group/btn border border-white/5 shadow-xl">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover/btn:scale-110 transition"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucune demande de révision en attente.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($revisions->hasPages())
        <div class="px-8 py-6 bg-slate-900/30 border-t border-white/5">
            {{ $revisions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Revision Modal -->
<div id="editRevisionModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('editRevisionModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-2xl p-12 shadow-2xl rounded-[4rem] rounded-tr-xl rounded-bl-xl overflow-hidden animate-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-2">Diagnostic Atélier</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-amber-500 pl-4">Expertise technique & devis estimatif</p>

            <form id="editRevisionForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Statut de l'intervention</label>
                    <select name="statut" id="edit_rev_statut" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black uppercase tracking-wider">
                        <option value="en_attente">En Attente</option>
                        <option value="en_diagnostic">Diagnostic en Cours</option>
                        <option value="devis_envoye">Devis Transmis</option>
                        <option value="termine">Révision Terminée</option>
                        <option value="annule">Intervention Annulée</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Rapport Technique</label>
                    <textarea name="diagnostic_technique" id="edit_rev_diag" rows="4" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-3xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner italic font-medium" placeholder="Détails des points contrôlés..."></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-2 italic">Prix Estimé (Total)</label>
                    <input type="number" name="prix_estime" id="edit_rev_prix" class="w-full py-5 px-8 bg-slate-950 border border-white/5 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-black text-amber-500">
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editRevisionModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-950 rounded-[2.5rem] border border-white/5 hover:bg-slate-900 transition font-black italic">Fermer</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-white transition shadow-xl shadow-amber-500/20 font-black italic">Valider le Diagnostic</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Revision Modal -->
<div id="showRevisionModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/95 backdrop-blur-2xl" onclick="closeModal('showRevisionModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-4xl shadow-2xl rounded-[5rem] rounded-tl-xl rounded-br-xl overflow-hidden flex flex-col md:flex-row animate-in fade-in slide-in-from-bottom duration-500">
             <!-- Mechanics Side -->
             <div class="w-full md:w-2/5 p-16 bg-slate-950 border-r border-white/5 flex flex-col">
                <div class="mb-12">
                    <span id="show_rev_id" class="px-4 py-2 rounded-xl bg-slate-900 text-slate-500 border border-white/5 text-[10px] font-black uppercase tracking-[0.2em] italic mb-6 inline-block"></span>
                    <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase leading-tight">Fiche de <br> Maintenance</h3>
                </div>

                <div class="space-y-12">
                    <div>
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Demandeur</div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center font-black text-amber-500 uppercase italic shadow-inner" id="show_rev_user_initials"></div>
                            <div>
                                <div id="show_rev_user_name" class="text-sm font-black text-white italic"></div>
                                <div id="show_rev_user_phone" class="text-[10px] text-slate-500 font-bold italic mt-1"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-white/5">
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Configuration Atélier</div>
                        <div class="space-y-4">
                             <div class="flex justify-between items-center bg-slate-900/50 p-4 rounded-2xl border border-white/5">
                                <span class="text-[9px] font-black text-slate-500 uppercase italic">Diagnostic</span>
                                <span id="show_rev_diag_status" class="text-[10px] font-black uppercase italic"></span>
                             </div>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Details Side -->
             <div class="w-full md:w-3/5 p-16 bg-slate-900 relative">
                <button onclick="closeModal('showRevisionModal')" class="absolute top-10 right-10 p-4 bg-white/5 text-slate-400 hover:text-white rounded-2xl transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="space-y-12 mt-4">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6 italic italic">Symptômes Déclarés</h4>
                        <div id="show_rev_problem" class="text-sm font-medium text-slate-300 leading-relaxed italic bg-slate-950 p-6 rounded-[2rem] border border-white/5"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <span class="text-[9px] font-black text-slate-600 uppercase italic block mb-2">Identité Véhicule</span>
                            <div id="show_rev_car" class="text-lg font-black text-white italic uppercase tracking-tighter"></div>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-600 uppercase italic block mb-2">Immatriculation</span>
                            <div id="show_rev_plate" class="text-lg font-black text-amber-500 italic uppercase"></div>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-white/5">
                        <div class="flex justify-between items-end mb-10">
                            <div>
                                <span class="text-[9px] font-black text-slate-600 uppercase italic block mb-2">Expertise Rendue</span>
                                <div id="show_rev_diag_full" class="text-sm font-medium text-emerald-500 italic bg-emerald-500/5 p-6 rounded-[2rem] border border-emerald-500/10 min-h-[100px]"></div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center p-8 bg-slate-950 rounded-[2.5rem] border border-white/10 shadow-2xl">
                            <div>
                                <div class="text-[9px] font-black text-slate-500 uppercase italic mb-1">Total Devis Estimé</div>
                                <div id="show_rev_price" class="text-3xl font-black text-white italic tracking-tighter"></div>
                            </div>
                            <button onclick="closeModal('showRevisionModal')" class="px-8 py-4 bg-white text-slate-950 rounded-2xl text-[9px] font-black uppercase tracking-widest italic hover:bg-amber-500 transition">Fermer</button>
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

    function openEditRevisionModal(rev) {
        const form = document.getElementById('editRevisionForm');
        form.action = `/admin/revisions/${rev.id}`;
        document.getElementById('edit_rev_statut').value = rev.statut;
        document.getElementById('edit_rev_diag').value = rev.diagnostic_technique || '';
        document.getElementById('edit_rev_prix').value = rev.prix_estime || 0;
        openModal('editRevisionModal');
    }

    function openShowRevisionModal(rev) {
        document.getElementById('show_rev_id').innerText = `ORDRE-WORKSHOP #${rev.id.toString().padStart(4, '0')}`;
        document.getElementById('show_rev_user_name').innerText = `${rev.user.prenom} ${rev.user.nom}`;
        document.getElementById('show_rev_user_phone').innerText = rev.user.telephone || 'Non spécifié';
        document.getElementById('show_rev_user_initials').innerText = rev.user.prenom[0] + rev.user.nom[0];
        
        document.getElementById('show_rev_diag_status').innerText = rev.statut.replace('_', ' ');
        document.getElementById('show_rev_diag_status').className = `text-[10px] font-black uppercase italic ${rev.statut === 'termine' ? 'text-emerald-500' : 'text-amber-500'}`;
        
        document.getElementById('show_rev_problem').innerText = rev.description_probleme;
        document.getElementById('show_rev_car').innerText = rev.marque_modele;
        document.getElementById('show_rev_plate').innerText = rev.immatriculation || 'NON-RENSEIGNÉ';
        document.getElementById('show_rev_diag_full').innerText = rev.diagnostic_technique || 'En attente d\'expertise technique...';
        document.getElementById('show_rev_price').innerText = new Intl.NumberFormat('fr-FR').format(rev.prix_estime || 0) + ' FCFA';

        openModal('showRevisionModal');
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('editRevisionModal');
            closeModal('showRevisionModal');
        }
    });
</script>
@endsection
@endsection
