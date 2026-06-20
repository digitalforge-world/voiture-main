@extends('layouts.admin')

@section('title', 'Réservation Transport #' . $reservation->reference)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  #admin-map { height: 300px; border-radius: 0.75rem; z-index: 1; }
  .bubble-admin  { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); border-radius: 0.75rem 0.75rem 0.75rem 0; }
  .bubble-client { background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.06); border-radius: 0.75rem 0.75rem 0 0.75rem; }
  .bubble-system { background: rgba(99,102,241,0.06); border: 1px solid rgba(99,102,241,0.15); border-radius: 0.75rem; }
  #chatBox { scroll-behavior: smooth; }
</style>
@endsection

@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="{{ route('admin.transport.index') }}" class="p-2 text-slate-400 hover:text-white bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
      </a>
      <div>
        <h1 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="car" class="w-5 h-5 text-amber-500"></i> {{ $reservation->reference }}
        </h1>
        <p class="text-xs text-slate-500 mt-0.5">{{ $reservation->date_reservation->format('d/m/Y à H:i') }}</p>
      </div>
    </div>

    {{-- Changement de statut rapide --}}
    <form action="{{ route('admin.transport.status', $reservation->id) }}" method="POST" class="flex items-center gap-2">
      @csrf
      <select name="statut" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-slate-900 dark:text-white focus:border-amber-500 transition">
        @foreach(\App\Models\ReservationTransport::statutLabels() as $val => $label)
        <option value="{{ $val }}" {{ $reservation->statut === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
      <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 text-sm font-bold rounded-xl transition">
        Mettre à jour
      </button>
    </form>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    {{-- ─── Colonne gauche ──────────────────────────────────────────────────── --}}
    <div class="lg:col-span-3 space-y-5">

      {{-- Infos client --}}
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
          <i data-lucide="user" class="w-4 h-4 text-amber-500"></i> Informations Client
        </h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <div class="text-xs text-slate-500 mb-1">Nom</div>
            <div class="font-semibold text-slate-900 dark:text-white">{{ $reservation->client_nom }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500 mb-1">📞 Téléphone</div>
            <a href="tel:{{ $reservation->client_telephone }}"
              class="font-bold text-amber-500 hover:text-amber-400 transition text-base">
              {{ $reservation->client_telephone }}
            </a>
          </div>
          @if($reservation->client_email)
          <div>
            <div class="text-xs text-slate-500 mb-1">Email</div>
            <div class="text-slate-900 dark:text-white text-xs">{{ $reservation->client_email }}</div>
          </div>
          @endif
          <div>
            <div class="text-xs text-slate-500 mb-1">Type de service</div>
            <div class="text-slate-900 dark:text-white">{{ \App\Models\ReservationTransport::typeServiceLabels()[$reservation->type_service] }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500 mb-1">Date prise en charge</div>
            <div class="text-slate-900 dark:text-white font-semibold">{{ $reservation->date_prise_en_charge->format('d/m/Y à H:i') }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500 mb-1">Passagers</div>
            <div class="text-slate-900 dark:text-white">{{ $reservation->nombre_personnes }} personne(s)</div>
          </div>
          @if($reservation->notes_client)
          <div class="col-span-2">
            <div class="text-xs text-slate-500 mb-1">Notes client</div>
            <div class="text-slate-900 dark:text-white text-xs bg-slate-50 dark:bg-slate-800 rounded-xl p-3">{{ $reservation->notes_client }}</div>
          </div>
          @endif
        </div>
      </div>

      {{-- Carte --}}
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 dark:border-slate-800">
          <h2 class="text-sm font-semibold text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="map" class="w-4 h-4 text-amber-500"></i> Trajet
          </h2>
          @if($reservation->isDriverTrackable())
          <span class="flex items-center gap-2 text-xs text-amber-500 font-semibold">
            <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
            Chauffeur en direct
          </span>
          @endif
        </div>
        <div id="admin-map"></div>
        <div class="px-5 py-3 grid grid-cols-2 gap-3 border-t border-slate-100 dark:border-slate-800">
          <div>
            <div class="text-xs text-slate-500 mb-0.5 flex items-center gap-1">
              <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-500"></i> Départ
            </div>
            <div class="text-xs text-slate-900 dark:text-white leading-relaxed">{{ $reservation->lieu_depart }}</div>
          </div>
          <div>
            <div class="text-xs text-slate-500 mb-0.5 flex items-center gap-1">
              <i data-lucide="flag" class="w-3.5 h-3.5 text-rose-500"></i> Arrivée
            </div>
            <div class="text-xs text-slate-900 dark:text-white leading-relaxed">{{ $reservation->lieu_arrivee }}</div>
          </div>
        </div>
      </div>

      {{-- Actions Transport --}}
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5">
        <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
          <i data-lucide="zap" class="w-4 h-4 text-amber-500"></i> Actions Chauffeur
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

          {{-- Générer lien chauffeur --}}
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
            <div class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1">
              <i data-lucide="smartphone" class="w-3.5 h-3.5 text-amber-500"></i> Lien GPS Chauffeur
            </div>
            <div id="driverLinkContainer" class="hidden mb-3">
              <input id="driverLinkInput" type="text" readonly
                class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-amber-500 font-mono">
            </div>
            <button id="btnGenerateLink" onclick="generateDriverLink()"
              class="w-full flex items-center justify-center gap-2 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-lg transition">
              <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
              Démarrer le tracking
            </button>
            <p class="text-xs text-slate-500 mt-1.5">Partagez ce lien au chauffeur via WhatsApp</p>
          </div>

          {{-- Notifier arrivée --}}
          @if(!$reservation->chauffeur_arrived)
          <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
            <div class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1">
              <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-500"></i> Notifier Arrivée
            </div>
            <p class="text-xs text-slate-500 mb-3">Le client sera immédiatement notifié.</p>
            <button onclick="notifyArrival()"
              class="w-full flex items-center justify-center gap-2 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-bold rounded-lg transition">
              <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
              Chauffeur est arrivé
            </button>
          </div>
          @else
          <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl p-4 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-6 h-6 text-emerald-500"></i>
            <div>
              <div class="text-emerald-700 dark:text-emerald-400 text-xs font-bold">Arrivée confirmée</div>
              <div class="text-emerald-600 dark:text-emerald-500 text-xs">{{ $reservation->chauffeur_arrived_at?->format('H:i') }}</div>
            </div>
          </div>
          @endif

        </div>

        {{-- Prix négociation --}}
        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
          <div class="flex items-center justify-between mb-3">
            <div class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1">
              <i data-lucide="banknote" class="w-3.5 h-3.5 text-amber-500"></i> Négociation Prix
            </div>
            @if($reservation->prix_propose)
            <div class="flex items-center gap-2">
              <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($reservation->prix_propose, 0, ',', ' ') }} FCFA</span>
              @if($reservation->prix_accepte)
              <span class="text-emerald-500 text-xs font-bold flex items-center gap-1"><i data-lucide="check" class="w-3 h-3"></i> Accepté</span>
              @else
              <span class="text-amber-500 text-xs font-bold flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3 animate-pulse"></i> En attente</span>
              @endif
            </div>
            @endif
          </div>
          <form id="priceForm" class="flex gap-2">
            @csrf
            <div class="relative flex-grow">
              <input type="number" id="prixInput" placeholder="Montant FCFA" min="0"
                class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 pr-14 text-sm text-slate-900 dark:text-white focus:border-amber-500 transition">
              <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500 font-semibold">FCFA</span>
            </div>
            <button type="submit"
              class="px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold rounded-lg transition flex-shrink-0">
              Proposer
            </button>
          </form>
        </div>
      </div>

    </div>

    {{-- ─── Chat Admin ─────────────────────────────────────────────────────── --}}
    <div class="lg:col-span-2 flex flex-col">
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex flex-col" style="height: 680px;">

        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex-shrink-0 flex items-center gap-3">
          <div class="w-9 h-9 bg-amber-500 rounded-xl flex items-center justify-center">
            <i data-lucide="message-circle" class="w-4 h-4 text-slate-950"></i>
          </div>
          <div>
            <div class="text-sm font-semibold text-slate-900 dark:text-white">Chat avec {{ $reservation->client_nom }}</div>
            <div class="text-xs text-slate-500">{{ $reservation->client_telephone }}</div>
          </div>
        </div>

        {{-- Messages --}}
        <div id="chatBox" class="flex-grow overflow-y-auto p-4 space-y-3">
          @foreach($reservation->conversations as $msg)
            @if($msg->type === 'notification_systeme' || $msg->auteur === 'systeme')
            <div class="flex justify-center" data-msg-id="{{ $msg->id }}">
              <div class="bubble-system px-4 py-2 text-indigo-500 dark:text-indigo-400 text-xs text-center max-w-xs">{{ $msg->message }}</div>
            </div>
            @elseif($msg->auteur === 'admin')
            <div class="flex items-start gap-2 flex-row-reverse" data-msg-id="{{ $msg->id }}">
              <div class="w-7 h-7 bg-amber-500 rounded-xl flex items-center justify-center text-slate-950 text-xs font-bold flex-shrink-0">A</div>
              <div class="max-w-[85%]">
                <div class="bubble-admin px-4 py-3 text-slate-900 dark:text-white text-xs leading-relaxed">{{ $msg->message }}</div>
                @if($msg->type === 'proposition_prix' && $msg->montant)
                <div class="text-xs text-amber-500 font-bold mt-1 text-right">{{ number_format($msg->montant, 0, ',', ' ') }} FCFA</div>
                @endif
                <div class="text-slate-400 text-[10px] mt-1 text-right">{{ $msg->created_at->format('H:i') }}</div>
              </div>
            </div>
            @else
            <div class="flex items-start gap-2" data-msg-id="{{ $msg->id }}">
              <div class="w-7 h-7 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-500 text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr($reservation->client_nom, 0, 1)) }}
              </div>
              <div class="max-w-[85%]">
                <div class="bubble-client px-4 py-3 text-slate-900 dark:text-white text-xs leading-relaxed">{{ $msg->message }}</div>
                @if($msg->type === 'confirmation_prix')
                <div class="text-xs text-emerald-500 font-bold mt-1">✅ Prix accepté</div>
                @endif
                <div class="text-slate-400 text-[10px] mt-1">{{ $msg->created_at->format('H:i') }}</div>
              </div>
            </div>
            @endif
          @endforeach
        </div>

        {{-- Input admin --}}
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex-shrink-0">
          <form id="adminChatForm" class="flex gap-2">
            @csrf
            <input type="text" id="adminInput" placeholder="Répondre au client..."
              class="flex-grow bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:border-amber-500 transition"
              autocomplete="off">
            <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2.5 rounded-xl transition flex-shrink-0">
              ➤
            </button>
          </form>
        </div>

      </div>
    </div>

  </div>

</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const RES_ID   = {{ $reservation->id }};
const LAT_DEP  = {{ $reservation->lat_depart  ?? 6.1372 }};
const LNG_DEP  = {{ $reservation->lng_depart  ?? 1.2125 }};
const LAT_ARR  = {{ $reservation->lat_arrivee ?? 6.1644 }};
const LNG_ARR  = {{ $reservation->lng_arrivee ?? 1.2514 }};
const CSRF     = document.querySelector('meta[name="csrf-token"]').content;
let LAST_MSG_ID = {{ $reservation->conversations->last()?->id ?? 0 }};
let driverMarker = null;
const STATUT    = '{{ $reservation->statut }}';
const LAT_DRV   = {{ $reservation->chauffeur_lat ?? 'null' }};
const LNG_DRV   = {{ $reservation->chauffeur_lng ?? 'null' }};

// ─── Carte ───────────────────────────────────────────────────────────────────
const map = L.map('admin-map').setView([LAT_DEP, LNG_DEP], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

const iconDep  = L.divIcon({ html: `<div style="width:20px;height:20px;background:#10b981;border:3px solid white;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.4)"></div>`, iconSize:[20,20], iconAnchor:[10,10], className:'' });
const iconArr  = L.divIcon({ html: `<div style="width:20px;height:20px;background:#ef4444;border:3px solid white;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.4)"></div>`, iconSize:[20,20], iconAnchor:[10,10], className:'' });

const carIconSvg = `
  <div style="background: #f59e0b; border: 2px solid white; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.4);">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;" class="text-slate-950">
      <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2a3 3 0 0 0 6 0h2a3 3 0 0 0 6 0Z"></path>
      <circle cx="7" cy="17" r="2"></circle>
      <circle cx="17" cy="17" r="2"></circle>
    </svg>
  </div>
`;
const iconDrv  = L.divIcon({ html: carIconSvg, iconSize:[32,32], iconAnchor:[16,16], className:'' });

if (LAT_DEP && LNG_DEP) L.marker([LAT_DEP, LNG_DEP], { icon: iconDep }).addTo(map).bindPopup('<b>📍 Départ client</b>');
if (LAT_ARR && LNG_ARR) {
  L.marker([LAT_ARR, LNG_ARR], { icon: iconArr }).addTo(map).bindPopup('<b>🏁 Destination</b>');
}
if (LAT_DRV && LNG_DRV && ['chauffeur_en_route', 'chauffeur_arrive', 'en_cours'].includes(STATUT)) {
  driverMarker = L.marker([LAT_DRV, LNG_DRV], { icon: iconDrv, zIndexOffset: 1000 }).addTo(map).bindPopup('<b>🚗 Chauffeur</b>');
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
          weight: 4,
          opacity: 0.85
        }).addTo(map);

        map.fitBounds(routeLine.getBounds(), { padding: [20, 20] });
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
    weight: 2,
    dashArray: '6,6',
    opacity: 0.85
  }).addTo(map);
  map.fitBounds(routeLine.getBounds(), { padding: [20, 20] });
}

