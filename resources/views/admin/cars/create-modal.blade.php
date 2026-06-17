<!-- Create Car Modal -->
<div id="createCarModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
 <div class="fixed inset-0 bg-white dark:bg-slate-900/90 xl transition-colors" onclick="closeModal('createCarModal')"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-4xl p-6 shadow-sm rounded-[5rem] rounded-tr-2xl rounded-bl-2xl overflow-hidden animate-in fade-in zoom-in duration-300 transition-colors">
   <div class="absolute -right-20 -top-20 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl"></div>
   
   <div class="flex items-center justify-between mb-12 relative">
    <div>
     <h2 class="text-xl font-semibold text-slate-900 dark:text-white uppercase tracking-normal transition-colors">Référencer un Véhicule</h2>
     <p class="text-slate-400 dark:text-slate-500 font-medium uppercase tracking-wide text-[10px] mt-1 transition-colors">Ajout d'une nouvelle unité au stock ou en importation</p>
    </div>
    <button onclick="closeModal('createCarModal')" class="p-5 bg-slate-50 dark:bg-slate-900 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-[2rem] border border-slate-100 dark:border-white/5 transition hover:scale-110 duration-300 transition-colors">
     <i data-lucide="x" class="w-6 h-6"></i>
    </button>
   </div>

   <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12 relative max-h-[70vh] overflow-y-auto px-4 custom-scrollbar">
    @csrf

    {{-- ─── Sélecteur de Catégorie ─── --}}
    <div class="space-y-4">
     <h3 class="text-[10px] font-semibold text-amber-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Catégorie de Véhicule</h3>
     <div class="grid grid-cols-2 gap-4">
      {{-- Option Voiture --}}
      <label id="cat_voiture_label" for="cat_voiture" class="relative flex flex-col items-center gap-3 p-5 rounded-3xl border-2 border-amber-500 bg-amber-500/5 cursor-pointer transition-all duration-300 group">
       <input type="radio" id="cat_voiture" name="categorie" value="voiture" checked class="sr-only" onchange="toggleCategorie('voiture')">
       <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center group-hover:bg-amber-500/20 transition">
        <i data-lucide="car" class="w-7 h-7 text-amber-500"></i>
       </div>
       <div class="text-center">
        <p class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wide">Voiture</p>
        <p class="text-[9px] text-slate-400 mt-0.5">Berline, SUV, 4x4, Pick-up…</p>
       </div>
       <div id="cat_voiture_check" class="absolute top-3 right-3 w-5 h-5 rounded-full bg-amber-500 flex items-center justify-center">
        <i data-lucide="check" class="w-3 h-3 text-white"></i>
       </div>
      </label>

      {{-- Option Scooter --}}
      <label id="cat_scooter_label" for="cat_scooter" class="relative flex flex-col items-center gap-3 p-5 rounded-3xl border-2 border-slate-200 dark:border-white/10 cursor-pointer transition-all duration-300 group hover:border-amber-500/50">
       <input type="radio" id="cat_scooter" name="categorie" value="scooter" class="sr-only" onchange="toggleCategorie('scooter')">
       <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center group-hover:bg-amber-500/10 transition">
        <svg class="w-7 h-7 text-slate-400 group-hover:text-amber-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
         <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c.5 0 1 .2 1.4.5L16 6h2.5a1.5 1.5 0 010 3H18l-1.5 3H6L4.5 9H3.5A1.5 1.5 0 013.5 6H6L8.6 3.5C9 3.2 9.5 3 10 3h2zm-7 9a3 3 0 100 6 3 3 0 000-6zm14 0a3 3 0 100 6 3 3 0 000-6z"/>
        </svg>
       </div>
       <div class="text-center">
        <p class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wide">Scooter</p>
        <p class="text-[9px] text-slate-400 mt-0.5">Cyclomoteur, moto légère…</p>
       </div>
       <div id="cat_scooter_check" class="absolute top-3 right-3 w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 hidden items-center justify-center">
        <i data-lucide="check" class="w-3 h-3 text-white"></i>
       </div>
      </label>
     </div>
    </div>
    
    <!-- Section 1: Informations Générales -->
    <div class="space-y-6">
     <h3 class="text-[10px] font-semibold text-amber-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Identité & Statut</h3>
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Marque</label>
       <input type="text"name="marque"required placeholder="Ex: Toyota" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Modèle</label>
       <input type="text"name="modele"required placeholder="Ex: RAV4" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Année</label>
       <input type="number"name="annee"required placeholder="2023" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
      </div>
     </div>

     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Prix de vente (FCFA)</label>
       <input type="number"name="prix"required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Kilométrage</label>
       <input type="number"name="kilometrage"required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">État général</label>
       <select name="etat" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none transition-colors">
        <option value="neuf">Neuf</option>
        <option value="occasion">Occasion</option>
        <option value="excellent">Excellent</option>
        <option value="bon">Bon état</option>
        <option value="reconditionne">Reconditionné</option>
       </select>
      </div>
     </div>
    

      {{-- ⚠️ CHAMPS REQUIS : pays_origine + disponibilite --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
       <div class="space-y-2">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-red-400 ml-2">Pays d'Origine <span class="text-red-400">*</span></label>
        <input type="text" name="pays_origine" required placeholder="Ex: Japon, Allemagne, France..." class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-red-100 dark:border-red-500/20 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner transition-colors">
       </div>
       <div class="space-y-2">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-red-400 ml-2">Disponibilité <span class="text-red-400">*</span></label>
        <select name="disponibilite" required class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-red-100 dark:border-red-500/20 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner appearance-none transition-colors">
         <option value="disponible" selected>En Stock (visible au catalogue)</option>
         <option value="importation">En Importation</option>
         <option value="reserve">Reserve</option>
        </select>
       </div>
      </div>
     </div>

     <!-- Section 2: Spécifications Techniques -->
    <div class="space-y-6">
     <h3 class="text-[10px] font-semibold text-amber-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Technique & Performance</h3>
     <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Puissance (CH)</label>
       <input type="text"name="puissance"placeholder="Ex: 150 ch" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Moteur / Cylindrée</label>
       <input type="text"name="moteur"placeholder="Ex: 2.0L V6" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Transmission</label>
       <select name="transmission" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
        <option value="automatique">Automatique</option>
        <option value="manuelle">Manuelle</option>
        <option value="semi-automatique">Semi-Auto</option>
       </select>
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Carburant</label>
       <select name="carburant" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
        <option value="essence">Essence</option>
        <option value="diesel">Diesel</option>
        <option value="hybride">Hybride</option>
        <option value="electrique">Electrique</option>
       </select>
      </div>
     </div>

     <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Consommation Mixte</label>
       <input type="text"name="consommation_mixte"placeholder="Ex: 6.5 L/100" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Vitesse Max</label>
       <input type="text"name="vitesse_max"placeholder="Ex: 210 km/h" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">0-100 km/h</label>
       <input type="text"name="acceleration_0_100"placeholder="Ex: 8.2s" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-2" id="type_vehicule_group">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Type de Véhicule</label>
        <select name="type_vehicule" id="type_vehicule_select" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
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

     {{-- ─── Section Scooter Spécifique ─── --}}
     <div id="section_scooter" class="space-y-6 hidden">
      <h3 class="text-[10px] font-semibold text-orange-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Spécifications Scooter</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
       <div class="space-y-2">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Cylindrée (cc)</label>
        <input type="text" name="cylindree" placeholder="Ex: 125 cc" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
       </div>
       <div class="space-y-2">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Puissance (CH/kW)</label>
        <input type="text" name="puissance" placeholder="Ex: 11 CH" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
       </div>
       <div class="space-y-2">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Vitesse Max</label>
        <input type="text" name="vitesse_max" placeholder="Ex: 95 km/h" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
       </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
       <div class="space-y-2">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Carburant</label>
        <select name="carburant" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
         <option value="essence">Essence</option>
         <option value="electrique">Électrique</option>
        </select>
       </div>
       <div class="space-y-2">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Consommation</label>
        <input type="text" name="consommation_mixte" placeholder="Ex: 3.2 L/100" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
       </div>
       <div class="space-y-2">
        <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Couleur</label>
        <input type="text" name="couleur" placeholder="Ex: Rouge mat" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
       </div>
      </div>
     </div>

    <!-- Section 3: Marché & Historique -->
    <div class="space-y-6">
     <h3 class="text-[10px] font-semibold text-amber-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Origine & Historique</h3>
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Marché d'Origine</label>
       <select name="origine_marche" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition appearance-none transition-colors">
        <option value="europe">Europe</option>
        <option value="usa">USA / Canada</option>
        <option value="gcc">GCC (Dubaï, etc.)</option>
        <option value="asie">Asie (Japon, Corée)</option>
        <option value="local">Local Africa</option>
       </select>
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Nbre de Propriétaires</label>
       <input type="number"name="nombre_proprietaires"value="1" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 transition shadow-inner transition-colors">
      </div>
      <div class="space-y-4 pt-8">
       <label class="flex items-center gap-3 cursor-pointer group">
        <input type="checkbox"name="carnet_entretien_ajour"value="1" class="w-5 h-5 rounded border-slate-200 dark:border-white/10 text-amber-500 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-900 transition-colors">
        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Carnet d'entretien à jour</span>
       </label>
       <label class="flex items-center gap-3 cursor-pointer group">
        <input type="checkbox"name="non_fumeur"value="1" class="w-5 h-5 rounded border-slate-200 dark:border-white/10 text-amber-500 focus:ring-amber-500/20 bg-slate-50 dark:bg-slate-900 transition-colors">
        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Véhicule Non-fumeur</span>
       </label>
      </div>
     </div>
    </div>

    <!-- Section 4: Équipements & Options (JSON Structure) -->
    <div class="space-y-6">
     <h3 class="text-[10px] font-semibold text-emerald-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Session des Équipements</h3>
     
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Confort -->
      <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
       <h4 class="text-[9px] font-semibold text-slate-400 dark:text-slate-600 uppercase tracking-wide mb-4 border-l-2 border-amber-500 pl-3">Confort & Intérieur</h4>
       <div class="space-y-3">
        @foreach(['Climatisation Bi-zone', 'Sièges Cuir', 'Sièges Chauffants', 'Toit Ouvrant/Pano', 'Régulateur Adaptatif', 'Démarrage sans clé'] as $opt)
        <label class="flex items-center gap-3 cursor-pointer group">
         <input type="checkbox"name="equipements_details[confort][]"value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-emerald-500 focus:ring-emerald-500/20 bg-white dark:bg-slate-900 transition-colors">
         <span class="text-[9px] font-medium text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
        </label>
        @endforeach
       </div>
      </div>

      <!-- Sécurité -->
      <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
       <h4 class="text-[9px] font-semibold text-slate-400 dark:text-slate-600 uppercase tracking-wide mb-4 border-l-2 border-rose-500 pl-3">Sécurité & Aide</h4>
       <div class="space-y-3">
        @foreach(['ABS / ESP', 'Airbags Front/Lat', 'Caméra 360°', 'Capteurs de stationnement', 'Aide au maintien de voie', 'Freinage d\'urgence'] as $opt)
        <label class="flex items-center gap-3 cursor-pointer group">
         <input type="checkbox"name="equipements_details[securite][]"value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-rose-500 focus:ring-rose-500/20 bg-white dark:bg-slate-900 transition-colors">
         <span class="text-[9px] font-medium text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
        </label>
        @endforeach
       </div>
      </div>

      <!-- Multimédia -->
      <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
       <h4 class="text-[9px] font-semibold text-slate-400 dark:text-slate-600 uppercase tracking-wide mb-4 border-l-2 border-blue-500 pl-3">Tech & Multimédia</h4>
       <div class="space-y-3">
        @foreach(['Écran Tactile', 'Système Navigation GPS', 'Apple CarPlay / Android Auto', 'Système Audio Premium', 'Chargeur Induction', 'Bluetooth / USB'] as $opt)
        <label class="flex items-center gap-3 cursor-pointer group">
         <input type="checkbox"name="equipements_details[tech][]"value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-blue-500 focus:ring-blue-500/20 bg-white dark:bg-slate-900 transition-colors">
         <span class="text-[9px] font-medium text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
        </label>
        @endforeach
       </div>
      </div>

      <!-- Extérieur -->
      <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-white/5 transition-colors">
       <h4 class="text-[9px] font-semibold text-slate-400 dark:text-slate-600 uppercase tracking-wide mb-4 border-l-2 border-slate-500 pl-3">Design & Extérieur</h4>
       <div class="space-y-3">
        @foreach(['Jantes Alliage', 'Feux LED / Matrix', 'Pack Chrome', 'Rétros Électriques', 'Peinture Métallisée', 'Attelage Remorque'] as $opt)
        <label class="flex items-center gap-3 cursor-pointer group">
         <input type="checkbox"name="equipements_details[exterieur][]"value="{{ $opt }}" class="w-4 h-4 rounded border-slate-200 dark:border-white/10 text-slate-500 focus:ring-slate-500/20 bg-white dark:bg-slate-900 transition-colors">
         <span class="text-[9px] font-medium text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">{{ $opt }}</span>
        </label>
        @endforeach
       </div>
      </div>
     </div>
    </div>

    <!-- Section 5: Visuels -->
    <div class="space-y-6">
     <h3 class="text-[10px] font-semibold text-amber-500 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-white/5 pb-2 transition-colors">Assets & Visuels</h3>
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Photo Principale</label>
      <div class="relative group">
       <input id="create_photo_principale" type="file" name="photo_principale" onchange="handleFilePreview(this, 'create_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
       <div class="w-full py-4 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] dark: flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-900 group-hover:border-amber-500/50 transition transition-colors">
        <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 dark:text-slate-600 group-hover:text-amber-500 transition transition-colors"></i>
        <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">Image de couverture</span>
        <button type="button" onclick="document.getElementById('create_photo_principale').click()" class="mt-2 px-3 py-1 text-xs rounded-full bg-amber-500 text-white">Choisir une image</button>
       </div>
      </div>
      </div>
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Galerie Photos (Multiple)</label>
      <div class="relative group">
       <input id="create_photos" type="file" name="photos[]" multiple onchange="handleFilePreview(this, 'create_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
       <div class="w-full py-4 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] dark: flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-900 group-hover:border-amber-500/50 transition transition-colors">
        <i data-lucide="images" class="w-8 h-8 text-slate-400 dark:text-slate-600 group-hover:text-amber-500 transition transition-colors"></i>
        <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">Vues secondaires</span>
        <button type="button" onclick="document.getElementById('create_photos').click()" class="mt-2 px-3 py-1 text-xs rounded-full bg-amber-500 text-white">Choisir les images</button>
       </div>
      </div>
      </div>
     </div>
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-2">
       <label class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 ml-2 transition-colors">Vidéos (Multiple)</label>
      <div class="relative group">
       <input id="create_videos" type="file" name="videos[]" accept="video/*" multiple onchange="handleFilePreview(this, 'create_media_preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
       <div class="w-full py-4 border-2 border-dashed border-slate-200 dark:border-white/5 rounded-[2.5rem] dark: flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 dark:group-hover:bg-slate-900 group-hover:border-amber-500/50 transition transition-colors">
        <i data-lucide="video" class="w-8 h-8 text-slate-400 dark:text-slate-600 group-hover:text-amber-500 transition transition-colors"></i>
        <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide group-hover:text-slate-900 dark:group-hover:text-slate-300 transition-colors">Ajouter des vidéos</span>
        <button type="button" onclick="document.getElementById('create_videos').click()" class="mt-2 px-3 py-1 text-xs rounded-full bg-amber-500 text-white">Choisir les vidéos</button>
       </div>
      </div>
      </div>
     </div>
     <div id="create_media_preview" class="flex flex-wrap gap-4 empty:hidden"></div>
    </div>

    <div class="pt-10 flex gap-6 pb-4">
     <button type="button" onclick="closeModal('createCarModal')" class="flex-1 py-4 text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-white/5 hover:bg-slate-100 dark:hover:bg-slate-900 transition transition-colors">Annuler</button>
     <button type="submit" id="createSubmitBtn" class="flex-[2] py-4 text-[10px] font-semibold uppercase tracking-wide text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 transition shadow-sm shadow-amber-500/20 font-semibold transition-colors">Confirmer l'inscription Catalogue</button>
    </div>
   </form>
  </div>
 </div>
</div>

<script>
function toggleCategorie(cat) {
 const isScooter = cat === 'scooter';

 // Labels radio cards
 const voitureLabel = document.getElementById('cat_voiture_label');
 const scooterLabel = document.getElementById('cat_scooter_label');
 const voitureCheck = document.getElementById('cat_voiture_check');
 const scooterCheck = document.getElementById('cat_scooter_check');
 const typeVehiculeGroup = document.getElementById('type_vehicule_group');

 if (isScooter) {
  // Activer scooter card
  scooterLabel.classList.add('border-amber-500', 'bg-amber-500/5');
  scooterLabel.classList.remove('border-slate-200', 'dark:border-white/10');
  scooterCheck.classList.remove('hidden');
  scooterCheck.classList.add('flex');
  scooterCheck.style.backgroundColor = '#f59e0b';

  // Désactiver voiture card
  voitureLabel.classList.remove('border-amber-500', 'bg-amber-500/5');
  voitureLabel.classList.add('border-slate-200', 'dark:border-white/10');
  voitureCheck.classList.add('hidden');
  voitureCheck.classList.remove('flex');

  // Afficher section scooter spécifique
  document.getElementById('section_scooter').classList.remove('hidden');

  // ⚠️ NE PAS mettre type_vehicule = 'scooter' (viole le CHECK constraint)
  // La distinction voiture/scooter est portée par le champ 'categorie'
  // On masque le select type_vehicule qui n'est pas pertinent pour un scooter
  const sel = document.getElementById('type_vehicule_select');
  if (sel) {
   sel.value = 'berline'; // valeur valide par défaut
   if (typeVehiculeGroup) typeVehiculeGroup.style.display = 'none';
  }

  // Mettre à jour le bouton submit
  const btn = document.getElementById('createSubmitBtn');
  if (btn) btn.textContent = "Inscrire le Scooter au Catalogue";

 } else {
  // Activer voiture card
  voitureLabel.classList.add('border-amber-500', 'bg-amber-500/5');
  voitureLabel.classList.remove('border-slate-200', 'dark:border-white/10');
  voitureCheck.classList.remove('hidden');
  voitureCheck.classList.add('flex');

  // Désactiver scooter card
  scooterLabel.classList.remove('border-amber-500', 'bg-amber-500/5');
  scooterLabel.classList.add('border-slate-200', 'dark:border-white/10');
  scooterCheck.classList.add('hidden');
  scooterCheck.classList.remove('flex');

  // Masquer section scooter
  document.getElementById('section_scooter').classList.add('hidden');

  // Remettre le select type_vehicule visible
  const sel = document.getElementById('type_vehicule_select');
  if (sel) {
   sel.value = 'berline';
   if (typeVehiculeGroup) typeVehiculeGroup.style.display = '';
  }

  // Rétablir bouton submit
  const btn = document.getElementById('createSubmitBtn');
  if (btn) btn.textContent = "Confirmer l'inscription Catalogue";
 }
}

const filePreviewState = new Map();

function handleFilePreview(input, previewId) {
 const previewContainer = document.getElementById(previewId);
 if (!previewContainer) return;

 const newFiles = input.files ? Array.from(input.files) : [];

 // If the input accepts multiple files, append new selections to existing ones
 if (input.multiple) {
  const existingState = filePreviewState.get(input);
  const existingFiles = existingState ? existingState.files : [];

  // Combine and dedupe by name+size+lastModified
  const combined = existingFiles.concat(newFiles);
  const seen = new Set();
  const unique = [];
  combined.forEach(f => {
   const key = `${f.name}::${f.size}::${f.lastModified}`;
   if (!seen.has(key)) {
    seen.add(key);
    unique.push(f);
   }
  });

  // Update the input.files using DataTransfer so the form will submit the aggregated files
  const dt = new DataTransfer();
  unique.forEach(f => dt.items.add(f));
  try {
   input.files = dt.files;
  } catch (e) {
   // Some browsers may throw on setting readonly property; still keep preview state
  }

  filePreviewState.set(input, { files: Array.from(dt.files), previewId });
 } else {
  // Single-file input: replace
  filePreviewState.set(input, { files: newFiles, previewId });
 }

 renderPreviewContainer(previewId);
}

function renderPreviewContainer(previewId) {
 const previewContainer = document.getElementById(previewId);
 if (!previewContainer) return;
 previewContainer.innerHTML = '';
 const inputs = [];
 filePreviewState.forEach((state, input) => {
  if (state.previewId === previewId && state.files.length) {
   inputs.push({ input, files: state.files });
  }
 });
 if (!inputs.length) return;
 inputs.forEach(({ input, files }) => {
  files.forEach((file, index) => {
   const card = document.createElement('div');
   card.className = "relative w-20 h-20 rounded-xl overflow-hidden border border-slate-200 dark:border-white/10 shadow-sm flex items-center justify-center bg-slate-50 dark:bg-slate-900";

   if (file.type.startsWith('image/')) {
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.className = "w-full h-full object-cover";
    img.onload = () => URL.revokeObjectURL(img.src);
    card.appendChild(img);
   } else if (file.type.startsWith('video/')) {
    const videoIcon = document.createElement('div');
    videoIcon.className = "flex flex-col items-center justify-center p-2 text-amber-500 text-center";
    videoIcon.innerHTML = `<i data-lucide="video" class="w-6 h-6 mb-1"></i><span class="text-[8px] font-bold truncate max-w-[60px]">${file.name}</span>`;
    card.appendChild(videoIcon);
   } else {
    const fileIcon = document.createElement('div');
    fileIcon.className = "flex flex-col items-center justify-center p-2 text-slate-400 text-center";
    fileIcon.innerHTML = `<i data-lucide="file" class="w-6 h-6 mb-1"></i><span class="text-[8px] font-bold truncate max-w-[60px]">${file.name}</span>`;
    card.appendChild(fileIcon);
   }

   const removeBtn = document.createElement('button');
   removeBtn.type = 'button';
   removeBtn.className = 'absolute top-1 right-1 w-6 h-6 rounded-full bg-slate-950/80 text-white flex items-center justify-center hover:bg-red-500 transition';
   removeBtn.innerHTML = '<i data-lucide="x" class="w-3 h-3"></i>';
   removeBtn.addEventListener('click', () => removePreviewFile(input, index, previewId));

   card.appendChild(removeBtn);
   previewContainer.appendChild(card);
  });
 });
 if (window.lucide) {
  window.lucide.createIcons();
 }
}

function removePreviewFile(input, index, previewId) {
 const state = filePreviewState.get(input);
 if (!state) return;
 const files = state.files;
 if (index < 0 || index >= files.length) return;
 files.splice(index, 1);
 const dataTransfer = new DataTransfer();
 files.forEach(file => dataTransfer.items.add(file));
 input.files = dataTransfer.files;
 filePreviewState.set(input, { files, previewId: state.previewId });
 renderPreviewContainer(previewId);
}
</script>

