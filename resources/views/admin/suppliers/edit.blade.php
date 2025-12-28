@extends('layouts.admin')
@section('title', 'Modifier Fournisseur')
@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.suppliers.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-xl transition"><i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i></a>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-4 underline-offset-[-2px]">Modifier Fournisseur</h1>
    </div>
    <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
        @csrf @method('PUT')
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-black uppercase tracking-widest text-slate-500 mb-2">Nom *</label><input type="text" name="name" value="{{ old('name', $supplier->name) }}" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none"></div>
                <div><label class="block text-sm font-black uppercase tracking-widest text-slate-500 mb-2">Type *</label><select name="type" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none">
                    @foreach(['dealer' => 'Concessionnaire', 'auction' => 'Enchère', 'logistics' => 'Logistique', 'service' => 'Service', 'other' => 'Autre'] as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $supplier->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-black uppercase tracking-widest text-slate-500 mb-2">Contact</label><input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none"></div>
                <div><label class="block text-sm font-black uppercase tracking-widest text-slate-500 mb-2">Pays</label><input type="text" name="country" value="{{ old('country', $supplier->country) }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-black uppercase tracking-widest text-slate-500 mb-2">Email</label><input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none"></div>
                <div><label class="block text-sm font-black uppercase tracking-widest text-slate-500 mb-2">Téléphone</label><input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none"></div>
            </div>
            <div><label class="block text-sm font-black uppercase tracking-widest text-slate-500 mb-2">Adresse</label><textarea name="address" rows="3" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none">{{ old('address', $supplier->address) }}</textarea></div>
            <div><label class="block text-sm font-black uppercase tracking-widest text-slate-500 mb-2">Notes</label><textarea name="notes" rows="4" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none">{{ old('notes', $supplier->notes) }}</textarea></div>
        </div>
        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <button type="submit" class="flex-1 py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-widest rounded-xl transition shadow-xl shadow-amber-900/20 flex items-center justify-center gap-2"><i data-lucide="save" class="w-4 h-4"></i>Enregistrer</button>
            <a href="{{ route('admin.suppliers.index') }}" class="flex-1 py-4 bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-black uppercase tracking-widest rounded-xl transition text-center">Annuler</a>
        </div>
    </form>
</div>
@endsection
