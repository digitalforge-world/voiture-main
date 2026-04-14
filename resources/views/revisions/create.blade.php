@extends('layouts.app')

@section('title', 'Atelier Technique - Maintenance de Précision')

@section('content')
<div class="bg-white dark:bg-slate-950 font-sans selection:bg-amber-500 selection:text-white transition-colors duration-500">
    <!-- Script Lottie -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- SECTION HERO - STYLE AUTOIMPORT HUB -->
    <section class="relative min-h-[85vh] flex items-center overflow-hidden bg-slate-950">
        <!-- Image de fond avec overlay -->
        <div class="absolute inset-0 z-0 opacity-20 transition-opacity duration-1000">
            <img src="{{ asset('images/auto_maintenance/hero voiture.png') }}" alt="Auto Maintenance Hero" class="w-full h-full object-cover object-center scale-105 animate-slow-zoom">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
        </div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_50%,_rgba(245,158,11,0.05)_0%,_transparent_50%)]"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Texte -->
                <div class="animate-in fade-in slide-in-from-left-10 duration-1000 max-w-lg">
                    <div class="inline-flex items-center gap-2 mb-6">
                        <div class="w-6 h-[1.5px] bg-amber-500"></div>
                        <span class="text-[9px] font-black text-amber-500 uppercase tracking-[0.4em]">Atelier de Haute Précision</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-5xl font-black text-white leading-none tracking-tighter mb-6 uppercase italic whitespace-nowrap">
                        EXPERTISE & <span class="text-amber-500 italic">RÉVISION</span> CERTIFIÉE.
                    </h1>
                    
                    <p class="text-slate-400 text-[11px] font-black max-w-md leading-relaxed mb-10 uppercase tracking-widest opacity-60">
                        L'excellence technique au service de votre sécurité. Nous traitons chaque véhicule avec une rigueur et une précision absolue.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="#rendez-vous" class="px-8 py-4 bg-amber-500 hover:bg-white text-slate-950 font-black text-[10px] uppercase tracking-[0.4em] rounded-sm transition-all shadow-xl italic">
                            Prendre Rendez-vous
                        </a>
                    </div>
                </div>

                <!-- Animation Inspection (Droite) -->
                <div class="flex justify-center items-center h-full animate-in zoom-in duration-1000 delay-300">
                    <lottie-player 
                        src="{{ asset('animations/AutoX-Inspect-loading.json') }}" 
                        background="transparent" 
                        speed="1" 
                        style="width: 550px; height: 550px;" 
                        loop 
                        autoplay>
                    </lottie-player>
                </div>
            </div>
        </div>

        <!-- Indicateur de défilement -->
        <div class="absolute bottom-10 left-10 animate-bounce opacity-40">
            <i data-lucide="chevron-down" class="text-white w-6 h-6"></i>
        </div>
    </section>

    <!-- SECTION FORMULAIRE - ATELIER TECHNIQUE -->
    <section id="rendez-vous" class="py-24 bg-slate-50 dark:bg-slate-900/50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                
                <!-- Titre & Infos de gauche -->
                <div class="lg:col-span-4 lg:sticky lg:top-10">
                    <h2 class="text-4xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-4 italic transition-colors">
                        Atelier <span class="text-amber-500">Technique</span>
                    </h2>
                    <div class="h-1.5 w-20 bg-amber-500 mb-8"></div>
                    
                    <p class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-[10px] mb-10 leading-relaxed">
                        Expertise, Transparence et Performance Automobile pour votre sérénité.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white dark:bg-slate-800 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
                                <i data-lucide="shield-check" class="w-5 h-5 text-amber-500"></i>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-slate-950 dark:text-white uppercase tracking-widest">Qualité Certifiée</h4>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 font-bold">Pièces d'origine garantie.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 group">
                        <img src="{{ asset('images/auto_maintenance/moteur.png') }}" class="rounded-2xl grayscale group-hover:grayscale-0 transition-all duration-700 shadow-2xl border border-slate-200 dark:border-slate-800">
                    </div>
                </div>

                <!-- Le Formulaire -->
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 p-8 md:p-14 shadow-2xl rounded-sm transition-colors">
                        @if(session('success'))
                            <div class="mb-10 p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4">
                                <i data-lucide="check-circle" class="text-emerald-500 w-8 h-8"></i>
                                <div>
                                    <h3 class="text-emerald-500 font-black uppercase text-sm italic">Demande Envoyée !</h3>
                                    <p class="text-emerald-600/80 text-xs font-bold">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('revisions.store') }}" method="POST" class="space-y-12">
                            @csrf
                            
                            <!-- Section Véhicule -->
                            <div class="space-y-8">
                                <div class="flex items-center gap-4">
                                    <span class="text-3xl font-black text-slate-100 dark:text-slate-800">01</span>
                                    <h4 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.4em] italic">Véhicule</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="relative group">
                                        <label class="absolute -top-3 left-4 px-2 bg-white dark:bg-slate-950 text-[10px] font-black text-amber-500 uppercase tracking-widest z-10 transition-all">Modèle</label>
                                        <input type="text" name="marque_vehicule" required placeholder="Ex: Lexus RX 350" class="w-full py-5 px-6 bg-transparent border-2 border-slate-100 dark:border-slate-800 focus:border-amber-500 outline-none transition-all text-sm font-black uppercase tracking-tight placeholder:text-slate-300 dark:placeholder:text-slate-700">
                                    </div>
                                    <div class="relative group">
                                        <label class="absolute -top-3 left-4 px-2 bg-white dark:bg-slate-950 text-[10px] font-black text-amber-500 uppercase tracking-widest z-10 transition-all">Année</label>
                                        <input type="number" name="annee_vehicule" required placeholder="2022" class="w-full py-5 px-6 bg-transparent border-2 border-slate-100 dark:border-slate-800 focus:border-amber-500 outline-none transition-all text-sm font-black uppercase tracking-tight">
                                    </div>
                                </div>
                            </div>

                            <!-- Section Service -->
                            <div class="space-y-8">
                                <div class="flex items-center gap-4">
                                    <span class="text-3xl font-black text-slate-100 dark:text-slate-800">02</span>
                                    <h4 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.4em] italic">Prestation</h4>
                                </div>
                                <div class="space-y-8">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="type_revision" value="entretien" class="peer hidden" checked>
                                            <div class="p-6 border-2 border-slate-100 dark:border-slate-800 peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all rounded-sm flex items-center justify-between">
                                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-slate-300">Entretien Périodique</span>
                                                <i data-lucide="check" class="w-4 h-4 text-amber-500 opacity-0 peer-checked:opacity-100"></i>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="type_revision" value="diagnostic" class="peer hidden">
                                            <div class="p-6 border-2 border-slate-100 dark:border-slate-800 peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all rounded-sm flex items-center justify-between">
                                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-slate-300">Diagnostic Scanner</span>
                                                <i data-lucide="check" class="w-4 h-4 text-amber-500 opacity-0 peer-checked:opacity-100"></i>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="relative group">
                                        <label class="absolute -top-3 left-4 px-2 bg-white dark:bg-slate-950 text-[10px] font-black text-amber-500 uppercase tracking-widest z-10 transition-all">Détails de l'intervention</label>
                                        <textarea name="probleme_description" required rows="4" placeholder="Décrivez vos besoins..." class="w-full py-5 px-6 bg-transparent border-2 border-slate-100 dark:border-slate-800 focus:border-amber-500 outline-none transition-all text-sm font-bold uppercase tracking-tight resize-none"></textarea>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-6 bg-slate-950 dark:bg-white text-white dark:text-slate-950 hover:bg-amber-500 dark:hover:bg-amber-500 dark:hover:text-slate-950 transition-all font-black text-xs uppercase tracking-[0.5em] shadow-xl italic">
                                Confirmer ma Demande
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION EXPERT - STYLE RÉPLIQUE ORANGE -->
    <section id="expert" class="py-24 bg-white dark:bg-slate-950 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                
                <!-- Bloc Image (À GAUCHE) -->
                <div class="w-full lg:w-1/2 relative">
                    <div class="relative z-10">
                        <img src="{{ asset('images/auto_maintenance/M_canicien.png') }}" alt="Expert Mécanicien" class="w-full max-w-lg rounded-sm shadow-xl transition-all duration-700 grayscale hover:grayscale-0">
                        
                        <!-- Badge flottant : EFFET VERRE DÉPOLI -->
                        <div class="absolute -bottom-10 -right-6 md:right-0 bg-slate-100/90 dark:bg-slate-800/90 backdrop-blur-md p-8 shadow-2xl border-l-[6px] border-amber-500 max-w-[280px] transition-colors">
                            <div class="text-4xl font-black text-amber-500 italic leading-none mb-2 tracking-tighter uppercase">EXPERTISE</div>
                            <div class="text-[10px] font-black text-slate-950 dark:text-white uppercase tracking-[0.3em] mb-2 leading-tight">RIGUEUR & PASSION</div>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest opacity-60">
                                Précision mécanique absolue depuis 15 ans.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bloc Texte (À DROITE) -->
                <div class="w-full lg:w-1/2 space-y-10">
                    <h2 class="text-6xl font-black text-slate-950 dark:text-white leading-[0.9] tracking-tighter uppercase italic">
                        EXPERTISE <br>
                        <span class="text-amber-500">TECHNIQUE</span>
                    </h2>
                    
                    <p class="text-slate-600 dark:text-slate-400 text-[11px] font-black uppercase tracking-[0.15em] leading-relaxed max-w-xl opacity-80">
                        Chaque intervention est supervisée par notre chef d'atelier <span class="text-amber-500 font-black italic underline decoration-2 underline-offset-4">Moussa Traoré</span>. Son expérience de 15 ans garantit une fiabilité totale.
                    </p>

                    <!-- Statistiques Style Stitch -->
                    <div class="grid grid-cols-2 gap-12 pt-4">
                        <div class="space-y-1">
                            <div class="text-6xl font-black text-slate-950 dark:text-white tracking-tighter italic">528</div>
                            <div class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Clients Heureux</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-6xl font-black text-slate-950 dark:text-white tracking-tighter italic">30+</div>
                            <div class="text-[9px] font-black text-amber-500 uppercase tracking-widest">Techs Experts</div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <a href="#rendez-vous" class="inline-block px-12 py-5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-black text-xs uppercase tracking-[0.3em] hover:bg-amber-500 dark:hover:bg-amber-500 dark:hover:text-slate-950 transition-all shadow-xl italic">
                            Prendre Rendez-vous
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

</div>

<style>
    @keyframes slow-zoom {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }
    .animate-slow-zoom {
        animation: slow-zoom 20s infinite alternate ease-in-out;
    }
</style>
@endsection