if (LAT_DEP && LNG_DEP && LAT_ARR && LNG_ARR) {
  drawRoute([LAT_DEP, LNG_DEP], [LAT_ARR, LNG_ARR]);
} else if (LAT_DEP && LNG_DEP) {
  map.setView([LAT_DEP, LNG_DEP], 14);
}

// ─── Scroll chat bottom ────────────────────────────────────────────────────
function scrollChatBottom() {
  const c = document.getElementById('chatBox');
  c.scrollTop = c.scrollHeight;
}
scrollChatBottom();

// ─── Append message ────────────────────────────────────────────────────────
function appendMsg(msg) {
  if (msg.id && document.querySelector(`[data-msg-id="${msg.id}"]`)) {
    return;
  }
  if (msg.id) {
    LAST_MSG_ID = Math.max(LAST_MSG_ID, msg.id);
  }

  const c = document.getElementById('chatBox');
  let dateObj = msg.created_at ? new Date(msg.created_at) : new Date();
  if (isNaN(dateObj.getTime())) {
    dateObj = new Date();
  }
  const time = dateObj.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
  let html = '';

  if (msg.type === 'notification_systeme' || msg.auteur === 'systeme') {
    html = `<div class="flex justify-center" data-msg-id="${msg.id || ''}"><div class="bubble-system px-4 py-2 text-indigo-400 text-xs text-center max-w-xs">${msg.message}</div></div>`;
  } else if (msg.auteur === 'admin') {
    html = `<div class="flex items-start gap-2 flex-row-reverse" data-msg-id="${msg.id || ''}">
      <div class="w-7 h-7 bg-amber-500 rounded-xl flex items-center justify-center text-slate-950 text-xs font-bold flex-shrink-0">A</div>
      <div class="max-w-[85%]"><div class="bubble-admin px-4 py-3 text-slate-900 dark:text-white text-xs">${msg.message}</div>
      <div class="text-slate-400 text-[10px] mt-1 text-right">${time}</div></div></div>`;
  } else {
    html = `<div class="flex items-start gap-2" data-msg-id="${msg.id || ''}">
      <div class="w-7 h-7 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-500 text-xs font-bold flex-shrink-0">C</div>
      <div class="max-w-[85%]"><div class="bubble-client px-4 py-3 text-slate-900 dark:text-white text-xs">${msg.message}</div>
      <div class="text-slate-400 text-[10px] mt-1">${time}</div></div></div>`;
  }
  c.insertAdjacentHTML('beforeend', html);
  scrollChatBottom();
}

