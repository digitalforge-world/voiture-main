<!-- Create Car Modal -->
<div id="createCarModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-white/80 dark:bg-slate-950/90 backdrop-blur-xl transition-colors" onclick="closeModal('createCarModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-4xl p-12 shadow-2xl rounded-[5rem] rounded-tr-2xl rounded-bl-2xl overflow-hidden animate-in fade-in zoom-in duration-300 transition-colors">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center justify-between mb-12 relative">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Référencer un Véhicule</h2>
                    <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mt-1 italic transition-colors">Ajout d'une nouvelle unité au stock ou en importation</p>
                </div>
                <button onclick="closeModal('createCarModal')" class="p-5 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-[2rem] border border-slate-100 dark:border-white/5 transition hover:scale-110 duration-300 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12 relative max-h-[70vh] overflow-y-auto px-4 custom-scrollbar">
                @csrf
                
                <!-- Section 1: Informations Générales -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Identité & Statut</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Marque</label>
                            <input type="text" name="marque" required placeholder="Ex: Toyota" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Modèle</label>
                            <input type="text" name="modele" required placeholder="Ex: RAV4" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Année</label>
                            <input type="number" name="annee" required placeholder="2023" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Prix de vente (FCFA)</label>
                            <input type="number" name="prix" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Kilométrage</label>
                            <input type="number" name="kilometrage" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">État général</label>
                            <select name="etat" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none transition-colors">
                                <option value="neuf">Neuf</option>
                                <option value="occasion">Occasion</option>
                                <option value="excellent">Excellent</option>
                                <option value="bon">Bon état</option>
                                <option value="reconditionne">Reconditionné</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Spécifications Techniques -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Technique & Performance</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Puissance (CH)</label>
                            <input type="text" name="puissance" placeholder="Ex: 150 ch" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Moteur / Cylindrée</label>
                            <input type="text" name="moteur" placeholder="Ex: 2.0L V6" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Transmission</label>
                            <select name="transmission" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="automatique">Automatique</option>
                                <option value="manuelle">Manuelle</option>
                                <option value="semi-automatique">Semi-Auto</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Carburant</label>
                            <select name="carburant" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="essence">Essence</option>
                                <option value="diesel">Diesel</option>
                                <option value="hybride">Hybride</option>
                                <option value="electrique">Electrique</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Consommation Mixte</label>
                            <input type="text" name="consommation_mixte" placeholder="Ex: 6.5 L/100" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Vitesse Max</label>
                            <input type="text" name="vitesse_max" placeholder="Ex: 210 km/h" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">0-100 km/h</label>
                            <input type="text" name="acceleration_0_100" placeholder="Ex: 8.2s" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Type de Véhicule</label>
                            <select name="type_vehicule" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="berline">Berline</option>
                                <option value="suv">SUV / Crossover</option>
                                <option value="4x4">4x4 / Tout-terrain</option>
                                <option value="pickup">Pick-up</option>
                                <option value="coupe">Coupé</option>
                                <option value="utilitaire">Utilitaire</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Marché & Historique -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Origine & Historique</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Marché d'Origine</label>
                            <select name="origine_marche" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
                                <option value="europe">Europe</option>
                                <option value="usa">USA / Canada</option>
                                <option value="gcc">GCC (Dubaï, etc.)</option>
                                <option value="asie">Asie (Japon, Corée)</option>
                                <option value="local">Local Africa</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Nbre de Propriétaires</label>
                            <input type="number" name="nombre_proprietaires" value="1" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
                        </div>
                        <div class="space-y-4 pt-8">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="carnet_entretien_ajour" value="1" class="w-5 h-5 rounded border-slate-200 dark:border-white/10 text-amber-500 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-950 transition-colors">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors italic">Carnet d'entretien à jour</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="non_fumeur" value="1" class="w-5 h-5 rounded border-slate-200 dark:border-white/10 text-amber-500 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-950 transition-colors">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors italic">Véhicule Non-fumeur</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Équipements & Options (JSON Structure) -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Session des Équipements</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Confort -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-amber-500 pl-3">Confort & Intérieur</h4>
                            <div class="space-y-3">
                                @foreach(['Climatisation Bi-zone', 'Sièges Cuir', 'Sièges Chauffants', 'Toit Ouvrant/Pano', 'Régulateur Adaptatif', 'Démarrage sans clé'] as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[confort][]" value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-emerald-500 focus:ring-emerald-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sécurité -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-rose-500 pl-3">Sécurité & Aide</h4>
                            <div class="space-y-3">
                                @foreach(['ABS / ESP', 'Airbags Front/Lat', 'Caméra 360°', 'Capteurs de stationnement', 'Aide au maintien de voie', 'Freinage d\'urgence'] as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[securite][]" value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-rose-500 focus:ring-rose-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Multimédia -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-blue-500 pl-3">Tech & Multimédia</h4>
                            <div class="space-y-3">
                                @foreach(['Écran Tactile', 'Système Navigation GPS', 'Apple CarPlay / Android Auto', 'Système Audio Premium', 'Chargeur Induction', 'Bluetooth / USB'] as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[tech][]" value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-blue-500 focus:ring-blue-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Extérieur -->
                        <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
                            <h4 class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-widest mb-4 border-l-2 border-slate-500 pl-3">Design & Extérieur</h4>
                            <div class="space-y-3">
                                @foreach(['Jantes Alliage', 'Feux LED / Matrix', 'Pack Chrome', 'Rétros Électriques', 'Peinture Métallisée', 'Attelage Remorque'] as $opt)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="equipements_details[exterieur][]" value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-slate-500 focus:ring-slate-500/20 bg-white dark:bg-slate-900 transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Visuels -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] italic border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Assets & Visuels</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Photo Principale</label>
                            <div class="relative group">
                                <input type="file" name="photo_principale" onchange="handleFilePreview(this, 'create_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full py-8 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] bg-slate-50/50 dark:bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-950 group-hover:border-amber-500/50 transition transition-colors">
                                    <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 dark:text-slate-600 group-hover:text-amber-500 transition transition-colors"></i>
                                    <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">Image de couverture</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Galerie Photos (Multiple)</label>
                            <div class="relative group">
                                <input type="file" name="photos[]" multiple onchange="handleFilePreview(this, 'create_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full py-8 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] bg-slate-50/50 dark:bg-slate-950/50 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-950 group-hover:border-amber-500/50 transition transition-colors">
                                    <i data-lucide="images" class="w-8 h-8 text-slate-400 dark:text-slate-600 group-hover:text-amber-500 transition transition-colors"></i>
                                    <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest italic group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">Vues secondaires</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="create_media_preview" class="flex flex-wrap gap-4 empty:hidden"></div>
                </div>

                <div class="pt-10 flex gap-6 pb-4">
                    <button type="button" onclick="closeModal('createCarModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-900 transition transition-colors">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-xl shadow-amber-500/20 font-black italic transition-colors">Confirmer l'inscription Catalogue</button>
                </div>
            </form>
        </div>
    </div>
</div>
