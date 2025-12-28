@extends('layouts.admin')

@section('title', 'Fournisseurs & Partenaires')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-4 underline-offset-[-2px] transition-colors">Fournisseurs & Partenaires</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Gérez votre réseau de fournisseurs</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-amber-500 text-slate-950 font-black uppercase tracking-widest text-[10px] rounded-xl hover:bg-amber-400 transition shadow-xl shadow-amber-900/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Nouveau Fournisseur
        </a>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4 px-2">
        @foreach(['dealer' => ['Concessionnaires', 'car-front', 'amber'], 'auction' => ['Enchères', 'gavel', 'blue'], 'logistics' => ['Logistique', 'truck', 'emerald'], 'service' => ['Services', 'wrench', 'purple']] as $type => $info)
            <div class="group p-5 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-2xl hover:border-{{ $info[2] }}-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden">
                <div class="p-2.5 bg-{{ $info[2] }}-500/10 rounded-xl text-{{ $info[2] }}-600 dark:text-{{ $info[2] }}-500 w-fit mb-4"><i data-lucide="{{ $info[1] }}" class="w-5 h-5"></i></div>
                <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter">{{ $suppliers->where('type', $type)->count() }}</div>
                <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic">{{ $info[0] }}</div>
            </div>
        @endforeach
    </div>

    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-3xl shadow-sm dark:shadow-2xl backdrop-blur-sm transition-colors">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Nom</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Type</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Contact</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Pays</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($suppliers as $supplier)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-500/10 rounded-lg">
                                <i data-lucide="building-2" class="w-4 h-4 text-amber-500"></i>
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900 dark:text-white">{{ $supplier->name }}</div>
                                @if($supplier->contact_person)
                                    <div class="text-[9px] font-bold text-slate-400 dark:text-slate-600 mt-1 uppercase">{{ $supplier->contact_person }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $badges = [
                                'dealer' => ['Concessionnaire', 'amber'],
                                'auction' => ['Enchère', 'blue'],
                                'logistics' => ['Logistique', 'emerald'],
                                'service' => ['Service', 'purple'],
                                'other' => ['Autre', 'slate']
                            ];
                            $badge = $badges[$supplier->type] ?? $badges['other'];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full bg-{{ $badge[1] }}-500/10 text-{{ $badge[1] }}-500 border-{{ $badge[1] }}-500/20">
                            {{ $badge[0] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-slate-600 dark:text-slate-400 space-y-1">
                            @if($supplier->email)
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="mail" class="w-3 h-3"></i>
                                    <span>{{ $supplier->email }}</span>
                                </div>
                            @endif
                            @if($supplier->phone)
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="phone" class="w-3 h-3"></i>
                                    <span>{{ $supplier->phone }}</span>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $supplier->country ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="p-2 hover:bg-amber-500/10 rounded-lg transition text-slate-400 hover:text-amber-500">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <button onclick="confirmDeletion('{{ route('admin.suppliers.destroy', $supplier) }}')" class="p-2 hover:bg-rose-500/10 rounded-lg transition text-slate-400 hover:text-rose-500">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="p-4 bg-slate-100 dark:bg-slate-900 rounded-full">
                                <i data-lucide="container" class="w-8 h-8 text-slate-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Aucun fournisseur</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">Ajoutez vos partenaires commerciaux</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($suppliers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">{{ $suppliers->links() }}</div>
        @endif
    </div>
</div>
@endsection