// ─── Polling messages ─────────────────────────────────────────────────────
let pollingActive = false;
function pollMessages() {
  if (pollingActive) return;
  pollingActive = true;

  fetch(`/admin/transport/${RES_ID}/messages?since_id=${LAST_MSG_ID}`, {
    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
  }).then(r => r.json()).then(data => {
    data.messages.forEach(msg => { appendMsg(msg); });
    if (data.prix_accepte) {
      // Highlight si prix accepté
    }
  }).catch(() => {})
  .finally(() => {
    pollingActive = false;
  });
}

// ─── Polling position chauffeur ────────────────────────────────────────────
let driverPollingActive = false;
function pollDriverPosition() {
  if (driverPollingActive) return;
  driverPollingActive = true;

  fetch(`/transport/driver-location/{{ $reservation->tracking_number }}`)
    .then(r => r.json())
    .then(data => {
      if (data.trackable && data.chauffeur_lat && data.chauffeur_lng) {
        const pos = [data.chauffeur_lat, data.chauffeur_lng];
        if (driverMarker) driverMarker.setLatLng(pos);
        else driverMarker = L.marker(pos, { icon: iconDrv, zIndexOffset: 1000 }).addTo(map).bindPopup('<b>🚗 Chauffeur</b>');
      }
    }).catch(() => {})
    .finally(() => {
      driverPollingActive = false;
    });
}

