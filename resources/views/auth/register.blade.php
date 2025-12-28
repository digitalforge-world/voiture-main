@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="min-h-screen bg-slate-950 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-10 right-10 w-[500px] h-[500px] bg-amber-500/10 rounded-full blur-[120px] -z-10 animate-pulse"></div>
    <div class="absolute bottom-10 left-10 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px] -z-10 animate-pulse" style="animation-delay: 1.5s;"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-2xl relative">
        <div class="text-center mb-12">
            <h2 class="text-5xl font-black text-white tracking-tight uppercase italic flex flex-col items-center gap-2">
                <span>Créer Un</span>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600 underline decoration-white/20 underline-offset-8">Compte</span>
            </h2>
            <p class="mt-8 text-[10px] font-black text-slate-500 uppercase tracking-[0.4em]">
                Rejoignez le réseau leader de l'automobile
            </p>
        </div>

        <div class="p-px bg-gradient-to-br from-amber-500/30 to-slate-800 rounded-[3rem] shadow-2xl">
            <div class="bg-slate-950/80 backdrop-blur-2xl py-12 px-10 sm:px-16 rounded-[2.9rem]">
                <form action="#" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Nom</label>
                            <input type="text" name="nom" required placeholder="Doe" 
                                class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition hover:border-slate-700">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Prénom</label>
                            <input type="text" name="prenom" required placeholder="John" 
                                class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition hover:border-slate-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Email professionnel</label>
                            <div class="relative group">
                                <input type="email" name="email" required placeholder="john@example.com" 
                                    class="w-full py-4 pl-12 pr-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                                <i data-lucide="mail" class="absolute left-4 top-4 w-5 h-5 text-slate-600 group-focus-within:text-amber-500 transition"></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Téléphone</label>
                            <div class="relative group">
                                <input type="tel" name="telephone" required placeholder="+228 90 00 00 00" 
                                    class="w-full py-4 pl-12 pr-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                                <i data-lucide="phone" class="absolute left-4 top-4 w-5 h-5 text-slate-600 group-focus-within:text-amber-500 transition"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-4 border-t border-slate-900">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Mot de passe</label>
                            <input type="password" name="password" required placeholder="••••••••" 
                                class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Confirmation</label>
                            <input type="password" name="password_confirmation" required placeholder="••••••••" 
                                class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-amber-900/20 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-3 group">
                            Débutez l'expérience
                            <i data-lucide="chevron-right" class="w-5 h-5 transition group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-12 flex items-center justify-center gap-4">
                    <span class="h-px bg-slate-900 flex-grow"></span>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-600 whitespace-nowrap">
                        Déjà inscrit ? <a href="{{ route('login') }}" class="text-amber-500 hover:text-white transition ml-2 underline decoration-amber-500/20">Se connecter</a>
                    </p>
                    <span class="h-px bg-slate-900 flex-grow"></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
