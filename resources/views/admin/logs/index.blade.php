@extends('layouts.admin')
@section('title', 'Journal d\'activités - AutoImport Hub')
@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Audit Trail</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Journalisation granulaire des mutations et authentifications</p>
        </div>
        <div class="flex items-center gap-4">
             <button class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 transition bg-slate-900 rounded-2xl hover:bg-slate-800 duration-300 border border-white/5 shadow-xl">
                <i data-lucide="shield-alert" class="w-4 h-4 text-rose-500"></i> Alertes Sécurité
            </button>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="border overflow-hidden bg-slate-950/50 border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-2xl backdrop-blur-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-900/50 border-b border-white/5">
                <tr>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Action / Horodatage</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Opérateur</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Entité</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic">Impact</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 text-right italic">Détails</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($logs as $log)
                <tr class="group hover:bg-white/[0.02] transition duration-300">
                    <td class="px-8 py-6">
                        <div class="text-[10px] font-black text-white uppercase tracking-widest mb-1 italic">{{ $log->action }}</div>
                        <div class="text-[9px] text-slate-600 font-bold uppercase tracking-widest italic">{{ $log->date_log->format('d/m/Y H:i:s') }}</div>
                    </td>
                    <td class="px-8 py-6">
                        @if($log->user)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 border border-white/5 flex items-center justify-center text-[10px] font-black text-amber-500 uppercase italic">
                                {{ $log->user->prenom[0] }}{{ $log->user->nom[0] }}
                            </div>
                            <div class="text-[11px] font-black text-slate-300 italic">{{ $log->user->prenom }} {{ $log->user->nom }}</div>
                        </div>
                        @else
                        <span class="text-[10px] text-slate-600 font-black italic">SYSTÈME</span>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-[10px] font-black text-slate-400 uppercase italic tracking-widest">{{ $log->table_affectee ?? 'N/A' }}</span>
                        <div class="text-[9px] text-slate-600 italic">REF_ID: {{ $log->id_enregistrement ?? 'SYS' }}</div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $impactColor = match(true) {
                                strpos(strtolower($log->action), 'suppression') !== false || strpos(strtolower($log->action), 'delete') !== false => 'text-rose-500',
                                strpos(strtolower($log->action), 'creation') !== false || strpos(strtolower($log->action), 'create') !== false => 'text-emerald-500',
                                strpos(strtolower($log->action), 'modification') !== false || strpos(strtolower($log->action), 'update') !== false => 'text-blue-500',
                                default => 'text-slate-500',
                            };
                        @endphp
                        <span class="text-[10px] font-black uppercase italic {{ $impactColor }}">● {{ $log->action }}</span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <button onclick="openShowLogModal({{ json_encode($log->load('user')) }})" class="p-3 bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800 transition rounded-2xl border border-white/5">
                            <i data-lucide="fingerprint" class="w-4 h-4"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-8 py-20 text-center text-slate-700 italic font-black uppercase tracking-[0.2em] text-xs">Aucun log d'activité.</td></tr>
                @endforelse
            </tbody>
        </table>

         <!-- Pagination -->
         @if($logs->hasPages())
         <div class="px-8 py-6 bg-slate-900/30 border-t border-white/5">
             {{ $logs->links() }}
         </div>
         @endif
    </div>
</div>

<!-- Show Log Modal -->
<div id="showLogModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/95 backdrop-blur-2xl" onclick="closeModal('showLogModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-900 border border-white/10 w-full max-w-2xl shadow-2xl rounded-[5rem] rounded-tl-xl rounded-br-xl overflow-hidden animate-in fade-in zoom-in duration-300">
             <div class="p-16 border-b border-white/5 bg-slate-950">
                 <div class="flex justify-between items-start mb-10">
                    <span id="show_log_action" class="px-6 py-2 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-black uppercase tracking-[0.2em] italic"></span>
                    <button onclick="closeModal('showLogModal')" class="p-4 bg-white/5 text-slate-400 hover:text-white rounded-2xl transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                 </div>
                 <h2 class="text-4xl font-black text-white italic tracking-tighter uppercase leading-tight">Détails de <br> l'opération</h2>
             </div>

             <div class="p-16 space-y-12 bg-slate-900">
                <div class="grid grid-cols-2 gap-12">
                    <div>
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Agent Responsable</div>
                        <div id="show_log_user" class="text-sm font-black text-white italic"></div>
                    </div>
                    <div>
                        <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-4 italic">Horodatage Précis</div>
                        <div id="show_log_date" class="text-sm font-black text-white italic"></div>
                    </div>
                </div>

                <div class="p-10 bg-slate-950 rounded-[3rem] border border-white/5 shadow-inner">
                    <div class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-6 italic">Empreinte de la mutation</div>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center text-[10px] font-black italic">
                            <span class="text-slate-500 uppercase tracking-widest">Table Affectée</span>
                            <span id="show_log_table" class="text-amber-500"></span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black italic">
                            <span class="text-slate-500 uppercase tracking-widest">Identifiant Record</span>
                            <span id="show_log_id" class="text-white"></span>
                        </div>
                    </div>
                </div>

                <div class="pt-8 text-center">
                    <button onclick="closeModal('showLogModal')" class="px-12 py-5 bg-white text-slate-950 rounded-[2rem] text-[10px] font-black uppercase tracking-widest italic hover:bg-amber-500 transition duration-300 shadow-2xl">
                        Fermer le Rapport
                    </button>
                </div>
             </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openShowLogModal(log) {
        document.getElementById('show_log_action').innerText = log.action.toUpperCase();
        document.getElementById('show_log_user').innerText = log.user ? `${log.user.prenom} ${log.user.nom}` : 'SYSTEM_KERNEL';
        
        const dateObj = new Date(log.date_log);
        document.getElementById('show_log_date').innerText = dateObj.toLocaleString('fr-FR', { 
            day: '2-digit', month: 'long', year: 'numeric', 
            hour: '2-digit', minute: '2-digit', second: '2-digit' 
        }).toUpperCase();
        
        document.getElementById('show_log_table').innerText = (log.table_affectee || 'N/A').toUpperCase();
        document.getElementById('show_log_id').innerText = log.id_enregistrement || 'SYS_INTERNAL';

        openModal('showLogModal');
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('showLogModal');
        }
    });
</script>
@endsection
@endsection
