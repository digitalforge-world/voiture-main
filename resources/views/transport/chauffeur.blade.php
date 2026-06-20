<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mode Chauffeur — {{ $reservation->reference }}</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Outfit', sans-serif;
      background: #0f172a;
      color: white;
      height: 100dvh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    /* Header */
    #header {
      padding: 1rem 1.25rem;
      background: #1e293b;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }
    #header h1 { font-size: 1rem; font-weight: 700; }
    #header p  { font-size: 0.7rem; color: #64748b; margin-top: 2px; }
    .status-dot {
      width: 12px; height: 12px;
      border-radius: 50%;
      background: #ef4444;
      flex-shrink: 0;
    }
    .status-dot.active { background: #10b981; animation: pulseDot 1.5s infinite; }
    @keyframes pulseDot {
      0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.4); }
      50%       { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
    }

    /* Carte */
    #driver-map {
      flex: 1;
      z-index: 1;
    }

    /* Panel boutons bas */
    #bottomPanel {
      flex-shrink: 0;
      background: #0f172a;
      border-top: 1px solid rgba(255,255,255,0.08);
      padding: 1rem 1.25rem;
      padding-bottom: calc(1rem + env(safe-area-inset-bottom));
      space-y: 1rem;
    }

    /* Infos trajet */
    #tripInfo {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.5rem;
      margin-bottom: 0.75rem;
    }
    .trip-card {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 0.75rem;
      padding: 0.75rem;
      font-size: 0.7rem;
    }
    .trip-card .label { color: #64748b; text-transform: uppercase; font-size: 0.6rem; letter-spacing: 0.05em; margin-bottom: 3px; }
    .trip-card .value { color: white; font-weight: 600; line-height: 1.3; }

    /* Boutons */
    .btn {
      width: 100%;
      padding: 1rem;
      border: none;
      border-radius: 1rem;
      font-family: 'Outfit', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    .btn:active { transform: scale(0.97); }
    .btn-gps {
      background: #10b981;
      color: white;
      margin-bottom: 0.75rem;
      font-size: 0.9rem;
    }
    .btn-gps.off { background: #1e293b; border: 2px solid rgba(255,255,255,0.1); }
    .btn-arrive {
      background: #f59e0b;
      color: #0f172a;
      font-size: 0.9rem;
    }
    .btn-arrive:disabled {
      background: rgba(255,255,255,0.05);
      color: #475569;
      cursor: not-allowed;
    }

    /* Alert GPS */
    #gpsAlert {
      display: none;
      background: rgba(239,68,68,0.1);
      border: 1px solid rgba(239,68,68,0.3);
      border-radius: 0.75rem;
      padding: 0.75rem 1rem;
      color: #f87171;
      font-size: 0.75rem;
      margin-bottom: 0.75rem;
    }

    /* Toast */
    #toast {
      position: fixed;
      bottom: 6rem;
      left: 50%;
      transform: translateX(-50%) translateY(20px);
      background: #1e293b;
      border: 1px solid rgba(255,255,255,0.1);
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 2rem;
      font-size: 0.8rem;
      font-weight: 600;
      opacity: 0;
      transition: all 0.3s;
      z-index: 9999;
      white-space: nowrap;
    }
    #toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
  </style>
</head>
<body>

  {{-- Header --}}
  <div id="header">
    <div>
      <h1 style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="navigation" style="width:1.2rem;height:1.2rem;color:#f59e0b;"></i> Mode Chauffeur</h1>
      <p>{{ $reservation->reference }} — {{ $reservation->client_nom }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:0.5rem;">
      <span id="gpsStatusText" style="font-size:0.7rem;color:#64748b;">GPS inactif</span>
      <div id="statusDot" class="status-dot"></div>
    </div>
  </div>

  {{-- Carte --}}
  <div id="driver-map"></div>

  {{-- Panel bas --}}
  <div id="bottomPanel">

    <div id="tripInfo">
      <div class="trip-card">
        <div class="label" style="display:flex;align-items:center;gap:3px;"><i data-lucide="map-pin" style="width:0.75rem;height:0.75rem;color:#10b981;"></i> Départ client</div>
        <div class="value" style="font-size:0.65rem;">{{ Str::limit($reservation->lieu_depart, 50) }}</div>
      </div>
      <div class="trip-card">
        <div class="label" style="display:flex;align-items:center;gap:3px;"><i data-lucide="flag" style="width:0.75rem;height:0.75rem;color:#ef4444;"></i> Destination</div>
        <div class="value" style="font-size:0.65rem;">{{ Str::limit($reservation->lieu_arrivee, 50) }}</div>
      </div>
      <div class="trip-card">
        <div class="label" style="display:flex;align-items:center;gap:3px;"><i data-lucide="phone" style="width:0.75rem;height:0.75rem;color:#f59e0b;"></i> Client</div>
        <div class="value">{{ $reservation->client_telephone }}</div>
      </div>
      <div class="trip-card">
        <div class="label" style="display:flex;align-items:center;gap:3px;"><i data-lucide="users" style="width:0.75rem;height:0.75rem;color:#38bdf8;"></i> Passagers</div>
        <div class="value">{{ $reservation->nombre_personnes }} pers.</div>
      </div>
    </div>

    <div id="gpsAlert">⚠️ Accès GPS refusé ou non disponible. Activez la localisation dans les paramètres de votre navigateur.</div>

    {{-- Bouton GPS --}}
    <button id="btnGPS" class="btn btn-gps off" onclick="toggleGPS()">
      <span id="gpsIcon" style="display:flex;align-items:center;"><i data-lucide="map-pin" style="width:1.2rem;height:1.2rem;"></i></span>
      <span id="gpsLabel">Activer le partage GPS</span>
    </button>

    {{-- Bouton Arrivée --}}
    @if($reservation->chauffeur_arrived)
    <button class="btn btn-arrive" style="background:#10b981;color:white;" disabled><i data-lucide="check" style="width:1.2rem;height:1.2rem;"></i> Vous avez signalé votre arrivée</button>
    @else
    <button id="btnArrive" class="btn btn-arrive" onclick="signalArrive()" disabled>
      <i data-lucide="check-circle" style="width:1.2rem;height:1.2rem;"></i> Je suis arrivé au point de départ
    </button>
    @endif

  </div>

  <div id="toast"></div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    const TOKEN    = '{{ $reservation->driver_token }}';
    const LAT_DEP  = {{ $reservation->lat_depart  ?? 6.1372 }};
    const LNG_DEP  = {{ $reservation->lng_depart  ?? 1.2125 }};
    const LAT_ARR  = {{ $reservation->lat_arrivee ?? 6.1644 }};
    const LNG_ARR  = {{ $reservation->lng_arrivee ?? 1.2514 }};
    const CSRF     = document.querySelector('meta[name="csrf-token"]').content;

    let gpsActive   = false;
    let watchId     = null;
    let myMarker    = null;
    let sendInterval = null;
    let lastLat     = null;
    let lastLng     = null;

    // ─── Carte ────────────────────────────────────────────────────────────────
    const map = L.map('driver-map').setView([LAT_DEP, LNG_DEP], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    const iconClient = L.divIcon({ html: `<div style="width:24px;height:24px;background:#10b981;border:3px solid white;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.5)"></div>`, iconSize:[24,24], iconAnchor:[12,12], className:'' });
    const iconDest   = L.divIcon({ html: `<div style="width:24px;height:24px;background:#ef4444;border:3px solid white;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.5)"></div>`, iconSize:[24,24], iconAnchor:[12,12], className:'' });
    
    const carIconSvg = `
      <div style="background: #f59e0b; border: 2px solid white; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;" class="text-slate-950">
          <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2a3 3 0 0 0 6 0h2a3 3 0 0 0 6 0Z"></path>
          <circle cx="7" cy="17" r="2"></circle>
          <circle cx="17" cy="17" r="2"></circle>
        </svg>
      </div>
    `;
    const iconMe     = L.divIcon({ html: carIconSvg, iconSize:[36,36], iconAnchor:[18,18], className:'' });

    if (LAT_DEP && LNG_DEP) L.marker([LAT_DEP, LNG_DEP], { icon: iconClient }).addTo(map).bindPopup('<b>📍 Le client vous attend ici</b>');
    if (LAT_ARR && LNG_ARR) {
      L.marker([LAT_ARR, LNG_ARR], { icon: iconDest }).addTo(map).bindPopup('<b>🏁 Destination finale</b>');
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

    // ─── Toggle GPS ────────────────────────────────────────────────────────────
    function toggleGPS() {
      if (gpsActive) stopGPS(); else startGPS();
    }

    function startGPS() {
      if (!navigator.geolocation) {
        document.getElementById('gpsAlert').style.display = 'block';
        return;
      }

      watchId = navigator.geolocation.watchPosition(
        function(pos) {
          lastLat = pos.coords.latitude;
          lastLng = pos.coords.longitude;

          // Mettre à jour marqueur sur la carte
          const latlng = [lastLat, lastLng];
          if (myMarker) {
            myMarker.setLatLng(latlng);
          } else {
            myMarker = L.marker(latlng, { icon: iconMe, zIndexOffset: 1000 }).addTo(map);
            myMarker.bindPopup('<b>🚗 Ma position</b>');
            map.setView(latlng, 15);
          }

          // Activer le bouton "Je suis arrivé" une fois qu'on a une position
          const btnArrive = document.getElementById('btnArrive');
          if (btnArrive) btnArrive.disabled = false;
        },
        function(err) {
          document.getElementById('gpsAlert').style.display = 'block';
          stopGPS();
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 5000 }
      );

      // Envoyer la position au serveur toutes les 5 secondes
      sendInterval = setInterval(sendLocation, 5000);

      gpsActive = true;
      document.getElementById('statusDot').classList.add('active');
      document.getElementById('gpsStatusText').textContent = 'GPS actif';
      document.getElementById('gpsStatusText').style.color = '#10b981';
      document.getElementById('btnGPS').classList.remove('off');
      document.getElementById('gpsLabel').textContent = 'GPS activé — Partage en direct';
      document.getElementById('gpsIcon').innerHTML = '<i data-lucide="wifi" style="width:1.2rem;height:1.2rem;"></i>';
      lucide.createIcons({ nodes: [document.getElementById('gpsIcon')] });
      document.getElementById('gpsAlert').style.display = 'none';
      showToast('GPS activé — Position partagée avec le client');
    }
 
    function stopGPS() {
      if (watchId) navigator.geolocation.clearWatch(watchId);
      if (sendInterval) clearInterval(sendInterval);
      gpsActive = false;
      document.getElementById('statusDot').classList.remove('active');
      document.getElementById('gpsStatusText').textContent = 'GPS inactif';
      document.getElementById('gpsStatusText').style.color = '#64748b';
      document.getElementById('btnGPS').classList.add('off');
      document.getElementById('gpsLabel').textContent = 'Activer le partage GPS';
      document.getElementById('gpsIcon').innerHTML = '<i data-lucide="map-pin" style="width:1.2rem;height:1.2rem;"></i>';
      lucide.createIcons({ nodes: [document.getElementById('gpsIcon')] });
      showToast('GPS désactivé');
    }

    // ─── Envoyer position au serveur ──────────────────────────────────────────
    function sendLocation() {
      if (!lastLat || !lastLng) return;

      fetch(`/chauffeur/${TOKEN}/location`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ lat: lastLat, lng: lastLng })
      }).catch(() => {});
    }

    // ─── Signaler arrivée ──────────────────────────────────────────────────────
    function signalArrive() {
      const btn = document.getElementById('btnArrive');
      if (!btn || btn.disabled) return;
      btn.disabled = true;
      btn.textContent = '⏳ Envoi en cours...';

      fetch(`/chauffeur/${TOKEN}/arrive`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({})
      }).then(r => r.json()).then(data => {
        if (data.success) {
          btn.innerHTML = '<i data-lucide="check"></i> Arrivée signalée ! Le client est notifié.';
          btn.style.background = '#10b981';
          btn.style.color = '#ffffff';
          lucide.createIcons();
          showToast('Client notifié de votre arrivée !');
        }
      }).catch(() => {
        btn.disabled = false;
        btn.textContent = 'Je suis arrivé au point de départ';
        showToast('Erreur — Réessayez');
      });
    }

    // ─── Toast ──────────────────────────────────────────────────────────────────
    function showToast(msg) {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 3000);
    }

    // Activer GPS automatiquement si la course est déjà en route ou en cours
    @if(in_array($reservation->statut, ['chauffeur_en_route', 'chauffeur_arrive', 'en_cours']))
    window.addEventListener('load', () => setTimeout(startGPS, 1000));
    @endif

    // Initialiser les icônes Lucide
    lucide.createIcons();
  </script>
</body>
</html>
