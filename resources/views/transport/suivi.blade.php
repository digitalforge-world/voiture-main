@extends('layouts.app')

@section('title', 'Suivi de votre transport — ' . $reservation->tracking_number)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  #suivi-map { height: 280px; border-radius: 1rem; z-index: 1; }
  
  @keyframes pulseGreen {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.4); }
    50%       { box-shadow: 0 0 0 12px rgba(16,185,129,0); }
  }
  @keyframes bounceDriver {
    0%, 100% { transform: translateY(0) rotate(-45deg); }
    50%       { transform: translateY(-4px) rotate(-45deg); }
  }

  /* Overlay notification arrivée */
  #arrivedOverlay {
    display: none;
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,0.85);
    backdrop-filter: blur(12px);
    flex-direction: column; align-items: center; justify-content: center;
    animation: fadeIn 0.4s ease;
  }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  @keyframes megaPulse {
    0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(245,158,11,0.6); }
    50%       { transform: scale(1.08); box-shadow: 0 0 0 30px rgba(245,158,11,0); }
  }
  .car-icon-big { animation: megaPulse 1.5s infinite; }

  /* Chat bubbles */
  .bubble-admin  { border-radius: 1rem 1rem 1rem 0; }
  .bubble-client { border-radius: 1rem 1rem 0 1rem; }
  .bubble-system { border-radius: 1rem; }

  /* Scroll chat */
  #chatMessages { scroll-behavior: smooth; }

  /* Prix accepté */
  @keyframes priceGlow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.3); }
    50%       { box-shadow: 0 0 20px rgba(16,185,129,0.4); }
  }
  .price-card { animation: priceGlow 2s infinite; }
</style>
@endsection

