@extends('layouts.app')

@section('content')
    <!-- Scripts requis (Alpine, Lucide, Lottie) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <div class="bg-white dark:bg-slate-950 font-sans selection:bg-amber-500 selection:text-white" x-data="cartSystem()" x-init="lucide.createIcons();" @add-to-cart.window="addToCart($event.detail)">
    <style>
        html { scroll-behavior: smooth; }
        .fly-item {
            position: fixed;
            z-index: 9999;
            pointer-events: none;
            transition: all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>

    <!-- HERO SECTION - AVEC ZONE ANIMATION -->
    <section class="relative min-h-[70vh] flex items-center bg-slate-950 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?q=80&w=2000" class="w-full h-full object-cover opacity-10 grayscale">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Gauche : Texte -->
                <div class="animate-in fade-in slide-in-from-left-10 duration-1000 max-w-lg">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-6 h-[1.5px] bg-amber-500"></div>
                        <span class="text-[9px] font-black text-amber-500 uppercase tracking-[0.4em]">Catalogue Pièces d'Origine</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-5xl font-black text-white leading-none tracking-tighter mb-8 uppercase italic whitespace-nowrap">
                        SOLUTIONS <span class="text-amber-500 underline underline-offset-8 decoration-1">TECHNIQUES</span> DE PRÉCISION.
                    </h1>
                    <p class="text-slate-400 text-[11px] font-black max-w-md leading-relaxed mb-10 uppercase tracking-widest opacity-60">
                        Accédez au stock le plus complet de pièces certifiées. Performance et durabilité garanties pour chaque composant mécanique.
                    </p>
                    <div class="flex">
                        <a href="#inventaire" class="px-8 py-4 bg-amber-500 hover:bg-white text-slate-950 font-black text-[10px] uppercase tracking-[0.4em] rounded-sm transition-all shadow-xl italic">
                            Découvrir le Stock
                        </a>
                    </div>
                </div>

                <!-- Droite : Zone Animation (Dessin Animé) -->
                <div class="flex justify-center items-center h-full animate-in zoom-in duration-1000 delay-300">
                    <!-- Ton animation personnalisée (Mécanicien supervisant une pièce) -->
                    <lottie-player 
                        src="{{ asset('animations/services.json') }}" 
                        background="transparent" 
                        speed="1" 
                        style="width: 500px; height: 500px;" 
                        loop 
                        autoplay>
                    </lottie-player>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION CATALOGUE - GRILLE 4 COLONNES COMPACTE -->
    <section id="inventaire" class="py-16 bg-slate-50 dark:bg-slate-950">
        <div class="container mx-auto px-6">
            
            <!-- Filtres -->
            <div class="flex flex-wrap items-center justify-between gap-6 mb-12 border-b border-slate-200 dark:border-slate-800 pb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-950 dark:text-white uppercase tracking-tighter italic">Notre <span class="text-amber-500">Inventaire</span></h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="/pieces" class="px-4 py-1.5 border border-slate-300 dark:border-slate-700 text-[9px] font-black uppercase tracking-widest rounded-sm hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-slate-950 transition-all {{ !request('categorie') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : '' }}">Toutes</a>
                    @foreach($categories as $cat)
                        <a href="?categorie={{ $cat }}" class="px-4 py-1.5 border border-slate-300 dark:border-slate-700 text-[9px] font-black uppercase tracking-widest rounded-sm hover:bg-amber-500 hover:border-amber-500 hover:text-white transition-all {{ request('categorie') == $cat ? 'bg-amber-500 border-amber-500 text-white' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Grille Optimisée & Dynamique -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($pieces as $piece)
                    <div class="group bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 hover:-translate-y-2 flex flex-col overflow-hidden relative">
                        
                        <!-- Badge d'état (En Stock) -->
                        <div class="absolute top-4 right-4 z-20 flex items-center gap-1.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm px-2 py-1 rounded-full border border-slate-100 dark:border-slate-800 shadow-sm">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-[7px] font-black uppercase tracking-widest text-slate-500">En Stock</span>
                        </div>

                        <!-- Image Container avec Gradient -->
                        <div class="aspect-square bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/10 dark:to-slate-800/30 flex items-center justify-center p-8 relative overflow-hidden">
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-from)_0%,_transparent_70%)] opacity-50"></div>
                            @if($piece->image && file_exists(public_path('images/pieces/' . $piece->image)))
                                <img src="{{ asset('images/pieces/' . $piece->image) }}" class="w-full h-full object-contain group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700 relative z-10">
                            @else
                                <div class="flex flex-col items-center gap-2 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i data-lucide="cog" class="w-16 h-16 text-slate-900 dark:text-white"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Content Raffiné -->
                        <div class="p-5 flex-1 space-y-3">
                            <div class="inline-block px-2 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-500 text-[8px] font-black uppercase tracking-widest rounded-sm">
                                {{ $piece->categorie }}
                            </div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-tight group-hover:text-amber-500 transition-colors">{{ $piece->nom }}</h3>
                            <p class="text-[10px] text-slate-400 font-medium line-clamp-2 leading-relaxed italic">
                                {{ $piece->description ?? 'Composant haute performance certifié.' }}
                            </p>
                        </div>

                        <!-- Price & Button Bar (Vibrante) -->
                        <div class="p-5 pt-0">
                            <div class="flex h-11 w-full overflow-hidden rounded-lg shadow-sm group-hover:shadow-md transition-shadow">
                                <!-- Prix -->
                                <div class="bg-slate-50 dark:bg-slate-800/50 flex-1 flex items-center px-4 font-black text-slate-950 dark:text-white text-xs italic tracking-tighter border-y border-l border-slate-100 dark:border-slate-800" 
                                     style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);">
                                    {{ number_format($piece->prix, 0, ',', ' ') }} <span class="ml-1 text-[8px] opacity-40">FCFA</span>
                                </div>
                                
                                <!-- Bouton -->
                                <button @click="handleAddToCart($event, {{ json_encode($piece) }})" 
                                        class="bg-slate-950 dark:bg-amber-500 hover:bg-amber-500 dark:hover:bg-white text-white dark:text-slate-950 flex-[1.2] -ml-4 flex items-center justify-center text-[9px] font-black uppercase tracking-widest transition-all group-hover:gap-2 active:scale-95"
                                        style="clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%);">
                                    <span>Ajouter</span>
                                    <i data-lucide="shopping-cart" class="w-3 h-3 hidden group-hover:block transition-all"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- ... vide ... -->
                @endforelse
            </div>

            <div class="mt-12">
                {{ $pieces->links() }}
            </div>
        </div>
    </section>

    <!-- PANIER FLOTTANT (STYLE CHAT) - RESTE IDENTIQUE CAR TRÈS PRATIQUE -->
    <div class="fixed bottom-8 right-8 z-[100]" x-cloak>
        <button @click="isCartOpen = true" 
                class="w-16 h-16 bg-amber-500 text-slate-950 rounded-full shadow-2xl flex items-center justify-center relative hover:scale-110 transition-all duration-300 ring-4 ring-white dark:ring-slate-900"
                id="cart-bubble">
            <i data-lucide="shopping-bag" class="w-8 h-8"></i>
            <div x-show="cartCount > 0" 
                 x-text="cartCount" 
                 class="absolute -top-1 -right-1 bg-slate-950 text-white text-[10px] font-black w-6 h-6 rounded-full border-2 border-white dark:border-slate-900 flex items-center justify-center"></div>
        </button>
    </div>

    <!-- MODAL PANIER (RESTE IDENTIQUE) -->
    <div x-show="isCartOpen" 
         class="fixed inset-0 z-[200] flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        
        <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-md" @click="isCartOpen = false"></div>

        <div class="relative bg-white dark:bg-slate-900 w-full max-w-3xl overflow-hidden flex flex-col rounded-sm shadow-2xl">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h2 class="text-xl font-black text-slate-950 dark:text-white uppercase tracking-tighter italic">Votre <span class="text-amber-500">Panier</span></h2>
                <button @click="isCartOpen = false" class="text-slate-400 hover:text-red-500"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 max-h-[60vh]">
                <template x-if="cart.length === 0">
                    <p class="text-center py-10 font-black uppercase tracking-widest text-slate-400 text-xs">Le panier est vide</p>
                </template>
                <div class="space-y-4">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex items-center gap-4 p-4 border border-slate-100 dark:border-slate-800">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 flex items-center justify-center rounded-sm">
                                <i data-lucide="package" class="w-6 h-6 text-slate-400"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-xs text-slate-900 dark:text-white uppercase" x-text="item.nom"></h4>
                                <p class="text-[8px] font-bold text-slate-400" x-text="item.reference"></p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-black text-slate-950 dark:text-white italic" x-text="formatPrice(item.prix)"></div>
                                <button @click="removeFromCart(index)" class="text-[8px] text-red-500 font-black uppercase tracking-widest mt-1">Enlever</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="cart.length > 0" class="p-6 bg-slate-50 dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total à payer</span>
                    <span class="text-3xl font-black text-slate-950 dark:text-white italic tracking-tighter" x-text="formatPrice(cartTotal)"></span>
                </div>
                <form @submit.prevent="checkout()" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" placeholder="NOM" required class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-4 py-3 text-[10px] font-black uppercase tracking-widest outline-none focus:border-amber-500 transition-all">
                    <input type="tel" placeholder="TÉLÉPHONE" required class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-4 py-3 text-[10px] font-black uppercase tracking-widest outline-none focus:border-amber-500 transition-all">
                    <button type="submit" class="md:col-span-2 py-4 bg-amber-500 text-slate-950 font-black text-[10px] uppercase tracking-[0.3em] hover:bg-slate-950 hover:text-white transition-all italic">
                        CONFIRMER LA COMMANDE 🚀
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL SUCCÈS COMMANDE (CORRIGÉ : SORTI DE LA MODAL PANIER) -->
    <div x-show="isSuccessOpen" 
         class="fixed inset-0 z-[300] flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        
        <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-xl" @click="isSuccessOpen = false"></div>

        <div class="relative bg-white dark:bg-slate-900 w-full max-w-lg p-10 text-center rounded-xl shadow-2xl border border-amber-500/20">
            <div class="mb-6 flex justify-center">
                <div class="w-16 h-16 bg-amber-500 rounded-full flex items-center justify-center animate-bounce">
                    <i data-lucide="check" class="w-8 h-8 text-slate-950"></i>
                </div>
            </div>
            
            <h2 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic mb-4">
                Commande <span class="text-amber-500">Transmise !</span>
            </h2>
            
            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest leading-relaxed mb-8">
                Votre demande a été enregistrée avec succès. Nos techniciens analysent votre commande et vous contacteront sous peu.
            </p>

            <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-lg border border-slate-100 dark:border-slate-700 mb-8">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2 text-center">Numéro de Suivi</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white tracking-widest uppercase italic" x-text="trackingNumber"></div>
            </div>

            <button @click="isSuccessOpen = false" class="w-full py-5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-black text-[10px] uppercase tracking-[0.4em] hover:bg-amber-500 dark:hover:bg-amber-500 dark:hover:text-slate-950 transition-all italic rounded-sm shadow-xl">
                Retour au Catalogue
            </button>
        </div>
    </div>
