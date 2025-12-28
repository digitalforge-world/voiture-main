@extends('layouts.admin')
@section('title', 'Facture ' . $invoice->invoice_number)
@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.invoices.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-xl transition"><i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i></a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase">{{ $invoice->invoice_number }}</h1>
                <p class="text-slate-500 dark:text-slate-400 font-bold mt-1 text-sm">Créée le {{ $invoice->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <a href="{{ route('admin.invoices.download', $invoice) }}" class="inline-flex items-center gap-3 px-6 py-3 bg-emerald-500 text-white font-black uppercase tracking-widest text-[10px] rounded-xl hover:bg-emerald-400 transition shadow-xl">
            <i data-lucide="download" class="w-4 h-4"></i>Télécharger PDF
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-3">Client</h3>
                <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl">
                    <div class="font-bold text-slate-900 dark:text-white">{{ $invoice->user->prenom }} {{ $invoice->user->nom }}</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $invoice->user->email }}</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">{{ $invoice->user->telephone }}</div>
                </div>
            </div>
            <div>
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-3">Détails</h3>
                <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-slate-600 dark:text-slate-400">Statut:</span><span class="font-bold text-slate-900 dark:text-white">{{ ucfirst($invoice->status) }}</span></div>
                    @if($invoice->due_date)<div class="flex justify-between text-sm"><span class="text-slate-600 dark:text-slate-400">Échéance:</span><span class="font-bold text-slate-900 dark:text-white">{{ $invoice->due_date->format('d/m/Y') }}</span></div>@endif
                    @if($invoice->paid_date)<div class="flex justify-between text-sm"><span class="text-slate-600 dark:text-slate-400">Payée le:</span><span class="font-bold text-emerald-500">{{ $invoice->paid_date->format('d/m/Y') }}</span></div>@endif
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 dark:border-slate-800 pt-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-4">Montant</h3>
            <div class="text-4xl font-black text-slate-900 dark:text-white">{{ number_format($invoice->amount_total, 2, ',', ' ') }} <span class="text-2xl text-amber-500">€</span></div>
        </div>

        <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST" class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl">
                        @foreach(['draft' => 'Brouillon', 'sent' => 'Envoyée', 'paid' => 'Payée', 'cancelled' => 'Annulée'] as $key => $label)
                            <option value="{{ $key }}" {{ $invoice->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Date de paiement</label>
                    <input type="date" name="paid_date" value="{{ $invoice->paid_date?->format('Y-m-d') }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl">
                </div>
            </div>
            <button type="submit" class="mt-4 w-full py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-widest rounded-xl transition">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
