@php
    $users = $users ?? \App\Models\User::all();
    $voitures = $voitures ?? \App\Models\VoitureLocation::where('disponible', true)->get();
@endphp

<!-- Create Rental Modal -->
<div id="createRentalModal" class="fixed inset-0 z-[101] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-xl transition-colors" onclick="closeModal('createRentalModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-2xl p-12 shadow-2xl rounded-[4rem] overflow-hidden animate-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter transition-colors">Nouveau Contrat</h2>
                    <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mt-1 italic border-l-2 border-amber-500 pl-4 transition-colors">Ouverture d'un dossier de location</p>
                </div>
                <button onclick="closeModal('createRentalModal')" class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl text-slate-400 hover:text-slate-900 dark:hover:text-white transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="{{ route('admin.rentals.store') }}" method="POST" class="space-y-8 relative">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Client Enregistré</label>
                        <select name="user_id" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 appearance-none font-black uppercase italic tracking-widest">
                            <option value="">-- SÉLECTIONNEZ UN CLIENT --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }} ({{ $user->telephone }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Véhicule</label>
                        <select name="voiture_location_id" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 appearance-none font-black uppercase italic tracking-widest">
                            @foreach($voitures as $voiture)
                            <option value="{{ $voiture->id }}">{{ $voiture->marque }} {{ $voiture->modele }} - {{ number_format($voiture->prix_jour, 0, ',', ' ') }} FCFA/J</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-white/5">
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest italic">Ou saisir un Client Invité</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="client_nom" placeholder="NOM COMPLET" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-400">
                        <input type="tel" name="client_telephone" placeholder="TÉLÉPHONE" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-400">
                        <input type="email" name="client_email" placeholder="EMAIL" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 pt-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Date Début</label>
                        <input type="date" name="date_debut" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 font-black">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Date Fin</label>
                        <input type="date" name="date_fin" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 font-black">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Statut Initial</label>
                        <select name="statut" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 appearance-none font-black uppercase italic tracking-widest">
                            <option value="reserve">Réservé</option>
                            <option value="confirme">Confirmé</option>
                            <option value="en_cours">Sortie immédiate</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Montant Total Estimé (FCFA)</label>
                        <input type="number" name="montant_total" required class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-emerald-500 text-sm font-black ring-2 ring-emerald-500/10">
                    </div>
                </div>

                <div class="pt-6 flex gap-6">
                    <button type="submit" class="flex-1 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-slate-950 bg-amber-500 rounded-[2.5rem] hover:bg-slate-950 hover:text-white transition shadow-2xl shadow-amber-500/20 italic">
                        Valider l'enregistrement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Rental Modal -->
<div id="editRentalModal" class="fixed inset-0 z-[101] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-xl transition-colors" onclick="closeModal('editRentalModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-xl p-12 shadow-2xl rounded-[4rem] animate-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white italic uppercase tracking-tighter mb-2 transition-colors">Ajuster Contrat</h2>
            <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-[10px] mb-10 italic border-l-2 border-blue-500 pl-4 transition-colors">Mise à jour des paramètres du contrat</p>

            <form id="editRentalForm" method="POST" class="space-y-8 relative">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Date Début</label>
                        <input type="date" name="date_debut" id="edit_rent_start" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/20 font-black">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Date Fin</label>
                        <input type="date" name="date_fin" id="edit_rent_end" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/20 font-black">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Statut de la Location</label>
                    <select name="statut" id="edit_rent_statut" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm font-black uppercase italic tracking-widest appearance-none focus:ring-1 focus:ring-blue-500">
                        <option value="reserve">Réservé</option>
                        <option value="confirme">Confirmé</option>
                        <option value="en_cours">En Cours (Véhicule sorti)</option>
                        <option value="termine">Terminé (Véhicule rendu)</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-2 italic transition-colors">Coût Total (FCFA)</label>
                    <input type="number" name="montant_total" id="edit_rent_total" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-blue-500 text-sm font-black ring-2 ring-blue-500/10">
                </div>

                <div class="pt-10 flex gap-6">
                    <button type="button" onclick="closeModal('editRentalModal')" class="flex-1 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] hover:bg-slate-100 transition italic">Annuler</button>
                    <button type="submit" class="flex-[2] py-6 text-[10px] font-black uppercase tracking-widest text-white bg-blue-600 rounded-[2.5rem] hover:bg-blue-700 transition shadow-xl shadow-blue-500/20 italic">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Show Rental Modal -->
<div id="showRentalModal" class="fixed inset-0 z-[101] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/95 backdrop-blur-2xl transition-colors" onclick="closeModal('showRentalModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-5xl shadow-2xl rounded-[5rem] overflow-hidden flex flex-col md:flex-row transition-colors">
             <!-- Left: Identity & Details -->
             <div class="w-full md:w-5/12 p-16 bg-slate-50 dark:bg-slate-950/50 border-r border-slate-100 dark:border-white/5 space-y-12 shrink-0 transition-colors">
                <div class="space-y-4">
                    <span id="show_rent_id" class="px-5 py-2 rounded-2xl bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[10px] font-black uppercase tracking-[0.3em] italic inline-block transition-colors"></span>
                    <h3 class="text-5xl font-black text-slate-900 dark:text-white italic tracking-tighter uppercase leading-[0.9] transition-colors">Detail du <br> Contrat</h3>
                </div>

                <div class="space-y-12">
                    <div>
                        <div class="text-[9px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.2em] mb-4 italic transition-colors text-[24px]">Client</div>
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-amber-500 font-black text-slate-950 flex items-center justify-center text-2xl italic transition-colors" id="show_rent_user_initials"></div>
                            <div class="space-y-1">
                                <div id="show_rent_user_name" class="text-xl font-black text-slate-900 dark:text-white italic transition-colors"></div>
                                <div id="show_rent_user_email" class="text-[10px] text-slate-400 font-bold uppercase transition-colors"></div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-white dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-white/5 space-y-6 shadow-xl transition-colors">
                        <div class="flex justify-between items-center text-[10px] font-black italic uppercase transition-colors">
                            <span class="text-slate-400">Date Départ</span>
                            <span id="show_rent_start" class="text-slate-900 dark:text-white"></span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black italic uppercase transition-colors">
                            <span class="text-slate-400">Date Retour</span>
                            <span id="show_rent_end" class="text-slate-900 dark:text-white"></span>
                        </div>
                        <div class="pt-6 border-t border-slate-100 dark:border-white/5 flex justify-between items-baseline group transition-colors">
                            <span class="text-[10px] font-black text-amber-500 uppercase italic">Montant Total</span>
                            <span id="show_rent_amount" class="text-3xl font-black text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors italic transition-colors"></span>
                        </div>
                    </div>
                </div>
             </div>

             <!-- Right: Vehicle & Status -->
             <div class="w-full md:w-7/12 p-16 space-y-12 relative bg-white dark:bg-slate-900 transition-colors">
                <button onclick="closeModal('showRentalModal')" class="absolute top-12 right-12 p-4 bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-2xl transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>

                <div class="space-y-10 group transition-colors">
                    <div class="flex items-center gap-4 text-slate-400 dark:text-slate-600 transition-colors text-[24px]">
                        <i data-lucide="car-front" class="w-6 h-6"></i>
                        <h4 class="text-[11px] font-black uppercase tracking-[0.3em] italic">Spécifications Flotte</h4>
                    </div>

                    <div class="relative aspect-video rounded-[3rem] overflow-hidden shadow-2xl transition-colors">
                        <img id="show_rent_car_photo" src="" class="absolute inset-0 w-full h-full object-cover transition-transform group-hover:scale-110 duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-10 left-10">
                            <div id="show_rent_car_title" class="text-5xl font-black text-white italic tracking-tighter uppercase leading-none mb-2"></div>
                            <div id="show_rent_car_plate" class="text-xl font-black text-amber-500 italic tracking-widest bg-slate-950/40 backdrop-blur-xl px-6 py-2 rounded-xl inline-block"></div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 pt-4 transition-colors">
                        <div id="show_rent_status_badge" class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] italic border shadow-2xl transition-colors"></div>
                        <button class="px-8 py-3 rounded-2xl bg-slate-950 dark:bg-white text-white dark:text-slate-950 text-[10px] font-black uppercase tracking-[0.3em] italic hover:bg-amber-500 hover:text-slate-950 transition-colors shadow-2xl">Gérer l'inventaire</button>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
