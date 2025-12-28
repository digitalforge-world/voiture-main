@extends('layouts.admin')

@section('title', 'Modifier le Coupon')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.coupons.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-xl transition">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-4 underline-offset-[-2px] transition-colors">Modifier le Coupon</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Code: {{ $coupon->code }}</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm dark:shadow-none transition-colors">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            {{-- Code --}}
            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Code Promo *</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required 
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none transition uppercase font-bold tracking-widest @error('code') border-rose-500 @enderror">
                @error('code')
                    <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Type & Value --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Type de Réduction *</label>
                    <select name="type" required 
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none transition @error('type') border-rose-500 @enderror">
                        <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Montant Fixe (€)</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Valeur *</label>
                    <input type="number" name="value" value="{{ old('value', $coupon->value) }}" required step="0.01" min="0"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none transition @error('value') border-rose-500 @enderror">
                    @error('value')
                        <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Usage Stats --}}
            <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-blue-500"></i>
                    <span class="text-sm font-bold text-blue-500 uppercase tracking-wider">Statistiques d'utilisation</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400">
                    Ce coupon a été utilisé <strong>{{ $coupon->current_uses }} fois</strong>
                    @if($coupon->max_uses)
                        sur un maximum de {{ $coupon->max_uses }} utilisations
                    @endif
                </p>
            </div>

            {{-- Max Uses --}}
            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Nombre d'utilisations maximum</label>
                <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" min="1"
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none transition @error('max_uses') border-rose-500 @enderror">
                @error('max_uses')
                    <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-[10px] text-slate-400 dark:text-slate-600 uppercase tracking-wider">Laissez vide pour un usage illimité</p>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Date de début</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none transition @error('starts_at') border-rose-500 @enderror">
                    @error('starts_at')
                        <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Date d'expiration</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none transition @error('expires_at') border-rose-500 @enderror">
                    @error('expires_at')
                        <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Active Toggle --}}
            <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-700">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                    class="w-5 h-5 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-amber-500 focus:ring-amber-500 focus:ring-offset-white dark:focus:ring-offset-slate-950 transition-colors">
                <label for="is_active" class="text-sm font-bold text-slate-900 dark:text-white cursor-pointer">Coupon actif</label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <button type="submit" class="flex-1 py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-widest rounded-xl transition shadow-xl shadow-amber-900/20 flex items-center justify-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                Enregistrer les modifications
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="flex-1 py-4 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-900 dark:text-white text-xs font-black uppercase tracking-widest rounded-xl transition text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
