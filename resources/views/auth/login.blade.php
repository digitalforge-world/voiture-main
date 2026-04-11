@extends('layouts.app')

@section('title', 'Connexion Administrateur')

@section('styles')
<style>
    /* Supprimer le footer et l'espaceur pour une expérience plein écran */
    footer, #header-spacer { display: none !important; }
    body { overflow: hidden !important; }
    main { height: 100vh !important; display: flex !important; flex-direction: column !important; }
    .auth-portal-container { flex-grow: 1; height: 100vh; position: relative; z-index: 10; margin-top: -64px; } /* Ajustement pour compenser le header fixe */
    @media (min-width: 1024px) {
        .auth-portal-container { margin-top: -80px; }
    }
</style>
@endsection

@section('content')
<div class="auth-portal-container bg-white dark:bg-slate-900 flex flex-col lg:flex-row overflow-hidden pt-16 lg:pt-20">
    
    <!-- Left: Form and Login Section -->
    <div class="w-full lg:w-1/2 flex flex-col h-full overflow-hidden">
        <div class="flex-grow flex flex-col justify-center p-6 sm:p-10 lg:p-12 xl:p-16">
            {{-- Mobile Header --}}
            <div class="mb-6 lg:hidden">
                <div class="inline-flex items-center gap-2 px-2 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-bold tracking-widest mb-2">
                    <i data-lucide="shield" class="w-3 h-3"></i>
                    Accès Restreint
                </div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white mb-2 tracking-tighter">Connexion <span class="text-amber-500 italic">Administrateur</span></h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Saisissez vos identifiants pour accéder au tableau de bord.
                </p>
            </div>

            {{-- Desktop Header --}}
            <div class="hidden lg:block mb-8">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full  text-amber-500 text-[10px] font-bold tracking-widest mb-4">
                    
                </div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-2 tracking-tighter">Bienvenue, <br> <span class="text-amber-500 italic">Administrateur</span>.</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Veuillez vous authentifier pour gérer la plateforme.</p>
            </div>

            <form action="{{ route('admin.login.url') }}" method="POST" class="space-y-6 max-w-md">
                @csrf
                
                <div class="space-y-2">
                    <div class="flex justify-between items-center mb-1 px-1">
                        <label for="email" class="text-[9px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Adresse Email</label>
                    </div>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-300 dark:text-slate-600">
                            <i data-lucide="mail" class="h-4 w-4 transition-colors group-focus-within/input:text-amber-500"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required placeholder="admin@autoimport.com" 
                            class="block w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-300 dark:placeholder-slate-700 focus:border-amber-500 focus:ring-0 transition-all text-sm @error('email') border-rose-500 @enderror" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase tracking-wider ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center mb-1 px-1">
                        <label for="password" class="text-[9px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Mot de passe</label>
                        <a href="#" class="text-[9px] font-black tracking-widest text-amber-500 hover:text-slate-900 dark:hover:text-white transition-colors uppercase">Oublié ?</a>
                    </div>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-300 dark:text-slate-600">
                            <i data-lucide="lock" class="h-4 w-4 transition-colors group-focus-within/input:text-amber-500"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••"
                            class="block w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-300 dark:placeholder-slate-700 focus:border-amber-500 focus:ring-0 transition-all text-sm @error('password') border-rose-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase tracking-wider ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center ml-1 pb-2">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 text-amber-500 focus:ring-amber-500 focus:ring-offset-white dark:focus:ring-offset-slate-950 transition-colors cursor-pointer">
                    <label for="remember-me" class="ml-3 block text-[10px] font-black uppercase tracking-widest text-slate-500 select-none transition-colors cursor-pointer">Se souvenir de moi</label>
                </div>

                <button type="submit" class="group/btn w-full py-4 bg-slate-900 dark:bg-amber-500 text-white dark:text-slate-950 font-black rounded-xl transition-all shadow-lg hover:shadow-amber-500/20 active:scale-[0.98] flex items-center justify-center gap-3">
                    <span class="text-[10px] uppercase tracking-widest">Connexion sécurisée</span>
                    <i data-lucide="arrow-right" class="w-3 h-3 transition-transform group-hover/btn:translate-x-1"></i>
                </button>
            </form>
        </div>

        {{-- Security Badges at bottom left --}}
        <div class="px-8 py-6 lg:px-12 lg:py-8 mt-auto border-t border-slate-100 dark:border-slate-800 bg-slate-50/30">
            <div class="grid grid-cols-3 gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[9px] font-black uppercase leading-none dark:text-white">Admin</p>
                        <p class="text-[7px] text-slate-400 mt-0.5">Accès Total</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                        <i data-lucide="lock-keyhole" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[9px] font-black uppercase leading-none dark:text-white">Chiffré</p>
                        <p class="text-[7px] text-slate-400 mt-0.5">AES-256 bits</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500">
                        <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[9px] font-black uppercase leading-none dark:text-white">Surveillé</p>
                        <p class="text-[7px] text-slate-400 mt-0.5">Logs Actifs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Image Section (Hidden on Mobile) -->
    <div class="hidden lg:block lg:w-1/2 h-full relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=1400" 
             alt="Administration Dashboard" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-l from-slate-950/20 via-transparent to-white dark:to-slate-900"></div>
        <div class="absolute inset-0 bg-amber-500/10 mix-blend-overlay"></div>
        
        <div class="absolute top-12 right-12 text-white text-right">
            <h2 class="text-3xl xl:text-4xl font-black tracking-tighter mb-4 leading-none text-right">
                Contrôle total sur <br>votre <span class="text-amber-500 italic">business</span>.
            </h2>
            <p class="text-slate-100 text-sm max-w-md ml-auto backdrop-blur-sm bg-black/20 p-4 rounded-xl border border-white/10 shadow-xl">
                Gérez vos véhicules, expéditions, réservations et utilisateurs depuis une interface centrale et performante conçue pour la croissance de votre entreprise.
            </p>
        </div>
    </div>

</div>
@endsection
