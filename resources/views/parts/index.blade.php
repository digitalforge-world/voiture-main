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
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <!-- HERO SECTION -->
    <section class="relative min-h-[60vh] lg:min-h-[70vh] flex items-center bg-slate-950 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?q=80&w=2000" class="w-full h-full object-cover opacity-10 grayscale">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Gauche : Texte -->
                <div class="lg:animate-in lg:fade-in lg:slide-in-from-left-10 lg:duration-1000 max-w-lg">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-6 h-[1.5px] bg-amber-500"></div>
                        <span class="text-[9px] font-black text-amber-500 uppercase tracking-[0.4em]">Catalogue Pièces d'Origine</span>
                    </div>
                    <h1 class="text-3xl lg:text-5xl font-black text-white leading-none tracking-tighter mb-8 uppercase italic">
                        SOLUTIONS <span class="text-amber-500 underline underline-offset-8 decoration-1">TECHNIQUES</span> DE PRÉCISION.
                    </h1>
                    <p class="text-slate-400 text-[10px] lg:text-[11px] font-black max-w-md leading-relaxed mb-10 uppercase tracking-widest opacity-60">
                        Accédez au stock le plus complet de pièces certifiées. Performance et durabilité garanties pour chaque composant mécanique.
                    </p>
                    <div class="flex">
                        <a href="#inventaire" class="px-8 py-4 bg-amber-500 hover:bg-white text-slate-950 font-black text-[10px] uppercase tracking-[0.4em] rounded-sm transition-all shadow-xl italic">
                            Découvrir le Stock
                        </a>
                    </div>
                </div>

                <!-- Droite : Zone Animation (Visible sur Desktop) -->
                <div class="hidden lg:flex justify-center items-center h-full lg:animate-in lg:zoom-in lg:duration-1000 lg:delay-300">
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

    <!-- SECTION CATALOGUE -->
    <section id="inventaire" class="py-16 bg-slate-50 dark:bg-slate-950">
        <div class="container mx-auto px-6">
            
            <!-- Filtres -->
            <div class="flex flex-wrap items-center justify-between gap-6 mb-12 border-b border-slate-200 dark:border-slate-800 pb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-950 dark:text-white uppercase tracking-tighter italic">Notre <span class="text-amber-500">Inventaire</span></h2>
                </div>
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2 max-w-full">
                    <a href="/pieces" class="whitespace-nowrap px-4 py-2 border border-slate-300 dark:border-slate-700 text-[9px] font-black uppercase tracking-widest rounded-sm hover:bg-slate-950 dark:hover:bg-white hover:text-white dark:hover:text-slate-950 transition-all {{ !request('categorie') ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : '' }}">Toutes</a>
                    @foreach($categories as $cat)
                        <a href="?categorie={{ $cat }}" class="whitespace-nowrap px-4 py-2 border border-slate-300 dark:border-slate-700 text-[9px] font-black uppercase tracking-widest rounded-sm hover:bg-amber-500 hover:border-amber-500 hover:text-white transition-all {{ request('categorie') == $cat ? 'bg-amber-500 border-amber-500 text-white' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Grille de Pièces (2 colonnes sur mobile) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-8">
                @forelse($pieces as $piece)
                    <div class="group bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl hover:shadow-2xl transition-all duration-500 flex flex-col overflow-hidden relative">
                        
                        <!-- Badge stock -->
                        <div class="absolute top-2 right-2 lg:top-4 lg:right-4 z-20 flex items-center gap-1 bg-white/90 dark:bg-slate-900/90 px-1.5 py-0.5 rounded-full border border-slate-100 dark:border-slate-800 shadow-sm">
                            <span class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-[6px] lg:text-[7px] font-black uppercase tracking-widest text-slate-500">Stock</span>
                        </div>

                        <!-- Image -->
                        <div class="aspect-square bg-slate-50 dark:bg-slate-800/10 flex items-center justify-center p-4 lg:p-8 relative">
                            @if($piece->image && file_exists(public_path('images/pieces/' . $piece->image)))
                                <img src="{{ asset('images/pieces/' . $piece->image) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="opacity-10"><i data-lucide="cog" class="w-10 lg:w-16 h-10 lg:h-16 text-slate-900 dark:text-white"></i></div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-3 lg:p-5 flex-1 space-y-2">
                             <div class="inline-block px-1.5 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-500 text-[6px] lg:text-[7px] font-black uppercase tracking-widest rounded-sm">
                                {{ $piece->categorie }}
                            </div>
                            <h3 class="text-[10px] lg:text-xs font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-tight line-clamp-2 h-7 lg:h-8">{{ $piece->nom }}</h3>
                        </div>

                        <!-- Price & Action -->
                        <div class="p-3 lg:p-5 pt-0">
                            <div class="flex h-9 lg:h-11 w-full overflow-hidden rounded-lg shadow-sm">
                                <div class="bg-slate-50 dark:bg-slate-800/50 flex-1 flex items-center px-2 lg:px-4 font-black text-slate-950 dark:text-white text-[8px] lg:text-xs italic tracking-tighter border-y border-l border-slate-100 dark:border-slate-800" style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);">
                                    {{ number_format($piece->prix, 0, ',', ' ') }}<span class="ml-0.5 text-[6px]">CFA</span>
                                </div>
                                <button @click="handleAddToCart($event, {{ json_encode($piece) }})" class="bg-slate-950 dark:bg-amber-500 text-white dark:text-slate-950 flex-[1.4] -ml-4 flex items-center justify-center text-[7px] lg:text-[9px] font-black uppercase tracking-widest active:scale-95 transition-all" style="clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%);">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center text-xs font-black uppercase tracking-widest text-slate-400">Aucune pièce disponible</div>
                @endforelse
            </div>

            <div class="mt-12 flex justify-center">
                {{ $pieces->links() }}
            </div>
        </div>
    </section>

    <!-- PANIER FLOTTANT -->
    <div class="fixed bottom-24 right-4 lg:right-8 z-[100]" x-cloak>
        <button @click="isCartOpen = true" 
                class="w-14 lg:w-16 h-14 lg:h-16 bg-amber-500 text-slate-950 rounded-full shadow-2xl flex items-center justify-center relative hover:scale-110 transition-all ring-4 ring-white dark:ring-slate-900"
                id="cart-bubble">
            <i data-lucide="shopping-bag" class="w-6 lg:w-8 h-6 lg:h-8"></i>
            <div x-show="cartCount > 0" 
                 x-text="cartCount" 
                 class="absolute -top-1 -right-1 bg-slate-950 text-white text-[10px] font-black w-6 h-6 rounded-full border-2 border-white dark:border-slate-900 flex items-center justify-center"></div>
        </button>
    </div>

    <!-- MODAL PANIER -->
    <div x-show="isCartOpen" 
         class="fixed inset-0 z-[200] flex items-center justify-center p-4" 
         x-transition x-cloak>
        <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-md" @click="isCartOpen = false"></div>
        <div class="relative bg-white dark:bg-slate-900 w-full max-w-2xl overflow-hidden flex flex-col rounded-xl shadow-2xl transition-colors">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h2 class="text-lg font-black text-slate-950 dark:text-white uppercase tracking-tighter italic">Votre <span class="text-amber-500">Panier</span></h2>
                <button @click="isCartOpen = false" class="text-slate-400 hover:text-red-500"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 max-h-[50vh]">
                <template x-if="cart.length === 0">
                    <p class="text-center py-10 font-black uppercase tracking-widest text-slate-400 text-[10px]">Le panier est vide</p>
                </template>
                <div class="space-y-3">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex items-center gap-4 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-700">
                            <div class="w-10 h-10 bg-white dark:bg-slate-900 flex items-center justify-center rounded-lg shadow-sm">
                                <i data-lucide="package" class="w-5 h-5 text-amber-500"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-[10px] text-slate-900 dark:text-white uppercase truncate" x-text="item.nom"></h4>
                                <div class="text-[9px] font-black text-amber-500 italic" x-text="formatPrice(item.prix)"></div>
                            </div>
                            <button @click="removeFromCart(index)" class="p-2 text-slate-400 hover:text-red-500 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="cart.length > 0" class="p-6 bg-slate-50 dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total</span>
                    <span class="text-2xl font-black text-slate-950 dark:text-white italic tracking-tighter" x-text="formatPrice(cartTotal)"></span>
                </div>
                <form @submit.prevent="checkout()" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" placeholder="NOM COMPLET" required class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-4 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg focus:border-amber-500 outline-none">
                        <input type="tel" placeholder="TÉLÉPHONE" required class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 px-4 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg focus:border-amber-500 outline-none">
                    </div>
                    <button type="submit" class="w-full py-4 bg-amber-500 text-slate-950 font-black text-[10px] uppercase tracking-[0.3em] rounded-lg shadow-xl shadow-amber-500/20 hover:bg-slate-950 hover:text-white transition-all italic">
                        Valider la commande 🚀
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL SUCCÈS -->
    <div x-show="isSuccessOpen" class="fixed inset-0 z-[300] flex items-center justify-center p-4" x-transition x-cloak>
        <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-xl" @click="isSuccessOpen = false"></div>
        <div class="relative bg-white dark:bg-slate-900 w-full max-w-md p-10 text-center rounded-2xl shadow-2xl border border-amber-500/20">
            <div class="mb-6 flex justify-center">
                <div class="w-16 h-16 bg-amber-500 rounded-full flex items-center justify-center animate-bounce shadow-xl shadow-amber-500/20">
                    <i data-lucide="check" class="w-8 h-8 text-slate-950"></i>
                </div>
            </div>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic mb-4">Commande <span class="text-amber-500">Transmise !</span></h2>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-relaxed mb-8">Nous traitons votre demande. Un technicien vous contactera sous 30 minutes.</p>
            <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-xl border border-slate-100 dark:border-slate-700 mb-8">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Code Suivi</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white tracking-widest italic" x-text="trackingNumber"></div>
            </div>
            <button @click="isSuccessOpen = false" class="w-full py-5 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-black text-[10px] uppercase tracking-[0.4em] rounded-xl transition-all shadow-xl italic">Retour au Catalogue</button>
        </div>
    </div>
