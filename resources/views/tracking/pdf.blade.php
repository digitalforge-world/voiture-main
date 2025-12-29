<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $tracking }} - AutoImport Hub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #1e293b;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #f59e0b;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #f59e0b;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 10px;
        }
        .tracking-number {
            font-size: 16px;
            font-weight: bold;
            color: #f59e0b;
            font-family: monospace;
            letter-spacing: 2px;
            margin-top: 5px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #e2e8f0;
        }
        .info-label {
            background-color: #f1f5f9;
            font-weight: bold;
            width: 35%;
        }
        .info-value {
            background-color: #fff;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f59e0b;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .section-content {
            padding: 12px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .status-en_attente { background-color: #fef3c7; color: #92400e; }
        .status-diagnostic_en_cours { background-color: #fed7aa; color: #9a3412; }
        .status-devis_envoye { background-color: #dbeafe; color: #1e40af; }
        .status-accepte { background-color: #e9d5ff; color: #6b21a8; }
        .status-en_intervention { background-color: #c7d2fe; color: #3730a3; }
        .status-termine { background-color: #d1fae5; color: #065f46; }
        .status-annule { background-color: #fecaca; color: #991b1b; }
        .price-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border: 2px solid #f59e0b;
            padding: 15px;
            text-align: center;
            margin: 15px 0;
        }
        .price-label {
            font-size: 10px;
            color: #92400e;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .price-amount {
            font-size: 24px;
            font-weight: bold;
            color: #b45309;
        }
        .timeline {
            padding: 10px;
        }
        .timeline-item {
            padding: 8px 0;
            border-left: 3px solid #e2e8f0;
            padding-left: 15px;
            margin-bottom: 8px;
        }
        .timeline-item.completed {
            border-left-color: #10b981;
        }
        .timeline-date {
            font-weight: bold;
            color: #10b981;
            font-size: 10px;
        }
        .timeline-label {
            color: #64748b;
            font-size: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 9px;
        }
        .grid-2 {
            display: table;
            width: 100%;
        }
        .grid-col {
            display: table-cell;
            width: 50%;
            padding: 5px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="company-name">AutoImport Hub</div>
        <div style="font-size: 10px; color: #64748b;">Import & Vente de Véhicules Premium</div>
        <div class="document-title">
            @switch($type)
                @case('voiture') COMMANDE VÉHICULE @break
                @case('location') LOCATION DE VÉHICULE @break
                @case('piece') COMMANDE PIÈCE DÉTACHÉE @break
                @case('revision') RÉVISION MÉCANIQUE @break
            @endswitch
        </div>
        <div class="tracking-number">{{ $tracking }}</div>
        <div style="margin-top: 10px;">
            <span class="status-badge status-{{ $order->statut ?? 'en_attente' }}">
                {{ str_replace('_', ' ', $order->statut ?? 'en_attente') }}
            </span>
        </div>
    </div>

    {{-- Date --}}
    <div style="text-align: right; margin-bottom: 20px; color: #64748b; font-size: 10px;">
        @php
            $createdDate = match($type) {
                'revision' => $order->date_demande ?? $order->created_at ?? now(),
                default => $order->created_at ?? $order->date_demande ?? now()
            };
        @endphp
        Généré le {{ now()->format('d/m/Y à H:i') }} | Créé le {{ \Carbon\Carbon::parse($createdDate)->format('d/m/Y') }}
    </div>

    @if($type === 'revision')
        {{-- Informations Client --}}
        <div class="section">
            <div class="section-title">👤 Informations Client</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell info-label">Nom</div>
                    <div class="info-cell info-value">{{ $order->client_nom ?? 'Non renseigné' }}</div>
                </div>
                @if(!empty($order->client_telephone))
                <div class="info-row">
                    <div class="info-cell info-label">Téléphone</div>
                    <div class="info-cell info-value">{{ $order->client_telephone }}</div>
                </div>
                @endif
                @if(!empty($order->client_email))
                <div class="info-row">
                    <div class="info-cell info-label">Email</div>
                    <div class="info-cell info-value">{{ $order->client_email }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Informations Véhicule --}}
        <div class="section">
            <div class="section-title">🚗 Véhicule</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell info-label">Marque / Modèle</div>
                    <div class="info-cell info-value"><strong>{{ $order->marque_vehicule }} {{ $order->modele_vehicule }}</strong></div>
                </div>
                @if($order->annee_vehicule)
                <div class="info-row">
                    <div class="info-cell info-label">Année</div>
                    <div class="info-cell info-value">{{ $order->annee_vehicule }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-cell info-label">Immatriculation</div>
                    <div class="info-cell info-value"><strong>{{ $order->immatriculation ?? 'N/A' }}</strong></div>
                </div>
                @if($order->kilometrage)
                <div class="info-row">
                    <div class="info-cell info-label">Kilométrage</div>
                    <div class="info-cell info-value">{{ number_format($order->kilometrage, 0, ',', ' ') }} km</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Prix du Devis --}}
        @if(!empty($order->montant_devis) && $order->montant_devis > 0)
            <div class="price-box">
                <div class="price-label">💰 Devis Estimatif</div>
                <div class="price-amount">{{ number_format($order->montant_devis, 0, ',', ' ') }} FCFA</div>
            </div>
        @endif

        {{-- Problème Signalé --}}
        <div class="section">
            <div class="section-title">⚠️ Problème Signalé</div>
            <div class="section-content">
                {{ $order->probleme_description ?? 'Non spécifié' }}
            </div>
        </div>

        {{-- Diagnostic --}}
        @if(!empty($order->diagnostic) || !empty($order->diagnostic_technique))
            <div class="section">
                <div class="section-title">✅ Diagnostic Technique</div>
                <div class="section-content">
                    {{ $order->diagnostic_technique ?? $order->diagnostic }}
                </div>
            </div>
        @endif

        {{-- Interventions & Pièces --}}
        @if(!empty($order->interventions_prevues) || !empty($order->pieces_necessaires))
            <div class="grid-2">
                @if(!empty($order->interventions_prevues))
                    <div class="grid-col">
                        <div class="section">
                            <div class="section-title">🔧 Interventions Prévues</div>
                            <div class="section-content">
                                {{ $order->interventions_prevues }}
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($order->pieces_necessaires))
                    <div class="grid-col">
                        <div class="section">
                            <div class="section-title">📦 Pièces Nécessaires</div>
                            <div class="section-content">
                                {{ $order->pieces_necessaires }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Timeline --}}
        <div class="section">
            <div class="section-title">📅 Historique</div>
            <div class="timeline">
                <div class="timeline-item completed">
                    <div class="timeline-label">Demande créée</div>
                    <div class="timeline-date">{{ \Carbon\Carbon::parse($order->date_demande ?? $order->created_at)->format('d/m/Y à H:i') }}</div>
                </div>
                @if(!empty($order->date_diagnostic))
                <div class="timeline-item completed">
                    <div class="timeline-label">Diagnostic effectué</div>
                    <div class="timeline-date">{{ \Carbon\Carbon::parse($order->date_diagnostic)->format('d/m/Y à H:i') }}</div>
                </div>
                @endif
                @if(!empty($order->date_devis))
                <div class="timeline-item completed">
                    <div class="timeline-label">Devis envoyé</div>
                    <div class="timeline-date">{{ \Carbon\Carbon::parse($order->date_devis)->format('d/m/Y à H:i') }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Notes --}}
        @if(!empty($order->notes))
            <div class="section">
                <div class="section-title">📝 Notes</div>
                <div class="section-content">
                    {{ $order->notes }}
                </div>
            </div>
        @endif
    @endif

    {{-- Footer --}}
    <div class="footer">
        <div style="font-weight: bold; margin-bottom: 5px;">AutoImport Hub</div>
        <div>Ce document a été généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</div>
        <div style="margin-top: 5px;">Pour toute question, contactez-nous avec votre numéro de tracking: {{ $tracking }}</div>
    </div>
</body>
</html>
