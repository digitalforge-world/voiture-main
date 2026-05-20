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
        <div id="search-section" class="flex-grow flex flex-col justify-center p-6 sm:p-10 lg:p-12 xl:p-16">
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
                
                <div id="error-container" class="hidden p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="flex items-center gap-2 text-rose-500">
                        <i data-lucide="octagon-alert" class="w-3.5 h-3.5 flex-shrink-0"></i>
                        <p id="error-text" class="text-[10px] font-bold leading-tight"></p>
                    </div>
                </div>

                @if(session('error') || $errors->any())
                    <div id="session-error-container" class="p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl animate-in fade-in slide-in-from-top-4 duration-500">
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

        <div id="results-section" class="hidden flex-grow flex flex-col overflow-y-auto p-6 sm:p-10 lg:p-12 xl:p-16">
            <div class="mb-6">
                <button id="btn-back-search" class="inline-flex items-center text-sm text-slate-500 dark:text-slate-400 hover:text-amber-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                    Retour à la recherche
                </button>
            </div>
            <div id="results-content"></div>
        </div>

        {{-- Features Grid at the bottom of the right section --}}
        <div id="features-grid" class="px-8 py-6 lg:px-12 lg:py-8 mt-auto border-t border-slate-100 dark:border-slate-800 bg-slate-50/30">
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const searchSection = document.getElementById('search-section');
    const resultsSection = document.getElementById('results-section');
    const resultsContent = document.getElementById('results-content');
    const featuresGrid = document.getElementById('features-grid');
    const errorContainer = document.getElementById('error-container');
    const errorText = document.getElementById('error-text');
    const sessionErrorContainer = document.getElementById('session-error-container');
    const submitBtn = form.querySelector('button[type="submit"]');
    const trackingInput = document.getElementById('tracking_number');
    const btnBackSearch = document.getElementById('btn-back-search');
    
    // Original button innerHTML
    const originalBtnHtml = submitBtn.innerHTML;
    const originalTitle = document.title;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous error
        errorContainer.classList.add('hidden');
        errorText.textContent = '';
        if (sessionErrorContainer) {
            sessionErrorContainer.classList.add('hidden');
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="inline-flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-[10px] uppercase tracking-widest">Recherche en cours...</span>
            </span>
        `;
        
        const trackingNumber = trackingInput.value.trim();
        const csrfToken = form.querySelector('input[name="_token"]').value;
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                tracking_number: trackingNumber
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            
            if (data.success) {
                // Populate results
                resultsContent.innerHTML = data.html;
                
                // Hide search form and features grid
                searchSection.classList.add('hidden');
                featuresGrid.classList.add('hidden');
                
                // Show results section
                resultsSection.classList.remove('hidden');
                
                // Reinitialize Lucide icons for dynamically loaded HTML
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                
                // Update page title
                document.title = 'Détails de la Commande ' + data.tracking;
                
                // Track back to search inside dynamically loaded content
                const dynamicBackBtn = resultsContent.querySelector('.back-to-search');
                if (dynamicBackBtn) {
                    dynamicBackBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        goBackToSearch();
                    });
                }
            } else {
                showError(data.error || 'Une erreur est survenue.');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            showError(err.error || 'Une erreur est survenue lors de la recherche. Veuillez vérifier votre connexion.');
        });
    });
    
    function goBackToSearch() {
        resultsSection.classList.add('hidden');
        searchSection.classList.remove('hidden');
        featuresGrid.classList.remove('hidden');
        document.title = originalTitle;
    }
    
    if (btnBackSearch) {
        btnBackSearch.addEventListener('click', function(e) {
            e.preventDefault();
            goBackToSearch();
        });
    }
    
    function showError(message) {
        errorText.textContent = message;
        errorContainer.classList.remove('hidden');
    }
});
</script>
@endsection