</div>

<script>
    function cartSystem() {
        return {
            cart: [], isCartOpen: false, isSuccessOpen: false, trackingNumber: '', cartCount: 0, cartTotal: 0,
            handleAddToCart(event, piece) {
                const btn = event.currentTarget;
                const rect = btn.getBoundingClientRect();
                const cartBtn = document.getElementById('cart-bubble');
                const cartRect = cartBtn.getBoundingClientRect();
                const fly = document.createElement('div');
                fly.className = 'fly-item bg-amber-500 w-6 h-6 rounded-full flex items-center justify-center shadow-xl';
                fly.innerHTML = '<i data-lucide="package" style="width:12px;height:12px;color:black"></i>';
                fly.style.left = rect.left + 'px'; fly.style.top = rect.top + 'px';
                document.body.appendChild(fly); lucide.createIcons();
                setTimeout(() => {
                    fly.style.left = (cartRect.left + 15) + 'px'; fly.style.top = (cartRect.top + 15) + 'px';
                    fly.style.transform = 'scale(0.1) rotate(180deg)'; fly.style.opacity = '0';
                }, 10);
                setTimeout(() => { document.body.removeChild(fly); this.addToCart(piece); }, 800);
            },
            addToCart(item) { this.cart.push(item); this.updateTotals(); },
            removeFromCart(index) { this.cart.splice(index, 1); this.updateTotals(); },
            updateTotals() { this.cartCount = this.cart.length; this.cartTotal = this.cart.reduce((sum, item) => sum + parseFloat(item.prix), 0); },
            formatPrice(price) { return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA'; },
            checkout() {
                const clientNom = document.querySelector('input[placeholder="NOM COMPLET"]').value;
                const clientTel = document.querySelector('input[placeholder="TÉLÉPHONE"]').value;
                if (!clientNom || !clientTel) return;
                fetch('/api/pieces/checkout', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ items: this.cart, client_nom: clientNom, client_telephone: clientTel })
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        this.trackingNumber = data.tracking_number; this.isCartOpen = false;
                        setTimeout(() => { this.isSuccessOpen = true; this.cart = []; this.updateTotals(); }, 300);
                    }
                });
            }
        }
    }
</script>
@endsection
