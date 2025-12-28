<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1e293b; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 3px solid #f59e0b; padding-bottom: 20px; }
        .company { font-size: 24px; font-weight: bold; color: #f59e0b; }
        .invoice-number { font-size: 18px; font-weight: bold; margin: 20px 0; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { padding: 8px; }
        .info-box { background: #f1f5f9; padding: 15px; border-radius: 8px; }
        .items-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        .items-table th { background: #f59e0b; color: #0f172a; padding: 12px; text-align: left; font-weight: bold; }
        .items-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
        .total { font-size: 20px; font-weight: bold; text-align: right; margin-top: 20px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">AUTOIMPORT HUB</div>
        <div style="font-size: 12px; color: #64748b; margin-top: 10px;">Votre partenaire automobile de confiance</div>
    </div>

    <div class="invoice-number">FACTURE {{ $invoice->invoice_number }}</div>

    <table class="info-table">
        <tr>
            <td width="50%" valign="top">
                <div class="info-box">
                    <strong>FACTURÉ À:</strong><br>
                    {{ $invoice->user->prenom }} {{ $invoice->user->nom }}<br>
                    {{ $invoice->user->email }}<br>
                    {{ $invoice->user->telephone }}<br>
                    @if($invoice->user->adresse){{ $invoice->user->adresse }}<br>@endif
                    {{ $invoice->user->ville }}, {{ $invoice->user->pays }}
                </div>
            </td>
            <td width="50%" valign="top">
                <div class="info-box">
                    <strong>DÉTAILS FACTURE:</strong><br>
                    Date: {{ $invoice->created_at->format('d/m/Y') }}<br>
                    @if($invoice->due_date)Échéance: {{ $invoice->due_date->format('d/m/Y') }}<br>@endif
                    @if($invoice->paid_date)Payée le: {{ $invoice->paid_date->format('d/m/Y') }}<br>@endif
                    Statut: <strong>{{ strtoupper($invoice->status) }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>DESCRIPTION</th>
                <th width="20%">QUANTITÉ</th>
                <th width="20%">PRIX UNITAIRE</th>
                <th width="20%">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($invoice->related_type)
                        {{ $invoice->related_type }} #{{ $invoice->related_id }}
                    @else
                        Service AutoImport Hub
                    @endif
                </td>
                <td>1</td>
                <td>{{ number_format($invoice->amount_total, 2, ',', ' ') }} €</td>
                <td>{{ number_format($invoice->amount_total, 2, ',', ' ') }} €</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <div style="margin-bottom: 10px;">Sous-total: {{ number_format($invoice->amount_total, 2, ',', ' ') }} €</div>
        <div style="color: #f59e0b;">TOTAL: {{ number_format($invoice->amount_total, 2, ',', ' ') }} €</div>
    </div>

    <div class="footer">
        <p><strong>Merci pour votre confiance !</strong></p>
        <p>AutoImport Hub - contact@autoimport.com - +33 1 23 45 67 89</p>
        <p style="font-size: 10px; margin-top: 20px;">Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
