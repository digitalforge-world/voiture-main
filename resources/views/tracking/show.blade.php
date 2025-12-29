@extends('layouts.app')

@section('title', 'Détails de la Commande ' . $tracking)

@section('content')
<div class="min-h-screen pt-24 pb-12 bg-white dark:bg-slate-950 transition-colors duration-500">
    <div class="container px-4 mx-auto max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('tracking.index') }}" class="inline-flex items-center text-sm text-slate-500 dark:text-slate-400 hover:text-amber-500 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Retour à la recherche
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-xl dark:shadow-2xl transition-colors">
            {{-- Header --}}
            <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 transition-colors">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $tracking }}</h1>
                            @php
                                $statusColors = [
                                    // Default / En attente
                                    'en_attente' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                    
                                    // Validé / Confirmé
                                    'validee' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'confirmee' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'devis_envoye' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'accepte' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',

                                    // En cours
                                    'en_cours' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'diagnostic_en_cours' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'sm' => 'bg-purple-500/10 text-purple-500 border-purple-500/20', // Sur mer
                                    'en_intervention' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                    
                                    // Terminé
                                    'livree' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                    'terminee' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                    'termine' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                    
                                    // Annulé / Refusé
                                    'annulee' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                    'annule' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                    'refuse' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                ];
                                $status = $order->statut ?? 'en_attente';
                                $colorClass = $statusColors[$status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                                $statusLabel = ucfirst(str_replace('_', ' ', $status));
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }} uppercase">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 transition-colors">
                            @php
                                $createdDate = match($type) {
                                    'revision' => $order->date_demande ?? $order->created_at ?? now(),
                                    default => $order->created_at ?? $order->date_demande ?? now()
                                };
                            @endphp
                            Commande créée le {{ \Carbon\Carbon::parse($createdDate)->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-sm text-slate-500 dark:text-slate-400 mb-1 transition-colors">Type de Service</div>
                        <div class="font-bold text-slate-900 dark:text-white uppercase tracking-wide transition-colors">
                            @switch($type)
                                @case('voiture') COMMANDE VÉHICULE @break
                                @case('location') LOCATION @break
                                @case('piece') PIÈCE DÉTACHÉE @break
                                @case('revision') RÉVISION MÉCANIQUE @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="py-12 px-6 md:px-12 bg-white dark:bg-slate-950/30 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <div class="relative">
                    {{-- Barre de fond --}}
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 dark:bg-slate-800 -translate-y-1/2 rounded-full transition-colors"></div>
                    
                    {{-- Calcul Progression --}}
                    @php
                        $progress = 0;
                        $bgClass = 'bg-amber-500';

                        // Statuts Étape 1 : Reçu (10%)
                        if (in_array($status, ['en_attente', 'recu'])) {
                            $progress = 10;
                        }
                        // Statuts Étape 2 : Validé / Diagnostic (40%)
                        elseif (in_array($status, ['validee', 'confirmee', 'devis_envoye', 'diagnostic_en_cours', 'accepte'])) {
                            $progress = 40;
                        }
                        // Statuts Étape 3 : En cours (70%)
                        elseif (in_array($status, ['en_cours', 'sm', 'en_intervention', 'expediee', 'en_preparation'])) {
                            $progress = 70;
                        }
                        // Statuts Étape 4 : Terminé (100%)
                        elseif (in_array($status, ['livree', 'terminee', 'termine', 'disponible'])) {
                            $progress = 100;
                            $bgClass = 'bg-emerald-500';
                        }
                        // Statuts Annulés : 100% Rouge
                        elseif (in_array($status, ['annule', 'annulee', 'refuse'])) {
                            $progress = 100;
                            $bgClass = 'bg-rose-500';
                        }
                    @endphp
                    
                    <div class="absolute top-1/2 left-0 h-1 {{ $bgClass }} -translate-y-1/2 rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>

                    {{-- Étapes --}}
                    <div class="relative flex justify-between">
                        @foreach(['Reçu', 'Validé', 'En cours', 'Terminé'] as $index => $step)
                        @php 
                            $stepThreshold = $index * 30 + 10;
                            $isActive = $progress >= $stepThreshold;
                            $isCompleted = $progress > $stepThreshold;
                        @endphp
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-4 {{ $isActive ? ($isCompleted ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-amber-500 border-white dark:border-slate-900 text-slate-950') : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 text-slate-300 dark:text-slate-500' }} transition-colors shadow-sm">
                                @if($isCompleted && !in_array($status, ['annule', 'annulee', 'refuse']))
                                    <i data-lucide="check" class="w-4 h-4 text-white"></i>
                                @elseif(in_array($status, ['annule', 'annulee', 'refuse']) && $index == 3)
                                    <i data-lucide="x" class="w-4 h-4 text-rose-500"></i>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                            <span class="text-xs font-medium {{ $isActive ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-500' }} transition-colors">{{ $step }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Payment Status --}}
            @php
                $montantTotal = 0;
                $montantPaye = 0;
                $showPayment = false;

                if ($type === 'voiture') {
                    $montantTotal = $order->montant_total ?? 0;
                    $montantPaye = $order->acompte_verse ?? 0;
                    $showPayment = true;
                } elseif ($type === 'revision') {
                    $montantTotal = $order->montant_final > 0 ? $order->montant_final : ($order->montant_devis ?? 0);
                    $montantPaye = $order->montant_paye ?? 0;
                    $showPayment = $montantTotal > 0;
                } elseif ($type === 'location' || $type === 'piece') {
                    $montantTotal = $order->montant_total ?? 0;
                    // TODO: Gérer acompte pour location/pièce si nécessaire
                    $showPayment = $montantTotal > 0; 
                }

                $resteAPayer = max(0, $montantTotal - $montantPaye);
                $isPaid = $resteAPayer <= 0 && $montantTotal > 0;
            @endphp

            @if($showPayment && $montantTotal > 0)
            <div class="px-6 md:px-12 py-8 bg-slate-50 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="w-full md:w-auto">
                        <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Statut du Paiement</h3>
                        <div class="flex items-center gap-4">
                            @if($isPaid)
                                <div class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 px-4 py-2 rounded-xl flex items-center gap-2 border border-emerald-500/20">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                    <span class="font-bold">Payé en totalité</span>
                                </div>
                            @elseif($resteAPayer > 0)
                                <div class="bg-amber-500/10 text-amber-600 dark:text-amber-500 px-4 py-2 rounded-xl flex items-center gap-2 border border-amber-500/20">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                    <span class="font-bold">Paiement partiel</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="w-full md:flex-1 grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="p-3 bg-white dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Montant Total</div>
                            <div class="font-bold text-slate-900 dark:text-white">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="p-3 bg-white dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Déjà Payé</div>
                            <div class="font-bold text-emerald-600 dark:text-emerald-500">{{ number_format($montantPaye, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-span-2 md:col-span-1 p-3 bg-white dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30">
                            <div class="text-xs text-amber-600 dark:text-amber-500 mb-1 font-bold">Reste à Payer</div>
                            <div class="font-bold text-amber-700 dark:text-amber-500">{{ number_format($resteAPayer, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>

                    @if($resteAPayer > 0)
                    <div class="w-full md:w-auto">
                        <div class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-xl flex items-center justify-center gap-2 cursor-help" title="Le paiement se fait en agence">
                            <i data-lucide="store" class="w-5 h-5"></i>
                            <span class="font-medium text-sm">Paiement en agence</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            <div class="p-6 md:p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Détails de l'article --}}
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2 transition-colors">Détails de la Commande</h3>
                        
                        @if($type === 'voiture')
                            <div class="flex gap-4 mb-4">
                                <img src="{{ $order->image ?? '' }}" class="w-24 h-24 object-cover rounded-lg bg-slate-100 dark:bg-slate-800 transition-colors" alt="Voiture">
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-lg transition-colors">{{ $order->marque }} {{ $order->modele }}</h4>
                                    <p class="text-slate-500 dark:text-slate-400 transition-colors">{{ $order->annee }}</p>
                                    <div class="mt-2 text-amber-500 font-bold">{{ number_format($order->prix, 0, ',', ' ') }} €</div>
                                </div>
                            </div>
                        @elseif($type === 'location')
                            <div class="flex gap-4 mb-4">
                                <img src="{{ $order->image ?? '' }}" class="w-24 h-24 object-cover rounded-lg bg-slate-100 dark:bg-slate-800 transition-colors" alt="Location">
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-lg transition-colors">{{ $order->marque }} {{ $order->modele }}</h4>
                                    <p class="text-slate-500 dark:text-slate-400 transition-colors">Du {{ \Carbon\Carbon::parse($order->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($order->date_fin)->format('d/m/Y') }}</p>
                                    <div class="mt-2 text-amber-500 font-bold">{{ number_format($order->montant_total, 0, ',', ' ') }} FCFA</div>
                                </div>
                            </div>
                        @elseif($type === 'piece')
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Pièce:</span>
                                    <span class="text-white font-bold">{{ $order->nom_piece ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Référence:</span>
                                    <span class="text-white">{{ $order->ref_piece ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Quantité:</span>
                                    <span class="text-white">{{ $order->quantite ?? 1 }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-slate-800">
                                    <span class="text-slate-400">Total:</span>
                                    <span class="text-amber-500 font-bold">{{ number_format($order->montant_total ?? 0, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                        @elseif($type === 'revision')
                            <div class="space-y-4">
                                {{-- Véhicule Info --}}
                                <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-800 transition-colors">
                                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1">
                                        <i data-lucide="car" class="w-3 h-3"></i>Véhicule
                                    </h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500 dark:text-slate-400">Marque/Modèle:</span>
                                            <span class="text-slate-900 dark:text-white font-bold">{{ $order->marque_vehicule }} {{ $order->modele_vehicule }}</span>
                                        </div>
                                        @if($order->annee_vehicule)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-slate-500 dark:text-slate-400">Année:</span>
                                                <span class="text-slate-900 dark:text-white">{{ $order->annee_vehicule }}</span>
                                            </div>
                                        @endif
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500 dark:text-slate-400">Immatriculation:</span>
                                            <span class="text-slate-900 dark:text-white font-mono bg-slate-100 dark:bg-slate-800 px-2 rounded">{{ $order->immatriculation ?? 'N/A' }}</span>
                                        </div>
                                        @if($order->kilometrage)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-slate-500 dark:text-slate-400">Kilométrage:</span>
                                                <span class="text-slate-900 dark:text-white">{{ number_format($order->kilometrage, 0, ',', ' ') }} km</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Prix Devis --}}
                                @if(!empty($order->montant_devis) && $order->montant_devis > 0)
                                    <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/20 dark:to-orange-950/20 rounded-lg border-2 border-amber-300 dark:border-amber-900/30">
                                        <div class="text-xs font-black uppercase tracking-wider text-amber-600 dark:text-amber-500 mb-2 flex items-center gap-1">
                                            <i data-lucide="dollar-sign" class="w-4 h-4"></i>Devis Estimatif
                                        </div>
                                        <div class="text-3xl font-black text-amber-600 dark:text-amber-500">
                                            {{ number_format($order->montant_devis, 0, ',', ' ') }} <span class="text-sm">FCFA</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-800">
                                        <div class="text-xs font-black uppercase tracking-wider text-slate-400 mb-2">Devis</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400 italic">En cours d'évaluation par nos techniciens</div>
                                    </div>
                                @endif

                                {{-- Problème --}}
                                <div class="p-4 bg-blue-50 dark:bg-blue-950/20 rounded-lg border border-blue-100 dark:border-blue-900/20">
                                    <div class="text-xs font-black uppercase tracking-wider text-blue-600 dark:text-blue-500 mb-2 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3 h-3"></i>Problème Signalé
                                    </div>
                                    <p class="text-sm text-blue-700 dark:text-blue-400">{{ $order->probleme_description ?? 'Non spécifié' }}</p>
                                </div>

                                {{-- Diagnostic --}}
                                @if(!empty($order->diagnostic) || !empty($order->diagnostic_technique))
                                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 rounded-lg border border-emerald-100 dark:border-emerald-900/20">
                                        <div class="text-xs font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-500 mb-2 flex items-center gap-1">
                                            <i data-lucide="clipboard-check" class="w-3 h-3"></i>Diagnostic Technique
                                        </div>
                                        <p class="text-sm text-emerald-700 dark:text-emerald-400">{{ $order->diagnostic_technique ?? $order->diagnostic }}</p>
                                    </div>
                                @endif

                                {{-- Interventions & Pièces --}}
                                @if(!empty($order->interventions_prevues) || !empty($order->pieces_necessaires))
                                    <div class="grid md:grid-cols-2 gap-4">
                                        @if(!empty($order->interventions_prevues))
                                            <div class="p-4 bg-purple-50 dark:bg-purple-950/20 rounded-lg border border-purple-100 dark:border-purple-900/20">
                                                <div class="text-xs font-black uppercase tracking-wider text-purple-600 dark:text-purple-500 mb-2 flex items-center gap-1">
                                                    <i data-lucide="list-checks" class="w-3 h-3"></i>Interventions Prévues
                                                </div>
                                                <p class="text-sm text-purple-700 dark:text-purple-400">{{ $order->interventions_prevues }}</p>
                                            </div>
                                        @endif

                                        @if(!empty($order->pieces_necessaires))
                                            <div class="p-4 bg-indigo-50 dark:bg-indigo-950/20 rounded-lg border border-indigo-100 dark:border-indigo-900/20">
                                                <div class="text-xs font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-500 mb-2 flex items-center gap-1">
                                                    <i data-lucide="package" class="w-3 h-3"></i>Pièces Nécessaires
                                                </div>
                                                <p class="text-sm text-indigo-700 dark:text-indigo-400">{{ $order->pieces_necessaires }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- Timeline --}}
                                <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-800">
                                    <div class="text-xs font-black uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-1">
                                        <i data-lucide="timeline" class="w-3 h-3"></i>Historique
                                    </div>
                                    <div class="space-y-4">
                                        {{-- Création --}}
                                        <div class="flex gap-3 relative">
                                            <div class="flex flex-col items-center">
                                                <div class="w-2.5 h-2.5 bg-slate-400 rounded-full z-10"></div>
                                                <div class="h-full w-0.5 bg-slate-200 dark:bg-slate-800 -mt-1"></div>
                                            </div>
                                            <div class="pb-2">
                                                <div class="text-xs font-bold text-slate-900 dark:text-white">Demande Créée</div>
                                                <div class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($order->date_demande ?? $order->created_at)->format('d/m/Y à H:i') }}</div>
                                            </div>
                                        </div>

                                        {{-- Diagnostic --}}
                                        @if(!empty($order->date_diagnostic) && $order->date_diagnostic)
                                            <div class="flex gap-3 relative">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-2.5 h-2.5 bg-purple-500 rounded-full z-10"></div>
                                                    <div class="h-full w-0.5 bg-slate-200 dark:bg-slate-800 -mt-1"></div>
                                                </div>
                                                <div class="pb-2">
                                                    <div class="text-xs font-bold text-purple-600 dark:text-purple-400">Diagnostic Effectué</div>
                                                    <div class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($order->date_diagnostic)->format('d/m/Y à H:i') }}</div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Devis --}}
                                        @if(!empty($order->date_devis) && $order->date_devis)
                                            <div class="flex gap-3 relative">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-2.5 h-2.5 bg-blue-500 rounded-full z-10"></div>
                                                    <div class="h-full w-0.5 bg-slate-200 dark:bg-slate-800 -mt-1"></div>
                                                </div>
                                                <div class="pb-2">
                                                    <div class="text-xs font-bold text-blue-600 dark:text-blue-400">Devis Envoyé</div>
                                                    <div class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($order->date_devis)->format('d/m/Y à H:i') }}</div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Statut Actuel --}}
                                        <div class="flex gap-3 relative">
                                            <div class="flex flex-col items-center">
                                                <div class="w-2.5 h-2.5 {{ $progress == 100 ? 'bg-emerald-500' : 'bg-amber-500' }} rounded-full z-10 animate-pulse"></div>
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold {{ $progress == 100 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                </div>
                                                <div class="text-[10px] text-slate-500">Dernière mise à jour : {{ $order->updated_at->format('d/m/Y à H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Notes si disponibles --}}
                                @if(!empty($order->notes))
                                    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-800">
                                        <div class="text-xs font-black uppercase tracking-wider text-slate-400 mb-2">Notes</div>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 italic">{{ $order->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Info Client --}}
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2 transition-colors">Informations Client</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 transition-colors">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 transition-colors">Nom Complet</p>
                                    <p class="text-slate-900 dark:text-white transition-colors">{{ $order->client_nom ?? 'Non renseigné' }}</p>
                                </div>
                            </div>

                            @if(!empty($order->client_email))
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 transition-colors">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 transition-colors">Email</p>
                                    <p class="text-slate-900 dark:text-white transition-colors">{{ $order->client_email }}</p>
                                </div>
                            </div>
                            @endif

                            @if(!empty($order->client_telephone))
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 transition-colors">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 transition-colors">Téléphone</p>
                                    <p class="text-slate-900 dark:text-white transition-colors">{{ $order->client_telephone }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-6 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl transition-all">
                            <div class="flex gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5"></i>
                                <p class="text-sm text-slate-700 dark:text-slate-300 transition-colors">
                                    Conservez précieusement votre numéro de tracking <strong class="text-slate-900 dark:text-white transition-colors">{{ $tracking }}</strong>. Il est le seul moyen d'accéder à ces informations.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-950 p-4 text-center border-t border-slate-100 dark:border-slate-800 transition-colors">
                <button onclick="window.print()" class="text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white text-sm flex items-center justify-center gap-2 mx-auto transition-colors">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Imprimer les détails de la commande
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