@section('content')
<div class="min-h-[calc(100vh-80px)] bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-8 px-4 transition-colors duration-300">
  <div class="max-w-5xl mx-auto space-y-6">

    {{-- ─── Header ─────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <a href="{{ route('transport.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-amber-500 text-xs mb-3 transition">
          ← Nouvelle réservation
        </a>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Suivi de votre transport</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Réf : <span class="font-mono text-amber-500 dark:text-amber-400 font-bold">{{ $reservation->tracking_number }}</span></p>
      </div>
      <div class="flex items-center gap-3">
        @php
          $statusColors = [
            'en_attente'          => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-500/20',
            'accepte'             => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20',
            'chauffeur_en_route'  => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
            'chauffeur_arrive'    => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
            'en_cours'            => 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-200 dark:border-purple-500/20',
            'termine'             => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
            'annule'              => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-500/20',
          ];
          $sc = $statusColors[$reservation->statut] ?? 'bg-slate-500/10 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-800';
        @endphp
        <span id="statutBadge" class="px-4 py-2 rounded-full text-xs font-bold border uppercase tracking-widest {{ $sc }}">
          {{ $reservation->statut_label }}
        </span>
      </div>
    </div>

    {{-- ─── Stepper ─────────────────────────────────────────────────────────── --}}
    @php
      $steps = [
        ['key' => 'en_attente',         'label' => 'En attente', 'icon' => 'clock'],
        ['key' => 'accepte',            'label' => 'Accepté',    'icon' => 'check-circle'],
        ['key' => 'chauffeur_en_route', 'label' => 'En route',   'icon' => 'navigation'],
        ['key' => 'chauffeur_arrive',   'label' => 'Arrivé',     'icon' => 'map-pin'],
        ['key' => 'en_cours',           'label' => 'En cours',   'icon' => 'play-circle'],
        ['key' => 'termine',            'label' => 'Terminé',    'icon' => 'check-square'],
      ];
      $statutOrder = ['en_attente' => 0, 'accepte' => 1, 'chauffeur_en_route' => 2, 'chauffeur_arrive' => 3, 'en_cours' => 4, 'termine' => 5, 'annule' => -1];
      $currentIdx  = $statutOrder[$reservation->statut] ?? 0;
    @endphp
    
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm overflow-x-auto custom-scrollbar">
      <div class="relative flex justify-between items-center w-full min-w-[600px] px-8 py-2">
        <!-- Progress Bar Background -->
        <div class="absolute top-[21px] left-12 right-12 h-0.5 bg-slate-200 dark:bg-slate-800 z-0">
          <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $currentIdx * 20 }}%"></div>
        </div>
        
        @foreach($steps as $i => $s)
          @php 
            $done = $currentIdx > $i; 
            $active = $currentIdx === $i; 
          @endphp
          <div class="relative z-10 flex flex-col items-center">
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 border-2
              {{ $done ? 'bg-emerald-500 border-emerald-500 text-white' : ($active ? 'bg-amber-500 border-amber-500 text-slate-950' : 'bg-white dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-600') }}">
              <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4"></i>
            </div>
            <span class="text-[9px] mt-2 font-bold uppercase tracking-wider
              {{ $done ? 'text-emerald-500' : ($active ? 'text-amber-500' : 'text-slate-400 dark:text-slate-600') }}">
              {{ $s['label'] }}
            </span>
          </div>
        @endforeach
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

      {{-- ─── Colonne gauche : Carte + Infos ────────────────────────────────── --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Carte --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800">
            <h2 class="text-slate-900 dark:text-white font-bold text-sm flex items-center gap-2">
              <i data-lucide="map" class="w-4 h-4 text-amber-500"></i> Votre trajet
            </h2>
            <div id="driverStatus" class="hidden items-center gap-2 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full">
              <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
              <span class="text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider">Chauffeur en direct</span>
            </div>
          </div>
          <div id="suivi-map" class="border-t border-slate-100 dark:border-slate-800/50"></div>
        </div>

        {{-- Infos course --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
          <h2 class="text-slate-900 dark:text-white font-bold text-sm mb-4 flex items-center gap-2">
            <i data-lucide="list-todo" class="w-4 h-4 text-amber-500"></i> Détails de la course
          </h2>
          <div class="space-y-3 text-sm">
            <div class="flex items-start gap-3 pb-3 border-b border-slate-100 dark:border-slate-800/50">
              <span class="w-8 h-8 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
              </span>
              <div>
                <div class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Départ</div>
                <div class="text-slate-800 dark:text-slate-100 text-xs leading-relaxed font-semibold">{{ $reservation->lieu_depart }}</div>
              </div>
            </div>
            <div class="flex items-start gap-3 pb-3 border-b border-slate-100 dark:border-slate-800/50">
              <span class="w-8 h-8 bg-rose-500/10 rounded-xl flex items-center justify-center text-rose-600 dark:text-rose-400 flex-shrink-0">
                <i data-lucide="flag" class="w-4 h-4"></i>
              </span>
              <div>
                <div class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Arrivée</div>
                <div class="text-slate-800 dark:text-slate-100 text-xs leading-relaxed font-semibold">{{ $reservation->lieu_arrivee }}</div>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800/40 rounded-xl p-3">
                <div class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-1">📅 Date</div>
                <div class="text-slate-800 dark:text-slate-200 text-xs font-semibold">{{ $reservation->date_prise_en_charge->format('d/m/Y à H:i') }}</div>
              </div>
              <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800/40 rounded-xl p-3">
                <div class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-1">👥 Passagers</div>
                <div class="text-slate-800 dark:text-slate-200 text-xs font-semibold">{{ $reservation->nombre_personnes }} personne(s)</div>
              </div>
              <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800/40 rounded-xl p-3">
                <div class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-1">🚗 Type</div>
                <div class="text-slate-800 dark:text-slate-200 text-xs font-semibold">{{ \App\Models\ReservationTransport::typeServiceLabels()[$reservation->type_service] }}</div>
              </div>
              <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800/40 rounded-xl p-3">
                <div class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-1">📞 Téléphone</div>
                <div class="text-slate-800 dark:text-slate-200 text-xs font-semibold">{{ $reservation->client_telephone }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Prix proposé --}}
        @if($reservation->prix_propose)
        <div id="priceCard" class="price-card bg-emerald-500/5 dark:bg-emerald-500/10 border border-emerald-500/20 dark:border-emerald-500/30 rounded-2xl p-5 shadow-sm">
          <div class="flex items-center justify-between gap-4">
            <div>
              <div class="text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                <i data-lucide="banknote" class="w-3.5 h-3.5"></i> Prix proposé
              </div>
              <div class="text-3xl font-bold text-slate-900 dark:text-white">
                {{ number_format($reservation->prix_propose, 0, ',', ' ') }}
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">FCFA</span>
              </div>
            </div>
            @if(!$reservation->prix_accepte)
            <form id="acceptPriceForm">
              @csrf
              <input type="hidden" name="tracking" value="{{ $reservation->tracking_number }}">
              <button type="submit" id="acceptPriceBtn"
                class="bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-6 py-3 rounded-xl text-sm transition hover:-translate-y-0.5 shadow-lg shadow-emerald-500/20">
                Accepter ce prix
              </button>
            </form>
            @else
            <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 px-4 py-2 rounded-xl">
              <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
              <span class="text-emerald-600 dark:text-emerald-400 font-bold text-sm">Prix accepté</span>
            </div>
            @endif
          </div>
        </div>
        @else
        <div id="priceCard" class="hidden price-card bg-emerald-500/5 dark:bg-emerald-500/10 border border-emerald-500/20 dark:border-emerald-500/30 rounded-2xl p-5 shadow-sm">
          <div class="flex items-center justify-between gap-4">
            <div>
              <div class="text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                <i data-lucide="banknote" class="w-3.5 h-3.5"></i> Prix proposé
              </div>
              <div class="text-3xl font-bold text-slate-900 dark:text-white" id="dynamicPrice">— <span class="text-sm font-medium text-slate-500 dark:text-slate-400">FCFA</span></div>
            </div>
            <form id="acceptPriceForm">
              @csrf
              <input type="hidden" name="tracking" value="{{ $reservation->tracking_number }}">
              <button type="submit" id="acceptPriceBtn"
                class="bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-6 py-3 rounded-xl text-sm transition hover:-translate-y-0.5 shadow-lg shadow-emerald-500/20">
                Accepter ce prix
              </button>
            </form>
          </div>
        </div>
        @endif

      </div>

      {{-- ─── Chat ───────────────────────────────────────────────────────────── --}}
      <div class="lg:col-span-2 flex flex-col">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col shadow-sm" style="height: 600px;">
          <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-3 flex-shrink-0">
            <div class="w-9 h-9 bg-amber-500 rounded-xl flex items-center justify-center text-slate-950 shadow-md">
              <i data-lucide="message-square" class="w-4 h-4"></i>
            </div>
            <div>
              <div class="text-slate-900 dark:text-white font-bold text-sm">Chat avec notre équipe</div>
              <div class="text-slate-500 dark:text-slate-400 text-xs">Temps de réponse : &lt; 5 min</div>
            </div>
          </div>

          {{-- Messages --}}
          <div id="chatMessages" class="flex-grow overflow-y-auto p-4 space-y-3 custom-chat-scroll bg-slate-50/50 dark:bg-slate-950/20">
            @foreach($reservation->conversations as $msg)
              @if($msg->type === 'notification_systeme')
                <div class="flex justify-center" data-msg-id="{{ $msg->id }}">
                  <div class="bubble-system bg-indigo-500/5 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-300 px-4 py-2 text-xs text-center max-w-xs">{{ $msg->message }}</div>
                </div>
              @elseif($msg->auteur === 'admin')
                <div class="flex items-start gap-2" data-msg-id="{{ $msg->id }}">
                  <div class="w-7 h-7 bg-amber-500 rounded-xl flex items-center justify-center text-slate-950 text-xs font-bold flex-shrink-0 shadow">A</div>
                  <div class="max-w-[85%]">
                    <div class="bubble-admin bg-amber-500/10 border border-amber-500/20 text-slate-800 dark:text-slate-100 px-4 py-3 text-xs leading-relaxed">{{ $msg->message }}</div>
                    <div class="text-slate-400 dark:text-slate-600 text-[10px] mt-1 ml-1">{{ $msg->created_at->format('H:i') }}</div>
                  </div>
                </div>
              @else
                <div class="flex items-start gap-2 flex-row-reverse" data-msg-id="{{ $msg->id }}">
                  <div class="w-7 h-7 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($reservation->client_nom, 0, 1)) }}
                  </div>
                  <div class="max-w-[85%]">
                    <div class="bubble-client bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-100 px-4 py-3 text-xs leading-relaxed">{{ $msg->message }}</div>
                    <div class="text-slate-400 dark:text-slate-600 text-[10px] mt-1 mr-1 text-right">{{ $msg->created_at->format('H:i') }}</div>
                  </div>
                </div>
              @endif
            @endforeach
          </div>

          {{-- Input message --}}
          <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex-shrink-0">
            <form id="chatForm" class="flex gap-2">
              @csrf
              <input type="hidden" name="tracking" value="{{ $reservation->tracking_number }}">
              <input type="text" name="message" id="chatInput" placeholder="Écrire un message..."
                class="flex-grow bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500/50 transition"
                autocomplete="off">
              <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2.5 rounded-xl transition flex-shrink-0 shadow-md">
                ➤
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- ─── Overlay "Chauffeur Arrivé" ──────────────────────────────────────────── --}}
<div id="arrivedOverlay">
  <div class="text-center px-8">
    <div class="car-icon-big w-32 h-32 bg-amber-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-amber-500/40 text-slate-950">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16">
        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2a3 3 0 0 0 6 0h2a3 3 0 0 0 6 0Z"></path>
        <circle cx="7" cy="17" r="2"></circle>
        <circle cx="17" cy="17" r="2"></circle>
      </svg>
    </div>
    <h2 class="text-4xl font-bold text-white mb-3">Votre chauffeur est arrivé !</h2>
    <p class="text-slate-300 text-lg mb-2">Il vous attend à votre point de départ.</p>
    <p class="text-slate-500 text-sm mb-8">Rejoignez-le dès que possible.</p>
    <div class="bg-white/10 border border-white/20 rounded-2xl px-6 py-4 inline-flex items-center gap-4 mb-8">
      <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
        <i data-lucide="phone" class="w-6 h-6"></i>
      </div>
      <div class="text-left">
        <div class="text-slate-400 text-xs">Téléphone du chauffeur via notre équipe</div>
        <div class="text-white font-bold text-lg">{{ $reservation->client_telephone }}</div>
      </div>
    </div>
    <br>
    <button onclick="document.getElementById('arrivedOverlay').style.display='none'"
      class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-8 py-4 rounded-2xl text-sm uppercase tracking-widest transition">
      Fermer cette notification
    </button>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const TRACKING  = '{{ $reservation->tracking_number }}';
const LAT_DEP   = {{ $reservation->lat_depart  ?? 6.1372 }};
const LNG_DEP   = {{ $reservation->lng_depart  ?? 1.2125 }};
const LAT_ARR   = {{ $reservation->lat_arrivee ?? 6.1644 }};
const LNG_ARR   = {{ $reservation->lng_arrivee ?? 1.2514 }};
let STATUT      = '{{ $reservation->statut }}';
let LAST_MSG_ID = {{ $reservation->conversations->last()?->id ?? 0 }};
let driverMarker = null;
let notifiedArrival = {{ $reservation->chauffeur_arrived ? 'true' : 'false' }};
let currentPrix = {{ $reservation->prix_propose ?? 'null' }};
let prixAccepte = {{ $reservation->prix_accepte ? 'true' : 'false' }};
const LAT_DRV   = {{ $reservation->chauffeur_lat ?? 'null' }};
const LNG_DRV   = {{ $reservation->chauffeur_lng ?? 'null' }};

// ─── Carte ───────────────────────────────────────────────────────────────────
const map = L.map('suivi-map').setView([LAT_DEP, LNG_DEP], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

const iconDepart = L.divIcon({ html: `<div style="width:24px;height:24px;background:#10b981;border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.4)"></div>`, iconSize:[24,24], iconAnchor:[12,12], className:'' });
const iconArrivee = L.divIcon({ html: `<div style="width:24px;height:24px;background:#ef4444;border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.4)"></div>`, iconSize:[24,24], iconAnchor:[12,12], className:'' });
const carIconSvg = `
  <div style="background: #f59e0b; border: 2px solid white; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;" class="text-slate-950">
      <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2a3 3 0 0 0 6 0h2a3 3 0 0 0 6 0Z"></path>
      <circle cx="7" cy="17" r="2"></circle>
      <circle cx="17" cy="17" r="2"></circle>
    </svg>
  </div>
`;
const iconDriver  = L.divIcon({ html: carIconSvg, iconSize:[36,36], iconAnchor:[18,18], className:'' });

if (LAT_DEP && LNG_DEP) L.marker([LAT_DEP, LNG_DEP], { icon: iconDepart }).addTo(map).bindPopup('<b>📍 Point de départ</b>');
if (LAT_ARR && LNG_ARR) {
  L.marker([LAT_ARR, LNG_ARR], { icon: iconArrivee }).addTo(map).bindPopup('<b>🏁 Destination</b>');
}
if (LAT_DRV && LNG_DRV && ['chauffeur_en_route', 'chauffeur_arrive', 'en_cours'].includes(STATUT)) {
  driverMarker = L.marker([LAT_DRV, LNG_DRV], { icon: iconDriver, zIndexOffset: 1000 }).addTo(map);
  driverMarker.bindPopup('<b>🚗 Votre chauffeur</b>');
  document.getElementById('driverStatus').classList.remove('hidden');
  document.getElementById('driverStatus').classList.add('flex');
}

// ─── Routage Réel OSRM ──────────────────────────────────────────────────────
let routeLine = null;

function drawRoute(latlngDep, latlngArr) {
  if (!latlngDep || !latlngArr) return;

  const url = `https://router.project-osrm.org/route/v1/driving/${latlngDep[1]},${latlngDep[0]};${latlngArr[1]},${latlngArr[0]}?overview=full&geometries=geojson`;

  fetch(url)
    .then(r => r.json())
    .then(data => {
      if (data.routes && data.routes.length > 0) {
        const route = data.routes[0];
        const coords = route.geometry.coordinates;
        const latlngs = coords.map(c => [c[1], c[0]]);

        if (routeLine) map.removeLayer(routeLine);
        routeLine = L.polyline(latlngs, {
          color: '#f59e0b',
          weight: 5,
          opacity: 0.85
        }).addTo(map);

        map.fitBounds(routeLine.getBounds(), { padding: [40, 50] });
      } else {
        drawStraightLine(latlngDep, latlngArr);
      }
    })
    .catch(() => {
      drawStraightLine(latlngDep, latlngArr);
    });
}

function drawStraightLine(latlngDep, latlngArr) {
  if (routeLine) map.removeLayer(routeLine);
  routeLine = L.polyline([latlngDep, latlngArr], {
    color: '#f59e0b',
    weight: 3,
    dashArray: '8,8',
    opacity: 0.85
  }).addTo(map);
  map.fitBounds(routeLine.getBounds(), { padding: [40, 50] });
}

if (LAT_DEP && LNG_DEP && LAT_ARR && LNG_ARR) {
  drawRoute([LAT_DEP, LNG_DEP], [LAT_ARR, LNG_ARR]);
} else if (LAT_DEP && LNG_DEP) {
  map.setView([LAT_DEP, LNG_DEP], 14);
}

// ─── Scroll chat au bas ───────────────────────────────────────────────────────
function scrollChatBottom() {
  const c = document.getElementById('chatMessages');
  c.scrollTop = c.scrollHeight;
}
scrollChatBottom();

// ─── Ajouter un message dans le chat ─────────────────────────────────────────
function appendMessage(msg) {
  if (msg.id && document.querySelector(`[data-msg-id="${msg.id}"]`)) {
    return;
  }
  if (msg.id) {
    LAST_MSG_ID = Math.max(LAST_MSG_ID, msg.id);
  }

  const c = document.getElementById('chatMessages');
  let dateObj = msg.created_at ? new Date(msg.created_at) : new Date();
  if (isNaN(dateObj.getTime())) {
    dateObj = new Date();
  }
  const time = dateObj.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
  let html = '';

  if (msg.type === 'notification_systeme' || msg.auteur === 'systeme') {
    html = `<div class="flex justify-center" data-msg-id="${msg.id || ''}"><div class="bubble-system bg-indigo-500/5 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-300 px-4 py-2 text-xs text-center max-w-xs">${msg.message}</div></div>`;
  } else if (msg.auteur === 'admin') {
    html = `<div class="flex items-start gap-2" data-msg-id="${msg.id || ''}">
      <div class="w-7 h-7 bg-amber-500 rounded-xl flex items-center justify-center text-slate-950 text-xs font-bold flex-shrink-0 shadow">A</div>
      <div class="max-w-[85%]"><div class="bubble-admin bg-amber-500/10 border border-amber-500/20 text-slate-800 dark:text-slate-100 px-4 py-3 text-xs leading-relaxed">${msg.message}</div>
      <div class="text-slate-400 dark:text-slate-600 text-[10px] mt-1 ml-1">${time}</div></div></div>`;
  } else {
    html = `<div class="flex items-start gap-2 flex-row-reverse" data-msg-id="${msg.id || ''}">
      <div class="w-7 h-7 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-xs font-bold flex-shrink-0">
        ${msg.auteur ? msg.auteur.substring(0, 1).toUpperCase() : 'C'}
      </div>
      <div class="max-w-[85%]"><div class="bubble-client bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-100 px-4 py-3 text-xs leading-relaxed">${msg.message}</div>
      <div class="text-slate-400 dark:text-slate-600 text-[10px] mt-1 mr-1 text-right">${time}</div></div></div>`;
  }

  c.insertAdjacentHTML('beforeend', html);
  scrollChatBottom();
}

// ─── Notification arrivée chauffeur ──────────────────────────────────────────
function triggerArrivedNotification() {
  if (notifiedArrival) return;
  notifiedArrival = true;

  // Overlay visuel
  document.getElementById('arrivedOverlay').style.display = 'flex';

  // Son via Web Audio API
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    [523, 659, 784].forEach((freq, i) => {
      const o = ctx.createOscillator();
      const g = ctx.createGain();
      o.connect(g); g.connect(ctx.destination);
      o.frequency.value = freq; o.type = 'sine';
      g.gain.setValueAtTime(0.3, ctx.currentTime + i * 0.2);
      g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.2 + 0.5);
      o.start(ctx.currentTime + i * 0.2);
      o.stop(ctx.currentTime + i * 0.2 + 0.5);
    });
  } catch(e) {}

  // Notification navigateur
  if (Notification.permission === 'granted') {
    new Notification('Votre chauffeur est arrivé !', {
      body: 'Il vous attend à votre point de départ.',
      icon: '/favicon.ico'
    });
  } else if (Notification.permission !== 'denied') {
    Notification.requestPermission();
  }
}

