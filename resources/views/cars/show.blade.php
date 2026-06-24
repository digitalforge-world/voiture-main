@extends('layouts.app')

@section('title', $voiture->marque . ' ' . $voiture->modele . ' ' . $voiture->annee)

@section('styles')
<style>
    /* ═══════════════════════════════════════════════ */
    /*  PREMIUM CAR DETAIL — CUSTOM STYLES            */
    /* ═══════════════════════════════════════════════ */

    /* Scroll-reveal animations */
    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .reveal-delay-1 { transition-delay: 0.1s; }
    .reveal-delay-2 { transition-delay: 0.2s; }
    .reveal-delay-3 { transition-delay: 0.3s; }
    .reveal-delay-4 { transition-delay: 0.4s; }

    /* Gallery thumbnail active state */
    .thumb-item {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .thumb-item.active {
        ring: 2px;
        transform: scale(1.05);
    }
    .thumb-item.active::after {
        content: '';
        position: absolute;
        inset: -3px;
        border-radius: 14px;
        border: 2px solid #f59e0b;
        pointer-events: none;
    }

    /* Spec card hover lift */
    .spec-card {
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .spec-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -12px rgba(245, 158, 11, 0.15);
    }

    /* Floating label animation */
    .float-label {
        animation: floatLabel 3s ease-in-out infinite;
    }
    @keyframes floatLabel {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    /* Shine sweep on CTA */
    .shine-sweep::after {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.8s ease;
    }
    .shine-sweep:hover::after {
        left: 100%;
    }

    /* Smooth image crossfade */
    .gallery-main img {
        transition: opacity 0.6s ease, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Pulse ring animation */
    @keyframes pulseRing {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(1.8); opacity: 0; }
    }
    .pulse-ring::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        border: 2px solid #f59e0b;
        animation: pulseRing 2s ease-out infinite;
    }

    /* Progress bar fill */
    .progress-fill {
        transition: width 1.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Tab underline animation */
    .tab-btn {
        position: relative;
    }
    .tab-btn::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        width: 0;
        height: 2px;
        background: #f59e0b;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        transform: translateX(-50%);
        border-radius: 2px;
    }
    .tab-btn.active::after {
        width: 100%;
    }

    /* Custom scrollbar for thumbnails */
    .thumb-scroll::-webkit-scrollbar {
        height: 4px;
    }
    .thumb-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .thumb-scroll::-webkit-scrollbar-thumb {
        background: rgba(245, 158, 11, 0.3);
        border-radius: 10px;
    }

    /* Glassmorphism card */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    .dark .glass-card {
        background: rgba(15, 23, 42, 0.6);
    }

    /* Availability badge pulse */
    .avail-pulse {
        animation: availPulse 2s ease-in-out infinite;
    }
    @keyframes availPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
    }

    /* Number counter animation */
    .count-up {
        display: inline-block;
    }

    /* 3D Showroom styles */
    #heroModel3D {
        background: radial-gradient(circle, #ffffff 40%, #cbd5e1 100%) !important;
        outline: none;
    }
    .color-dot {
        position: relative;
    }
    .color-dot::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 9999px;
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    .color-dot.active::after {
        border-color: #f59e0b;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-50 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900 transition-colors duration-500">

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--  BREADCRUMBS                                           --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="pt-4 pb-2">
        <div class="container px-4 mx-auto lg:px-8">
            <nav class="flex items-center space-x-2 text-[10px] font-bold tracking-widest uppercase text-slate-400">
                <a href="{{ url('/') }}" class="hover:text-amber-500 transition-colors duration-300">Accueil</a>
                <i data-lucide="chevron-right" class="w-3 h-3 opacity-40"></i>
                <a href="{{ route('cars.index') }}" class="hover:text-amber-500 transition-colors duration-300">Catalogue</a>
                <i data-lucide="chevron-right" class="w-3 h-3 opacity-40"></i>
                <span class="text-amber-500">{{ $voiture->marque }} {{ $voiture->modele }}</span>
            </nav>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--  HERO GALLERY SECTION                                  --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="container px-4 mx-auto lg:px-8 pt-2 pb-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- LEFT — Gallery --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-4 reveal">

                {{-- Main Image --}}
                <div class="relative overflow-hidden rounded-3xl bg-slate-100 dark:bg-slate-900 border border-slate-200/60 dark:border-white/5 group gallery-main shadow-2xl shadow-slate-200/50 dark:shadow-slate-950/50">
                    @php
                        $mainImage = $voiture->photo_principale ?: 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=1200';
                        
                        $mediaItems = [];
                        
                        // Main photo as first item
                        $mediaItems[] = [
                            'url' => $mainImage,
                            'type' => 'image'
                        ];
                        
                        // Remaining photos (excluding main image to avoid duplicates)
                        foreach ($voiture->photos as $photo) {
                            if ($photo->url !== $mainImage) {
                                $mediaItems[] = [
                                    'url' => $photo->url,
                                    'type' => 'image'
                                ];
                            }
                        }
                        
                        // All videos
                        foreach ($voiture->videos as $video) {
                            $mediaItems[] = [
                                'url' => $video->url,
                                'type' => 'video'
                            ];
                        }
                    @endphp

                    <div id="heroImageContainer" class="relative w-full aspect-[16/10] overflow-hidden bg-slate-900 flex items-center justify-center">
                           <img id="heroImage"
                             src="{{ $mainImage }}"
                             alt="{{ $voiture->marque }} {{ $voiture->modele }}"
                             class="object-cover w-full h-full group-hover:scale-[1.03] transition-all duration-[1.5s] cursor-zoom-in"
                             onclick="openLightbox()">

                           <video id="heroVideo"
                               src=""
                               controls
                               controlsList="nodownload"
                               oncontextmenu="return false"
                               class="hidden w-full h-full object-cover"
                               playsinline muted></video>

                        <button id="heroUnmuteBtn" type="button" class="absolute bottom-4 right-4 z-20 px-3 py-2 bg-white/90 text-slate-900 rounded-full text-xs hidden" onclick="toggleHeroMute()">Activer le son</button>

                        @if($voiture->model_3d)
                        <model-viewer id="heroModel3D" 
                                      src="{{ $voiture->model_3d }}" 
                                      alt="Modèle 3D de {{ $voiture->marque }} {{ $voiture->modele }}" 
                                      auto-rotate 
                                      camera-controls 
                                      ar 
                                      ar-modes="webxr scene-viewer quick-look" 
                                      shadow-intensity="1.5" 
                                      shadow-softness="1"
                                      exposure="1.0"
                                      environment-image="neutral"
                                      interaction-prompt="auto"
                                      auto-rotate-delay="2000"
                                      rotation-per-second="20deg"
                                      class="hidden w-full h-full bg-slate-50 dark:bg-slate-50"
                                      style="--poster-color: transparent;">
                            
                            {{-- Custom Loading Indicator --}}
                            <div id="model-loader" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950/80 backdrop-blur-md z-30 transition-opacity duration-500">
                                <div class="w-12 h-12 border-4 border-amber-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                                <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest animate-pulse">Chargement du modèle 3D...</span>
                                <div class="w-48 h-1.5 bg-white/10 rounded-full overflow-hidden mt-3 border border-white/5">
                                    <div id="model-progress-bar" class="w-0 h-full bg-amber-500 rounded-full transition-all duration-300"></div>
                                </div>
                            </div>
                            
                            {{-- Fullscreen button inside model-viewer --}}
                            <button slot="hotspot-fullscreen" type="button" onclick="toggleModelFullscreen()" class="absolute bottom-4 right-4 z-20 p-2.5 bg-slate-900/80 backdrop-blur-xl border border-white/10 rounded-xl text-white hover:bg-amber-500 hover:text-slate-950 transition-all flex items-center justify-center gap-1.5 shadow-lg">
                                <i data-lucide="maximize" class="w-4 h-4"></i>
                                <span class="text-[9px] font-black uppercase tracking-wider hidden sm:inline">Plein Écran</span>
                            </button>
                        </model-viewer>
                        
                        {{-- XPeng-style Color Configurator (Right Side) --}}
                        <div id="showroom-colors" class="hidden absolute right-6 top-1/2 -translate-y-1/2 z-20 flex flex-col gap-4 bg-slate-950/80 backdrop-blur-xl p-4 rounded-3xl border border-white/10 shadow-2xl">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest text-center">Teinte</span>
                            <button type="button" onclick="changeCarColor('#c21807', this)" class="color-dot w-7 h-7 rounded-full bg-[#c21807] border-2 border-white/20 hover:scale-110 active:scale-95 transition-all shadow-md active" title="Rouge Sport"></button>
                            <button type="button" onclick="changeCarColor('#0047ab', this)" class="color-dot w-7 h-7 rounded-full bg-[#0047ab] border-2 border-white/20 hover:scale-110 active:scale-95 transition-all shadow-md" title="Bleu Cobalt"></button>
                            <button type="button" onclick="changeCarColor('#7c8d9c', this)" class="color-dot w-7 h-7 rounded-full bg-[#7c8d9c] border-2 border-white/20 hover:scale-110 active:scale-95 transition-all shadow-md" title="Gris Nardo"></button>
                            <button type="button" onclick="changeCarColor('#111111', this)" class="color-dot w-7 h-7 rounded-full bg-[#111111] border-2 border-white/20 hover:scale-110 active:scale-95 transition-all shadow-md" title="Noir Profond"></button>
                            <button type="button" onclick="changeCarColor('#fcfcfc', this)" class="color-dot w-7 h-7 rounded-full bg-[#fcfcfc] border-2 border-white/20 hover:scale-110 active:scale-95 transition-all shadow-md" title="Blanc Showroom"></button>
                        </div>

                        {{-- XPeng-style Camera Angles (Bottom Center) --}}
                        <div id="showroom-camera" class="hidden absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2 bg-slate-950/80 backdrop-blur-xl p-1.5 rounded-full border border-white/10 shadow-2xl">
                            <button type="button" onclick="setCameraOrbit('0deg', '75deg', '105%', this)" class="camera-btn px-3 py-1.5 text-[8px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-slate-950 bg-amber-500 shadow-md">
                                Avant
                            </button>
                            <button type="button" onclick="setCameraOrbit('90deg', '75deg', '105%', this)" class="camera-btn px-3 py-1.5 text-[8px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-white hover:text-amber-500">
                                Profil
                            </button>
                            <button type="button" onclick="setCameraOrbit('180deg', '75deg', '105%', this)" class="camera-btn px-3 py-1.5 text-[8px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-white hover:text-amber-500">
                                Arrière
                            </button>
                            <button type="button" onclick="setCameraOrbit('0deg', '15deg', '140%', this)" class="camera-btn px-3 py-1.5 text-[8px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-white hover:text-amber-500">
                                Dessus
                            </button>
                        </div>
                        @endif

                        {{-- Cinematic gradient overlays (using pointer-events-none so users can click video controls) --}}
                        <div id="heroOverlay" class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent pointer-events-none"></div>
                        <div id="heroOverlayLeft" class="absolute inset-0 bg-gradient-to-r from-slate-950/20 via-transparent to-transparent pointer-events-none"></div>
                    </div>

                    @if($voiture->model_3d)
                    <div class="absolute top-5 left-1/2 -translate-x-1/2 z-20 bg-slate-950/80 backdrop-blur-xl p-1 rounded-full border border-white/15 shadow-xl flex items-center gap-1">
                        <button id="btn-gallery-photos" type="button" onclick="switchMediaMode('photos')" class="px-4 py-1.5 text-[9px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-slate-950 bg-amber-500 shadow-md">
                            Photos / Vidéos
                        </button>
                        <button id="btn-gallery-3d" type="button" onclick="switchMediaMode('3d')" class="px-4 py-1.5 text-[9px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-white hover:text-amber-500">
                            Visualisation 3D
                        </button>
                    </div>
                    @endif

                    {{-- Top-left badges --}}
                    <div class="absolute top-5 left-5 flex flex-wrap items-center gap-2 z-10">
                        <span class="px-3.5 py-1.5 bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-[0.15em] rounded-full shadow-lg shadow-amber-500/30 float-label">
                            {{ $voiture->etat }}
                        </span>
                        <span class="px-3.5 py-1.5 bg-white/15 backdrop-blur-xl border border-white/20 text-[10px] font-black text-white uppercase tracking-widest rounded-full">
                            {{ $voiture->annee }}
                        </span>
                        @if($voiture->disponibilite === 'disponible')
                            <span class="relative px-3 py-1.5 bg-emerald-500/20 backdrop-blur-xl border border-emerald-400/30 text-[9px] font-black text-emerald-400 uppercase tracking-widest rounded-full avail-pulse">
                                <span class="inline-block w-1.5 h-1.5 bg-emerald-400 rounded-full mr-1.5 animate-pulse"></span>
                                Disponible
                            </span>
                        @elseif($voiture->disponibilite === 'en_transit')
                            <span class="px-3 py-1.5 bg-blue-500/20 backdrop-blur-xl border border-blue-400/30 text-[9px] font-black text-blue-400 uppercase tracking-widest rounded-full">
                                <span class="inline-block w-1.5 h-1.5 bg-blue-400 rounded-full mr-1.5 animate-pulse"></span>
                                En transit
                            </span>
                        @endif
                    </div>

                    {{-- Top-right zoom hint --}}
                    <button onclick="openLightbox()" class="absolute top-5 right-5 z-10 p-2.5 bg-white/10 backdrop-blur-xl border border-white/20 rounded-xl text-white hover:bg-white/20 transition-all opacity-0 group-hover:opacity-100 duration-500">
                        <i data-lucide="maximize-2" class="w-4 h-4"></i>
                    </button>

                    {{-- Bottom info bar --}}
                    <div class="absolute bottom-0 left-0 right-0 z-10 p-5 flex items-end justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-[9px] font-black text-amber-500 uppercase tracking-[0.25em]">{{ $voiture->marque }}</span>
                                <div class="w-1 h-1 rounded-full bg-white/30"></div>
                                <span class="text-[9px] font-bold text-white/60 uppercase tracking-widest">{{ $voiture->type_vehicule ?? 'Berline' }}</span>
                            </div>
                            <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight leading-none">{{ $voiture->modele }}</h1>
                        </div>
                        <div class="text-right">
                            <div class="px-5 py-3 bg-slate-950/60 backdrop-blur-2xl rounded-2xl border border-white/10">
                                <div class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] mb-0.5">Prix FOB</div>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xl lg:text-2xl font-black text-white tabular-nums tracking-tight">{{ number_format($voiture->prix, 0, ',', ' ') }}</span>
                                    <span class="text-[10px] font-black text-amber-500">FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation arrows (for gallery) --}}
                    @if(count($mediaItems) > 1)
                        <button onclick="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 p-2.5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-full text-white hover:bg-white/25 transition-all opacity-0 group-hover:opacity-100 duration-300">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 p-2.5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-full text-white hover:bg-white/25 transition-all opacity-0 group-hover:opacity-100 duration-300">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    @endif

                    {{-- Image counter --}}
                    @if(count($mediaItems) > 1)
                        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-10 px-3 py-1 bg-slate-950/50 backdrop-blur-xl rounded-full border border-white/10">
                            <span class="text-[10px] font-bold text-white/80"><span id="currentImageIdx">1</span> / {{ count($mediaItems) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Thumbnail Strip --}}
                @if(count($mediaItems) > 1)
                    <div class="flex gap-2.5 overflow-x-auto pb-2 thumb-scroll">
                        @foreach($mediaItems as $idx => $item)
                            <button onclick="setMedia({{ $idx }})"
                                    class="thumb-item relative flex-shrink-0 w-20 h-16 rounded-xl overflow-hidden border-2 transition-all duration-300 {{ $idx === 0 ? 'border-amber-500 scale-105' : 'border-transparent hover:border-amber-500/50 opacity-60 hover:opacity-100' }}"
                                    data-idx="{{ $idx }}">
                                @if($item['type'] === 'image')
                                    <img src="{{ $item['url'] }}" alt="Photo {{ $idx + 1 }}" class="w-full h-full object-cover">
                                @else
                                    <div class="relative w-full h-full bg-slate-950">
                                        <video src="{{ $item['url'] }}#t=0.5" class="w-full h-full object-cover opacity-80" preload="metadata" muted playsinline controlsList="nodownload" oncontextmenu="return false"></video>
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 hover:bg-black/10 transition-colors">
                                            <div class="p-1 bg-amber-500 rounded-full text-slate-950 shadow-md">
                                                <i data-lucide="play" class="w-3.5 h-3.5 fill-current"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT — Sticky Calculator --}}
            <div class="lg:col-span-5 xl:col-span-4 reveal reveal-delay-2">
                <div class="sticky top-24">
                    <div class="glass-card border border-slate-200/60 dark:border-white/5 rounded-3xl overflow-hidden shadow-xl shadow-slate-200/30 dark:shadow-slate-950/50">

                        {{-- Card Header with gradient --}}
                        <div class="relative p-6 pb-5 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 dark:from-slate-800 dark:via-slate-900 dark:to-slate-950 overflow-hidden">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                            <div class="relative z-10 flex items-center gap-3">
                                <div class="relative w-11 h-11 rounded-2xl bg-amber-500/15 flex items-center justify-center pulse-ring">
                                    <i data-lucide="calculator" class="w-5 h-5 text-amber-500"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-white tracking-tight">Simuler l'importation</h3>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Estimation instantanée</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            {{-- Port Selection --}}
                            <div>
                                <label class="flex items-center gap-2 text-[9px] font-black tracking-[0.2em] uppercase text-slate-400 mb-2.5">
                                    <i data-lucide="anchor" class="w-3 h-3 text-amber-500"></i>
                                    Port de destination
                                </label>
                                <div class="relative">
                                    <select id="port_id" class="w-full py-3.5 px-4 pr-10 text-sm font-semibold bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-slate-900 dark:text-white appearance-none cursor-pointer">
                                        @foreach($ports as $port)
                                            <option value="{{ $port->id }}" data-frais="{{ $port->frais_base }}" {{ $voiture->port_recommande_id == $port->id ? 'selected' : '' }}>
                                                {{ $port->nom }} ({{ $port->pays }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            {{-- Cost Breakdown --}}
                            <div class="bg-gradient-to-br from-slate-50 to-slate-100/50 dark:from-slate-900 dark:to-slate-900/50 rounded-2xl p-5 space-y-4 border border-slate-100 dark:border-white/5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-slate-200 dark:bg-slate-800 flex items-center justify-center">
                                            <i data-lucide="car-front" class="w-3.5 h-3.5 text-slate-500"></i>
                                        </div>
                                        <span class="text-xs text-slate-500 font-medium">Prix du véhicule</span>
                                    </div>
                                    <span class="text-sm text-slate-900 dark:text-white font-bold tabular-nums">{{ number_format($voiture->prix, 0, ',', ' ') }} <span class="text-[9px] text-slate-400">FCFA</span></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                                            <i data-lucide="ship" class="w-3.5 h-3.5 text-emerald-500"></i>
                                        </div>
                                        <span class="text-xs text-slate-500 font-medium">Frais de transport</span>
                                    </div>
                                    <span class="text-sm text-emerald-600 dark:text-emerald-400 font-bold tabular-nums" id="shipping_cost">0 <span class="text-[9px]">FCFA</span></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                                            <i data-lucide="landmark" class="w-3.5 h-3.5 text-amber-500"></i>
                                        </div>
                                        <span class="text-xs text-slate-500 font-medium">Douane estimée <span class="text-[9px] opacity-60">(10%)</span></span>
                                    </div>
                                    <span class="text-sm text-amber-600 dark:text-amber-400 font-bold tabular-nums" id="customs_cost">{{ number_format($voiture->prix * 0.1, 0, ',', ' ') }} <span class="text-[9px]">FCFA</span></span>
                                </div>

                                {{-- Total --}}
                                <div class="pt-4 mt-1 border-t-2 border-dashed border-slate-200 dark:border-white/10">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-[0.15em]">Total estimé</span>
                                        <div class="text-right">
                                            <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums tracking-tight" id="total_cost">0</span>
                                            <span class="text-xs font-black text-amber-500 ml-1">FCFA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Order CTA --}}
                            <div class="space-y-4">
                                <button type="button" 
                                        onclick="openOrderModal({
                                            id: {{ $voiture->id }},
                                            marque: '{{ addslashes($voiture->marque) }}',
                                            modele: '{{ addslashes($voiture->modele) }}',
                                            prix: {{ $voiture->prix }},
                                            photo: '{{ $voiture->photo_principale ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=800' }}',
                                            slug: '{{ $voiture->slug }}',
                                            portId: document.getElementById('port_id').value
                                        })"
                                        class="shine-sweep group relative w-full py-5 text-[11px] font-black text-slate-950 uppercase tracking-[0.2em] bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 rounded-2xl hover:shadow-xl hover:shadow-amber-500/25 transition-all duration-500 flex items-center justify-center gap-2.5 overflow-hidden active:scale-[0.97]">
                                    <span class="relative z-10">Commander ce véhicule</span>
                                    <i data-lucide="arrow-right" class="relative z-10 w-4 h-4 group-hover:translate-x-1.5 transition-transform duration-300"></i>
                                </button>
                                
                                <p class="text-[8px] text-center text-slate-400 leading-relaxed tracking-wider uppercase font-medium">
                                    <i data-lucide="shield-check" class="w-3 h-3 inline-block mr-1 text-emerald-500"></i>
                                    Paiement sécurisé · Les frais de douane sont indicatifs
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--  KEY SPECS — HORIZONTAL CARDS                          --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="container px-4 mx-auto lg:px-8 py-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 reveal">
            @php
                $specs = [
                    ['icon' => 'gauge', 'label' => 'Kilométrage', 'value' => number_format($voiture->kilometrage, 0, ',', ' '), 'unit' => 'km', 'color' => 'blue'],
                    ['icon' => 'fuel', 'label' => 'Carburant', 'value' => ucfirst($voiture->carburant), 'unit' => '', 'color' => 'emerald'],
                    ['icon' => 'cog', 'label' => 'Transmission', 'value' => ucfirst($voiture->transmission), 'unit' => '', 'color' => 'purple'],
                    ['icon' => 'zap', 'label' => 'Moteur', 'value' => $voiture->moteur ?: 'N/A', 'unit' => '', 'color' => 'amber'],
                    ['icon' => 'palette', 'label' => 'Couleur', 'value' => ucfirst($voiture->couleur ?: 'N/A'), 'unit' => '', 'color' => 'rose'],
                    ['icon' => 'map-pin', 'label' => 'Origine', 'value' => $voiture->pays_origine, 'unit' => '', 'color' => 'cyan'],
                ];
            @endphp

            @foreach($specs as $idx => $spec)
                <div class="spec-card group bg-white dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-white/5 hover:border-{{ $spec['color'] }}-500/30 cursor-default reveal-delay-{{ $idx + 1 > 4 ? 4 : $idx + 1 }}">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-{{ $spec['color'] }}-500/10 flex items-center justify-center group-hover:bg-{{ $spec['color'] }}-500/20 transition-colors">
                            <i data-lucide="{{ $spec['icon'] }}" class="w-4 h-4 text-{{ $spec['color'] }}-500"></i>
                        </div>
                    </div>
                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">{{ $spec['label'] }}</div>
                    <div class="text-sm font-black text-slate-900 dark:text-white capitalize truncate">
                        {{ $spec['value'] }}
                        @if($spec['unit'])
                            <span class="text-[10px] font-bold text-slate-400">{{ $spec['unit'] }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--  TABBED DETAILS SECTION                                --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="container px-4 mx-auto lg:px-8 py-8">
        <div class="reveal">
            {{-- Tab Navigation --}}
            <div class="flex items-center gap-1 mb-8 border-b border-slate-200 dark:border-slate-800 overflow-x-auto">
                <button onclick="switchTab('description')" class="tab-btn active px-5 py-3 text-[10px] font-black uppercase tracking-[0.15em] text-amber-500 transition-colors whitespace-nowrap" data-tab="description">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 inline-block mr-1.5 -mt-0.5"></i>
                    Description
                </button>
                <button onclick="switchTab('equipements')" class="tab-btn px-5 py-3 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors whitespace-nowrap" data-tab="equipements">
                    <i data-lucide="list-checks" class="w-3.5 h-3.5 inline-block mr-1.5 -mt-0.5"></i>
                    Équipements
                </button>
                <button onclick="switchTab('technique')" class="tab-btn px-5 py-3 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors whitespace-nowrap" data-tab="technique">
                    <i data-lucide="wrench" class="w-3.5 h-3.5 inline-block mr-1.5 -mt-0.5"></i>
                    Fiche technique
                </button>
            </div>

            {{-- Tab: Description --}}
            <div id="tab-description" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white dark:bg-slate-900/30 border border-slate-100 dark:border-white/5 rounded-2xl p-7">
                        <h3 class="flex items-center gap-2 mb-5 text-sm font-black text-slate-900 dark:text-white tracking-wide">
                            <div class="w-8 h-8 rounded-xl bg-amber-500/10 flex items-center justify-center">
                                <i data-lucide="sparkles" class="w-4 h-4 text-amber-500"></i>
                            </div>
                            À propos de ce véhicule
                        </h3>
                        <p class="text-sm leading-[1.9] text-slate-500 dark:text-slate-400 whitespace-pre-line">{{ $voiture->description ?: "Ce véhicule " . $voiture->marque . " " . $voiture->modele . " " . $voiture->annee . " a été soigneusement sélectionné et inspecté par nos experts internationaux. Il présente un excellent état mécanique et esthétique, avec un kilométrage certifié de " . number_format($voiture->kilometrage, 0, ',', ' ') . " km.\n\nIdéal pour une importation sereine, ce véhicule bénéficie de notre garantie de conformité et d'un suivi logistique complet jusqu'à la livraison dans votre port de destination." }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 dark:from-slate-800 dark:to-slate-900 rounded-2xl p-7 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <h3 class="flex items-center gap-2 mb-5 text-sm font-black tracking-wide">
                                <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center">
                                    <i data-lucide="shield" class="w-4 h-4 text-amber-500"></i>
                                </div>
                                Nos garanties
                            </h3>
                            <ul class="space-y-4">
                                @php
                                    $guarantees = [
                                        ['icon' => 'check-circle', 'text' => 'Véhicule inspecté par nos experts'],
                                        ['icon' => 'truck', 'text' => 'Livraison jusqu\'au port de destination'],
                                        ['icon' => 'file-check', 'text' => 'Documents douaniers complets'],
                                        ['icon' => 'headphones', 'text' => 'Support client 7j/7'],
                                        ['icon' => 'refresh-cw', 'text' => 'Suivi tracking en temps réel'],
                                    ];
                                @endphp
                                @foreach($guarantees as $g)
                                    <li class="flex items-start gap-3">
                                        <div class="w-6 h-6 rounded-lg bg-amber-500/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i data-lucide="{{ $g['icon'] }}" class="w-3 h-3 text-amber-500"></i>
                                        </div>
                                        <span class="text-xs font-medium text-slate-300 leading-relaxed">{{ $g['text'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Equipements --}}
            <div id="tab-equipements" class="tab-content hidden">
                <div class="bg-white dark:bg-slate-900/30 border border-slate-100 dark:border-white/5 rounded-2xl p-7">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @php
                            $options = explode(',', $voiture->options_equipements ?: 'Climatisation automatique,Système Bluetooth,Sièges chauffants,Radar de recul,Jantes alliage,Caméra 360°,Toit ouvrant panoramique,Régulateur de vitesse,Système GPS intégré');
                        @endphp
                        @foreach($options as $idx => $option)
                            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950/50 border border-slate-100 dark:border-white/5 hover:border-amber-500/30 transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-500/20 transition-colors">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-amber-500"></i>
                                </div>
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ trim($option) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tab: Fiche technique --}}
            <div id="tab-technique" class="tab-content hidden">
                <div class="bg-white dark:bg-slate-900/30 border border-slate-100 dark:border-white/5 rounded-2xl overflow-hidden">
                    @php
                        $techSpecs = [
                            ['label' => 'Marque', 'value' => $voiture->marque, 'icon' => 'badge-check'],
                            ['label' => 'Modèle', 'value' => $voiture->modele, 'icon' => 'car-front'],
                            ['label' => 'Année', 'value' => $voiture->annee, 'icon' => 'calendar'],
                            ['label' => 'Kilométrage', 'value' => number_format($voiture->kilometrage, 0, ',', ' ') . ' km', 'icon' => 'gauge'],
                            ['label' => 'Moteur', 'value' => $voiture->moteur ?: 'Non spécifié', 'icon' => 'zap'],
                            ['label' => 'Cylindrée', 'value' => $voiture->cylindree ?: 'Non spécifié', 'icon' => 'circle-dot'],
                            ['label' => 'Puissance', 'value' => $voiture->puissance ?: 'Non spécifié', 'icon' => 'flame'],
                            ['label' => 'Carburant', 'value' => ucfirst($voiture->carburant), 'icon' => 'fuel'],
                            ['label' => 'Transmission', 'value' => ucfirst($voiture->transmission), 'icon' => 'cog'],
                            ['label' => 'Couleur', 'value' => ucfirst($voiture->couleur ?: 'Non spécifié'), 'icon' => 'palette'],
                            ['label' => 'Nombre de portes', 'value' => $voiture->nombre_portes ?: 'Non spécifié', 'icon' => 'door-open'],
                            ['label' => 'Nombre de places', 'value' => $voiture->nombre_places ?: 'Non spécifié', 'icon' => 'users'],
                            ['label' => 'Type de véhicule', 'value' => ucfirst(str_replace('_', ' ', $voiture->type_vehicule ?? 'Berline')), 'icon' => 'layout-grid'],
                            ['label' => 'Pays d\'origine', 'value' => $voiture->pays_origine, 'icon' => 'globe'],
                            ['label' => 'Numéro de châssis', 'value' => $voiture->numero_chassis ?: 'Confidentiel', 'icon' => 'shield-check'],
                            ['label' => 'État', 'value' => ucfirst($voiture->etat), 'icon' => 'star'],
                        ];
                    @endphp

                    @foreach($techSpecs as $idx => $ts)
                        <div class="flex items-center justify-between px-6 py-4 {{ $idx % 2 === 0 ? 'bg-slate-50/50 dark:bg-slate-950/30' : '' }} border-b border-slate-100 dark:border-white/5 last:border-none hover:bg-amber-50/50 dark:hover:bg-amber-500/5 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                    <i data-lucide="{{ $ts['icon'] }}" class="w-3.5 h-3.5 text-slate-400"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $ts['label'] }}</span>
                            </div>
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $ts['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{--  CTA BANNER                                            --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="container px-4 mx-auto lg:px-8 pb-16 reveal">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-8 lg:p-12">
            <div class="absolute top-0 left-1/4 w-60 h-60 bg-amber-500/15 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 right-1/4 w-40 h-40 bg-amber-500/10 rounded-full blur-[80px]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6 text-center lg:text-left">
                <div>
                    <h3 class="text-xl lg:text-2xl font-black text-white tracking-tight mb-2">Besoin d'informations supplémentaires ?</h3>
                    <p class="text-sm text-slate-400 max-w-lg">Notre équipe d'experts est disponible pour répondre à toutes vos questions sur ce {{ $voiture->marque }} {{ $voiture->modele }}.</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="{{ route('tracking.index') }}" class="px-6 py-3.5 bg-white/10 backdrop-blur border border-white/20 rounded-2xl text-[10px] font-black text-white uppercase tracking-[0.15em] hover:bg-white/20 transition-all flex items-center gap-2">
                        <i data-lucide="package" class="w-4 h-4"></i>
                        Suivre une commande
                    </a>
                    <a href="#" class="px-6 py-3.5 bg-amber-500 rounded-2xl text-[10px] font-black text-slate-950 uppercase tracking-[0.15em] hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{--  LIGHTBOX MODAL                                        --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div id="lightbox" class="fixed inset-0 z-[300] hidden">
    <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-xl" onclick="closeLightbox()"></div>
    <div class="relative w-full h-full flex items-center justify-center p-4">
        <button onclick="closeLightbox()" class="absolute top-6 right-6 z-[310] p-3 bg-white/10 backdrop-blur-xl border border-white/15 rounded-xl text-white hover:bg-white/20 transition-all">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <button onclick="prevImage()" class="absolute left-6 top-1/2 -translate-y-1/2 z-[310] p-3 bg-white/10 backdrop-blur-xl border border-white/15 rounded-full text-white hover:bg-white/20 transition-all">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </button>

        <img id="lightboxImage" src="" alt="Lightbox" class="relative z-[305] max-w-[90vw] max-h-[85vh] object-contain rounded-2xl shadow-2xl">
        <video id="lightboxVideo" src="" controls controlsList="nodownload" oncontextmenu="return false" class="hidden relative z-[305] max-w-[90vw] max-h-[85vh] object-contain rounded-2xl shadow-2xl" playsinline muted></video>
        <button id="lightboxUnmuteBtn" type="button" class="absolute bottom-8 right-8 z-[320] px-3 py-2 bg-white/90 text-slate-900 rounded-full text-sm hidden" onclick="toggleLightboxMute()">Activer le son</button>

        <button onclick="nextImage()" class="absolute right-6 top-1/2 -translate-y-1/2 z-[310] p-3 bg-white/10 backdrop-blur-xl border border-white/15 rounded-full text-white hover:bg-white/20 transition-all">
            <i data-lucide="chevron-right" class="w-6 h-6"></i>
        </button>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-[310] px-4 py-2 bg-white/10 backdrop-blur-xl rounded-full border border-white/15">
            <span class="text-xs font-bold text-white"><span id="lightboxIdx">1</span> / {{ count($mediaItems ?? [1]) }}</span>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ═══════════════════════════════════════════════
    //  COST CALCULATOR
    // ═══════════════════════════════════════════════
    const vehiclePrice = {{ $voiture->prix }};
    const portSelect = document.getElementById('port_id');
    const shippingDisplay = document.getElementById('shipping_cost');
    const totalDisplay = document.getElementById('total_cost');
    const customsDisplay = document.getElementById('customs_cost');
    const hiddenPortId = document.getElementById('hidden_port_id');

    function formatNumber(n) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(n));
    }

    function updateCosts() {
        const selectedOption = portSelect.options[portSelect.selectedIndex];
        const shippingCost = parseFloat(selectedOption.dataset.frais) || 0;
        const customsCost = vehiclePrice * 0.10;
        const totalCost = vehiclePrice + shippingCost + customsCost;

        shippingDisplay.innerHTML = formatNumber(shippingCost) + ' <span class="text-[9px]">FCFA</span>';
        customsDisplay.innerHTML = formatNumber(customsCost) + ' <span class="text-[9px]">FCFA</span>';
        totalDisplay.textContent = formatNumber(totalCost);
        hiddenPortId.value = portSelect.value;
    }

    portSelect.addEventListener('change', updateCosts);
    window.addEventListener('load', updateCosts);

    // ═══════════════════════════════════════════════
    //  IMAGE GALLERY
    // ═══════════════════════════════════════════════
    const mediaItems = @json($mediaItems ?? []);
    let currentIdx = 0;

    function setMedia(idx) {
        currentIdx = idx;
        const item = mediaItems[idx];
        
        const heroImg = document.getElementById('heroImage');
        const heroVideo = document.getElementById('heroVideo');
        
        // Pause and clear video if playing
        if (heroVideo) {
            heroVideo.pause();
            heroVideo.src = '';
            heroVideo.classList.add('hidden');
        }

        if (item.type === 'image') {
            if (heroImg) {
                heroImg.style.opacity = '0';
                heroImg.classList.remove('hidden');
                heroImg.src = item.url;
                setTimeout(() => {
                    heroImg.style.opacity = '1';
                }, 100);
            }
            const btn = document.getElementById('heroUnmuteBtn');
            if (btn) btn.classList.add('hidden');
        } else if (item.type === 'video') {
            if (heroImg) {
                heroImg.classList.add('hidden');
            }
            if (heroVideo) {
                heroVideo.src = item.url;
                heroVideo.muted = true; // ensure autoplay allowed
                heroVideo.classList.remove('hidden');
                heroVideo.play().then(() => {
                    const btn = document.getElementById('heroUnmuteBtn');
                    if (btn) btn.classList.remove('hidden');
                }).catch(err => console.log("Auto-play blocked", err));
            }
        }

        // Update counter
        const counter = document.getElementById('currentImageIdx');
        if (counter) counter.textContent = idx + 1;

        // Update thumbnails
        document.querySelectorAll('.thumb-item').forEach((t, i) => {
            if (i === idx) {
                t.classList.add('border-amber-500', 'scale-105', 'opacity-100');
                t.classList.remove('border-transparent', 'opacity-60');
            } else {
                t.classList.remove('border-amber-500', 'scale-105', 'opacity-100');
                t.classList.add('border-transparent', 'opacity-60');
            }
        });

        // Lightbox sync
        const lbImg = document.getElementById('lightboxImage');
        const lbVideo = document.getElementById('lightboxVideo');
        
        if (lbVideo) {
            lbVideo.pause();
            lbVideo.src = '';
            lbVideo.classList.add('hidden');
        }

        if (lbImg) {
            lbImg.classList.add('hidden');
        }

        if (item.type === 'image') {
            if (lbImg) {
                lbImg.src = item.url;
                lbImg.classList.remove('hidden');
            }
            const lbUnmute = document.getElementById('lightboxUnmuteBtn');
            if (lbUnmute) lbUnmute.classList.add('hidden');
        } else if (item.type === 'video') {
            if (lbVideo) {
                lbVideo.src = item.url;
                lbVideo.muted = true;
                lbVideo.classList.remove('hidden');
                const lbUnmute = document.getElementById('lightboxUnmuteBtn');
                if (lbUnmute) lbUnmute.classList.remove('hidden');
            }
        }

        const lbIdx = document.getElementById('lightboxIdx');
        if (lbIdx) lbIdx.textContent = idx + 1;
    }

    // Keep setImage alias for backwards compatibility
    function setImage(idx) {
        setMedia(idx);
    }

    function nextImage() {
        setMedia((currentIdx + 1) % mediaItems.length);
    }

    // Auto-advance carousel (pause on hover or when a video is playing)
    let autoAdvanceTimer = null;
    const AUTO_ADVANCE_MS = 5000;

    function startAutoAdvance() {
        stopAutoAdvance();
        if (!mediaItems || mediaItems.length <= 1) return;
        autoAdvanceTimer = setInterval(() => {
            const heroVideo = document.getElementById('heroVideo');
            // don't advance if current item is a video and it's playing
            if (mediaItems[currentIdx] && mediaItems[currentIdx].type === 'video' && heroVideo && !heroVideo.paused) return;
            setMedia((currentIdx + 1) % mediaItems.length);
        }, AUTO_ADVANCE_MS);
    }

    function stopAutoAdvance() {
        if (autoAdvanceTimer) {
            clearInterval(autoAdvanceTimer);
            autoAdvanceTimer = null;
        }
    }

    // Pause auto-advance when hovering gallery or when interacting with thumbnails
    const heroContainer = document.getElementById('heroImageContainer');
    if (heroContainer) {
        heroContainer.addEventListener('mouseenter', stopAutoAdvance);
        heroContainer.addEventListener('mouseleave', startAutoAdvance);
    }

    // Pause when lightbox is open
    const lightboxEl = document.getElementById('lightbox');
    const mutationObserver = new MutationObserver(() => {
        if (!lightboxEl) return;
        if (!lightboxEl.classList.contains('hidden')) stopAutoAdvance(); else startAutoAdvance();
    });
    if (lightboxEl) mutationObserver.observe(lightboxEl, { attributes: true, attributeFilter: ['class'] });

    // Pause when hero video plays
    const heroVideoEl = document.getElementById('heroVideo');
    if (heroVideoEl) {
        heroVideoEl.addEventListener('play', stopAutoAdvance);
        heroVideoEl.addEventListener('pause', () => { setTimeout(startAutoAdvance, 800); });
    }

    // Unmute helpers for hero and lightbox
    function toggleHeroMute() {
        const v = document.getElementById('heroVideo');
        const btn = document.getElementById('heroUnmuteBtn');
        if (!v) return;
        v.muted = !v.muted;
        if (!v.muted) {
            if (btn) btn.classList.add('hidden');
        } else {
            if (btn) btn.classList.remove('hidden');
        }
    }

    function toggleLightboxMute() {
        const v = document.getElementById('lightboxVideo');
        const btn = document.getElementById('lightboxUnmuteBtn');
        if (!v) return;
        v.muted = !v.muted;
        if (!v.muted) {
            if (btn) btn.classList.add('hidden');
        } else {
            if (btn) btn.classList.remove('hidden');
        }
    }

    // Start auto-advance on load
    window.addEventListener('load', () => {
        startAutoAdvance();
    });

    function prevImage() {
        setMedia((currentIdx - 1 + mediaItems.length) % mediaItems.length);
    }

    // ═══════════════════════════════════════════════
    //  LIGHTBOX
    // ═══════════════════════════════════════════════
    function openLightbox() {
        const lb = document.getElementById('lightbox');
        const lbImg = document.getElementById('lightboxImage');
        const lbVideo = document.getElementById('lightboxVideo');
        const item = mediaItems[currentIdx];

        if (lbVideo) {
            lbVideo.pause();
            lbVideo.src = '';
            lbVideo.classList.add('hidden');
        }
        if (lbImg) {
            lbImg.classList.add('hidden');
        }

        if (item.type === 'image') {
            if (lbImg) {
                lbImg.src = item.url;
                lbImg.classList.remove('hidden');
            }
        } else if (item.type === 'video') {
            if (lbVideo) {
                lbVideo.src = item.url;
                lbVideo.muted = true;
                lbVideo.classList.remove('hidden');
                lbVideo.play().catch(err => console.log("Lightbox play blocked", err));
                const lbUnmute = document.getElementById('lightboxUnmuteBtn');
                if (lbUnmute) lbUnmute.classList.remove('hidden');
            }
        }

        lb.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        lucide.createIcons();
    }

    function closeLightbox() {
        const lb = document.getElementById('lightbox');
        const lbVideo = document.getElementById('lightboxVideo');
        if (lbVideo) {
            lbVideo.pause();
            lbVideo.src = '';
            const lbUnmute = document.getElementById('lightboxUnmuteBtn');
            if (lbUnmute) lbUnmute.classList.add('hidden');
        }
        lb.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        const lb = document.getElementById('lightbox');
        if (!lb.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
        }
    });

    // ═══════════════════════════════════════════════
    //  TABS
    // ═══════════════════════════════════════════════
    function switchTab(tab) {
        // Hide all content
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        // Deactivate all buttons
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'text-amber-500');
            b.classList.add('text-slate-400');
        });
        // Show target
        document.getElementById('tab-' + tab)?.classList.remove('hidden');
        // Activate button
        const btn = document.querySelector(`.tab-btn[data-tab="${tab}"]`);
        if (btn) {
            btn.classList.add('active', 'text-amber-500');
            btn.classList.remove('text-slate-400');
        }
    }

    // ═══════════════════════════════════════════════
    //  SCROLL REVEAL
    // ═══════════════════════════════════════════════
    const revealElements = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    revealElements.forEach(el => observer.observe(el));

    // Re-initialize Lucide icons
    lucide.createIcons();

    // ═══════════════════════════════════════════════
    //  3D SHOWROOM AUTOMOBILE
    // ═══════════════════════════════════════════════
    window.switchMediaMode = function(mode) {
        const photoBtn = document.getElementById('btn-gallery-photos');
        const d3Btn = document.getElementById('btn-gallery-3d');
        const imgEl = document.getElementById('heroImage');
        const videoEl = document.getElementById('heroVideo');
        const modelViewer = document.getElementById('heroModel3D');
        const overlay = document.getElementById('heroOverlay');
        const overlayLeft = document.getElementById('heroOverlayLeft');
        const unmuteBtn = document.getElementById('heroUnmuteBtn');
        const zoomBtn = document.querySelector('[onclick="openLightbox()"]');
        const thumbStrip = document.querySelector('.thumb-scroll');
        const colorsPanel = document.getElementById('showroom-colors');
        const cameraPanel = document.getElementById('showroom-camera');

        if (mode === '3d') {
            if (photoBtn) {
                photoBtn.className = "px-4 py-1.5 text-[9px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-white hover:text-amber-500";
            }
            if (d3Btn) {
                d3Btn.className = "px-4 py-1.5 text-[9px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-slate-950 bg-amber-500 shadow-md";
            }
            
            if (imgEl) imgEl.classList.add('hidden');
            if (videoEl) {
                videoEl.classList.add('hidden');
                videoEl.pause();
            }
            if (unmuteBtn) unmuteBtn.classList.add('hidden');
            if (zoomBtn) zoomBtn.classList.add('hidden');
            if (overlay) overlay.classList.add('hidden');
            if (overlayLeft) overlayLeft.classList.add('hidden');
            if (thumbStrip) thumbStrip.style.opacity = '0.3';
            
            if (modelViewer) modelViewer.classList.remove('hidden');
            if (colorsPanel) colorsPanel.classList.remove('hidden');
            if (cameraPanel) cameraPanel.classList.remove('hidden');
            stopAutoAdvance();
        } else {
            if (d3Btn) {
                d3Btn.className = "px-4 py-1.5 text-[9px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-white hover:text-amber-500";
            }
            if (photoBtn) {
                photoBtn.className = "px-4 py-1.5 text-[9px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-slate-950 bg-amber-500 shadow-md";
            }
            
            if (modelViewer) modelViewer.classList.add('hidden');
            if (colorsPanel) colorsPanel.classList.add('hidden');
            if (cameraPanel) cameraPanel.classList.add('hidden');
            if (overlay) overlay.classList.remove('hidden');
            if (overlayLeft) overlayLeft.classList.remove('hidden');
            if (zoomBtn) zoomBtn.classList.remove('hidden');
            if (thumbStrip) thumbStrip.style.opacity = '1';
            
            setMedia(currentIdx);
            startAutoAdvance();
        }
    };

    window.changeCarColor = function(hexColor, button) {
        const modelViewer = document.getElementById('heroModel3D');
        if (!modelViewer || !modelViewer.model) return;
        
        // Convert hex to RGBA
        const r = parseInt(hexColor.slice(1, 3), 16) / 255;
        const g = parseInt(hexColor.slice(3, 5), 16) / 255;
        const b = parseInt(hexColor.slice(5, 7), 16) / 255;
        const colorArr = [r, g, b, 1.0];
        
        const targetNames = ['paint', 'body', 'carpaint', 'car_paint', 'coque', 'carrosserie', 'exterior', 'metal'];
        let updated = false;
        
        modelViewer.model.materials.forEach(material => {
            const name = material.name.toLowerCase();
            if (targetNames.some(t => name.includes(t))) {
                material.pbrMetallicRoughness.setBaseColorFactor(colorArr);
                updated = true;
            }
        });
        
        if (!updated && modelViewer.model.materials.length > 0) {
            // Fallback: apply to first material
            modelViewer.model.materials[0].pbrMetallicRoughness.setBaseColorFactor(colorArr);
        }
        
        // Toggle active border class
        document.querySelectorAll('.color-dot').forEach(dot => {
            dot.classList.remove('active');
        });
        if (button) button.classList.add('active');
    };

    window.setCameraOrbit = function(yaw, pitch, radius, btn) {
        const modelViewer = document.getElementById('heroModel3D');
        if (!modelViewer) return;
        
        modelViewer.cameraOrbit = `${yaw} ${pitch} ${radius}`;
        
        document.querySelectorAll('.camera-btn').forEach(b => {
            b.className = "camera-btn px-3 py-1.5 text-[8px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-white hover:text-amber-500";
        });
        if (btn) btn.className = "camera-btn px-3 py-1.5 text-[8px] font-black uppercase tracking-wider rounded-full transition-all duration-300 text-slate-950 bg-amber-500 shadow-md";
    };

    window.toggleModelFullscreen = function() {
        const modelViewer = document.getElementById('heroModel3D');
        if (!modelViewer) return;
        
        if (!document.fullscreenElement) {
            modelViewer.requestFullscreen().catch(err => {
                console.error(`Error attempting to enable full-screen mode: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    };

    // Set up model progress loader
    const modelViewerElement = document.getElementById('heroModel3D');
    if (modelViewerElement) {
        modelViewerElement.addEventListener('progress', (event) => {
            const progressBar = document.getElementById('model-progress-bar');
            const progress = event.detail.totalProgress * 100;
            if (progressBar) progressBar.style.width = `${progress}%`;
            
            if (progress >= 100) {
                const loader = document.getElementById('model-loader');
                if (loader) {
                    loader.classList.add('opacity-0');
                    setTimeout(() => loader.classList.add('hidden'), 500);
                }
            }
        });
        
        modelViewerElement.addEventListener('load', () => {
            const loader = document.getElementById('model-loader');
            if (loader) {
                loader.classList.add('opacity-0');
                setTimeout(() => loader.classList.add('hidden'), 500);
            }
            if (window.lucide) window.lucide.createIcons();
        });
    }
</script>
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
@endsection
