@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="min-h-[80vh] bg-white dark:bg-slate-950 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden transition-colors duration-500">
    <!-- Decorative background elements -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-[100px] -z-10 animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] -z-10 animate-pulse" style="animation-delay: 1s;"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-8 underline-offset-[-2px] transition-colors">
                Bon retour !
            </h2>
            <p class="mt-4 text-xs font-bold text-slate-500 uppercase tracking-[0.3em]">
                Accédez à votre espace AutoImport Hub
            </p>
        </div>

        <div class="p-px bg-gradient-to-br from-amber-500/30 to-slate-200 dark:to-slate-800 rounded-[2.5rem] shadow-xl dark:shadow-2xl transition-colors">
            <div class="bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl py-12 px-10 rounded-[2.4rem] transition-colors">
                <form action="{{ route('admin.login.url') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1 transition-colors">Adresse Email</label>
                        <div class="relative group">
                            <input id="email" name="email" type="email" autocomplete="email" required placeholder="exemple@mail.com" 
                                class="w-full py-4 pl-12 pr-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition group-hover:border-slate-300 dark:group-hover:border-slate-700 @error('email') border-rose-500 @enderror transition-colors" value="{{ old('email') }}">
                            <i data-lucide="mail" class="absolute left-4 top-4 w-5 h-5 text-slate-400 dark:text-slate-600 group-focus-within:text-amber-500 transition transition-colors"></i>
                        </div>
                        @error('email')
                            <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase tracking-wider ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between ml-1">
                            <label for="password" class="text-[10px] font-black uppercase tracking-widest text-slate-500 transition-colors">Mot de passe</label>
                            <a href="#" class="text-[10px] font-black uppercase tracking-widest text-amber-500 hover:text-slate-900 dark:hover:text-white transition-colors">Oublié ?</a>
                        </div>
                        <div class="relative group">
                            <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••"
                                class="w-full py-4 pl-12 pr-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition group-hover:border-slate-300 dark:group-hover:border-slate-700 @error('password') border-rose-500 @enderror transition-colors">
                            <i data-lucide="lock" class="absolute left-4 top-4 w-5 h-5 text-slate-400 dark:text-slate-600 group-focus-within:text-amber-500 transition transition-colors"></i>
                        </div>
                        @error('password')
                            <p class="mt-1 text-[10px] font-bold text-rose-500 uppercase tracking-wider ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center ml-1">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 text-amber-500 focus:ring-amber-500 focus:ring-offset-white dark:focus:ring-offset-slate-950 transition-colors">
                        <label for="remember-me" class="ml-3 block text-[10px] font-black uppercase tracking-widest text-slate-500 select-none transition-colors">Se souvenir de moi</label>
                    </div>

                    <div>
                        <button type="submit" class="w-full py-5 px-4 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-amber-900/20 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-3">
                            <i data-lucide="key-round" class="w-4 h-4"></i>
                            Connexion sécurisée
                        </button>
                    </div>
                </form>

                <!-- Registration link removed for Admin Portal security -->
            </div>
        </div>
    </div>
</div>
@endsection