setInterval(pollMessages, 5000);
setInterval(pollDriverPosition, 5000);

// ─── Envoyer message admin ────────────────────────────────────────────────
document.getElementById('adminChatForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const input = document.getElementById('adminInput');
  const msg = input.value.trim();
  if (!msg) return;
  input.value = '';

  fetch(`/admin/transport/${RES_ID}/message`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({ message: msg })
  }).then(r => r.json()).then(data => {
    if (data.success) appendMsg(data.message);
  });
});

// ─── Proposer un prix ──────────────────────────────────────────────────────
document.getElementById('priceForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const prix = document.getElementById('prixInput').value;
  if (!prix || prix <= 0) return;

  fetch(`/admin/transport/${RES_ID}/proposer-prix`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({ prix: parseFloat(prix) })
  }).then(r => r.json()).then(data => {
    if (data.success) {
      showAlertModal('Prix Proposé', `Le prix de ${new Intl.NumberFormat('fr-FR').format(prix)} FCFA a été proposé au client.`, 'success');
      document.getElementById('prixInput').value = '';
      pollMessages();
    }
  });
});

// ─── Générer lien chauffeur ────────────────────────────────────────────────
function generateDriverLink() {
  fetch(`/admin/transport/${RES_ID}/driver-link`, {
    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
  }).then(r => r.json()).then(data => {
    if (data.success) {
      const container = document.getElementById('driverLinkContainer');
      const input = document.getElementById('driverLinkInput');
      input.value = data.driver_url;
      container.classList.remove('hidden');

      // Copier dans le presse-papier
      navigator.clipboard.writeText(data.driver_url).then(() => {
        document.getElementById('btnGenerateLink').innerHTML = '<i data-lucide="check" class="w-3.5 h-3.5"></i> Lien copié !';
        lucide.createIcons();
        setTimeout(() => {
          document.getElementById('btnGenerateLink').innerHTML = '<i data-lucide="navigation" class="w-3.5 h-3.5"></i> Renouveler le lien';
          lucide.createIcons();
        }, 3000);
      }).catch(() => {});

      showAlertModal('Tracking activé', 'Le statut est passé à "Chauffeur en route". Partagez le lien GPS au chauffeur.', 'success');
      pollMessages();
    }
  });
}

// ─── Notifier arrivée ──────────────────────────────────────────────────────
function notifyArrival() {
  fetch(`/admin/transport/${RES_ID}/chauffeur-arrive`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({})
  }).then(r => r.json()).then(data => {
    if (data.success) {
      showAlertModal('Client Notifié', 'Le client a été notifié que le chauffeur est arrivé !', 'success');
      setTimeout(() => location.reload(), 2000);
    }
  });
}
</script>
@endsection
