@extends('layouts.app')

@section('title', 'Réserver une Révision')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-16 transition-colors duration-500">
    <div class="container px-4 mx-auto max-w-5xl">
        
        <!-- En-tête -->
        <div class="mb-12">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter transition-colors italic">
                Rendez-vous <span class="text-amber-500">Atelier Technique</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-[9px] mt-2">
                Expertise, Transparence et Performance Automobile.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Colonne Infos / Image -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm transition-colors">
                    {{-- Image d'illustration --}}
                    <div class="relative h-48 bg-slate-100 dark:bg-slate-800">
                        <img src="{{ asset('images/Garage Luxe 3.png') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-black text-slate-900 dark:text-white uppercase text-xs mb-3 tracking-wide">Qualité Certifiée</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                            Toutes nos révisions sont effectuées sous la supervision d'un chef d'atelier certifié pour garantir la longévité de votre moteur.
                        </p>
                    </div>
                </div>

                {{-- Bloc de réassurance humain --}}
                <div class="p-6 bg-amber-500/5 border border-amber-500/10 rounded-2xl transition-colors">
                    <div class="flex items-center gap-3 mb-3">
                        <i data-lucide="info" class="w-4 h-4 text-amber-500"></i>
                        <span class="text-[9px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest">Note Technique</span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-600 dark:text-slate-400 leading-relaxed">
                        Un devis gratuit vous sera proposé systématiquement avant toute opération de remplacement.
                    </p>
                </div>

                <div class="p-6 bg-slate-900 dark:bg-white text-white dark:text-slate-950 rounded-2xl shadow-xl transition-colors">
                    <div class="text-[9px] font-black uppercase tracking-[0.2em] mb-4 opacity-60">Estimation de réponse</div>
                    <div class="text-2xl font-black italic">Moins de 2h</div>
                    <div class="mt-4 pt-4 border-t border-white/10 dark:border-slate-200 flex items-center gap-3">
                        <i data-lucide="phone" class="w-4 h-4 text-amber-500"></i>
                        <span class="text-xs font-black">+228 90 00 00 00</span>
                    </div>
                </div>
            </div>

            <!-- Colonne Formulaire (Style Humain) -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-8 md:p-12 shadow-sm transition-colors">
                    <form action="{{ route('revisions.store') }}" method="POST" class="space-y-10">
                        @csrf
                        
                        <!-- Section Véhicule -->
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] flex items-center gap-3">
                                <span class="w-8 h-px bg-slate-200 dark:bg-slate-700"></span>
                                Votre Véhicule
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Modèle du véhicule</label>
                                    <input type="text" name="marque_vehicule" required placeholder="Ex: Toyota Land Cruiser" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white text-sm font-bold focus:border-amber-500 focus:ring-4 focus:ring-amber-500/5 outline-none transition-all placeholder:text-slate-300 dark:placeholder:text-slate-700">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Année</label>
                                    <input type="number" name="annee_vehicule" required placeholder="2022" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white text-sm font-bold focus:border-amber-500 focus:ring-4 focus:ring-amber-500/5 outline-none transition-all placeholder:text-slate-300 dark:placeholder:text-slate-700">
                                </div>
                            </div>
                        </div>

                        <!-- Section Motif -->
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] flex items-center gap-3">
                                <span class="w-8 h-px bg-slate-200 dark:bg-slate-700"></span>
                                Type d'intervention
                            </h4>
                            <div class="space-y-4">
                                <div class="relative">
                                    <select name="type_revision" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-[10px] font-bold uppercase tracking-widest text-slate-700 dark:text-slate-200 focus:border-amber-500 outline-none transition-all appearance-none cursor-pointer">
                                        <option value="entretien">Vidange et entretien périodique</option>
                                        <option value="reparation">Réparation de panne spécifique</option>
                                        <option value="diagnostic">Diagnostic complet au scanner</option>
                                        <option value="complete">Check-up de sécurité intégral</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-6 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                                </div>
                                <textarea name="probleme_description" required rows="4" placeholder="Décrivez ici vos besoins ou les problèmes rencontrés..." class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white text-sm font-medium leading-relaxed focus:border-amber-500 focus:ring-4 focus:ring-amber-500/5 outline-none transition-all resize-none placeholder:text-slate-300 dark:placeholder:text-slate-700"></textarea>
                            </div>
                        </div>

                        <!-- Section Client -->
                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] flex items-center gap-3">
                                <span class="w-8 h-px bg-slate-200 dark:bg-slate-700"></span>
                                Vos Coordonnées
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Nom Complet</label>
                                    <input type="text" name="client_nom" required placeholder="Votre Nom" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white text-sm font-bold focus:border-amber-500 outline-none transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Numéro de Téléphone</label>
                                    <input type="tel" name="client_telephone" required placeholder="+228..." class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white text-sm font-bold focus:border-amber-500 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full py-6 bg-amber-500 hover:bg-slate-900 dark:hover:bg-white dark:hover:text-slate-950 text-slate-950 font-black text-xs uppercase tracking-[0.4em] rounded-2xl shadow-xl shadow-amber-500/10 transition-all active:scale-[0.98]">
                                Valider la Demande
                            </button>
                            <p class="text-[9px] text-center text-slate-400 uppercase tracking-[0.2em] font-black mt-10 italic flex items-center justify-center gap-2">
                                <i data-lucide="check-circle" class="w-3 h-3 text-emerald-500"></i>
                                Vos données sont sécurisées
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