// ─── Polling : messages + position chauffeur ──────────────────────────────────
let pollingActive = false;
function pollMessages() {
  if (pollingActive) return;
  pollingActive = true;

  fetch(`/transport/messages/${TRACKING}?since_id=${LAST_MSG_ID}`)
    .then(r => r.json())
    .then(data => {
      // Nouveaux messages
      data.messages.forEach(msg => {
        appendMessage(msg);
      });

      // Mise à jour statut
      if (data.statut && data.statut !== STATUT) {
        STATUT = data.statut;
        // Mettre à jour le badge (rechargement léger)
        location.reload();
      }

      // Mise à jour prix
      if (data.prix_propose && data.prix_propose !== currentPrix) {
        currentPrix = data.prix_propose;
        const card = document.getElementById('priceCard');
        const dynPrice = document.getElementById('dynamicPrice');
        if (dynPrice) dynPrice.innerHTML = new Intl.NumberFormat('fr-FR').format(currentPrix) + ' <span class="text-sm font-medium text-slate-500 dark:text-slate-400">FCFA</span>';
        card.classList.remove('hidden');
      }
    })
    .catch(() => {})
    .finally(() => {
      pollingActive = false;
    });
}

let driverPollingActive = false;
function pollDriverLocation() {
  if (driverPollingActive) return;
  if (!['chauffeur_en_route', 'chauffeur_arrive'].includes(STATUT)) return;

  driverPollingActive = true;
  fetch(`/transport/driver-location/${TRACKING}`)
    .then(r => r.json())
    .then(data => {
      // Mise à jour marqueur chauffeur
      if (data.trackable && data.chauffeur_lat && data.chauffeur_lng) {
        const pos = [data.chauffeur_lat, data.chauffeur_lng];
        if (driverMarker) {
          driverMarker.setLatLng(pos);
        } else {
          driverMarker = L.marker(pos, { icon: iconDriver, zIndexOffset: 1000 }).addTo(map);
          driverMarker.bindPopup('<b>🚗 Votre chauffeur</b>');
          document.getElementById('driverStatus').classList.remove('hidden');
          document.getElementById('driverStatus').classList.add('flex');
        }
      }

      // Notification arrivée
      if (data.chauffeur_arrived && !notifiedArrival) {
        triggerArrivedNotification();
      }

      STATUT = data.statut || STATUT;
    })
    .catch(() => {})
    .finally(() => {
      driverPollingActive = false;
    });
}

