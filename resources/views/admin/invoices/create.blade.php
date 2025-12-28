@extends('layouts.admin')
@section('title', 'Nouvelle Facture')
@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.invoices.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-xl transition">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-4 underline-offset-[-2px]">Nouvelle Facture</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Générer une facture client</p>
        </div>
    </div>

    <form action="{{ route('admin.invoices.store') }}" method="POST" class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
        @csrf
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Client *</label>
                <select name="user_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none @error('user_id') border-rose-500 @enderror">
                    <option value="">Sélectionnez un client</option>
                    @foreach(\App\Models\User::where('role', 'client')->orderBy('prenom')->get() as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->prenom }} {{ $user->nom }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')<p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Montant Total (€) *</label>
                <input type="number" name="amount_total" value="{{ old('amount_total') }}" required step="0.01" min="0"
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none @error('amount_total') border-rose-500 @enderror"
                    placeholder="Ex: 1500.00">
                @error('amount_total')<p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Date d'échéance</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}"
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none @error('due_date') border-rose-500 @enderror">
                @error('due_date')<p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Statut *</label>
                <select name="status" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none @error('status') border-rose-500 @enderror">
                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="sent" {{ old('status') === 'sent' ? 'selected' : '' }}>Envoyée</option>
                    <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Payée</option>
                    <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
                @error('status')<p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <button type="submit" class="flex-1 py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-widest rounded-xl transition shadow-xl shadow-amber-900/20 flex items-center justify-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>Créer la facture
            </button>
            <a href="{{ route('admin.invoices.index') }}" class="flex-1 py-4 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-900 dark:text-white text-xs font-black uppercase tracking-widest rounded-xl transition text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
