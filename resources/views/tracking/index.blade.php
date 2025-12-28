@extends('layouts.app')

@section('title', 'Suivre ma Commande')

@section('content')
<div class="min-h-screen pt-24 pb-12 bg-slate-950">
    <div class="container px-4 mx-auto max-w-3xl">
        <div class="max-w-2xl mx-auto text-center mb-12">
            <h1 class="text-3xl font-bold text-white mb-4">Suivre votre Commande</h1>
            <p class="text-slate-400">
                Entrez votre numéro de tracking unique reçu lors de votre commande pour voir son statut en temps réel.
            </p>
        </div>

        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-8 shadow-2xl backdrop-blur-sm">
            <form action="{{ route('tracking.search') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-slate-300 mb-2">Numéro de Tracking</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="hash" class="h-5 w-5 text-slate-500"></i>
                        </div>
                        <input type="text" 
                               name="tracking_number" 
                               id="tracking_number" 
                               class="block w-full pl-11 pr-4 py-4 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors uppercase tracking-widest font-mono"
                               placeholder="EX: CAR-2024-X8Y9"
                               required
                               maxlength="14">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm text-slate-400 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        CAR-... (Voitures)
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        LOC-... (Locations)
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        PCE-... (Pièces)
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        REV-... (Révisions)
                    </div>
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 px-8 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl transition-all shadow-lg shadow-amber-500/20">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    Rechercher ma Commande
                </button>
            </form>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3 text-center">
            <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800/50">
                <div class="w-10 h-10 mx-auto bg-slate-800 rounded-full flex items-center justify-center text-amber-500 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-white mb-1">Sécurisé</h3>
                <p class="text-xs text-slate-400">Accès privé via numéro unique</p>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800/50">
                <div class="w-10 h-10 mx-auto bg-slate-800 rounded-full flex items-center justify-center text-amber-500 mb-3">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-white mb-1">Temps Réel</h3>
                <p class="text-xs text-slate-400">Mises à jour instantanées</p>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800/50">
                <div class="w-10 h-10 mx-auto bg-slate-800 rounded-full flex items-center justify-center text-amber-500 mb-3">
                    <i data-lucide="help-circle" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-white mb-1">Besoin d'aide ?</h3>
                <p class="text-xs text-slate-400">Support disponible 24/7</p>
            </div>
        </div>
    </div>
</div>
@endsection
