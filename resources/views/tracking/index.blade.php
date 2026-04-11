@extends('layouts.app')

@section('title', 'Suivre ma Commande')

@section('styles')
<style>
    /* Supprimer le footer et l'espaceur pour une expérience plein écran */
    footer, #header-spacer { display: none !important; }
    body { overflow: hidden !important; }
    main { height: 100vh !important; display: flex !important; flex-direction: column !important; }
    .tracking-portal-container { flex-grow: 1; height: 100vh; position: relative; z-index: 10; margin-top: -64px; } /* Ajustement pour compenser le header fixe */
    @media (min-width: 1024px) {
        .tracking-portal-container { margin-top: -80px; }
    }
</style>
@endsection

@section('content')
<div class="tracking-portal-container bg-white dark:bg-slate-900 flex flex-col lg:flex-row overflow-hidden pt-16 lg:pt-20">
    <!-- Left: Image Section (Hidden on Mobile) -->
    <div class="hidden lg:block lg:w-1/2 h-full relative overflow-hidden">
        <img src="{{ asset('images/tracking.png') }}" 
             alt="Logistics Tracking" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/40 via-transparent to-white dark:to-slate-900"></div>
        
        <div class="absolute bottom-12 left-12 right-12 text-white">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 backdrop-blur-md text-amber-500 text-[10px] font-bold tracking-widest mb-6">
                <i data-lucide="shield-check" class="w-3 h-3"></i>
                Portail sécurisé
            </div>
            <h2 class="text-3xl xl:text-4xl font-black tracking-tighter mb-4 leading-none">
                Suivez votre investissement <br>en <span class="text-amber-500 italic">temps réel</span>.
            </h2>
            <p class="text-slate-300 text-xs max-w-sm">
                Notre système de logistique avancée vous permet de surveiller chaque étape, du port d'embarquement jusqu'à votre porte.
            </p>
        </div>
    </div>

    <!-- Right: Form and Features Section -->
    <div class="w-full lg:w-1/2 flex flex-col h-full overflow-hidden">
        <div class="flex-grow flex flex-col justify-center p-6 sm:p-10 lg:p-12 xl:p-16">
            {{-- Mobile Header --}}
            <div class="mb-6 lg:hidden">
                <div class="inline-flex items-center gap-2 px-2 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-bold tracking-widest mb-2">
                    <i data-lucide="shield-check" class="w-3 h-3"></i>
                    Portail sécurisé
                </div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white mb-2 tracking-tighter">Votre commande en <span class="text-amber-500 italic">un clic</span></h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Saisissez votre ID unique de tracking pour commencer.
                </p>
            </div>

            {{-- Desktop Header --}}
            <div class="hidden lg:block mb-6">
                <h1 class="text-2xl font-black text-slate-900 dark:text-white mb-2 tracking-tighter">Rechercher une commande</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Accès exclusif aux clients AutoImport Hub.</p>
            </div>

            <form action="{{ route('tracking.search') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="relative">
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label for="tracking_number" class="text-[9px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Numéro de tracking</label>
                        <span class="text-[9px] font-bold text-amber-500/60 italic">Ex: CAR-2024-X8Y9</span>
                    </div>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-300 dark:text-slate-600">
                            <i data-lucide="hash" class="h-4 w-4"></i>
                        </div>
                        <input type="text" 
                            name="tracking_number" 
                            id="tracking_number" 
                            value="{{ old('tracking_number') }}"
                            class="block w-full pl-14 pr-6 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white placeholder-slate-300 dark:placeholder-slate-700 focus:border-amber-500 focus:ring-0 transition-all font-mono text-base"
                            placeholder="Entrer votre code..."
                            required
                            maxlength="20">
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @php
                        $types = [
                            ['label' => 'Cars', 'prefix' => 'CAR', 'color' => 'bg-amber-500'],
                            ['label' => 'Loc', 'prefix' => 'LOC', 'color' => 'bg-blue-500'],
                            ['label' => 'Parts', 'prefix' => 'PCE', 'color' => 'bg-emerald-500'],
                            ['label' => 'Service', 'prefix' => 'REV', 'color' => 'bg-purple-500'],
                        ];
                    @endphp
                    @foreach($types as $t)
                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg bg-slate-50/50 dark:bg-slate-950/30 border border-slate-100 dark:border-slate-800/50">
                        <div class="w-1.5 h-1.5 rounded-full {{ $t['color'] }}"></div>
                        <span class="text-[8px] font-bold text-slate-500 dark:text-slate-400">{{ $t['prefix'] }}</span>
                    </div>
                    @endforeach
                </div>

                <button type="submit" class="group/btn w-full py-4 bg-slate-900 dark:bg-amber-500 text-white dark:text-slate-950 font-black rounded-xl transition-all shadow-lg hover:shadow-amber-500/20 active:scale-[0.98] flex items-center justify-center gap-3">
                    <span class="text-[10px] uppercase tracking-widest">Voir le statut de livraison</span>
                    <i data-lucide="arrow-right" class="w-3 h-3 transition-transform group-hover/btn:translate-x-1"></i>
                </button>
                
                @if(session('error') || $errors->any())
                    <div class="p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl animate-in fade-in slide-in-from-top-4 duration-500">
                        <div class="flex flex-col gap-1.5">
                            @if(session('error'))
                                <div class="flex items-center gap-2 text-rose-500">
                                    <i data-lucide="octagon-alert" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                    <p class="text-[10px] font-bold leading-tight">{{ session('error') }}</p>
                                </div>
                            @endif
                            @foreach ($errors->all() as $error)
                                <div class="flex items-center gap-2 text-rose-500">
                                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                    <p class="text-[9px] font-medium leading-tight">{{ $error }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </form>
        </div>

        {{-- Features Grid at the bottom of the right section --}}
        <div class="px-8 py-6 lg:px-12 lg:py-8 mt-auto border-t border-slate-100 dark:border-slate-800 bg-slate-50/30">
            <div class="grid grid-cols-3 gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[9px] font-black uppercase leading-none dark:text-white">Sécurisé</p>
                        <p class="text-[7px] text-slate-400 mt-0.5">AES-256</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[9px] font-black uppercase leading-none dark:text-white">Temps réel</p>
                        <p class="text-[7px] text-slate-400 mt-0.5">Direct API</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                        <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[9px] font-black uppercase leading-none dark:text-white">Support</p>
                        <p class="text-[7px] text-slate-400 mt-0.5">24/7 Live</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
