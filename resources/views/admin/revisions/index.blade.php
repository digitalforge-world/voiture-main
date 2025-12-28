@extends('layouts.admin')

@section('title', 'Demandes de Révision - AutoImport Hub')

@section('content')
<div class="space-y-4">
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mx-2 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg flex items-center gap-2 animate-in fade-in slide-in-from-top duration-500">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
            <span class="text-xs font-bold text-emerald-500">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-2 p-3 bg-rose-500/10 border border-rose-500/20 rounded-lg flex items-center gap-2 animate-in fade-in slide-in-from-top duration-500">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500"></i>
            <span class="text-xs font-bold text-rose-500">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header Area --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-2 underline-offset-4">Demandes de Révision</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-0.5 uppercase tracking-widest text-[8px] italic">{{ $revisions->total() }} demande(s) • Aujourd'hui: {{ $revisions->filter(fn($r) => $r->date_demande->isToday())->count() }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mx-2 p-3 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher (client, voiture, plaque)..." class="px-2 py-1.5 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg focus:border-amber-500 focus:outline-none">
            
            <select name="statut" class="px-2 py-1.5 text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg focus:border-amber-500 focus:outline-none">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En Attente</option>
                <option value="diagnostic_en_cours" {{ request('statut') === 'diagnostic_en_cours' ? 'selected' : '' }}>Diagnostic en Cours</option>
                <option value="devis_envoye" {{ request('statut') === 'devis_envoye' ? 'selected' : '' }}>Devis Envoyé</option>
                <option value="accepte" {{ request('statut') === 'accepte' ? 'selected' : '' }}>Accepté</option>
                <option value="en_intervention" {{ request('statut') === 'en_intervention' ? 'selected' : '' }}>En Intervention</option>
                <option value="termine" {{ request('statut') === 'termine' ? 'selected' : '' }}>Terminé</option>
                <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulé</option>
            </select>

            <label class="flex items-center gap-2 px-2 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-900 transition">
                <input type="checkbox" name="today" value="1" {{ request('today') ? 'checked' : '' }} class="w-3 h-3 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Aujourd'hui uniquement</span>
            </label>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-2 py-1.5 bg-amber-500 text-slate-950 text-[8px] font-black uppercase tracking-widest rounded-lg hover:bg-amber-400 transition">
                    <i data-lucide="filter" class="w-3 h-3 inline mr-1"></i>Filtrer
                </button>
                <a href="{{ route('admin.revisions.index') }}" class="px-2 py-1.5 bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-[8px] font-black uppercase rounded-lg hover:bg-slate-200 transition">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Revisions Table --}}
    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-xl shadow-sm">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5">
                <tr>
                    <th class="px-2 py-1.5 text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Client</th>
                    <th class="px-2 py-1.5 text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Véhicule</th>
                    <th class="px-2 py-1.5 text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Problème</th>
                    <th class="px-2 py-1.5 text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Diagnostic</th>
                    <th class="px-2 py-1.5 text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Prix Devis</th>
                    <th class="px-2 py-1.5 text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">Statut</th>
                    <th class="px-2 py-1.5 text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($revisions as $revision)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition">
                    <td class="px-2 py-1.5">
                        <div class="text-[10px] font-bold text-slate-900 dark:text-white">
                            @if($revision->user)
                                {{ $revision->user->prenom }} {{ $revision->user->nom }}
                            @else
                                Client inconnu
                            @endif
                        </div>
                        <div class="text-[8px] text-slate-400 dark:text-slate-600 mt-0.5">{{ $revision->date_demande->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-2 py-1.5">
                        <div class="text-[10px] font-bold text-slate-900 dark:text-white">{{ $revision->marque_vehicule }} {{ $revision->modele_vehicule }}</div>
                        <div class="text-[8px] text-slate-400 dark:text-slate-600 mt-0.5">{{ $revision->immatriculation ?? 'N/A' }}</div>
                    </td>
                    <td class="px-2 py-1.5">
                        <div class="text-[9px] text-slate-600 dark:text-slate-400 max-w-[200px]">
                            {{ Str::limit($revision->probleme_description, 50) }}
                        </div>
                    </td>
                    <td class="px-2 py-1.5">
                        @if($revision->diagnostic || $revision->diagnostic_technique)
                            <div class="text-[9px] text-emerald-600 dark:text-emerald-500 max-w-[150px]">
                                {{ Str::limit($revision->diagnostic_technique ?? $revision->diagnostic, 40) }}
                            </div>
                        @else
                            <div class="text-[8px] text-slate-400 italic">En attente</div>
                        @endif
                    </td>
                    <td class="px-2 py-1.5">
                        @if($revision->montant_devis > 0 || $revision->prix_estime > 0)
                            <div class="text-[10px] font-black text-amber-600 dark:text-amber-500">
                                {{ number_format($revision->montant_devis ?? $revision->prix_estime, 0, ',', ' ') }} <span class="text-[7px]">FCFA</span>
                            </div>
                        @else
                            <div class="text-[8px] text-slate-400">-</div>
                        @endif
                    </td>
                    <td class="px-2 py-1.5">
                        @php
                            $statusColor = match($revision->statut) {
                                'en_attente' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                'diagnostic_en_cours' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'devis_envoye' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                'accepte' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                'en_intervention' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                'termine' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'annule' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                            };
                        @endphp
                        <span class="inline-block px-1.5 py-0.5 rounded border {{ $statusColor }} text-[7px] font-black uppercase tracking-wide">
                            {{ str_replace('_', ' ', $revision->statut) }}
                        </span>
                    </td>
                    <td class="px-2 py-1.5 text-right">
                        <button onclick="openValidateModal({{ json_encode($revision) }})" class="px-2 py-1 bg-amber-500/10 hover:bg-amber-500/20 rounded text-amber-600 dark:text-amber-500 transition text-[8px] font-black uppercase">
                            <i data-lucide="check-square" class="w-3 h-3 inline mr-1"></i>Valider
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-2 py-6 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <div class="p-2 bg-slate-100 dark:bg-slate-900 rounded-full">
                                <i data-lucide="wrench" class="w-4 h-4 text-slate-400"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-900 dark:text-white mb-1">Aucune demande</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-[9px]">Les demandes apparaîtront ici</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($revisions->hasPages())
            <div class="px-2 py-1.5 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-900/30">
                {{ $revisions->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Validate Revision Modal --}}
<div id="validateModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/90 backdrop-blur-xl" onclick="closeModal('validateModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-3xl p-5 shadow-2xl rounded-xl overflow-hidden">
            
            {{-- Header --}}
            <div class="flex items-start justify-between mb-4 pb-3 border-b border-slate-100 dark:border-white/5">
                <div>
                    <h2 class="text-lg font-black text-slate-900 dark:text-white uppercase">Analyse & Communication au Client</h2>
                    <p class="text-amber-500 font-bold text-xs mt-0.5" id="val_reference"></p>
                </div>
                <button onclick="closeModal('validateModal')" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition">
                    <i data-lucide="x" class="w-4 h-4 text-slate-400"></i>
                </button>
            </div>

            {{-- Content --}}
            <form id="validateForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Info Summary --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-100 dark:border-white/5">
                        <div class="text-[8px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                            <i data-lucide="user" class="w-3 h-3"></i>Client
                        </div>
                        <div id="val_client_name" class="text-sm font-bold text-slate-900 dark:text-white"></div>
                        <div id="val_client_phone" class="text-[9px] text-slate-500 mt-0.5"></div>
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-100 dark:border-white/5">
                        <div class="text-[8px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                            <i data-lucide="car" class="w-3 h-3"></i>Véhicule
                        </div>
                        <div id="val_vehicle" class="text-sm font-bold text-slate-900 dark:text-white"></div>
                        <div id="val_plate" class="text-[9px] text-slate-500 mt-0.5"></div>
                    </div>
                </div>

                {{-- Problem --}}
                <div class="p-3 bg-blue-50 dark:bg-blue-950/20 rounded-lg border border-blue-100 dark:border-blue-900/20">
                    <div class="text-[8px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-500 mb-1.5 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3 h-3"></i>Problème Signalé
                    </div>
                    <p id="val_problem" class="text-xs text-blue-700 dark:text-blue-400"></p>
                </div>

                {{-- Diagnostic Section --}}
                <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 rounded-xl border border-emerald-200 dark:border-emerald-900/30">
                    <div class="text-[9px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-500 mb-3 flex items-center gap-1">
                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>Analyse & Diagnostic
                    </div>
                    
                    <div class="space-y-3">
                        <div class="space-y-1">
                            <label class="text-[8px] font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-400 ml-1">Diagnostic Technique *</label>
                            <textarea name="diagnostic_technique" id="val_diagnostic" rows="3" required class="w-full py-1.5 px-2 bg-white dark:bg-slate-950 border border-emerald-200 dark:border-emerald-900/30 rounded-lg text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" placeholder="Description détaillée du diagnostic effectué..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[8px] font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-400 ml-1">Interventions Prévues</label>
                                <textarea name="interventions_prevues" id="val_interventions" rows="2" class="w-full py-1.5 px-2 bg-white dark:bg-slate-950 border border-emerald-200 dark:border-emerald-900/30 rounded-lg text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" placeholder="Liste des travaux à effectuer..."></textarea>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[8px] font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-400 ml-1">Pièces Nécessaires</label>
                                <textarea name="pieces_necessaires" id="val_pieces" rows="2" class="w-full py-1.5 px-2 bg-white dark:bg-slate-950 border border-emerald-200 dark:border-emerald-900/30 rounded-lg text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" placeholder="Liste des pièces à commander..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pricing Section - Communication au Client --}}
                <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/20 dark:to-orange-950/20 rounded-xl border-2 border-amber-300 dark:border-amber-900/30">
                    <div class="text-[9px] font-black uppercase tracking-wider text-amber-600 dark:text-amber-500 mb-3 flex items-center gap-2">
                        <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                        <span>Tarification - Communication au Client</span>
                        <span class="ml-auto px-2 py-0.5 bg-amber-500 text-white rounded-full text-[7px]">IMPORTANT</span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="p-3 bg-amber-100/50 dark:bg-amber-900/10 rounded-lg border border-amber-200 dark:border-amber-800/30">
                            <p class="text-[9px] text-amber-800 dark:text-amber-400 flex items-start gap-2">
                                <i data-lucide="info" class="w-3 h-3 mt-0.5 flex-shrink-0"></i>
                                <span>Ce montant sera <strong>communiqué au client</strong> comme devis estimatif. Il pourra suivre l'évolution de sa demande depuis son espace.</span>
                            </p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[8px] font-black uppercase tracking-wider text-amber-700 dark:text-amber-400 ml-1 flex items-center gap-1">
                                <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                                Montant du Devis (FCFA) *
                            </label>
                            <input type="number" name="montant_devis" id="val_prix" step="0.01" required class="w-full py-2 px-3 bg-white dark:bg-slate-950 border-2 border-amber-300 dark:border-amber-900/30 rounded-lg text-slate-900 dark:text-white text-sm font-bold focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" placeholder="0.00">
                            <p class="text-[8px] text-amber-600 dark:text-amber-500 ml-1 mt-1">💡 Prix qui sera affiché au client dans son suivi</p>
                        </div>
                    </div>
                </div>

                {{-- Payment Section --}}
                <div class="p-4 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-950/20 dark:to-blue-950/20 rounded-xl border-2 border-indigo-300 dark:border-indigo-900/30">
                    <div class="text-[9px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-500 mb-3 flex items-center gap-2">
                        <i data-lucide="wallet" class="w-4 h-4"></i>
                        <span>Enregistrement du Paiement</span>
                        <span class="ml-auto px-2 py-0.5 bg-indigo-500 text-white rounded-full text-[7px]">ADMIN UNIQUEMENT</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[8px] font-black uppercase tracking-wider text-indigo-700 dark:text-indigo-400 ml-1">Montant Perçu (FCFA)</label>
                            <input type="number" name="montant_paye" id="val_montant_paye" step="0.01" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border-2 border-indigo-300 dark:border-indigo-900/30 rounded-lg text-slate-900 dark:text-white text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="0.00">
                        </div>

                        <div class="space-y-1">
                             <label class="text-[8px] font-black uppercase tracking-wider text-indigo-700 dark:text-indigo-400 ml-1">Statut Paiement</label>
                             <select name="statut_paiement" id="val_statut_paiement" class="w-full py-2 px-3 bg-white dark:bg-slate-950 border border-indigo-300 dark:border-indigo-900/30 rounded-lg text-slate-900 dark:text-white text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                 <option value="non_paye">Non Payé</option>
                                 <option value="partiel">Partiel</option>
                                 <option value="paye">Totalement Payé</option>
                             </select>
                        </div>
                    </div>
                </div>

                {{-- Status & Notification --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 ml-1">Statut de la Demande *</label>
                        <select name="statut" id="val_statut" required class="w-full py-1.5 px-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-lg text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                            <option value="en_attente">En Attente</option>
                            <option value="diagnostic_en_cours">Diagnostic en Cours</option>
                            <option value="devis_envoye">Devis Envoyé</option>
                            <option value="accepte">Accepté</option>
                            <option value="en_intervention">En Intervention</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 ml-1">Notes Internes</label>
                        <input type="text" name="notes_internes" id="val_notes" class="w-full py-1.5 px-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-lg text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500" placeholder="Notes pour le service...">
                    </div>
                </div>

                <div class="p-3 bg-blue-50 dark:bg-blue-950/20 rounded-lg border border-blue-100 dark:border-blue-900/20">
                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" name="notify_client" value="1" checked class="mt-0.5 w-4 h-4 rounded border-blue-300 text-blue-500 focus:ring-blue-500">
                        <div>
                            <div class="text-[9px] font-black text-blue-700 dark:text-blue-400 uppercase">Notifier le Client</div>
                            <p class="text-[8px] text-blue-600 dark:text-blue-500 mt-0.5">Envoyer un email/SMS au client avec le devis et l'état de sa demande</p>
                        </div>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 pt-3 border-t border-slate-100 dark:border-white/5">
                    <button type="button" onclick="closeModal('validateModal')" class="flex-1 py-2 bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white rounded-lg text-[9px] font-black uppercase tracking-wider hover:bg-slate-200 dark:hover:bg-slate-800 transition">
                        <i data-lucide="x" class="w-3 h-3 inline mr-1"></i>Annuler
                    </button>
                    <button type="submit" class="flex-1 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg text-[9px] font-black uppercase tracking-wider hover:from-amber-400 hover:to-orange-400 transition shadow-lg shadow-amber-500/30">
                        <i data-lucide="send" class="w-3 h-3 inline mr-1"></i>Valider & Communiquer au Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentRevision = null;

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openValidateModal(revision) {
        currentRevision = revision;
        
        // Update form action
        document.getElementById('validateForm').action = `/admin/revisions/${revision.id}`;
        
        // Fill reference
        document.getElementById('val_reference').textContent = `#REV-${revision.id}`;
        
        // Fill client info
        const clientName = revision.user 
            ? `${revision.user.prenom} ${revision.user.nom}` 
            : 'Client inconnu';
        document.getElementById('val_client_name').textContent = clientName;
        document.getElementById('val_client_phone').textContent = revision.user?.telephone || 'N/A';
        
        // Fill vehicle info
        document.getElementById('val_vehicle').textContent = `${revision.marque_vehicule} ${revision.modele_vehicule}`;
        document.getElementById('val_plate').textContent = revision.immatriculation || 'N/A';
        
        // Fill problem
        document.getElementById('val_problem').textContent = revision.probleme_description || 'N/A';
        
        // Fill form fields
        document.getElementById('val_statut').value = revision.statut;
        document.getElementById('val_prix').value = revision.montant_devis || revision.prix_estime || 0;
        document.getElementById('val_diagnostic').value = revision.diagnostic_technique || revision.diagnostic || '';
        document.getElementById('val_interventions').value = revision.interventions_prevues || '';
        document.getElementById('val_pieces').value = revision.pieces_necessaires || '';
        document.getElementById('val_notes').value = revision.notes_internes || revision.notes || '';
        document.getElementById('val_montant_paye').value = revision.montant_paye || 0;
        document.getElementById('val_statut_paiement').value = revision.statut_paiement || 'non_paye';
        
        openModal('validateModal');
    }

    // Close modals with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('validateModal');
        }
    });
</script>
@endsection
