@extends('layouts.admin')

@section('title', 'Paramètres Système - AutoImport Hub')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8">Configuration Système</h1>
            <p class="text-slate-500 font-bold mt-2 uppercase tracking-widest text-[10px] italic">Paramètres généraux, règles métiers et sécurité</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex items-center gap-3 px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-white hover:scale-105 duration-300 shadow-xl shadow-amber-900/10">
                <i data-lucide="save" class="w-4 h-4"></i> Enregistrer les modifications
            </button>
        </div>
    </div>

    <!-- Settings Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Site Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="p-8 border bg-slate-900/30 border-slate-900 rounded-[2.5rem] shadow-xl">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8 flex items-center gap-2 italic">
                    <i data-lucide="info" class="w-4 h-4"></i> Informations Générales
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-600 ml-1">Nom du Site</label>
                        <input type="text" value="AutoImport Hub" class="w-full py-3.5 px-4 bg-slate-950 border border-white/5 rounded-xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-600 ml-1">Devise Locale</label>
                        <input type="text" value="FCFA" class="w-full py-3.5 px-4 bg-slate-950 border border-white/5 rounded-xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-600 ml-1">Email Système</label>
                        <input type="email" value="system@autoimport-hub.com" class="w-full py-3.5 px-4 bg-slate-950 border border-white/5 rounded-xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-600 ml-1">Contact Support</label>
                        <input type="text" value="+225 00 00 00 00" class="w-full py-3.5 px-4 bg-slate-950 border border-white/5 rounded-xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                    </div>
                </div>
            </div>

            <div class="p-8 border bg-slate-900/30 border-slate-900 rounded-[2.5rem] shadow-xl">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8 flex items-center gap-2 italic">
                    <i data-lucide="shield-check" class="w-4 h-4"></i> Règles Métiers & Acomptes
                </h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-slate-950 rounded-2xl border border-white/5">
                        <div>
                            <div class="text-[11px] font-black text-white uppercase tracking-wider italic">Acompte Minimum Commande</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1 italic">Pourcentage requis pour valider une importation</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="number" value="30" class="w-20 py-2 px-3 bg-slate-900 border border-white/5 rounded-xl text-amber-500 text-center font-black">
                            <span class="text-xs font-black text-slate-600">%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-950 rounded-2xl border border-white/5">
                        <div>
                            <div class="text-[11px] font-black text-white uppercase tracking-wider italic">Caution Location Standard</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1 italic">Montant par défaut pour les véhicules de tourisme</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="number" value="150000" class="w-32 py-2 px-3 bg-slate-900 border border-white/5 rounded-xl text-amber-500 text-center font-black">
                            <span class="text-[10px] font-black text-slate-600 italic uppercase">FCFA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Settings -->
        <div class="space-y-8">
            <div class="p-8 border bg-slate-900/30 border-slate-900 rounded-[2.5rem] shadow-xl">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8 italic">État du Système</h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic leading-none">Maintenance</span>
                        <div class="w-12 h-6 bg-slate-950 rounded-full p-1 relative border border-white/5">
                            <div class="w-4 h-4 bg-slate-700 rounded-full transition translate-x-0"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic leading-none">Locations Ouvertes</span>
                        <div class="w-12 h-6 bg-emerald-500/20 rounded-full p-1 relative border border-emerald-500/30">
                            <div class="w-4 h-4 bg-emerald-500 rounded-full transition translate-x-6"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic leading-none">Importations Actives</span>
                        <div class="w-12 h-6 bg-emerald-500/20 rounded-full p-1 relative border border-emerald-500/30">
                            <div class="w-4 h-4 bg-emerald-500 rounded-full transition translate-x-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
