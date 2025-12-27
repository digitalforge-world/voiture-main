@extends('layouts.app')

@section('title', $voiture->marque . ' ' . $voiture->modele)

@section('content')
<div class="min-h-screen bg-slate-950">
    <!-- Breadcrumbs -->
    <div class="pt-8 pb-4">
        <div class="container px-4 mx-auto lg:px-8">
            <nav class="flex space-x-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                <a href="{{ url('/') }}" class="hover:text-amber-500 transition">Accueil</a>
                <span>/</span>
                <a href="{{ route('cars.index') }}" class="hover:text-amber-500 transition">Catalogue</a>
                <span>/</span>
                <span class="text-amber-500">{{ $voiture->marque }} {{ $voiture->modele }}</span>
            </nav>
        </div>
    </div>

    <div class="container px-4 py-8 mx-auto lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
            <!-- Left Column: Gallery & Details -->
            <div class="lg:col-span-8 space-y-12">
                <!-- Gallery Mockup -->
                <div class="relative overflow-hidden group rounded-[2.5rem] bg-slate-900 border border-slate-800 shadow-2xl">
                    <img src="{{ $voiture->image ?? 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1200' }}" alt="{{ $voiture->marque }}" class="object-cover w-full aspect-[16/9] group-hover:scale-105 transition duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                </div>

                <!-- Main Info Section -->
                <div class="p-10 border bg-slate-900/40 border-slate-800 rounded-[2.5rem] backdrop-blur-sm">
                    <div class="flex flex-col gap-6 mb-10 md:flex-row md:items-end md:justify-between border-b border-white/5 pb-10">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-4 py-1 text-xs font-black tracking-widest uppercase bg-amber-500 text-slate-950 rounded-full">{{ $voiture->etat }}</span>
                                <span class="px-4 py-1 text-xs font-black tracking-widest uppercase border border-slate-800 text-slate-400 rounded-full">{{ $voiture->annee }}</span>
                            </div>
                            <h1 class="text-4xl font-black text-white lg:text-5xl tracking-tight leading-none">{{ $voiture->marque }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">{{ $voiture->modele }}</span></h1>
                            <div class="flex items-center gap-2 mt-4 text-slate-400">
                                <i data-lucide="shield-check" class="w-5 h-5 text-amber-500"></i>
                                <span class="text-sm font-medium">Châssis: <span class="font-mono text-white">{{ $voiture->numero_chassis }}</span></span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold tracking-widest uppercase text-slate-500 mb-1">Prix de vente Fob</div>
                            <div class="text-5xl font-black text-white">{{ number_format($voiture->prix, 0, ',', ' ') }}<span class="text-xl text-amber-500 ml-1">€</span></div>
                        </div>
                    </div>

                    <!-- Key Specs Grid -->
                    <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                                <i data-lucide="gauge-circle" class="w-4 h-4 text-amber-500"></i> Kilométrage
                            </div>
                            <div class="text-lg font-bold text-white">{{ number_format($voiture->kilometrage, 0, ',', ' ') }} km</div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                                <i data-lucide="fuel" class="w-4 h-4 text-amber-500"></i> Carburant
                            </div>
                            <div class="text-lg font-bold text-white">{{ $voiture->carburant }}</div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                                <i data-lucide="settings-2" class="w-4 h-4 text-amber-500"></i> Transmission
                            </div>
                            <div class="text-lg font-bold text-white">{{ $voiture->transmission }}</div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                                <i data-lucide="zap" class="w-4 h-4 text-amber-500"></i> Moteur
                            </div>
                            <div class="text-lg font-bold text-white">{{ $voiture->moteur }}</div>
                        </div>
                    </div>
                </div>

                <!-- Description & Features -->
                <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                    <div class="p-10 border bg-slate-900/20 border-slate-800 rounded-[2.5rem]">
                        <h3 class="mb-6 text-xl font-bold text-white flex items-center gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-amber-500"></i> Description
                        </h3>
                        <p class="text-sm leading-relaxed text-slate-400 whitespace-pre-line">
                            {{ $voiture->description ?: "Ce véhicule a été inspecté par nos experts internationaux. Il présente un excellent état mécanique et esthétique. Idéal pour une importation sereine." }}
                        </p>
                    </div>
                    <div class="p-10 border bg-slate-900/20 border-slate-800 rounded-[2.5rem]">
                        <h3 class="mb-6 text-xl font-bold text-white flex items-center gap-3">
                            <i data-lucide="list-checks" class="w-5 h-5 text-amber-500"></i> Équipements
                        </h3>
                        <ul class="grid grid-cols-1 gap-3">
                            @php
                                $options = explode(',', $voiture->options_equipements ?: 'Climatisation,Bluetooth,Sièges chauffants,Radar de recul,Jantes alliage');
                            @endphp
                            @foreach($options as $option)
                                <li class="flex items-center gap-3 text-sm font-medium text-slate-400">
                                    <i data-lucide="check" class="w-4 h-4 text-amber-500 p-0.5 bg-amber-500/10 rounded"></i>
                                    {{ trim($option) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Calculator -->
            <div class="lg:col-span-4">
                <div class="sticky h-fit top-28 p-1 p-px bg-gradient-to-br from-amber-500/30 to-slate-800 rounded-[2.5rem]">
                    <div class="bg-slate-950 p-8 rounded-[2.4rem]">
                        <h3 class="mb-8 text-2xl font-black text-white tracking-tight">Estimer l'Importation</h3>
                        
                        <div class="space-y-8">
                            <!-- Port Selection -->
                            <div class="space-y-4">
                                <label class="text-xs font-black tracking-widest uppercase text-slate-500">Port de Destination</label>
                                <select id="port_id" class="w-full py-4 px-5 text-sm bg-slate-900 border border-slate-800 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-white">
                                    @foreach($ports as $port)
                                        <option value="{{ $port->id }}" data-frais="{{ $port->frais_base }}" {{ $voiture->port_recommande_id == $port->id ? 'selected' : '' }}>
                                            {{ $port->nom }} ({{ $port->pays }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Cost Breakdown -->
                            <div class="space-y-4 p-6 bg-slate-900/50 rounded-3xl border border-white/5">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500 font-medium">Prix du véhicule</span>
                                    <span class="text-white font-bold">{{ number_format($voiture->prix, 0, ',', ' ') }} €</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500 font-medium whitespace-nowrap">Frais de transport (Fret)</span>
                                    <span class="text-emerald-500 font-bold" id="shipping_cost">0 €</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500 font-medium whitespace-nowrap">Estimation Douane (10%)</span>
                                    <span class="text-amber-500/80 font-bold" id="customs_cost">{{ number_format($voiture->prix * 0.1, 0, ',', ' ') }} €</span>
                                </div>
                                <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-between">
                                    <span class="text-white font-black uppercase tracking-widest text-xs">Total Estimé</span>
                                    <span class="text-3xl font-black text-white" id="total_cost">0 €</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <form action="{{ route('cars.order', $voiture->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="port_id" id="hidden_port_id">
                                    <button type="submit" class="group relative w-full py-5 text-sm font-black text-slate-950 bg-amber-500 rounded-2xl hover:bg-amber-400 transition shadow-2xl shadow-amber-900/20 flex items-center justify-center gap-3 overflow-hidden">
                                        <span class="relative z-10 uppercase tracking-widest">Confirmer la Commande</span>
                                        <i data-lucide="arrow-right-circle" class="relative z-10 w-5 h-5 group-hover:translate-x-1 transition"></i>
                                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                                    </button>
                                </form>
                                <p class="text-[10px] text-center text-slate-600 font-medium px-4">
                                    Les frais de douane sont donnés à titre indicatif et peuvent varier selon la législation en vigueur au moment de l'entrée au port.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    const vehiclePrice = {{ $voiture->prix }};
    const portSelect = document.getElementById('port_id');
    const shippingDisplay = document.getElementById('shipping_cost');
    const totalDisplay = document.getElementById('total_cost');
    const customsDisplay = document.getElementById('customs_cost');
    const hiddenPortId = document.getElementById('hidden_port_id');

    function updateCosts() {
        const selectedOption = portSelect.options[portSelect.selectedIndex];
        const shippingCost = parseFloat(selectedOption.dataset.frais);
        const customsCost = vehiclePrice * 0.10;
        const totalCost = vehiclePrice + shippingCost + customsCost;

        shippingDisplay.textContent = new Intl.NumberFormat('fr-FR').format(shippingCost) + ' €';
        totalDisplay.textContent = new Intl.NumberFormat('fr-FR').format(totalCost) + ' €';
        hiddenPortId.value = portSelect.value;
    }

    portSelect.addEventListener('change', updateCosts);
    window.addEventListener('load', updateCosts);
</script>
@endsection
@endsection
