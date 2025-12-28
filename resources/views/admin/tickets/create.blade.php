@extends('layouts.admin')
@section('title', 'Nouveau Ticket')
@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tickets.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-xl transition">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-4 underline-offset-[-2px]">Nouveau Ticket</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Créer une demande de support</p>
        </div>
    </div>

    <form action="{{ route('admin.tickets.store') }}" method="POST" class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
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
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Sujet *</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required 
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none @error('subject') border-rose-500 @enderror"
                    placeholder="Ex: Problème avec ma commande">
                @error('subject')<p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Priorité *</label>
                <select name="priority" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none @error('priority') border-rose-500 @enderror">
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Moyenne</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Haute</option>
                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgente</option>
                </select>
                @error('priority')<p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Message initial *</label>
                <textarea name="message" rows="6" required 
                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none @error('message') border-rose-500 @enderror"
                    placeholder="Décrivez le problème...">{{ old('message') }}</textarea>
                @error('message')<p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
            <button type="submit" class="flex-1 py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-widest rounded-xl transition shadow-xl shadow-amber-900/20 flex items-center justify-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>Créer le ticket
            </button>
            <a href="{{ route('admin.tickets.index') }}" class="flex-1 py-4 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-900 dark:text-white text-xs font-black uppercase tracking-widest rounded-xl transition text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
