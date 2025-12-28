@extends('layouts.app')

@section('title', 'Détails de la Commande ' . $tracking)

@section('content')
<div class="min-h-screen pt-24 pb-12 bg-slate-950">
    <div class="container px-4 mx-auto max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('tracking.index') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-amber-500 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Retour à la recherche
            </a>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
            {{-- Header --}}
            <div class="p-6 md:p-8 border-b border-slate-800 bg-slate-900/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl font-bold text-white">{{ $tracking }}</h1>
                            @php
                                $statusColors = [
                                    'en_attente' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'validee' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'sm' => 'bg-purple-500/10 text-purple-500 border-purple-500/20', // Sur mer pour voiture
                                    'livree' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'terminee' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20', // Pour location/révision
                                    'annulee' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                ];
                                $status = $order->statut ?? 'en_attente';
                                $colorClass = $statusColors[$status] ?? $statusColors['en_attente'];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }} uppercase">
                                {{ str_replace('_', ' ', $status) }}
                            </span>
                        </div>
                        <p class="text-slate-400">
                            Commande créée le {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-sm text-slate-400 mb-1">Type de Service</div>
                        <div class="font-bold text-white uppercase tracking-wide">
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
            <div class="py-8 px-6 md:px-12 bg-slate-950/30 border-b border-slate-800">
                <div class="relative">
                    {{-- Barre de fond --}}
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-800 -translate-y-1/2 rounded-full"></div>
                    
                    {{-- Barre de progression active --}}
                    @php
                        $progress = match($status) {
                            'en_attente' => 10,
                            'validee' => 40,
                            'sm' => 70, // Sur mer
                            'en_cours' => 60, // Révision/Location
                            'livree', 'terminee' => 100,
                            default => 0
                        };
                    @endphp
                    <div class="absolute top-1/2 left-0 h-1 bg-amber-500 -translate-y-1/2 rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>

                    {{-- Étapes --}}
                    <div class="relative flex justify-between">
                        @foreach(['Reçu', 'Validé', 'En cours', 'Terminé'] as $index => $step)
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-4 {{ $progress >= ($index * 33 + 10) ? 'bg-amber-500 border-slate-900 text-slate-950' : 'bg-slate-900 border-slate-800 text-slate-500' }}">
                                {{ $index + 1 }}
                            </div>
                            <span class="text-xs font-medium {{ $progress >= ($index * 33 + 10) ? 'text-white' : 'text-slate-500' }}">{{ $step }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Content Details --}}
            <div class="p-6 md:p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Détails de l'article --}}
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4 border-b border-slate-800 pb-2">Détails de la Commande</h3>
                        
                        @if($type === 'voiture')
                            <div class="flex gap-4 mb-4">
                                <img src="{{ $order->image ?? '' }}" class="w-24 h-24 object-cover rounded-lg bg-slate-800" alt="Voiture">
                                <div>
                                    <h4 class="font-bold text-white text-lg">{{ $order->marque }} {{ $order->modele }}</h4>
                                    <p class="text-slate-400">{{ $order->annee }}</p>
                                    <div class="mt-2 text-amber-500 font-bold">{{ number_format($order->prix, 0, ',', ' ') }} €</div>
                                </div>
                            </div>
                        @elseif($type === 'location')
                            <div class="flex gap-4 mb-4">
                                <img src="{{ $order->image ?? '' }}" class="w-24 h-24 object-cover rounded-lg bg-slate-800" alt="Location">
                                <div>
                                    <h4 class="font-bold text-white text-lg">{{ $order->marque }} {{ $order->modele }}</h4>
                                    <p class="text-slate-400">Du {{ \Carbon\Carbon::parse($order->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($order->date_fin)->format('d/m/Y') }}</p>
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
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Véhicule:</span>
                                    <span class="text-white">{{ $order->marque_vehicule }} {{ $order->modele_vehicule }} ({{ $order->annee_vehicule }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Immatriculation:</span>
                                    <span class="text-white font-mono bg-slate-800 px-2 rounded">{{ $order->immatriculation ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Date de demande:</span>
                                    <span class="text-white">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span>
                                </div>
                                <div class="mt-4">
                                    <span class="block text-slate-400 text-sm mb-1">Description du problème:</span>
                                    <p class="text-slate-300 bg-slate-900 p-3 rounded-lg text-sm border border-slate-800">{{ $order->probleme_description ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Info Client --}}
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4 border-b border-slate-800 pb-2">Informations Client</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Nom Complet</p>
                                    <p class="text-white">{{ $order->client_nom ?? 'Non renseigné' }}</p>
                                </div>
                            </div>

                            @if(!empty($order->client_email))
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Email</p>
                                    <p class="text-white">{{ $order->client_email }}</p>
                                </div>
                            </div>
                            @endif

                            @if(!empty($order->client_telephone))
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-400">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Téléphone</p>
                                    <p class="text-white">{{ $order->client_telephone }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-6 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                            <div class="flex gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5"></i>
                                <p class="text-sm text-slate-300">
                                    Conservez précieusement votre numéro de tracking <strong class="text-white">{{ $tracking }}</strong>. Il est le seul moyen d'accéder à ces informations.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-950 p-4 text-center border-t border-slate-800">
                <button onclick="window.print()" class="text-slate-400 hover:text-white text-sm flex items-center justify-center gap-2 mx-auto transition-colors">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Imprimer les détails de la commande
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
