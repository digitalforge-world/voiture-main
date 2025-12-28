@extends('layouts.admin')
@section('title', 'Ticket #' . $ticket->id)
@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tickets.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-xl transition"><i data-lucide="arrow-left" class="w-5 h-5 text-slate-500"></i></a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase">Ticket #{{ $ticket->id }}</h1>
                <p class="text-slate-500 dark:text-slate-400 font-bold mt-1 text-sm">{{ $ticket->subject }}</p>
            </div>
        </div>
        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="flex items-center gap-3">
            @csrf @method('PUT')
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl text-xs font-bold">
                @foreach(['open' => 'Ouvert', 'answered' => 'Répondu', 'customer_reply' => 'Client a répondu', 'resolved' => 'Résolu', 'closed' => 'Fermé'] as $key => $label)
                    <option value="{{ $key }}" {{ $ticket->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="priority" onchange="this.form.submit()" class="px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl text-xs font-bold">
                @foreach(['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'urgent' => 'Urgente'] as $key => $label)
                    <option value="{{ $key }}" {{ $ticket->priority === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div class="w-12 h-12 rounded-full bg-amber-500/10 flex items-center justify-center font-black text-amber-500 text-lg">{{ substr($ticket->user->prenom, 0, 1) }}</div>
            <div>
                <div class="text-sm font-black text-slate-900 dark:text-white">{{ $ticket->user->prenom }} {{ $ticket->user->nom }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $ticket->user->email }}</div>
            </div>
        </div>

        <div class="space-y-6">
            @foreach($ticket->messages as $message)
                <div class="flex gap-4 {{ $message->is_internal_note ? 'bg-blue-500/5 border border-blue-500/20 rounded-xl p-4' : '' }}">
                    <div class="w-10 h-10 rounded-full bg-{{ $message->user_id === $ticket->user_id ? 'amber' : 'emerald' }}-500/10 flex items-center justify-center font-bold text-{{ $message->user_id === $ticket->user_id ? 'amber' : 'emerald' }}-500 text-sm shrink-0">{{ substr($message->user->prenom ?? 'A', 0, 1) }}</div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $message->user->prenom ?? 'Admin' }} {{ $message->user->nom ?? '' }}</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-600 uppercase tracking-wider">{{ $message->created_at->diffForHumans() }}</span>
                            @if($message->is_internal_note)
                                <span class="px-2 py-0.5 bg-blue-500/20 text-blue-500 text-[9px] font-black uppercase tracking-widest rounded-full">Note interne</span>
                            @endif
                        </div>
                        <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $message->message }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" class="bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        @csrf
        <label class="block text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-3">Votre réponse</label>
        <textarea name="message" rows="5" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-amber-500 focus:outline-none mb-4" placeholder="Tapez votre réponse..."></textarea>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_internal_note" id="is_internal_note" class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-blue-500 focus:ring-blue-500">
                <label for="is_internal_note" class="text-xs font-bold text-slate-600 dark:text-slate-400 cursor-pointer">Note interne (invisible au client)</label>
            </div>
            <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-widest rounded-xl transition shadow-xl shadow-amber-900/20 flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>Envoyer
            </button>
        </div>
    </form>
</div>
@endsection