</div>

<script>
    function cartSystem() {
        return {
            cart: [],
            isCartOpen: false,
            isSuccessOpen: false,
            trackingNumber: '',
            cartCount: 0,
            cartTotal: 0,

            handleAddToCart(event, piece) {
                const btn = event.currentTarget;
                const rect = btn.getBoundingClientRect();
                const cartBtn = document.getElementById('cart-bubble');
                const cartRect = cartBtn.getBoundingClientRect();

                const fly = document.createElement('div');
                fly.className = 'fly-item bg-amber-500 w-8 h-8 rounded-full flex items-center justify-center shadow-xl';
                fly.innerHTML = '<i data-lucide="package" style="width:16px;height:16px;color:black"></i>';
                fly.style.left = rect.left + 'px';
                fly.style.top = rect.top + 'px';
                document.body.appendChild(fly);
                lucide.createIcons();

                setTimeout(() => {
                    fly.style.left = (cartRect.left + 15) + 'px';
                    fly.style.top = (cartRect.top + 15) + 'px';
                    fly.style.transform = 'scale(0.1) rotate(180deg)';
                    fly.style.opacity = '0';
                }, 10);

                setTimeout(() => {
                    document.body.removeChild(fly);
                    this.addToCart(piece);
                }, 800);
            },

            addToCart(item) {
                this.cart.push(item);
                this.updateTotals();
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
                this.updateTotals();
            },

            updateTotals() {
                this.cartCount = this.cart.length;
                this.cartTotal = this.cart.reduce((sum, item) => sum + parseFloat(item.prix), 0);
            },

            formatPrice(price) {
                return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
            },

            checkout() {
                const clientNom = document.querySelector('input[placeholder="NOM"]').value;
                const clientTel = document.querySelector('input[placeholder="TÉLÉPHONE"]').value;

                if (!clientNom || !clientTel) {
                    alert('Veuillez remplir vos informations.');
                    return;
                }

                // Désactiver le bouton pendant l'envoi
                const btn = event.target.querySelector('button[type="submit"]');
                if(btn) {
                    btn.disabled = true;
                    btn.innerText = 'TRAITEMENT EN COURS...';
                }

                fetch('/api/pieces/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        items: this.cart,
                        client_nom: clientNom,
                        client_telephone: clientTel
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.trackingNumber = data.tracking_number;
                        this.isCartOpen = false;
                        
                        setTimeout(() => {
                            this.isSuccessOpen = true;
                            this.cart = [];
                            this.updateTotals();
                            setTimeout(() => lucide.createIcons(), 100);
                        }, 300);
                    } else {
                        alert('Erreur : ' + data.message);
                        if(btn) {
                            btn.disabled = false;
                            btn.innerText = 'CONFIRMER LA COMMANDE 🚀';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la commande.');
                    if(btn) {
                        btn.disabled = false;
                        btn.innerText = 'CONFIRMER LA COMMANDE 🚀';
                    }
                });
            }
        }
    }
</script>
@endsection
