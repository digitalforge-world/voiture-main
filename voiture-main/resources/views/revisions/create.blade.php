@extends('layouts.app')

@section('title', 'Demander une Révision')

@section('content')
<div class="min-h-screen bg-slate-950">
    <!-- Header Section -->
    <div class="relative py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-purple-500/10 via-slate-950/0 to-slate-950"></div>
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <nav class="flex justify-center mb-8 space-x-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                    <a href="{{ url('/') }}" class="hover:text-amber-500 transition">Accueil</a>
                    <span>/</span>
                    <span class="text-amber-500">Service Technique</span>
                </nav>
                <h1 class="text-5xl font-black text-white lg:text-7xl leading-none tracking-tight">
                    Maintenance <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-amber-500">Haute Performance.</span>
                </h1>
                <p class="mt-8 text-lg text-slate-400 font-medium">Prenez soin de votre investissement. Nos experts certifiés assurent le suivi technique complet de votre véhicule.</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="container px-4 py-12 mx-auto lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="p-px bg-gradient-to-br from-purple-500/30 via-slate-800 to-amber-500/30 rounded-[3rem] shadow-2xl">
                <div class="bg-slate-950/80 backdrop-blur-3xl p-10 sm:p-16 rounded-[2.9rem]">
                    <div class="flex items-center gap-4 mb-12">
                        <div class="p-4 bg-purple-500/10 rounded-2xl text-purple-500 shadow-inner">
                            <i data-lucide="clipboard-list" class="w-8 h-8"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-white tracking-tight uppercase">Demande de Diagnostic</h2>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1 italic">Obtenez un devis estimatif sous 24h</p>
                        </div>
                    </div>

                    <form action="{{ route('revisions.store') }}" method="POST" class="space-y-12">
                        @csrf
                        
                        <!-- Vehicle Section -->
                        <div class="space-y-8">
                            <h3 class="flex items-center gap-3 text-sm font-black text-amber-500 uppercase tracking-[0.2em]">
                                <span class="w-8 h-px bg-amber-500/30"></span>
                                Informations Véhicule
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Marque</label>
                                    <input type="text" name="marque_vehicule" required placeholder="Ex: Toyota" class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Modèle</label>
                                    <input type="text" name="modele_vehicule" required placeholder="Ex: Land Cruiser" class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Année</label>
                                    <input type="number" name="annee_vehicule" required placeholder="2020" class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Immatriculation</label>
                                    <input type="text" name="immatriculation" placeholder="TG-0000-AR" class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition font-mono uppercase">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Kilométrage</label>
                                    <div class="relative">
                                        <input type="number" name="kilometrage" placeholder="65000" class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                                        <span class="absolute right-4 top-4 text-[10px] font-black text-slate-600 uppercase tracking-widest">KM</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Section -->
                        <div class="space-y-8">
                            <h3 class="flex items-center gap-3 text-sm font-black text-purple-400 uppercase tracking-[0.2em]">
                                <span class="w-8 h-px bg-purple-500/30"></span>
                                Type d'Intervention
                            </h3>

                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Catégorie de service</label>
                                    <select name="type_revision" class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl text-white text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition appearance-none">
                                        <option value="entretien">Entretien périodique (Vidange, filtres...)</option>
                                        <option value="reparation">Réparation spécifique / Panne</option>
                                        <option value="diagnostic">Diagnostic complet (Scanner)</option>
                                        <option value="complete">Check-up complet & Préparation</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Détails ou Observations</label>
                                    <textarea name="probleme_description" required rows="5" placeholder="Précisez ici les symptômes, bruits suspects ou l'historique de l'entretien..." 
                                        class="w-full py-4 px-6 bg-slate-900 border border-slate-800 rounded-3xl text-white text-sm focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Footer / Submit -->
                        <div class="pt-8 space-y-8">
                            <div class="p-6 bg-purple-500/5 border-l-4 border-purple-500 rounded-2xl flex gap-4 items-start">
                                <i data-lucide="info" class="w-6 h-6 text-purple-500 shrink-0"></i>
                                <p class="text-[11px] text-slate-400 leading-relaxed uppercase tracking-wider font-medium">
                                    En envoyant cette demande, vous recevrez une notification par email dès que notre chef d'atelier aura validé la faisabilité et le coût estimatif.
                                </p>
                            </div>
                            
                            <button type="submit" class="w-full py-6 bg-gradient-to-r from-purple-600 to-amber-600 hover:from-purple-500 hover:to-amber-500 text-white text-xs font-black uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-purple-900/20 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-3">
                                Valider la Demande d'Entretien
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
