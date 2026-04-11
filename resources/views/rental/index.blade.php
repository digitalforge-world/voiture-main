@extends('layouts.app')

@section('title', 'Location de Véhicules de Prestige - AutoImport Hub')

@section('styles')
<style>
    @keyframes slideInLeft {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    .modal-animate-in {
        animation: slideInLeft 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .modal-animate-out {
        animation: shake 0.5s ease-in-out, slideOutRight 0.5s cubic-bezier(0.7, 0, 0.84, 0) 0.5s forwards;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-500">
    <!-- Rental Hero Section -->
    <div class="relative py-12 lg:py-20 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-amber-500/10 via-transparent to-transparent opacity-50"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-50/50 dark:from-slate-900/50 to-white dark:to-slate-950 transition-colors"></div>
        
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="max-w-4xl space-y-10">
                <div class="inline-flex items-center gap-3 px-5 py-2  dark:border-white/10 rounded-full shadow-2xl animate-in fade-in slide-in-from-left duration-700">
                    <span class="relative flex h-2 w-2">
                    </span>
                </div>

                <div class="space-y-4 animate-in fade-in slide-in-from-bottom duration-1000">
                    <h1 class="text-2xl lg:text-3xl font-black leading-tight tracking-tighter text-slate-900 dark:text-white uppercase transition-colors">
                        L'art du <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600 italic font-serif normal-case tracking-normal">Voyage.</span>
                    </h1>
                    <p class="max-w-2xl text-base text-slate-600 dark:text-slate-400 leading-relaxed font-medium transition-colors">
                        Expérimentez l'excellence au volant de notre sélection exclusive. <br class="hidden lg:block"> Location premium à Lomé avec service de conciergerie personnalisé.
                    </p>
                </div>

                <div class="flex flex-wrap gap-4 pt-4 animate-in fade-in slide-in-from-bottom duration-1000 delay-300">
                    <a href="#parc" class="group relative px-10 py-5 bg-amber-500 rounded-2xl overflow-hidden shadow-2xl shadow-amber-500/20 transition-transform active:scale-95">
                        <div class="absolute inset-0 bg-slate-950 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                        <span class="relative text-xs font-black uppercase tracking-widest text-slate-950 group-hover:text-white transition-colors flex items-center gap-3">
                            Découvrir le parc <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Categories Bar -->
    <div id="parc" class="sticky top-[80px] lg:top-[80px] z-[40] bg-white/80 dark:bg-slate-950/80 backdrop-blur-2xl border-y border-slate-100 dark:border-white/5 py-6 transition-all duration-300">
        <div class="container px-4 mx-auto lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- Tabs -->
                <div class="flex items-center gap-1 p-1 bg-slate-50 dark:bg-slate-900/50 rounded-2xl w-fit border border-slate-100 dark:border-white/5 transition-colors">
                    <a href="{{ route('rental.index', ['category' => 'all']) }}" 
                       class="px-6 py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition {{ !request('category') || request('category') == 'all' ? 'bg-white dark:bg-slate-800 text-amber-500 shadow-xl shadow-black/5 dark:shadow-black' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">
                        Tous
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('rental.index', ['category' => $cat]) }}" 
                       class="px-6 py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition {{ request('category') == $cat ? 'bg-white dark:bg-slate-800 text-amber-500 shadow-xl shadow-black/5 dark:shadow-black' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                </div>

                <div class="flex items-center gap-4 text-slate-400 dark:text-slate-600 text-[10px] font-black tracking-widest italic transition-colors">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    {{ $voitures->total() }} véhicules disponibles
                </div>
            </div>
        </div>
    </div>

    <!-- Multi-Column Parc Grid -->
    <div class="container px-4 py-10 mx-auto lg:px-8">
        <div class="grid grid-cols-2 gap-4 lg:gap-8 lg:grid-cols-4">
            @forelse($voitures as $car)
                <div class="group relative bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden hover:border-amber-500/50 transition-all duration-500 shadow-sm hover:shadow-2xl hover:shadow-amber-500/10 flex flex-col h-full animate-in fade-in slide-in-from-bottom duration-700">
                    {{-- Card Image Section --}}
                    <div class="relative aspect-[16/10] overflow-hidden">
                        {{-- Hover Background Blur --}}
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                            <img src="{{ $car->photo_principale ?? 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1000' }}" class="absolute inset-0 w-full h-full object-cover scale-150 blur-3xl opacity-20">
                        </div>

                        {{-- Main Vehicle Image --}}
                        <img src="{{ $car->photo_principale ?? 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1000' }}" 
                             alt="{{ $car->marque }}" 
                             class="absolute inset-0 object-cover w-full h-full transition duration-1000 group-hover:scale-110">
                        
                        {{-- Dark Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                        
                        {{-- Badges --}}
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="px-3 py-1 bg-slate-950/60 backdrop-blur-md border border-white/10 text-[8px] font-black text-white tracking-[0.2em] rounded-full">
                                {{ $car->transmission }}
                            </span>
                            @if($car->categorie == 'premium' || $car->categorie == 'suv')
                            <span class="px-3 py-1 bg-amber-500 text-slate-950 text-[8px] font-black tracking-[0.2em] rounded-full shadow-lg shadow-amber-500/20">
                                Luxe
                            </span>
                            @endif
                        </div>

                        {{-- Year Badge --}}
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 text-[10px] font-black text-white rounded-lg">
                                {{ $car->annee ?? '2024' }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6 flex-grow flex flex-col space-y-6">
                        {{-- Header: Brand & Price --}}
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tighter italic leading-none transition-colors">
                                    {{ $car->marque }}
                                </h3>
                                <p class="text-[10px] font-black text-amber-500 tracking-[0.3em] leading-none">
                                    {{ $car->modele }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="block text-[8px] font-black text-slate-400 dark:text-slate-600 tracking-widest mb-1 italic">Par jour</span>
                                <span class="text-lg font-black text-slate-900 dark:text-white italic transition-colors group-hover:text-amber-500">
                                    {{ number_format($car->prix_jour, 0, ',', ' ') }}<span class="ml-1 text-[10px] font-bold">CFA</span>
                                </span>
                            </div>
                        </div>

                        {{-- Technical Specs Pills --}}
                        <div class="flex flex-wrap gap-2">
                            <div class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-lg flex items-center gap-2 transition-colors">
                                <i data-lucide="users" class="w-3 h-3 text-slate-400"></i>
                                <span class="text-[9px] font-bold text-slate-500">{{ $car->nombre_places }} places</span>
                            </div>
                            <div class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-lg flex items-center gap-2 transition-colors">
                                <i data-lucide="fuel" class="w-3 h-3 text-slate-400"></i>
                                <span class="text-[9px] font-bold text-slate-500">{{ $car->carburant }}</span>
                            </div>
                            <div class="px-3 py-1.5 bg-amber-500/5 border border-amber-500/10 rounded-lg flex items-center gap-2 transition-colors">
                                <i data-lucide="shield-check" class="w-3 h-3 text-amber-500"></i>
                                <span class="text-[9px] font-bold text-amber-500">Inclus</span>
                            </div>
                        </div>

                        {{-- Actions Section --}}
                        <div class="pt-4 flex items-center gap-2">
                            <button onclick='openDetailsModal(@json($car))' 
                                class="flex-1 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white rounded-xl text-[8px] font-black tracking-widest hover:border-amber-500 hover:text-amber-500 transition-all">
                                Détails
                            </button>
                            <button onclick='openBookingModal(@json($car))' 
                                class="flex-1 py-3.5 bg-amber-500 text-slate-950 rounded-xl text-[8px] font-black tracking-widest shadow-lg transition-all transform active:scale-[0.98] hover:bg-slate-950 hover:text-white">
                                Réserver
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-40 text-center space-y-8 animate-in zoom-in duration-1000">
                    <div class="w-32 h-32 bg-slate-50 dark:bg-slate-900 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-white/5 flex items-center justify-center mx-auto transition-colors">
                        <i data-lucide="car-off" class="w-12 h-12 text-slate-300 dark:text-slate-700"></i>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Aucun véhicule trouvé</h3>
                        <p class="text-slate-500 uppercase tracking-widest text-xs font-bold">Essayez une autre catégorie ou revenez plus tard.</p>
                    </div>
                    <a href="{{ route('rental.index') }}" class="inline-flex items-center gap-3 text-amber-500 text-[10px] font-black uppercase tracking-widest hover:gap-5 transition-all">
                        Réinitialiser les filtres <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                </div>
            @endforelse
        </div>

        @if($voitures->hasPages())
            <div class="mt-20 flex justify-center">
                {{ $voitures->links() }}
            </div>
        @endif
    </div>

    <!-- Features Row -->
    <div class="py-16 bg-slate-50 dark:bg-slate-900/30 transition-colors">
        <div class="container px-4 mx-auto lg:px-8">
            <div class="grid grid-cols-1 gap-16 md:grid-cols-3">
                <div class="space-y-6">
                    <div class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl shadow-xl flex items-center justify-center text-amber-500 transition-colors">
                        <i data-lucide="shield-check" class="w-8 h-8"></i>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter transition-colors">Sécurité Totale</h4>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium">Chaque véhicule bénéficie d'une assurance tous risques et d'un entretien rigoureux.</p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl shadow-xl flex items-center justify-center text-amber-500 transition-colors">
                        <i data-lucide="star" class="w-8 h-8"></i>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter transition-colors">Service Premium</h4>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium">Chauffeur en option, bouteille d'eau offerte et livraison à domicile ou aéroport.</p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl shadow-xl flex items-center justify-center text-amber-500 transition-colors">
                        <i data-lucide="key" class="w-8 h-8"></i>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter transition-colors">Flexibilité</h4>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium">Prolongation facile, annulation sans frais jusqu'à 24h et paiement sécurisé.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 z-[100] hidden overflow-hidden">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity" onclick="requestClose('bookingModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4 pointer-events-none">
        <div id="bookingModalContent" class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row transition-colors pointer-events-auto">
            <!-- Left Side: Summary -->
            <div class="w-full md:w-2/5 p-6 bg-slate-50 dark:bg-slate-950/50 border-r border-slate-100 dark:border-white/5 transition-colors">
                <div class="space-y-6">
                    <div class="space-y-1">
                        <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest italic">Votre sélection</span>
                        <h2 id="modal_car_name" class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none transition-colors italic"></h2>
                    </div>
                    <div class="rounded-3xl overflow-hidden shadow-2xl">
                        <img id="modal_car_img" src="" class="w-full h-48 object-cover">
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest transition-colors">
                            <span class="text-slate-400">Prix / Jour</span>
                            <span id="modal_car_price" class="text-slate-900 dark:text-white transition-colors"></span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest transition-colors">
                            <span class="text-slate-400">Caution</span>
                            <span id="modal_car_caution" class="text-slate-900 dark:text-white transition-colors"></span>
                        </div>
                        <div class="pt-4 border-t border-slate-200 dark:border-white/5 flex justify-between items-center">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-500">Total Estimation</span>
                            <span id="modal_total_est" class="text-2xl font-black text-slate-900 dark:text-white transition-colors">A calculer...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full md:w-3/5 p-8 bg-white dark:bg-slate-900 transition-colors">
                <button onclick="requestClose('bookingModal')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 dark:hover:text-white transition group/close">
                    <i data-lucide="x" class="w-5 h-5 transition-transform group-hover/close:rotate-90"></i>
                </button>
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-6 transition-colors italic border-l-4 border-amber-500 pl-4">Données de réservation</h3>
                
                <form id="bookingForm" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Début du contrat</label>
                            <input type="date" name="date_debut" id="date_debut" required onchange="calculateTotal()" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Fin du contrat</label>
                            <input type="date" name="date_fin" id="date_fin" required onchange="calculateTotal()" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Informations Client</label>
                        <div class="grid grid-cols-1 gap-4">
                            <input type="text" name="client_nom" required placeholder="NOM COMPLET" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                            <input type="tel" name="client_telephone" required placeholder="TÉLÉPHONE (+228...)" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                            <input type="email" name="client_email" placeholder="EMAIL (OPTIONNEL)" class="w-full py-4 px-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                        </div>
                    </div>

                    <p class="text-[9px] text-slate-400 font-bold uppercase leading-relaxed italic border-t border-slate-100 dark:border-white/5 pt-4">
                        En cliquant sur confirmer, vous initiez une demande de réservation. Notre service conciergerie vous contactera sous 30 minutes pour validation.
                    </p>

                    <button type="submit" class="w-full py-5 bg-amber-500 text-slate-950 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-amber-500/20 transition hover:bg-slate-950 hover:text-white group/submit">
                        Confirmer la réservation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 z-[100] hidden overflow-hidden">
    <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" onclick="requestClose('detailsModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4 pointer-events-none">
        <div id="detailsModalContent" class="relative w-full max-w-2xl bg-white dark:bg-slate-950 border border-white/5 rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row pointer-events-auto">
             <div class="w-full md:w-1/2 relative bg-slate-950 overflow-hidden">
                 <img id="details_car_img" src="" class="absolute inset-0 w-full h-full object-cover">
                 <div class="absolute inset-0 bg-gradient-to-r from-slate-950/60 to-transparent"></div>
                 <div class="absolute bottom-6 left-6 space-y-2">
                    <span id="details_car_cat" class="px-3 py-1 bg-amber-500 text-slate-950 text-[8px] font-black uppercase tracking-[0.3em] rounded-full"></span>
                    <h2 id="details_car_name" class="text-xl font-black text-white uppercase tracking-tighter leading-none italic"></h2>
                 </div>
             </div>
             <div class="w-full md:w-1/2 p-10 space-y-8 dark:text-white">
                <button onclick="requestClose('detailsModal')" class="absolute top-6 right-6 text-slate-400 hover:text-white transition group/close">
                    <i data-lucide="x" class="w-6 h-6 transition-transform group-hover/close:rotate-90"></i>
                </button>
                
                <div class="space-y-3">
                    <h3 class="text-[8px] font-black text-amber-500 uppercase tracking-[0.3em] italic">Spécifications</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[8px] font-black text-slate-500 uppercase block mb-0.5">Transmission</span>
                            <div id="details_transmission" class="text-xs font-black uppercase italic"></div>
                        </div>
                        <div>
                            <span class="text-[8px] font-black text-slate-500 uppercase block mb-0.5">Carburant</span>
                            <div id="details_fuel" class="text-xs font-black uppercase italic"></div>
                        </div>
                        <div>
                            <span class="text-[8px] font-black text-slate-500 uppercase block mb-0.5">Capacité</span>
                            <div id="details_capacity" class="text-xs font-black uppercase italic"></div>
                        </div>
                        <div>
                            <span class="text-[8px] font-black text-slate-500 uppercase block mb-0.5">État</span>
                            <div id="details_condition" class="text-xs font-black uppercase italic"></div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-[8px] font-black text-amber-500 uppercase tracking-[0.3em] italic">Équipements</h3>
                    <p id="details_equipments" class="text-xs text-slate-500 leading-relaxed font-medium italic"></p>
                </div>

                <div class="pt-6 border-t border-white/5 space-y-6">
                     <div class="flex justify-between items-baseline">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Tarif / jour</span>
                        <div class="text-2xl font-black text-amber-500 italic"><span id="details_price"></span> <span class="text-xs">CFA</span></div>
                     </div>
                     <button onclick="closeDetailsAndOpenBook()" class="w-full py-4 bg-white text-slate-950 rounded-xl text-[9px] font-black uppercase tracking-[0.3em] shadow-2xl shadow-white/5 transition hover:bg-amber-500 active:scale-95">
                        Réserver maintenant
                     </button>
                </div>
             </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    let currentCar = null;

    function openBookingModal(car) {
        currentCar = car;
        const modal = document.getElementById('bookingModal');
        const content = document.getElementById('bookingModalContent');
        const form = document.getElementById('bookingForm');
        
        form.action = `/location/${car.id}/reserver`;
        document.getElementById('modal_car_name').innerText = `${car.marque} ${car.modele}`;
        document.getElementById('modal_car_img').src = car.photo_principale || 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1000';
        document.getElementById('modal_car_price').innerText = new Intl.NumberFormat('fr-FR').format(car.prix_jour) + ' FCFA';
        document.getElementById('modal_car_caution').innerText = new Intl.NumberFormat('fr-FR').format(car.caution) + ' FCFA';
        
        modal.classList.remove('hidden');
        content.classList.remove('modal-animate-out');
        content.classList.add('modal-animate-in');
        document.body.style.overflow = 'hidden';
        calculateTotal();
    }

    function openDetailsModal(car) {
        currentCar = car;
        const modal = document.getElementById('detailsModal');
        const content = document.getElementById('detailsModalContent');
        
        document.getElementById('details_car_img').src = car.photo_principale || 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1000';
        document.getElementById('details_car_name').innerText = `${car.marque} ${car.modele}`;
        document.getElementById('details_car_cat').innerText = (car.categorie || 'SÉLECTION').toUpperCase();
        document.getElementById('details_transmission').innerText = (car.transmission || 'N/A').toUpperCase();
        document.getElementById('details_fuel').innerText = (car.carburant || 'N/A').toUpperCase();
        document.getElementById('details_capacity').innerText = `${car.nombre_places || 5} PLACES`;
        document.getElementById('details_condition').innerText = (car.etat_general || 'EXCELLENT').toUpperCase();
        document.getElementById('details_equipments').innerText = car.equipements || 'Climatisation, Bluetooth, Radio, Assurance tous risques incluse.';
        document.getElementById('details_price').innerText = new Intl.NumberFormat('fr-FR').format(car.prix_jour);

        modal.classList.remove('hidden');
        content.classList.remove('modal-animate-out');
        content.classList.add('modal-animate-in');
        document.body.style.overflow = 'hidden';
    }

    function requestClose(id) {
        const contentId = id + 'Content';
        const content = document.getElementById(contentId);
        content.classList.remove('modal-animate-in');
        content.classList.add('modal-animate-out');
        
        // Wait for animations: 0.5s shake + 0.5s slideOut = 1s
        setTimeout(() => {
            closeModal(id);
        }, 1000);
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function closeDetailsAndOpenBook() {
        if(!currentCar) return;
        requestClose('detailsModal');
        setTimeout(() => openBookingModal(currentCar), 1100);
    }

    function calculateTotal() {
        const start = document.getElementById('date_debut').value;
        const end = document.getElementById('date_fin').value;
        const totalEl = document.getElementById('modal_total_est');

        if (start && end && currentCar) {
            const d1 = new Date(start);
            const d2 = new Date(end);
            const diff = d2.getTime() - d1.getTime();
            const days = Math.ceil(diff / (1000 * 3600 * 24));

            if (days > 0) {
                const total = (days * currentCar.prix_jour) + parseFloat(currentCar.caution);
                totalEl.innerText = new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
                totalEl.classList.add('text-amber-500');
            } else {
                totalEl.innerText = 'Fin invalide';
                totalEl.classList.remove('text-amber-500');
            }
        }
    }

    window.addEventListener('keydown', (e) => {
        if(e.key === 'Escape') {
            closeModal('bookingModal');
            closeModal('detailsModal');
        }
    });

    // Set min date to today for inputs
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date_debut')?.setAttribute('min', today);
    document.getElementById('date_fin')?.setAttribute('min', today);
</script>
@endsection

