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
      margin: 0;
      padding: 0;
      overflow: hidden;
      position: relative;
    }

    /* Full-screen Map */
    #driver-map {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
    }

    /* Floating Header Overlay */
    .header-overlay {
      position: absolute;
      top: 16px;
      left: 16px;
      right: 16px;
      z-index: 1010;
      background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
    }
    .client-info {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
    .client-label {
      font-size: 0.65rem;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-weight: 700;
    }
    .client-name {
      font-size: 1.1rem;
      font-weight: 800;
      color: white;
    }
    .btn-back {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #94a3b8;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .btn-back:hover {
      color: white;
      background: rgba(255, 255, 255, 0.12);
      transform: scale(1.05);
    }
    .btn-back:active {
      transform: scale(0.95);
    }

    /* Actions panel bottom right */
    .actions-panel {
      position: absolute;
      bottom: 32px;
      right: 16px;
      z-index: 1010;
      display: flex;
      flex-direction: column;
      gap: 12px;
      background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 30px;
      padding: 10px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .action-btn {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      border: none;
      outline: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .action-btn:active {
      transform: scale(0.92);
    }

    /* GPS Button styles */
    .btn-gps.off {
      background: rgba(255, 255, 255, 0.08);
      color: #94a3b8;
      border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .btn-gps.off:hover {
      background: rgba(255, 255, 255, 0.15);
      color: #f1f5f9;
    }
    .btn-gps.on {
      background: #10b981;
      color: white;
      box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
    }
    .btn-gps.on .pulse-ring {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      border: 3px solid #10b981;
      border-radius: 50%;
      animation: gpsPulse 2s infinite;
      pointer-events: none;
    }
    @keyframes gpsPulse {
      0% { transform: scale(1); opacity: 1; }
      100% { transform: scale(1.4); opacity: 0; }
    }

    /* Arrive Button styles */
    .btn-arrive {
      background: #f59e0b;
      color: #0f172a;
    }
    .btn-arrive:disabled {
      background: rgba(255, 255, 255, 0.05);
      color: #475569;
      cursor: not-allowed;
    }
    .btn-arrive.arrived {
      background: #10b981;
      color: white;
      cursor: default;
      pointer-events: none;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-arrive:not(:disabled):not(.arrived) .btn-icon {
      animation: bellRing 2s infinite;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    @keyframes bellRing {
      0%, 100% { transform: rotate(0); }
      10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
      20%, 40%, 60%, 80% { transform: rotate(10deg); }
    }

    /* Loader spin animation */
    .animate-spin {
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Custom Leaflet Tooltips */
    .custom-map-tooltip {
      background: rgba(15, 23, 42, 0.9) !important;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.15) !important;
      border-radius: 12px !important;
      padding: 8px 12px !important;
      color: white !important;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4) !important;
      font-family: 'Outfit', sans-serif !important;
      font-size: 0.75rem !important;
      max-width: 200px !important;
      white-space: normal !important;
      text-align: left !important;
    }
    .custom-map-tooltip .tooltip-title {
      font-size: 0.6rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-weight: 700;
      margin-bottom: 2px;
    }
    .depart-tooltip .tooltip-title {
      color: #10b981;
    }
    .arrivee-tooltip .tooltip-title {
      color: #ef4444;
    }
    .custom-map-tooltip .tooltip-body {
      font-weight: 600;
      line-height: 1.3;
    }
    .custom-map-tooltip::before {
      border-top-color: rgba(15, 23, 42, 0.9) !important;
    }

    /* Floating Alert GPS */
    #gpsAlert {
      display: none;
      position: absolute;
      top: 96px;
      left: 16px;
      right: 16px;
      z-index: 1010;
      background: rgba(239, 68, 68, 0.15);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid rgba(239, 68, 68, 0.4);
      border-radius: 12px;
      padding: 12px 16px;
      color: #f87171;
      font-size: 0.8rem;
      font-weight: 600;
      align-items: center;
      gap: 10px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      animation: slideDown 0.3s ease-out;
    }
    @keyframes slideDown {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    /* Toast */
    #toast {
      position: fixed;
      bottom: 100px;
      left: 50%;
      transform: translateX(-50%) translateY(20px);
      background: rgba(15, 23, 42, 0.9);
      backdrop-filter: blur(8px);
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
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    #toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
  </style>
</head>
<body>

  {{-- Carte --}}
  <div id="driver-map"></div>

  {{-- Header Overlay --}}
  <div class="header-overlay">
    <div class="client-info">
      <span class="client-label">Client</span>
      <h1 class="client-name">{{ $reservation->client_nom }}</h1>
    </div>
    <a href="{{ route('driver.dashboard') }}" class="btn-back" title="Retour au Dashboard">
      <i data-lucide="chevron-left" style="width:1.6rem;height:1.6rem;"></i>
    </a>
  </div>

  {{-- Alert GPS --}}
  <div id="gpsAlert">
    <i data-lucide="alert-triangle" style="width:1.2rem;height:1.2rem;"></i>
    <span>Accès GPS refusé. Veuillez activer la localisation.</span>
  </div>

  {{-- Actions Panel Overlay --}}
  <div class="actions-panel">
    {{-- Bouton GPS --}}
    <button id="btnGPS" class="action-btn btn-gps off" onclick="toggleGPS()" title="Partage en direct">
      <span id="gpsIcon" style="display:flex;align-items:center;">
        <i data-lucide="map-pin" style="width:1.4rem;height:1.4rem;"></i>
      </span>
      <span class="pulse-ring"></span>
    </button>

    {{-- Bouton Arrivée --}}
    @if($reservation->chauffeur_arrived)
      <button class="action-btn btn-arrive arrived" disabled title="Arrivée signalée">
        <span class="btn-icon" style="display:flex;align-items:center;">
          <i data-lucide="check" style="width:1.4rem;height:1.4rem;"></i>
        </span>
      </button>
    @else
      <button id="btnArrive" class="action-btn btn-arrive" onclick="signalArrive()" disabled title="Signaler mon arrivée">
        <span class="btn-icon" style="display:flex;align-items:center;">
          <i data-lucide="bell" style="width:1.4rem;height:1.4rem;"></i>
        </span>
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
    const LIEU_DEP = {!! json_encode($reservation->lieu_depart) !!};
    const LIEU_ARR = {!! json_encode($reservation->lieu_arrivee) !!};
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

    function escapeHtml(text) {
      if (!text) return '';
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    if (LAT_DEP && LNG_DEP) {
      const markerClient = L.marker([LAT_DEP, LNG_DEP], { icon: iconClient }).addTo(map);
      markerClient.bindPopup('<b>📍 Le client vous attend ici</b>');
      
      markerClient.bindTooltip(`<div class="tooltip-title">Départ</div><div class="tooltip-body">${escapeHtml(LIEU_DEP)}</div>`, {
        permanent: true,
        direction: 'top',
        offset: [0, -12],
        className: 'custom-map-tooltip depart-tooltip'
      });
    }

    if (LAT_ARR && LNG_ARR) {
      const markerDest = L.marker([LAT_ARR, LNG_ARR], { icon: iconDest }).addTo(map);
      markerDest.bindPopup('<b>🏁 Destination finale</b>');
      
      markerDest.bindTooltip(`<div class="tooltip-title">Arrivée</div><div class="tooltip-body">${escapeHtml(LIEU_ARR)}</div>`, {
        permanent: true,
        direction: 'top',
        offset: [0, -12],
        className: 'custom-map-tooltip arrivee-tooltip'
      });
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
        const gpsAlert = document.getElementById('gpsAlert');
        if (gpsAlert) gpsAlert.style.display = 'flex';
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
          const gpsAlert = document.getElementById('gpsAlert');
          if (gpsAlert) gpsAlert.style.display = 'flex';
          stopGPS();
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 5000 }
      );

      // Envoyer la position au serveur toutes les 5 secondes
      sendInterval = setInterval(sendLocation, 5000);

      gpsActive = true;
      
      const btnGPS = document.getElementById('btnGPS');
      if (btnGPS) {
        btnGPS.classList.remove('off');
        btnGPS.classList.add('on');
      }
      
      const gpsIcon = document.getElementById('gpsIcon');
      if (gpsIcon) {
        gpsIcon.innerHTML = '<i data-lucide="wifi" style="width:1.4rem;height:1.4rem;"></i>';
        lucide.createIcons({ nodes: [gpsIcon] });
      }
      
      const gpsAlert = document.getElementById('gpsAlert');
      if (gpsAlert) gpsAlert.style.display = 'none';
      
      showToast('GPS activé — Position partagée avec le client');
    }
 
    function stopGPS() {
      if (watchId) navigator.geolocation.clearWatch(watchId);
      if (sendInterval) clearInterval(sendInterval);
      gpsActive = false;
      
      const btnGPS = document.getElementById('btnGPS');
      if (btnGPS) {
        btnGPS.classList.remove('on');
        btnGPS.classList.add('off');
      }
      
      const gpsIcon = document.getElementById('gpsIcon');
      if (gpsIcon) {
        gpsIcon.innerHTML = '<i data-lucide="map-pin" style="width:1.4rem;height:1.4rem;"></i>';
        lucide.createIcons({ nodes: [gpsIcon] });
      }
      
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
      
      const iconSpan = btn.querySelector('.btn-icon');
      if (iconSpan) {
        iconSpan.innerHTML = '<i data-lucide="loader" class="animate-spin" style="width:1.4rem;height:1.4rem;"></i>';
        lucide.createIcons({ nodes: [iconSpan] });
      }

      fetch(`/chauffeur/${TOKEN}/arrive`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({})
      }).then(r => r.json()).then(data => {
        if (data.success) {
          btn.classList.add('arrived');
          btn.disabled = true;
          if (iconSpan) {
            iconSpan.innerHTML = '<i data-lucide="check" style="width:1.4rem;height:1.4rem;"></i>';
            lucide.createIcons({ nodes: [iconSpan] });
          }
          showToast('Client notifié de votre arrivée !');
        } else {
          btn.disabled = false;
          if (iconSpan) {
            iconSpan.innerHTML = '<i data-lucide="bell" style="width:1.4rem;height:1.4rem;"></i>';
            lucide.createIcons({ nodes: [iconSpan] });
          }
          showToast('Erreur — Réessayez');
        }
      }).catch(() => {
        btn.disabled = false;
        if (iconSpan) {
          iconSpan.innerHTML = '<i data-lucide="bell" style="width:1.4rem;height:1.4rem;"></i>';
          lucide.createIcons({ nodes: [iconSpan] });
        }
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
