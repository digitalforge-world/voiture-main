@extends('layouts.admin')
@section('title', 'Journal d\'activités - AutoImport Hub')
@section('content')
<div class="space-y-8">
 
    <!-- Server Log Interface -->
    <div class="space-y-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Audit Trail</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Journalisation granulaire des mutations et authentifications</p>
        </div>
        <!-- Terminal Header & Filters -->
        <div class="bg-slate-900 border border-slate-800 rounded-t-xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                </div>
                <div class="font-mono text-xs text-slate-400">root@auto-import-hub:~/logs# audit_trail.log</div>
            </div>

            <form method="GET" class="flex flex-wrap gap-2 items-center">
                <div class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="grep 'keyword'..." class="bg-slate-950 text-emerald-500 font-mono text-xs border border-slate-800 rounded px-3 py-1.5 w-64 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 transition-all placeholder:text-slate-700">
                </div>
                <input type="date" name="date_start" value="{{ request('date_start') }}" class="bg-slate-950 text-slate-400 font-mono text-xs border border-slate-800 rounded px-2 py-1.5 focus:outline-none focus:border-slate-600">
                <span class="text-slate-600 font-mono text-xs">-</span>
                <input type="date" name="date_end" value="{{ request('date_end') }}" class="bg-slate-950 text-slate-400 font-mono text-xs border border-slate-800 rounded px-2 py-1.5 focus:outline-none focus:border-slate-600">
                
                <button type="submit" class="bg-emerald-900/30 text-emerald-500 border border-emerald-900/50 hover:bg-emerald-900/50 px-3 py-1.5 rounded font-mono text-xs transition-colors">EXECUTE</button>
                @if(request()->anyFilled(['search', 'date_start', 'date_end']))
                <a href="{{ route('admin.logs') }}" class="bg-rose-900/30 text-rose-500 border border-rose-900/50 hover:bg-rose-900/50 px-3 py-1.5 rounded font-mono text-xs transition-colors">RESET</a>
                @endif
            </form>
        </div>

        <!-- Terminal Console Output -->
        <div class="bg-slate-950 border border-slate-800 rounded-b-xl overflow-hidden shadow-2xl font-mono text-[11px] md:text-xs">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-900/50 text-slate-500 border-b border-slate-800">
                        <tr>
                            <th class="px-4 py-2 w-48 font-normal border-r border-slate-800">TIMESTAMP</th>
                            <th class="px-4 py-2 w-32 font-normal border-r border-slate-800">LEVEL/ACTION</th>
                            <th class="px-4 py-2 w-48 font-normal border-r border-slate-800">OPERATOR</th>
                            <th class="px-4 py-2 w-32 font-normal border-r border-slate-800">ENTITY</th>
                            <th class="px-4 py-2 font-normal">DETAILS_PREVIEW</th>
                            <th class="px-2 py-2 w-10 text-center">CMD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-300">
                        @forelse($logs as $log)
                        @php
                            $actionLower = strtolower($log->action);
                            $colorClass = match(true) {
                                str_contains($actionLower, 'suppr') || str_contains($actionLower, 'delete') => 'text-rose-500',
                                str_contains($actionLower, 'creat') => 'text-emerald-500',
                                str_contains($actionLower, 'modif') || str_contains($actionLower, 'update') => 'text-amber-500',
                                str_contains($actionLower, 'login') => 'text-blue-400',
                                default => 'text-slate-400'
                            };
                            
                            // Try to format details for preview
                            $detailsPreview = '';
                            if($log->details) {
                                $decoded = is_string($log->details) ? json_decode($log->details, true) : $log->details;
                                if(is_array($decoded)) {
                                     $detailsPreview = json_encode($decoded);
                                     if(strlen($detailsPreview) > 80) $detailsPreview = substr($detailsPreview, 0, 80) . '...';
                                } else {
                                    $detailsPreview = (string)$log->details;
                                }
                            }
                        @endphp
                        <tr class="hover:bg-slate-900/50 transition-colors group">
                            <td class="px-4 py-1.5 border-r border-slate-800/50 whitespace-nowrap text-slate-500">
                                {{ $log->date_action?->format('Y-m-d H:i:s') ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-1.5 border-r border-slate-800/50 whitespace-nowrap {{ $colorClass }}">
                                [{{ strtoupper(substr($log->action, 0, 4)) }}] {{ $log->action }}
                            </td>
                            <td class="px-4 py-1.5 border-r border-slate-800/50 whitespace-nowrap text-slate-400">
                                @if($log->user)
                                    <span class="text-blue-400">user:</span>{{ $log->user->prenom }}_{{ $log->user->nom }}
                                @else
                                    <span class="text-amber-600">guest:</span>{{ \Illuminate\Support\Str::slug($log->operator_name) }}
                                @endif
                            </td>
                            <td class="px-4 py-1.5 border-r border-slate-800/50 whitespace-nowrap text-slate-400">
                                {{ $log->table_concernee }}:{{ $log->enregistrement_id }}
                            </td>
                            <td class="px-4 py-1.5 text-slate-600 truncate max-w-md group-hover:text-slate-400 transition-colors cursor-help" title="{{ is_array($log->details) ? json_encode($log->details) : $log->details }}">
                                {{ $detailsPreview }}
                            </td>
                            <td class="px-2 py-1.5 text-center">
                                <button onclick="openShowLogModal({{ json_encode($log->load('user')) }})" class="text-slate-600 hover:text-emerald-500 transition-colors">
                                    <i data-lucide="terminal-square" class="w-3.5 h-3.5"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-600 font-mono">
                                > No logs found matching query...
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Terminal Style -->
            @if($logs->hasPages())
            <div class="bg-slate-900 border-t border-slate-800 px-4 py-2 flex justify-between items-center text-xs text-slate-500">
                <div>Pages: {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</div>
                <div>{{ $logs->links('pagination::simple-tailwind') }}</div>
            </div>
            @endif
        </div>
    </div>

<!-- Show Log Modal -->
<div id="showLogModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/95 backdrop-blur-2xl transition-colors" onclick="closeModal('showLogModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-950 border border-slate-800 w-full max-w-4xl shadow-2xl rounded-xl overflow-hidden font-mono text-sm">
             <div class="p-4 border-b border-slate-800 bg-slate-900 flex justify-between items-center">
                 <div class="flex gap-2">
                     <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                     <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                     <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                     <span class="ml-2 text-slate-500 text-xs">log_details_viewer.exe</span>
                 </div>
                 <button onclick="closeModal('showLogModal')" class="text-slate-500 hover:text-white transition-colors">
                     <i data-lucide="x" class="w-5 h-5"></i>
                 </button>
             </div>

             <div class="p-6 space-y-6 text-slate-300">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">OPERATOR</div>
                        <div id="show_log_user" class="text-emerald-500 font-bold"></div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">TIMESTAMP</div>
                        <div id="show_log_date" class="text-blue-400"></div>
                    </div>
                </div>

                <div class="p-4 bg-slate-900 rounded border border-slate-800">
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-2">TARGET ENTITY</div>
                    <div class="flex gap-4">
                         <div>TABLE: <span id="show_log_table" class="text-amber-500"></span></div>
                         <div>ID: <span id="show_log_id" class="text-amber-500"></span></div>
                         <div class="ml-auto">ACTION: <span id="show_log_action" class="text-white px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-xs"></span></div>
                    </div>
                </div>

                <div class="p-4 bg-slate-900 rounded border border-slate-800">
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-2">TECHNICAL_DATA (JSON)</div>
                    <pre id="show_log_details" class="text-xs font-mono text-emerald-500/80 overflow-x-auto whitespace-pre-wrap break-all"></pre>
                </div>

                <div class="pt-4 text-center">
                    <button onclick="closeModal('showLogModal')" class="px-8 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded text-xs font-mono uppercase transition-colors">
                        [ CLOSE_REPORT ]
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
        document.getElementById('show_log_user').innerText = (log.operator_name || 'INCONNU').toUpperCase();
        
        const dateObj = new Date(log.date_action);
        document.getElementById('show_log_date').innerText = dateObj.toLocaleString('fr-FR', { 
            day: '2-digit', month: 'long', year: 'numeric', 
            hour: '2-digit', minute: '2-digit', second: '2-digit' 
        }).toUpperCase();
        
        document.getElementById('show_log_table').innerText = (log.table_concernee || 'N/A').toUpperCase();
        document.getElementById('show_log_id').innerText = log.enregistrement_id || 'SYS_INTERNAL';

        // Display Details
        const detailsEl = document.getElementById('show_log_details');
        if (log.details) {
            try {
                // If it's already an object (from JSON response), stringify it
                const details = typeof log.details === 'string' ? JSON.parse(log.details) : log.details;
                detailsEl.textContent = JSON.stringify(details, null, 2);
            } catch (e) {
                detailsEl.textContent = log.details;
            }
        } else {
            detailsEl.textContent = "Aucun détail technique disponible.";
        }

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
