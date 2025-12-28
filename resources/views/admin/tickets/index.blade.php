@extends('layouts.admin')
@section('title', 'Support Client')
@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-4 underline-offset-[-2px]">Support Client</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Gérez les demandes d'assistance</p>
        </div>
        <a href="{{ route('admin.tickets.create') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-amber-500 text-slate-950 font-black uppercase tracking-widest text-[10px] rounded-xl hover:bg-amber-400 transition shadow-xl shadow-amber-900/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>Nouveau Ticket
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 px-2">
        @foreach(['open' => ['Ouverts', 'circle-dot', 'amber'], 'answered' => ['Répondus', 'check-circle', 'emerald'], 'resolved' => ['Résolus', 'check-check', 'blue'], 'urgent' => ['Urgents', 'alert-circle', 'rose']] as $key => $info)
            <div class="group p-5 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-2xl hover:border-{{ $info[2] }}-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden">
                <div class="p-2.5 bg-{{ $info[2] }}-500/10 rounded-xl text-{{ $info[2] }}-600 dark:text-{{ $info[2] }}-500 w-fit mb-4"><i data-lucide="{{ $info[1] }}" class="w-5 h-5"></i></div>
                <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter">{{ $key === 'urgent' ? $tickets->where('priority', 'urgent')->count() : $tickets->where('status', $key)->count() }}</div>
                <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic">{{ $info[0] }}</div>
            </div>
        @endforeach
    </div>

    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-3xl shadow-sm dark:shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Ticket</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Client</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Priorité</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Statut</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($tickets as $ticket)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-500/10 rounded-lg"><i data-lucide="message-circle" class="w-4 h-4 text-amber-500"></i></div>
                            <div>
                                <div class="text-sm font-black text-slate-900 dark:text-white">#{{ $ticket->id }} - {{ $ticket->subject }}</div>
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-600 mt-1 uppercase">{{ $ticket->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $ticket->user->prenom }} {{ $ticket->user->nom }}</div>
                        <div class="text-[9px] text-slate-400 dark:text-slate-600">{{ $ticket->user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php $priorities = ['low' => ['Basse', 'slate'], 'medium' => ['Moyenne', 'blue'], 'high' => ['Haute', 'amber'], 'urgent' => ['Urgente', 'rose']]; $p = $priorities[$ticket->priority] ?? $priorities['low']; @endphp
                        <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full bg-{{ $p[1] }}-500/10 text-{{ $p[1] }}-500 border-{{ $p[1] }}-500/20">{{ $p[0] }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php $statuses = ['open' => ['Ouvert', 'amber'], 'answered' => ['Répondu', 'emerald'], 'customer_reply' => ['Client', 'blue'], 'resolved' => ['Résolu', 'purple'], 'closed' => ['Fermé', 'slate']]; $s = $statuses[$ticket->status] ?? $statuses['open']; @endphp
                        <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full bg-{{ $s[1] }}-500/10 text-{{ $s[1] }}-500 border-{{ $s[1] }}-500/20">
                            @if($ticket->status === 'open')<span class="w-1.5 h-1.5 bg-{{ $s[1] }}-500 rounded-full mr-2 animate-pulse"></span>@endif{{ $s[0] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="p-2 hover:bg-amber-500/10 rounded-lg transition text-slate-400 hover:text-amber-500"><i data-lucide="eye" class="w-4 h-4"></i></a>
                            <button onclick="confirmDeletion('{{ route('admin.tickets.destroy', $ticket) }}')" class="p-2 hover:bg-rose-500/10 rounded-lg transition text-slate-400 hover:text-rose-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-16 text-center"><div class="flex flex-col items-center gap-4"><div class="p-4 bg-slate-100 dark:bg-slate-900 rounded-full"><i data-lucide="message-circle-question" class="w-8 h-8 text-slate-400"></i></div><div><h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Aucun ticket</h3><p class="text-slate-500 dark:text-slate-400 text-sm">Tous les clients sont satisfaits !</p></div></div></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($tickets->hasPages())<div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">{{ $tickets->links() }}</div>@endif
    </div>
</div>
@endsection
