@extends('layouts.app')

@section('title', 'Mes Révisions - AutoImport Hub')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors duration-500">
    {{-- Header --}}
    <div class="relative py-12 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 via-transparent to-amber-500/5"></div>
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="text-center">
                <h1 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">
                    Mes Révisions
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">
                    Suivez l'état de vos demandes de révision
                </p>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="container px-4 py-8 mx-auto lg:px-8">
        <div class="max-w-6xl mx-auto space-y-4">
            @forelse($revisions as $revision)
                <div class="p-px bg-gradient-to-br from-purple-500/20 via-slate-200 dark:via-slate-800 to-amber-500/20 rounded-xl transition-all hover:shadow-lg">
                    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl">
                        {{-- Header --}}
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-amber-500/10 rounded-full flex items-center justify-center">
                                        <i data-lucide="wrench" class="w-4 h-4 text-amber-500"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase">
                                            {{ $revision->marque_vehicule }} {{ $revision->modele_vehicule }}
                                        </h3>
                                        <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold">
                                            Ref: #REV-{{ $revision->id }} • {{ $revision->date_demande->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Status Badge --}}
                            <div>
                                @php
                                    $statusConfig = match($revision->statut) {
                                        'en_attente' => ['color' => 'bg-slate-500/10 text-slate-500 border-slate-500/20', 'icon' => 'clock'],
                                        'diagnostic_en_cours' => ['color' => 'bg-amber-500/10 text-amber-500 border-amber-500/20', 'icon' => 'search'],
                                        'devis_envoye' => ['color' => 'bg-blue-500/10 text-blue-500 border-blue-500/20', 'icon' => 'file-text'],
                                        'accepte' => ['color' => 'bg-purple-500/10 text-purple-500 border-purple-500/20', 'icon' => 'check-circle'],
                                        'en_intervention' => ['color' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20', 'icon' => 'tool'],
                                        'termine' => ['color' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20', 'icon' => 'check-circle-2'],
                                        'annule' => ['color' => 'bg-rose-500/10 text-rose-500 border-rose-500/20', 'icon' => 'x-circle'],
                                        default => ['color' => 'bg-slate-500/10 text-slate-500 border-slate-500/20', 'icon' => 'help-circle'],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border {{ $statusConfig['color'] }} text-[9px] font-black uppercase tracking-wide">
                                    <i data-lucide="{{ $statusConfig['icon'] }}" class="w-3 h-3"></i>
                                    {{ str_replace('_', ' ', $revision->statut) }}
                                </span>
                            </div>
                        </div>

                        {{-- Details Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                            {{-- Vehicle Info --}}
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-100 dark:border-slate-800">
                                <div class="text-[8px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                                    <i data-lucide="car" class="w-3 h-3"></i>Véhicule
                                </div>
                                <div class="space-y-0.5">
                                    <div class="text-[10px] text-slate-600 dark:text-slate-400">
                                        <span class="font-bold">Immat:</span> {{ $revision->immatriculation ?? 'N/A' }}
                                    </div>
                                    @if($revision->annee_vehicule)
                                        <div class="text-[10px] text-slate-600 dark:text-slate-400">
                                            <span class="font-bold">Année:</span> {{ $revision->annee_vehicule }}
                                        </div>
                                    @endif
                                    @if($revision->kilometrage)
                                        <div class="text-[10px] text-slate-600 dark:text-slate-400">
                                            <span class="font-bold">Km:</span> {{ number_format($revision->kilometrage, 0, ',', ' ') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Type de Révision --}}
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-100 dark:border-slate-800">
                                <div class="text-[8px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                                    <i data-lucide="clipboard-list" class="w-3 h-3"></i>Type de Service
                                </div>
                                <div class="text-xs font-bold text-slate-900 dark:text-white">
                                    {{ ucfirst($revision->type_revision ?? 'Standard') }}
                                </div>
                            </div>

                            {{-- Prix --}}
                            @if($revision->montant_devis > 0)
                                <div class="p-3 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/20 dark:to-orange-950/20 rounded-lg border border-amber-200 dark:border-amber-900/30">
                                    <div class="text-[8px] font-black uppercase tracking-wider text-amber-600 dark:text-amber-500 mb-1.5 flex items-center gap-1">
                                        <i data-lucide="dollar-sign" class="w-3 h-3"></i>Devis Estimatif
                                    </div>
                                    <div class="text-xl font-black text-amber-600 dark:text-amber-500">
                                        {{ number_format($revision->montant_devis, 0, ',', ' ') }} <span class="text-[10px]">FCFA</span>
                                    </div>
                                </div>
                            @else
                                <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-100 dark:border-slate-800">
                                    <div class="text-[8px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i>Devis
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 italic">
                                        En cours d'évaluation
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Problem Description --}}
                        <div class="p-3 bg-blue-50 dark:bg-blue-950/20 rounded-lg border border-blue-100 dark:border-blue-900/20 mb-3">
                            <div class="text-[8px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-500 mb-1.5 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>Problème Signalé
                            </div>
                            <p class="text-xs text-blue-700 dark:text-blue-400">
                                {{ $revision->probleme_description }}
                            </p>
                        </div>

                        {{-- Diagnostic (if available) --}}
                        @if($revision->diagnostic || $revision->diagnostic_technique)
                            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 rounded-lg border border-emerald-100 dark:border-emerald-900/20 mb-3">
                                <div class="text-[8px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-500 mb-1.5 flex items-center gap-1">
                                    <i data-lucide="clipboard-check" class="w-3 h-3"></i>Diagnostic Technique
                                </div>
                                <p class="text-xs text-emerald-700 dark:text-emerald-400">
                                    {{ $revision->diagnostic_technique ?? $revision->diagnostic }}
                                </p>
                            </div>
                        @endif

                        {{-- Interventions & Pieces --}}
                        @if($revision->interventions_prevues || $revision->pieces_necessaires)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                @if($revision->interventions_prevues)
                                    <div class="p-3 bg-purple-50 dark:bg-purple-950/20 rounded-lg border border-purple-100 dark:border-purple-900/20">
                                        <div class="text-[8px] font-black uppercase tracking-wider text-purple-600 dark:text-purple-500 mb-1.5 flex items-center gap-1">
                                            <i data-lucide="list-checks" class="w-3 h-3"></i>Interventions Prévues
                                        </div>
                                        <p class="text-xs text-purple-700 dark:text-purple-400">
                                            {{ $revision->interventions_prevues }}
                                        </p>
                                    </div>
                                @endif

                                @if($revision->pieces_necessaires)
                                    <div class="p-3 bg-indigo-50 dark:bg-indigo-950/20 rounded-lg border border-indigo-100 dark:border-indigo-900/20">
                                        <div class="text-[8px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-500 mb-1.5 flex items-center gap-1">
                                            <i data-lucide="package" class="w-3 h-3"></i>Pièces Nécessaires
                                        </div>
                                        <p class="text-xs text-indigo-700 dark:text-indigo-400">
                                            {{ $revision->pieces_necessaires }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Timeline --}}
                        <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-100 dark:border-slate-800">
                            <div class="text-[8px] font-black uppercase tracking-wider text-slate-400 mb-2 flex items-center gap-1">
                                <i data-lucide="timeline" class="w-3 h-3"></i>Historique
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 text-[10px] text-slate-600 dark:text-slate-400">
                                    <div class="w-1.5 h-1.5 bg-slate-400 rounded-full"></div>
                                    <span class="font-bold">Demande créée:</span>
                                    <span>{{ $revision->date_demande->format('d/m/Y à H:i') }}</span>
                                </div>
                                @if($revision->date_diagnostic)
                                    <div class="flex items-center gap-2 text-[10px] text-emerald-600 dark:text-emerald-500">
                                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                                        <span class="font-bold">Diagnostic effectué:</span>
                                        <span>{{ $revision->date_diagnostic->format('d/m/Y à H:i') }}</span>
                                    </div>
                                @endif
                                @if($revision->date_devis)
                                    <div class="flex items-center gap-2 text-[10px] text-amber-600 dark:text-amber-500">
                                        <div class="w-1.5 h-1.5 bg-amber-500 rounded-full"></div>
                                        <span class="font-bold">Devis envoyé:</span>
                                        <span>{{ $revision->date_devis->format('d/m/Y à H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Aucune révision</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Vous n'avez pas encore de demande de révision</p>
                    <a href="{{ route('revisions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-black uppercase tracking-wider rounded-full hover:from-amber-400 hover:to-orange-400 transition shadow-lg shadow-amber-500/30">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Demander une révision
                    </a>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($revisions->hasPages())
                <div class="mt-6">
                    {{ $revisions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
