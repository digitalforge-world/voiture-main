@extends('layouts.admin')
@section('title', 'Factures')
@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-4 underline-offset-[-2px]">Factures PDF</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Gérez vos factures clients</p>
        </div>
        <a href="{{ route('admin.invoices.create') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-amber-500 text-slate-950 font-black uppercase tracking-widest text-[10px] rounded-xl hover:bg-amber-400 transition shadow-xl shadow-amber-900/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>Nouvelle Facture
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 px-2">
        @foreach(['draft' => ['Brouillons', 'file-edit', 'slate'], 'sent' => ['Envoyées', 'send', 'blue'], 'paid' => ['Payées', 'check-circle', 'emerald'], 'cancelled' => ['Annulées', 'x-circle', 'rose']] as $key => $info)
            <div class="group p-5 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-2xl hover:border-{{ $info[2] }}-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden">
                <div class="p-2.5 bg-{{ $info[2] }}-500/10 rounded-xl text-{{ $info[2] }}-600 dark:text-{{ $info[2] }}-500 w-fit mb-4"><i data-lucide="{{ $info[1] }}" class="w-5 h-5"></i></div>
                <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter">{{ $invoices->where('status', $key)->count() }}</div>
                <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic">{{ $info[0] }}</div>
            </div>
        @endforeach
    </div>

    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-3xl shadow-sm dark:shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Numéro</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Client</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Montant</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Échéance</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Statut</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($invoices as $invoice)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-500/10 rounded-lg"><i data-lucide="file-text" class="w-4 h-4 text-amber-500"></i></div>
                            <div>
                                <div class="text-sm font-black text-slate-900 dark:text-white">{{ $invoice->invoice_number }}</div>
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-600 mt-1 uppercase">{{ $invoice->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $invoice->user->prenom }} {{ $invoice->user->nom }}</div>
                        <div class="text-[9px] text-slate-400 dark:text-slate-600">{{ $invoice->user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-lg font-black text-slate-900 dark:text-white">{{ number_format($invoice->amount_total, 2, ',', ' ') }} <span class="text-xs text-amber-500">€</span></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php $statuses = ['draft' => ['Brouillon', 'slate'], 'sent' => ['Envoyée', 'blue'], 'paid' => ['Payée', 'emerald'], 'cancelled' => ['Annulée', 'rose']]; $s = $statuses[$invoice->status] ?? $statuses['draft']; @endphp
                        <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full bg-{{ $s[1] }}-500/10 text-{{ $s[1] }}-500 border-{{ $s[1] }}-500/20">{{ $s[0] }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="p-2 hover:bg-blue-500/10 rounded-lg transition text-slate-400 hover:text-blue-500"><i data-lucide="eye" class="w-4 h-4"></i></a>
                            <a href="{{ route('admin.invoices.download', $invoice) }}" class="p-2 hover:bg-emerald-500/10 rounded-lg transition text-slate-400 hover:text-emerald-500"><i data-lucide="download" class="w-4 h-4"></i></a>
                            <button onclick="confirmDeletion('{{ route('admin.invoices.destroy', $invoice) }}')" class="p-2 hover:bg-rose-500/10 rounded-lg transition text-slate-400 hover:text-rose-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-16 text-center"><div class="flex flex-col items-center gap-4"><div class="p-4 bg-slate-100 dark:bg-slate-900 rounded-full"><i data-lucide="file-text" class="w-8 h-8 text-slate-400"></i></div><div><h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Aucune facture</h3><p class="text-slate-500 dark:text-slate-400 text-sm">Créez votre première facture</p></div></div></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($invoices->hasPages())<div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">{{ $invoices->links() }}</div>@endif
    </div>
</div>
@endsection