// Démarrer les pollings
setInterval(pollMessages, 5000);
setInterval(pollDriverLocation, 5000);

// Demande permission notification au chargement
if (Notification.permission === 'default') {
  setTimeout(() => Notification.requestPermission(), 3000);
}

// Si déjà arrivé au chargement
if (notifiedArrival) {
  document.getElementById('arrivedOverlay').style.display = 'flex';
}

// ─── Envoi message chat ────────────────────────────────────────────────────────
document.getElementById('chatForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const input = document.getElementById('chatInput');
  const msg   = input.value.trim();
  if (!msg) return;

  input.value = '';
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  fetch('{{ route("transport.message") }}', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    body: JSON.stringify({ tracking: TRACKING, message: msg })
  }).then(r => r.json()).then(data => {
    if (data.success) appendMessage(data.message);
  });
});

// ─── Accepter le prix ──────────────────────────────────────────────────────────
const acceptForm = document.getElementById('acceptPriceForm');
if (acceptForm) {
  acceptForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch('{{ route("transport.accept-price") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ tracking: TRACKING })
    }).then(r => r.json()).then(data => {
      if (data.success) {
        const btn = document.getElementById('acceptPriceBtn');
        if (btn) {
          btn.closest('form').innerHTML = `<div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 px-4 py-2 rounded-xl"><i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i><span class="text-emerald-600 dark:text-emerald-400 font-bold text-sm">Prix accepté</span></div>`;
          lucide.createIcons({ nodes: [btn.closest('form')] });
        }
      }
    });
  });
}
</script>
<style>
.custom-chat-scroll::-webkit-scrollbar { width: 4px; }
.custom-chat-scroll::-webkit-scrollbar-thumb { background: rgba(245,158,11,0.2); border-radius: 4px; }
</style>
@endsection
