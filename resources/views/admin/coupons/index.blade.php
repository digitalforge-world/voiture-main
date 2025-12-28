@extends('layouts.admin')

@section('title', 'Coupons & Promotions')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-2">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight italic uppercase underline decoration-amber-500 decoration-4 underline-offset-[-2px] transition-colors">Coupons & Promotions</h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Gérez vos codes promo et réductions</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-amber-500 text-slate-950 font-black uppercase tracking-widest text-[10px] rounded-xl hover:bg-amber-400 transition shadow-xl shadow-amber-900/20">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Nouveau Coupon
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 px-2">
        <div class="group p-5 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-2xl hover:border-amber-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition"></div>
            <div class="p-2.5 bg-amber-500/10 rounded-xl text-amber-600 dark:text-amber-500 w-fit mb-4 group-hover:bg-amber-500 group-hover:text-slate-950 transition duration-500 shadow-inner"><i data-lucide="tag" class="w-5 h-5"></i></div>
            <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $coupons->total() }}</div>
            <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Total Coupons</div>
        </div>

        <div class="group p-5 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-2xl hover:border-emerald-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition"></div>
            <div class="p-2.5 bg-emerald-500/10 rounded-xl text-emerald-600 dark:text-emerald-500 w-fit mb-4 group-hover:bg-emerald-500 group-hover:text-slate-950 transition duration-500 shadow-inner"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
            <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $coupons->where('is_active', true)->count() }}</div>
            <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Actifs</div>
        </div>

        <div class="group p-5 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-2xl hover:border-blue-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition"></div>
            <div class="p-2.5 bg-blue-500/10 rounded-xl text-blue-600 dark:text-blue-500 w-fit mb-4 group-hover:bg-blue-500 group-hover:text-slate-950 transition duration-500 shadow-inner"><i data-lucide="percent" class="w-5 h-5"></i></div>
            <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $coupons->where('type', 'percentage')->count() }}</div>
            <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Pourcentage</div>
        </div>

        <div class="group p-5 border bg-white dark:bg-slate-900/30 border-slate-100 dark:border-slate-900 rounded-2xl hover:border-purple-500/30 transition shadow-lg dark:shadow-xl relative overflow-hidden transition-colors">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-purple-500 opacity-[0.03] dark:opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition"></div>
            <div class="p-2.5 bg-purple-500/10 rounded-xl text-purple-600 dark:text-purple-500 w-fit mb-4 group-hover:bg-purple-500 group-hover:text-slate-950 transition duration-500 shadow-inner"><i data-lucide="banknote" class="w-5 h-5"></i></div>
            <div class="text-3xl font-black text-slate-900 dark:text-white italic tracking-tighter transition-colors">{{ $coupons->where('type', 'fixed')->count() }}</div>
            <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-2 italic transition-colors">Montant Fixe</div>
        </div>
    </div>

    {{-- Coupons Table --}}
    <div class="border overflow-hidden bg-white dark:bg-slate-950/50 border-slate-100 dark:border-slate-900 rounded-3xl shadow-sm dark:shadow-2xl backdrop-blur-sm transition-colors">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-white/5 transition-colors">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Code</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Type</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Valeur</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Utilisation</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Validité</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 italic">Statut</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 text-right italic">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5 transition-colors">
                @forelse($coupons as $coupon)
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/[0.02] transition duration-300">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-500/10 rounded-lg">
                                <i data-lucide="ticket" class="w-4 h-4 text-amber-500"></i>
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900 dark:text-white tracking-widest transition-colors">{{ $coupon->code }}</div>
                                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-600 mt-1 uppercase transition-colors">{{ $coupon->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->type === 'percentage')
                            <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full bg-blue-500/10 text-blue-500 border-blue-500/20">
                                <i data-lucide="percent" class="w-3 h-3 mr-1"></i> Pourcentage
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full bg-purple-500/10 text-purple-500 border-purple-500/20">
                                <i data-lucide="banknote" class="w-3 h-3 mr-1"></i> Fixe
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-black text-slate-900 dark:text-white transition-colors">
                            @if($coupon->type === 'percentage')
                                {{ $coupon->value }}%
                            @else
                                {{ number_format($coupon->value, 0, ',', ' ') }} <span class="text-[10px] text-amber-500 uppercase">€</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                            {{ $coupon->current_uses }} / {{ $coupon->max_uses ?? '∞' }}
                        </div>
                        @if($coupon->max_uses)
                            <div class="mt-1 w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                                <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ min(100, ($coupon->current_uses / $coupon->max_uses) * 100) }}%"></div>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase">
                            @if($coupon->starts_at)
                                Du {{ $coupon->starts_at->format('d/m/Y') }}
                            @endif
                            @if($coupon->expires_at)
                                <br>Au {{ $coupon->expires_at->format('d/m/Y') }}
                            @endif
                            @if(!$coupon->starts_at && !$coupon->expires_at)
                                Illimité
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->is_active)
                            <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full bg-emerald-500/10 text-emerald-500 border-emerald-500/20">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2 animate-pulse"></span> Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-full bg-slate-500/10 text-slate-500 border-slate-500/20">
                                Inactif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 hover:bg-amber-500/10 rounded-lg transition text-slate-400 hover:text-amber-500" title="Modifier">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <button onclick="confirmDeletion('{{ route('admin.coupons.destroy', $coupon) }}', 'Êtes-vous sûr de vouloir supprimer ce coupon ?')" class="p-2 hover:bg-rose-500/10 rounded-lg transition text-slate-400 hover:text-rose-500" title="Supprimer">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="p-4 bg-slate-100 dark:bg-slate-900 rounded-full">
                                <i data-lucide="tag" class="w-8 h-8 text-slate-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Aucun coupon</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">Créez votre premier code promo pour stimuler les ventes</p>
                            </div>
                            <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 text-slate-950 font-bold rounded-xl hover:bg-amber-400 transition">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                Créer un coupon
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($coupons->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
