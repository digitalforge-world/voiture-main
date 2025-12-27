@extends('layouts.app')

@section('title', 'Location de Véhicules de Luxe')

@section('content')
<div class="min-h-screen bg-slate-950">
    <!-- Rental Hero -->
    <div class="relative py-32 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 to-slate-950"></div>
        <img src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=2000" alt="Luxury Interior" class="absolute inset-0 object-cover w-full h-full opacity-20 -z-10 grayscale">
        
        <div class="container relative px-4 mx-auto lg:px-8">
            <div class="max-w-3xl text-center mx-auto space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/5 border border-white/10 backdrop-blur rounded-full text-amber-500 text-xs font-black uppercase tracking-[0.2em] shadow-2xl">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 italic"></i> Excellence & Confort
                </div>
                <h1 class="text-6xl font-black leading-none tracking-tight text-white lg:text-8xl">
                    Liberté Sans <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600 italic font-serif">Compromis.</span>
                </h1>
                <p class="text-lg text-slate-400 leading-relaxed font-medium">
                    Une gamme exclusive de véhicules récents pour vos séjours au Togo. <br class="hidden lg:block"> Location courte et longue durée avec service premium.
                </p>
            </div>
        </div>
    </div>

    <!-- Rental Grid -->
    <div class="container px-4 py-16 mx-auto lg:px-8">
        <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($voitures as $car)
                <div class="group relative bg-slate-950 border border-slate-900 rounded-[2.5rem] overflow-hidden hover:border-amber-500/30 transition duration-700 shadow-2xl shadow-black">
                    <!-- Image Wrapper -->
                    <div class="relative overflow-hidden aspect-[16/10]">
                        <img src="{{ $car->image ?? 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1000' }}" alt="{{ $car->marque }}" class="object-cover w-full h-full transition duration-1000 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                        <div class="absolute p-6 top-0 left-0">
                            <span class="px-4 py-1 text-[10px] font-black tracking-widest uppercase bg-white/10 backdrop-blur border border-white/20 text-white rounded-full">Automatique</span>
                        </div>
                        <div class="absolute p-6 bottom-0 right-0 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-amber-500">{{ number_format($car->prix_location_jour, 0, ',', ' ') }}</span>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">€ / jour</span>
                        </div>
                    </div>

                    <!-- Details Wrapper -->
                    <div class="p-10 space-y-8">
                        <div>
                            <div class="flex items-center gap-2 mb-2 text-xs font-bold uppercase tracking-widest text-slate-500">
                                <span>{{ $car->type_vehicule }}</span>
                                <span class="w-1 h-1 bg-amber-500 rounded-full"></span>
                                <span>{{ $car->annee }}</span>
                            </div>
                            <h3 class="text-3xl font-black text-white group-hover:text-amber-500 transition leading-none tracking-tight">{{ $car->marque }} {{ $car->modele }}</h3>
                        </div>

                        <div class="grid grid-cols-3 gap-1 pt-6 border-t border-white/5">
                            <div class="flex flex-col items-center gap-2 text-center">
                                <i data-lucide="users" class="w-5 h-5 text-slate-600 group-hover:text-white transition"></i>
                                <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $car->nombre_places }} places</span>
                            </div>
                            <div class="flex flex-col items-center gap-2 text-center">
                                <i data-lucide="briefcase" class="w-5 h-5 text-slate-600 group-hover:text-white transition"></i>
                                <span class="text-[10px] font-bold text-slate-500 uppercase">3 bagages</span>
                            </div>
                            <div class="flex flex-col items-center gap-2 text-center border-l border-white/5">
                                <i data-lucide="snowflake" class="w-5 h-5 text-amber-500"></i>
                                <span class="text-[10px] font-bold text-slate-500 uppercase italic">Clim</span>
                            </div>
                        </div>

                        <!-- Booking Mini Form -->
                        <div class="pt-4">
                            <form action="{{ route('rental.book', $car->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1.5 focus-within:text-amber-500 transition">
                                        <label class="text-[10px] font-black uppercase tracking-widest mb-1 block ml-1">Début</label>
                                        <input type="date" name="date_debut" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-3 text-xs text-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                                    </div>
                                    <div class="space-y-1.5 focus-within:text-amber-500 transition">
                                        <label class="text-[10px] font-black uppercase tracking-widest mb-1 block ml-1">Fin</label>
                                        <input type="date" name="date_fin" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-3 text-xs text-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                                    </div>
                                </div>
                                <button type="submit" class="w-full relative py-4 text-xs font-black uppercase tracking-widest text-slate-950 bg-amber-500 rounded-2xl hover:bg-amber-400 transition shadow-lg shadow-amber-900/10 flex items-center justify-center gap-2 group/book overflow-hidden">
                                    <i data-lucide="calendar-check" class="w-4 h-4"></i> Réserver Maintenant
                                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover/book:translate-y-0 transition-transform duration-500"></div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-32 text-center rounded-[3rem] border-2 border-dashed border-slate-900">
                    <i data-lucide="car-off" class="w-20 h-20 mx-auto mb-6 text-slate-800"></i>
                    <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tighter">Aucun véhicule disponible</h3>
                    <p class="text-slate-500 text-sm">Tous nos véhicules sont actuellement loués. Revenez plus tard !</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Features Row -->
    <div class="py-24 border-t border-slate-900">
        <div class="container px-4 mx-auto lg:px-8">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
                <div class="flex items-start gap-6">
                    <div class="p-4 bg-slate-900 rounded-[1.5rem] text-amber-500"><i data-lucide="check-shield" class="w-8 h-8"></i></div>
                    <div>
                        <h4 class="text-lg font-black text-white mb-2 tracking-tight uppercase">Assurance Tous Risques</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Partez l'esprit tranquille, tous nos véhicules sont protégés par les meilleures garanties.</p>
                    </div>
                </div>
                <div class="flex items-start gap-6">
                    <div class="p-4 bg-slate-900 rounded-[1.5rem] text-amber-500"><i data-lucide="map-pin" class="w-8 h-8"></i></div>
                    <div>
                        <h4 class="text-lg font-black text-white mb-2 tracking-tight uppercase">Livraison à Lomé</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Nous livrons votre véhicule gratuitement à l'aéroport de Lomé ou à votre hôtel.</p>
                    </div>
                </div>
                <div class="flex items-start gap-6">
                    <div class="p-4 bg-slate-900 rounded-[1.5rem] text-amber-500"><i data-lucide="headset" class="w-8 h-8"></i></div>
                    <div>
                        <h4 class="text-lg font-black text-white mb-2 tracking-tight uppercase">Assistance 24/7</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Un problème mécanique ? Notre équipe technique intervient partout au Togo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
