@extends('layouts.app')

@section('title', 'Transport avec Chauffeur — Réservez votre course')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  /* Carte plein écran dans son container */
  #transport-map {
    height: 100%;
    width: 100%;
    min-height: 500px;
    z-index: 1;
  }

  /* Panneau flottant sur la carte */
  .map-panel {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 340px;
    z-index: 10;
    max-height: calc(100% - 2rem);
    overflow-y: auto;
  }

  @media (max-width: 768px) {
    .map-panel {
      position: relative !important;
      top: auto !important;
      right: auto !important;
      width: 100% !important;
      max-height: none !important;
      overflow-y: visible !important;
      padding: 0 !important;
      margin-top: 1rem;
    }
  }

  /* Instructions carte */
  .map-instruction {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    white-space: nowrap;
  }

  /* Cache la scrollbar sur webkit */
  .map-panel::-webkit-scrollbar { width: 3px; }
  .map-panel::-webkit-scrollbar-thumb { background: rgba(245,158,11,0.3); border-radius: 4px; }

  /* Input style sombre pour le panneau */
  .panel-input {
    width: 100%;
    background: rgba(15,23,42,0.7);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 0.75rem;
    padding: 0.6rem 0.875rem;
    font-size: 0.8125rem;
    color: white;
    outline: none;
    transition: border-color 0.2s;
    font-family: 'Outfit', sans-serif;
  }
  .panel-input::placeholder { color: rgba(148,163,184,0.6); }
  .panel-input:focus { border-color: rgba(245,158,11,0.6); }
  .panel-input option { background: #0f172a; }

  /* Service type buttons */
  .svc-btn input { display: none; }
  .svc-btn label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 8px 6px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.03);
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
  }
  .svc-btn input:checked + label {
    border-color: rgba(245,158,11,0.6);
    background: rgba(245,158,11,0.12);
  }
  .svc-btn label .svc-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: rgba(255,255,255,0.06);
    display: flex; align-items: center; justify-content: center;
    transition: background 0.2s;
  }
  .svc-btn input:checked + label .svc-icon {
    background: rgba(245,158,11,0.2);
    color: #f59e0b;
  }
  .svc-btn label span { font-size: 0.6rem; font-weight: 600; color: #64748b; letter-spacing: 0.05em; }
  .svc-btn input:checked + label span { color: #f59e0b; }

  /* Steps indicator */
  .step-dot {
    width: 28px; height: 28px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700;
    flex-shrink: 0;
    transition: all 0.3s;
  }
  .step-line { flex: 1; height: 1px; }

  /* Leaflet overrides */
  .leaflet-container { font-family: 'Outfit', sans-serif; }
  .leaflet-control-zoom a {
    border-radius: 8px !important;
    font-weight: 700;
  }
</style>
@endsection

@section('content')
{{-- Plein écran minus header --}}
<div class="relative flex flex-col md:block h-auto md:h-[calc(100vh-80px)] overflow-y-auto md:overflow-hidden">

  {{-- ═══ CARTE PLEIN ÉCRAN ═══ --}}
  <div id="transport-map" class="h-[350px] md:h-full w-full min-h-[300px] md:min-h-[500px]"></div>

  {{-- ═══ PANNEAU FLOTTANT GAUCHE : Steps --}}
  <div class="absolute top-4 left-4 z-10 hidden md:block">
    <div class="bg-slate-950/85 backdrop-blur-md border border-white/10 rounded-2xl px-5 py-4 shadow-2xl">
      <div class="flex items-center gap-3">
        {{-- Step 1 --}}
        <div id="step1dot" class="step-dot bg-amber-500 text-slate-950">
          <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
        </div>
        <div class="step-line bg-white/10" id="line1"></div>
        {{-- Step 2 --}}
        <div id="step2dot" class="step-dot bg-white/5 border border-white/10 text-slate-600">
          <i data-lucide="flag" class="w-3.5 h-3.5"></i>
        </div>
        <div class="step-line bg-white/10" id="line2"></div>
        {{-- Step 3 --}}
        <div id="step3dot" class="step-dot bg-white/5 border border-white/10 text-slate-600">
          <i data-lucide="user" class="w-3.5 h-3.5"></i>
        </div>
      </div>
      <div class="flex justify-between mt-2">
        <span id="step1lbl" class="text-[9px] font-bold text-amber-400 uppercase tracking-widest">Départ</span>
        <span id="step2lbl" class="text-[9px] font-semibold text-slate-600 uppercase tracking-widest mx-4">Arrivée</span>
        <span id="step3lbl" class="text-[9px] font-semibold text-slate-600 uppercase tracking-widest">Infos</span>
      </div>
    </div>

    {{-- Instruction dynamique --}}
    <div id="mapInstruction" class="mt-3 bg-amber-500/90 backdrop-blur-md text-slate-950 rounded-xl px-4 py-2.5 flex items-center gap-2.5 shadow-lg shadow-amber-500/30">
      <i data-lucide="mouse-pointer-click" class="w-4 h-4 flex-shrink-0"></i>
      <span id="instrText" class="text-xs font-bold">Cliquez sur la carte pour placer le point de <strong>départ</strong></span>
    </div>
  </div>

  {{-- ═══ PANNEAU FLOTTANT DROIT : Formulaire --}}
  <div class="map-panel">

    {{-- Erreurs --}}
    @if($errors->any())
    <div class="bg-rose-500/90 backdrop-blur-md border border-rose-400/30 rounded-2xl p-4 mb-3 shadow-xl">
      <div class="flex items-center gap-2 mb-2">
        <i data-lucide="alert-circle" class="w-4 h-4 text-white flex-shrink-0"></i>
        <span class="text-xs font-bold text-white">Veuillez corriger :</span>
      </div>
      <ul class="space-y-1">
        @foreach($errors->all() as $e)
        <li class="text-white/80 text-xs flex items-start gap-1.5">
          <i data-lucide="chevron-right" class="w-3 h-3 flex-shrink-0 mt-0.5"></i>{{ $e }}
        </li>
        @endforeach
      </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-emerald-500/90 backdrop-blur-md border border-emerald-400/30 rounded-2xl p-4 mb-3 flex items-center gap-3 shadow-xl">
      <i data-lucide="check-circle" class="w-5 h-5 text-white flex-shrink-0"></i>
      <span class="text-xs font-bold text-white">{{ session('success') }}</span>
    </div>
    @endif

    <form action="{{ route('transport.store') }}" method="POST" id="transportForm">
      @csrf

      {{-- Champs cachés --}}
      <input type="hidden" name="lat_depart"   id="lat_depart"   value="{{ old('lat_depart') }}">
      <input type="hidden" name="lng_depart"   id="lng_depart"   value="{{ old('lng_depart') }}">
      <input type="hidden" name="lat_arrivee"  id="lat_arrivee"  value="{{ old('lat_arrivee') }}">
      <input type="hidden" name="lng_arrivee"  id="lng_arrivee"  value="{{ old('lng_arrivee') }}">
      <input type="hidden" name="lieu_depart"  id="lieu_depart"  value="{{ old('lieu_depart') }}">
      <input type="hidden" name="lieu_arrivee" id="lieu_arrivee" value="{{ old('lieu_arrivee') }}">

      {{-- ─── Bloc 1 : Trajet --}}
      <div class="bg-slate-950/85 backdrop-blur-md border border-white/10 rounded-2xl p-4 mb-3 shadow-2xl">
        <div class="flex items-center gap-2 mb-3">
          <div class="w-6 h-6 bg-amber-500/20 rounded-lg flex items-center justify-center">
            <i data-lucide="route" class="w-3.5 h-3.5 text-amber-400"></i>
          </div>
          <span class="text-xs font-bold text-white uppercase tracking-widest">Votre trajet</span>
        </div>

        {{-- Départ --}}
        <div class="mb-2">
          <div class="flex items-center justify-between gap-2 mb-1.5">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 rounded-full bg-emerald-400 border-2 border-emerald-300 flex-shrink-0"></div>
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Point de départ</span>
            </div>
            <button type="button" id="btn_gps_depart" title="Utiliser ma position GPS"
              class="flex items-center gap-1 text-[9px] font-bold text-amber-500 hover:text-amber-400 transition uppercase tracking-wider">
              <i data-lucide="locate" class="w-3 h-3"></i> Ma position
            </button>
          </div>
          <div class="relative">
            <input type="text" id="search_depart" placeholder="Rechercher ou cliquez sur la carte..."
              class="panel-input pr-8" autocomplete="off" value="{{ old('lieu_depart') }}">
            <button type="button" id="clear_depart" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white hidden">
              <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div id="results_depart" class="absolute left-0 right-0 mt-1 bg-slate-900/95 border border-white/10 rounded-xl max-h-48 overflow-y-auto hidden z-[99] custom-scrollbar shadow-2xl"></div>
          </div>
        </div>

        {{-- Ligne connecteur --}}
        <div class="flex justify-center py-0.5">
          <div class="w-px h-3 bg-white/10"></div>
        </div>

        {{-- Arrivée --}}
        <div>
          <div class="flex items-center gap-2 mb-1.5">
            <div class="w-3 h-3 rounded-full bg-rose-400 border-2 border-rose-300 flex-shrink-0"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Point d'arrivée</span>
          </div>
          <div class="relative">
            <input type="text" id="search_arrivee" placeholder="Rechercher ou cliquez sur la carte..."
              class="panel-input pr-8" autocomplete="off" value="{{ old('lieu_arrivee') }}">
            <button type="button" id="clear_arrivee" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white hidden">
              <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div id="results_arrivee" class="absolute left-0 right-0 mt-1 bg-slate-900/95 border border-white/10 rounded-xl max-h-48 overflow-y-auto hidden z-[99] custom-scrollbar shadow-2xl"></div>
          </div>
        </div>
      </div>

      {{-- ─── Bloc 2 : Infos client --}}
      <div class="bg-slate-950/85 backdrop-blur-md border border-white/10 rounded-2xl p-4 mb-3 shadow-2xl">
        <div class="flex items-center gap-2 mb-3">
          <div class="w-6 h-6 bg-blue-500/20 rounded-lg flex items-center justify-center">
            <i data-lucide="user" class="w-3.5 h-3.5 text-blue-400"></i>
          </div>
          <span class="text-xs font-bold text-white uppercase tracking-widest">Vos coordonnées</span>
        </div>

        <div class="space-y-2.5">
          <div class="relative">
            <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500"></i>
            <input type="text" name="client_nom" value="{{ old('client_nom') }}" required
              placeholder="Nom complet" class="panel-input pl-9">
          </div>

          <div class="relative">
            <i data-lucide="phone" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-amber-500"></i>
            <input type="tel" name="client_telephone" value="{{ old('client_telephone') }}" required
              placeholder="+225 07 00 00 00 00" class="panel-input pl-9"
              style="border-color:rgba(245,158,11,0.4)">
            <div class="mt-1 flex items-center gap-1.5 px-1">
              <i data-lucide="info" class="w-3 h-3 text-slate-600"></i>
              <span class="text-[10px] text-slate-600">Le chauffeur vous appellera à ce numéro</span>
            </div>
          </div>

          <div class="relative">
            <i data-lucide="mail" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500"></i>
            <input type="email" name="client_email" value="{{ old('client_email') }}"
              placeholder="Email (optionnel)" class="panel-input pl-9">
          </div>
        </div>
      </div>

      {{-- ─── Bloc 3 : Détails --}}
      <div class="bg-slate-950/85 backdrop-blur-md border border-white/10 rounded-2xl p-4 mb-3 shadow-2xl">
        <div class="flex items-center gap-2 mb-3">
          <div class="w-6 h-6 bg-purple-500/20 rounded-lg flex items-center justify-center">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-purple-400"></i>
          </div>
          <span class="text-xs font-bold text-white uppercase tracking-widest">Détails de la course</span>
        </div>

        {{-- Date --}}
        <div class="mb-2.5">
          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
            <i data-lucide="clock" class="w-3 h-3"></i> Date & heure <span class="text-amber-400">*</span>
          </label>
          <input type="datetime-local" name="date_prise_en_charge" value="{{ old('date_prise_en_charge') }}" required
            min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
            class="panel-input">
        </div>

        {{-- Passagers --}}
        <div class="mb-3">
          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
            <i data-lucide="users" class="w-3 h-3"></i> Passagers
          </label>
          <div class="flex items-center gap-2">
            <button type="button" onclick="changePassagers(-1)"
              class="w-8 h-8 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center text-white hover:bg-white/10 transition">
              <i data-lucide="minus" class="w-3.5 h-3.5"></i>
            </button>
            <input type="number" name="nombre_personnes" id="passagers" value="{{ old('nombre_personnes', 1) }}"
              min="1" max="20" readonly
              class="flex-grow text-center panel-input font-bold text-base py-1.5">
            <button type="button" onclick="changePassagers(1)"
              class="w-8 h-8 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center text-white hover:bg-white/10 transition">
              <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            </button>
          </div>
        </div>

        {{-- Type de service --}}
        <div class="mb-2.5">
          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 flex items-center gap-1.5">
            <i data-lucide="tag" class="w-3 h-3"></i> Type de service
          </label>
          <div class="grid grid-cols-5 gap-1.5">
            @foreach([
              ['aeroport', 'plane-takeoff', 'Aéroport'],
              ['gare',     'train-front',   'Gare'],
              ['evenement','party-popper',  'Événement'],
              ['course',   'car',           'Course'],
              ['autre',    'navigation',    'Autre'],
            ] as [$val, $ico, $label])
            <div class="svc-btn">
              <input type="radio" name="type_service" value="{{ $val }}" id="svc_{{ $val }}"
                {{ old('type_service', 'course') === $val ? 'checked' : '' }}>
              <label for="svc_{{ $val }}">
                <div class="svc-icon">
                  <i data-lucide="{{ $ico }}" class="w-4 h-4"></i>
                </div>
                <span>{{ $label }}</span>
              </label>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Notes --}}
        <div>
          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
            <i data-lucide="message-square" class="w-3 h-3"></i> Notes pour le chauffeur
          </label>
          <textarea name="notes_client" rows="2" placeholder="Ex: Vol AF512, terminal 2A..." class="panel-input resize-none">{{ old('notes_client') }}</textarea>
        </div>
      </div>

      {{-- ─── Bouton Submit --}}
      <button type="submit" id="submitBtn"
        class="w-full flex items-center justify-center gap-2.5 py-3.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm uppercase tracking-widest transition-all duration-300 hover:-translate-y-0.5 shadow-xl shadow-amber-500/30 disabled:opacity-50 disabled:cursor-not-allowed mb-3">
        <i data-lucide="car" class="w-4.5 h-4.5"></i>
        <span id="submitText">Réserver mon chauffeur</span>
        <i data-lucide="arrow-right" class="w-4 h-4"></i>
      </button>

      <div class="bg-slate-950/70 backdrop-blur-md border border-white/5 rounded-xl px-4 py-3 flex items-start gap-3">
        <i data-lucide="shield-check" class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5"></i>
        <p class="text-[10px] text-slate-500 leading-relaxed">
          Réservation <strong class="text-slate-400">sans paiement immédiat</strong>. Le tarif est négocié directement avec notre équipe après réservation.
        </p>
      </div>
    </form>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  lucide.createIcons();

  // ─── Carte ──────────────────────────────────────────────────────────────────
  const map = L.map('transport-map', { zoomControl: true }).setView([6.1372, 1.2125], 12);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap',
    maxZoom: 19,
  }).addTo(map);

  // Icônes propres Leaflet (sans emoji)
  function makeMarker(color) {
    return L.divIcon({
      html: `<div style="
        width: 22px; height: 22px;
        background: ${color};
        border: 3px solid white;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        box-shadow: 0 2px 10px rgba(0,0,0,0.45);
      "></div>`,
      iconSize: [22, 22], iconAnchor: [11, 22], className: ''
    });
  }

  const iconDepart  = makeMarker('#10b981'); // vert émeraude
  const iconArrivee = makeMarker('#ef4444'); // rouge
  let markerDepart  = null;
  let markerArrivee = null;
  let routeLine     = null;
  let step = 1;

  // ─── Routage Réel OSRM ──────────────────────────────────────────────────────
  function drawRoute(latlngDep, latlngArr) {
    if (!latlngDep || !latlngArr) return;

    const url = `https://router.project-osrm.org/route/v1/driving/${latlngDep.lng},${latlngDep.lat};${latlngArr.lng},${latlngArr.lat}?overview=full&geometries=geojson`;

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

  // ─── Clic carte ─────────────────────────────────────────────────────────────
  map.on('click', function(e) {
    if (step === 1) {
      if (markerDepart) map.removeLayer(markerDepart);
      markerDepart = L.marker(e.latlng, { icon: iconDepart, draggable: true }).addTo(map);
      markerDepart.on('dragend', () => {
        geocodeAndSet(markerDepart.getLatLng(), 'depart');
        if (markerArrivee) drawRoute(markerDepart.getLatLng(), markerArrivee.getLatLng());
      });

      document.getElementById('lat_depart').value = e.latlng.lat;
      document.getElementById('lng_depart').value = e.latlng.lng;

      geocodeAndSet(e.latlng, 'depart');

      // Étape 2
      step = 2;
      setStepUI(2);

    } else if (step === 2) {
      if (markerArrivee) map.removeLayer(markerArrivee);
      markerArrivee = L.marker(e.latlng, { icon: iconArrivee, draggable: true }).addTo(map);
      markerArrivee.on('dragend', () => {
        geocodeAndSet(markerArrivee.getLatLng(), 'arrivee');
        if (markerDepart) drawRoute(markerDepart.getLatLng(), markerArrivee.getLatLng());
      });

      document.getElementById('lat_arrivee').value = e.latlng.lat;
      document.getElementById('lng_arrivee').value = e.latlng.lng;

      geocodeAndSet(e.latlng, 'arrivee');

      // Tracer itinéraire routier
      drawRoute(markerDepart.getLatLng(), e.latlng);

      step = 3;
      setStepUI(3);
    }
  });

  // ─── Géocodage Nominatim ────────────────────────────────────────────────────
  function geocodeAndSet(latlng, type) {
    const lat = latlng.lat, lng = latlng.lng;
    const coords = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;

    if (type === 'depart') {
      document.getElementById('search_depart').value = coords;
      document.getElementById('clear_depart').classList.remove('hidden');
      document.getElementById('lat_depart').value = lat;
      document.getElementById('lng_depart').value = lng;
    } else {
      document.getElementById('search_arrivee').value = coords;
      document.getElementById('clear_arrivee').classList.remove('hidden');
      document.getElementById('lat_arrivee').value = lat;
      document.getElementById('lng_arrivee').value = lng;
    }

    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=fr`)
      .then(r => r.json())
      .then(data => {
        const addr = data.display_name ?? coords;
        const short = addr.substring(0, 80);
        if (type === 'depart') {
          document.getElementById('search_depart').value = short;
          document.getElementById('lieu_depart').value = short;
        } else {
          document.getElementById('search_arrivee').value = short;
          document.getElementById('lieu_arrivee').value = short;
        }
      }).catch(() => {
        if (type === 'depart') document.getElementById('lieu_depart').value = coords;
        else                   document.getElementById('lieu_arrivee').value = coords;
      });
  }

  // ─── Interface des étapes ────────────────────────────────────────────────────
  function setStepUI(currentStep) {
    const dots  = ['step1dot','step2dot','step3dot'];
    const lbls  = ['step1lbl','step2lbl','step3lbl'];
    const icons = [
      'map-pin','flag','user'
    ];
    const instrMessages = {
      1: 'Cliquez sur la carte pour placer le point de <strong>départ</strong>',
      2: 'Maintenant cliquez pour placer le point d\'<strong>arrivée</strong>',
      3: '<strong>Trajet défini !</strong> Complétez le formulaire et réservez',
    };

    dots.forEach((id, i) => {
      const el = document.getElementById(id);
      if (i + 1 < currentStep) {
        el.className = 'step-dot bg-emerald-500 text-white';
        el.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
      } else if (i + 1 === currentStep) {
        el.className = 'step-dot bg-amber-500 text-slate-950';
        el.innerHTML = `<i data-lucide="${icons[i]}" class="w-3.5 h-3.5"></i>`;
        lucide.createIcons({ nodes: [el] });
      } else {
        el.className = 'step-dot bg-white/5 border border-white/10 text-slate-600';
        el.innerHTML = `<i data-lucide="${icons[i]}" class="w-3.5 h-3.5"></i>`;
        lucide.createIcons({ nodes: [el] });
      }
      document.getElementById(lbls[i]).className = i + 1 <= currentStep
        ? 'text-[9px] font-bold text-amber-400 uppercase tracking-widest' + (i>0 ? ' mx-4' : '')
        : 'text-[9px] font-semibold text-slate-600 uppercase tracking-widest' + (i>0 ? ' mx-4' : '');
    });

    const instrEl = document.getElementById('mapInstruction');
    document.getElementById('instrText').innerHTML = instrMessages[currentStep] || '';

    if (currentStep === 3) {
      instrEl.classList.remove('bg-amber-500/90', 'text-slate-950', 'shadow-amber-500/30');
      instrEl.classList.add('bg-emerald-500/90', 'text-white', 'shadow-emerald-500/20');
    } else if (currentStep === 2) {
      instrEl.classList.remove('bg-emerald-500/90', 'text-white', 'shadow-emerald-500/20');
      instrEl.classList.add('bg-amber-500/90', 'text-slate-950', 'shadow-amber-500/30');
    }
  }

  // ─── Compteur passagers ──────────────────────────────────────────────────────
  function changePassagers(delta) {
    const input = document.getElementById('passagers');
    const val = parseInt(input.value) + delta;
    if (val >= 1 && val <= 20) input.value = val;
  }

  // ─── Autocomplete Nominatim ──────────────────────────────────────────────────
  function initSearch(inputId, resultsId, type) {
    const input = document.getElementById(inputId);
    const results = document.getElementById(resultsId);
    const clearBtn = document.getElementById('clear_' + type);
    let timeout = null;

    input.addEventListener('input', function() {
      const query = input.value.trim();
      if (query.length > 0) {
        clearBtn.classList.remove('hidden');
      } else {
        clearBtn.classList.add('hidden');
      }

      clearTimeout(timeout);
      if (query.length < 3) {
        results.innerHTML = '';
        results.classList.add('hidden');
        return;
      }

      timeout = setTimeout(() => {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&accept-language=fr&countrycodes=ci,tg,bj,gh,sn,bf`)
          .then(r => r.json())
          .then(data => {
            results.innerHTML = '';
            if (data.length === 0) {
              results.innerHTML = `<div class="p-3 text-xs text-slate-500 italic">Aucun résultat trouvé</div>`;
              results.classList.remove('hidden');
              return;
            }

            data.forEach(item => {
              const div = document.createElement('div');
              div.className = 'p-3 text-xs text-slate-300 hover:bg-white/5 cursor-pointer border-b border-white/5 last:border-b-0 leading-normal transition';
              
              // Affichage raccourci
              const short = item.display_name.substring(0, 80);
              div.innerHTML = `<strong>${item.name}</strong><br><span class="text-[10px] text-slate-500">${short}</span>`;
              
              div.addEventListener('click', () => {
                input.value = short;
                results.innerHTML = '';
                results.classList.add('hidden');

                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lon);
                const latlng = L.latLng(lat, lng);

                if (type === 'depart') {
                  if (markerDepart) map.removeLayer(markerDepart);
                  markerDepart = L.marker(latlng, { icon: iconDepart, draggable: true }).addTo(map);
                  markerDepart.on('dragend', () => {
                    geocodeAndSet(markerDepart.getLatLng(), 'depart');
                    if (markerArrivee) drawRoute(markerDepart.getLatLng(), markerArrivee.getLatLng());
                  });
                  
                  document.getElementById('lat_depart').value = lat;
                  document.getElementById('lng_depart').value = lng;
                  document.getElementById('lieu_depart').value = short;

                  if (step === 1) {
                    step = 2;
                    setStepUI(2);
                  }
                  map.setView(latlng, 14);
                } else {
                  if (markerArrivee) map.removeLayer(markerArrivee);
                  markerArrivee = L.marker(latlng, { icon: iconArrivee, draggable: true }).addTo(map);
                  markerArrivee.on('dragend', () => {
                    geocodeAndSet(markerArrivee.getLatLng(), 'arrivee');
                    if (markerDepart) drawRoute(markerDepart.getLatLng(), markerArrivee.getLatLng());
                  });
                  
                  document.getElementById('lat_arrivee').value = lat;
                  document.getElementById('lng_arrivee').value = lng;
                  document.getElementById('lieu_arrivee').value = short;

                  if (step === 2) {
                    step = 3;
                    setStepUI(3);
                  }
                  
                  if (markerDepart) {
                    drawRoute(markerDepart.getLatLng(), latlng);
                  } else {
                    map.setView(latlng, 14);
                  }
                }
              });
              results.appendChild(div);
            });
            results.classList.remove('hidden');
          })
          .catch(() => {});
      }, 400);
    });

    clearBtn.addEventListener('click', () => {
      input.value = '';
      results.innerHTML = '';
      results.classList.add('hidden');
      clearBtn.classList.add('hidden');
      
      if (type === 'depart') {
        if (markerDepart) map.removeLayer(markerDepart);
        markerDepart = null;
        document.getElementById('lat_depart').value = '';
        document.getElementById('lng_depart').value = '';
        document.getElementById('lieu_depart').value = '';
        if (routeLine) { map.removeLayer(routeLine); routeLine = null; }
        step = 1;
        setStepUI(1);
      } else {
        if (markerArrivee) map.removeLayer(markerArrivee);
        markerArrivee = null;
        document.getElementById('lat_arrivee').value = '';
        document.getElementById('lng_arrivee').value = '';
        document.getElementById('lieu_arrivee').value = '';
        if (routeLine) { map.removeLayer(routeLine); routeLine = null; }
        if (markerDepart) {
          step = 2;
          setStepUI(2);
        } else {
          step = 1;
          setStepUI(1);
        }
      }
    });

    document.addEventListener('click', function(e) {
      if (!input.contains(e.target) && !results.contains(e.target)) {
        results.classList.add('hidden');
      }
    });
  }

  initSearch('search_depart', 'results_depart', 'depart');
  initSearch('search_arrivee', 'results_arrivee', 'arrivee');

  // ─── Bouton GPS ──────────────────────────────────────────────────────────────
  document.getElementById('btn_gps_depart').addEventListener('click', function() {
    const btn = this;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-pulse text-amber-500">⏳ Localisation...</span>';

    if (!navigator.geolocation) {
      alert("La géolocalisation n'est pas supportée par votre navigateur.");
      btn.disabled = false;
      btn.innerHTML = originalHtml;
      return;
    }

    navigator.geolocation.getCurrentPosition(
      function(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        const latlng = L.latLng(lat, lng);

        if (markerDepart) map.removeLayer(markerDepart);
        markerDepart = L.marker(latlng, { icon: iconDepart, draggable: true }).addTo(map);
        markerDepart.on('dragend', () => {
          geocodeAndSet(markerDepart.getLatLng(), 'depart');
          if (markerArrivee) drawRoute(markerDepart.getLatLng(), markerArrivee.getLatLng());
        });

        document.getElementById('lat_depart').value = lat;
        document.getElementById('lng_depart').value = lng;
        document.getElementById('search_depart').value = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        document.getElementById('clear_depart').classList.remove('hidden');

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=fr`)
          .then(r => r.json())
          .then(data => {
            const addr = data.display_name ?? `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
            const short = addr.substring(0, 80);
            document.getElementById('search_depart').value = short;
            document.getElementById('lieu_depart').value = short;
          })
          .catch(() => {
            document.getElementById('lieu_depart').value = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
          })
          .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
          });

        if (step === 1) {
          step = 2;
          setStepUI(2);
        }
        
        if (markerArrivee) {
          drawRoute(latlng, markerArrivee.getLatLng());
        } else {
          map.setView(latlng, 15);
        }
      },
      function(error) {
        alert("Impossible d'obtenir votre position GPS. Veuillez autoriser l'accès.");
        btn.disabled = false;
        btn.innerHTML = originalHtml;
      },
      { enableHighAccuracy: true, timeout: 10000 }
    );
  });

  // ─── Validation ──────────────────────────────────────────────────────────────
  document.getElementById('transportForm').addEventListener('submit', function(e) {
    if (!document.getElementById('lat_depart').value || !document.getElementById('lat_arrivee').value) {
      e.preventDefault();
      alert('Veuillez placer les deux points sur la carte (départ et arrivée).');
      return;
    }
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitText').textContent = 'Envoi en cours...';
  });
</script>
@endsection
