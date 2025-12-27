@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="min-h-[80vh] bg-slate-950 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-[100px] -z-10 animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] -z-10 animate-pulse" style="animation-delay: 1s;"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-black text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-8 underline-offset-[-2px]">
                Bon retour !
            </h2>
            <p class="mt-4 text-xs font-bold text-slate-500 uppercase tracking-[0.3em]">
                Accédez à votre espace AutoImport Hub
            </p>
        </div>

        <div class="p-px bg-gradient-to-br from-amber-500/30 to-slate-800 rounded-[2.5rem] shadow-2xl">
            <div class="bg-slate-950/80 backdrop-blur-xl py-12 px-10 rounded-[2.4rem]">
                <form action="#" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Adresse Email</label>
                        <div class="relative group">
                            <input id="email" name="email" type="email" autocomplete="email" required placeholder="exemple@mail.com" 
                                class="w-full py-4 pl-12 pr-4 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition group-hover:border-slate-700">
                            <i data-lucide="mail" class="absolute left-4 top-4 w-5 h-5 text-slate-600 group-focus-within:text-amber-500 transition"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between ml-1">
                            <label for="password" class="text-[10px] font-black uppercase tracking-widest text-slate-500">Mot de passe</label>
                            <a href="#" class="text-[10px] font-black uppercase tracking-widest text-amber-500 hover:text-white transition">Oublié ?</a>
                        </div>
                        <div class="relative group">
                            <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••"
                                class="w-full py-4 pl-12 pr-4 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition group-hover:border-slate-700">
                            <i data-lucide="lock" class="absolute left-4 top-4 w-5 h-5 text-slate-600 group-focus-within:text-amber-500 transition"></i>
                        </div>
                    </div>

                    <div class="flex items-center ml-1">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-slate-800 bg-slate-900 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-950">
                        <label for="remember-me" class="ml-3 block text-[10px] font-black uppercase tracking-widest text-slate-500 select-none">Se souvenir de moi</label>
                    </div>

                    <div>
                        <button type="submit" class="w-full py-5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-amber-900/20 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-3">
                            <i data-lucide="key-round" class="w-4 h-4"></i>
                            Connexion sécurisée
                        </button>
                    </div>
                </form>

                <div class="mt-10 pt-10 border-t border-slate-900">
                    <p class="text-center text-[10px] font-black uppercase tracking-widest text-slate-600">
                        Pas encore membre ? <a href="{{ route('register') }}" class="text-amber-500 hover:text-white transition ml-2">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
