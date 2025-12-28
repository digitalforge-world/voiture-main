@extends('layouts.app')

@section('title', 'Pièces Détachées Certifiées')

@section('content')
<div class="min-h-screen bg-slate-950">
    <!-- Hero/Header -->
    <div class="relative py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 via-slate-900/40 to-slate-950"></div>
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3"></div>
        
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="grid items-center gap-16 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="p-2 bg-amber-500 rounded-lg text-slate-950 shadow-lg shadow-amber-900/20">
                            <i data-lucide="package-search" class="w-6 h-6"></i>
                        </span>
                        <span class="text-sm font-black tracking-[0.2em] uppercase text-amber-500">Magasin de Pièces</span>
                    </div>
                    <h1 class="text-5xl font-black leading-tight tracking-tight text-white lg:text-7xl">
                        Qualité <span class="italic font-serif text-amber-500">Garantie</span> <br>
                        Précision Mécanique.
                    </h1>
                    <p class="max-w-xl mt-6 text-lg text-slate-400">
                        Trouvez exactement ce qu'il vous faut grâce à notre vérificateur de compatibilité intelligent. Pièces d'origine et adaptables certifiées.
                    </p>
                </div>

                <!-- Compatibility Checker Box -->
                <div class="lg:col-span-5 p-px bg-gradient-to-br from-amber-500/40 to-transparent rounded-[2.5rem]">
                    <div class="p-10 bg-slate-950 rounded-[2.4rem] shadow-2xl">
                        <h3 class="flex items-center gap-3 mb-8 text-xl font-black text-white uppercase tracking-tighter">
                            <i data-lucide="zap" class="w-5 h-5 text-amber-500"></i>
                            Vérificateur de Compatibilité
                        </h3>
                        <form action="{{ route('parts.compatibility') }}" method="GET" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black tracking-widest uppercase text-slate-500 ml-1">Marque du Véhicule</label>
                                <input type="text" name="marque" required placeholder="Ex: Toyota, Mercedes..." class="w-full py-4 px-5 text-sm bg-slate-900 border border-slate-800 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-white placeholder:text-slate-700">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black tracking-widest uppercase text-slate-500 ml-1">Modèle</label>
                                    <input type="text" name="modele" required placeholder="Ex: Rav4" class="w-full py-4 px-5 text-sm bg-slate-900 border border-slate-800 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-white placeholder:text-slate-700">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black tracking-widest uppercase text-slate-500 ml-1">Année</label>
                                    <input type="number" name="annee" placeholder="Ex: 2018" class="w-full py-4 px-5 text-sm bg-slate-900 border border-slate-800 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-white placeholder:text-slate-700">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-5 text-sm font-bold text-slate-950 bg-amber-500 rounded-2xl hover:bg-amber-400 transition shadow-xl shadow-amber-900/10 flex items-center justify-center gap-2">
                                <i data-lucide="search" class="w-4 h-4"></i> Vérifier la Compatibilité
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container px-4 py-16 mx-auto lg:px-8">
        <div class="flex flex-col gap-16 lg:flex-row">
            <!-- Sidebar -->
            <aside class="w-full lg:w-72 shrink-0">
                <div class="sticky p-8 space-y-10 border bg-slate-900/30 border-slate-900 rounded-[2.5rem] top-28">
                    <div>
                        <h4 class="mb-6 text-sm font-black tracking-widest uppercase text-amber-500">Catégories</h4>
                        <div class="flex flex-wrap gap-3">
                            @foreach($categories as $cat)
                                <a href="{{ route('parts.index', ['category' => $cat]) }}" class="px-4 py-2 text-xs font-bold transition border border-slate-800 rounded-xl hover:bg-slate-800 hover:border-amber-500/30 {{ request('category') == $cat ? 'bg-amber-500 text-slate-950 border-amber-500' : 'text-slate-400' }}">
                                    {{ ucfirst($cat) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="p-6 bg-slate-950 rounded-2xl border border-white/5">
                        <h4 class="mb-4 text-xs font-black tracking-widest uppercase text-slate-500">Besoin d'aide ?</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed mb-4 italic">Nos techniciens sont disponibles pour identifier la référence exacte de votre pièce sur simple envoi du numéro de châssis.</p>
                        <a href="#" class="flex items-center justify-center gap-2 py-3 text-xs font-bold border border-slate-800 text-white rounded-xl hover:bg-slate-900 transition font-mono uppercase">WhatsApp Support</a>
                    </div>
                </div>
            </aside>

            <!-- Grid -->
            <div class="flex-grow">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($pieces as $piece)
                        <div class="group relative bg-slate-900/40 border border-slate-900 rounded-[2.5rem] p-4 hover:border-amber-500/30 transition duration-500 flex flex-col h-full shadow-lg">
                            <div class="relative overflow-hidden aspect-square bg-slate-950 rounded-[2rem] mb-6 flex items-center justify-center">
                                @if($piece->image)
                                    <img src="{{ $piece->image }}" alt="{{ $piece->nom }}" class="object-cover w-full h-full transition duration-700 group-hover:scale-110 opacity-80 group-hover:opacity-100">
                                @else
                                    <i data-lucide="package" class="w-20 h-20 text-slate-800 stroke-1 group-hover:scale-110 transition duration-700"></i>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 to-transparent"></div>
                                <div class="absolute p-3 top-2 right-2">
                                    <span class="px-3 py-1 text-[9px] font-black tracking-widest uppercase bg-white/5 text-slate-400 backdrop-blur rounded-full border border-white/5">{{ $piece->etat }}</span>
                                </div>
                            </div>

                            <div class="px-4 flex-grow flex flex-col">
                                <div class="flex items-start justify-between mb-2">
                                    <span class="text-[10px] font-black tracking-[0.2em] uppercase text-amber-500/80">{{ $piece->categorie }}</span>
                                    <span class="text-xl font-black text-white">{{ number_format($piece->prix, 0, ',', ' ') }} <span class="text-xs text-amber-500">€</span></span>
                                </div>
                                <h3 class="mb-2 text-lg font-bold text-white group-hover:text-amber-500 transition tracking-tight leading-tight">{{ $piece->nom }}</h3>
                                <div class="flex items-center gap-2 mb-4 text-xs font-mono text-slate-600">
                                    Ref: <span class="bg-white/5 px-2 py-0.5 rounded text-slate-400 border border-white/5">{{ $piece->reference }}</span>
                                </div>
                                
                                <div class="p-4 bg-slate-950/50 rounded-2xl border border-white/5 mb-6">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500">
                                            <i data-lucide="check" class="w-3 h-3 text-amber-500"></i>
                                            Compatible: {{ $piece->marque_compatible }} {{ $piece->modele_compatible }}
                                        </div>
                                        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500">
                                            <i data-lucide="box" class="w-3 h-3 text-emerald-500"></i>
                                            En stock: {{ $piece->stock }} unités
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <form action="{{ route('parts.buy', $piece->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div class="space-y-2">
                                            <input type="text" name="client_nom" required placeholder="Votre Nom" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:ring-1 focus:ring-amber-500">
                                            <input type="tel" name="client_telephone" required placeholder="Votre Téléphone" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:ring-1 focus:ring-amber-500">
                                        </div>
                                        <button type="submit" class="w-full relative py-4 text-xs font-black uppercase tracking-widest text-white border border-slate-800 bg-slate-900 rounded-xl hover:bg-amber-500 hover:text-slate-950 hover:border-amber-500 transition flex items-center justify-center gap-2 overflow-hidden group/buy">
                                            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Acheter Maintenant
                                            <div class="absolute inset-0 bg-white/5 opacity-0 group-hover/buy:opacity-100 transition translate-x-[-100%] group-hover/buy:translate-x-[100%] duration-1000"></div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 py-32 text-center border-2 border-dashed border-slate-900 rounded-[3rem]">
                            <i data-lucide="search-x" class="w-20 h-20 mx-auto mb-6 text-slate-800"></i>
                            <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tighter">Aucune pièce trouvée</h3>
                            <p class="text-slate-500 text-sm">Réessayez avec d'autres critères de recherche ou une autre catégorie.</p>
                            <a href="{{ route('parts.index') }}" class="inline-block mt-8 px-8 py-3 bg-slate-900 text-white font-bold rounded-xl border border-slate-800 hover:border-amber-500 transition">Réinitialiser</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
