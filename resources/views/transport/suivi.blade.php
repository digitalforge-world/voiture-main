@extends('layouts.app')

@section('title', 'Suivi de votre transport — ' . $reservation->tracking_number)

@section('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <style>
    #suivi-map {
      height: 280px;
      border-radius: 1rem;
      z-index: 1;
    }

    @keyframes pulseGreen {

      0%,
      100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
      }

      50% {
        box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
      }
    }

    @keyframes bounceDriver {

      0%,
      100% {
        transform: translateY(0) rotate(-45deg);
      }

      50% {
        transform: translateY(-4px) rotate(-45deg);
      }
    }

    /* Overlay notification arrivée */
    #arrivedOverlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 9999;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(12px);
      flex-direction: column;
      align-items: center;
      justify-content: center;
      animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes megaPulse {

      0%,
      100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.6);
      }

      50% {
        transform: scale(1.08);
        box-shadow: 0 0 0 30px rgba(245, 158, 11, 0);
      }
    }

    .car-icon-big {
      animation: megaPulse 1.5s infinite;
    }

    /* Chat bubbles */
    .bubble-admin {
      border-radius: 1rem 1rem 1rem 0;
    }

    .bubble-client {
      border-radius: 1rem 1rem 0 1rem;
    }

    .bubble-system {
      border-radius: 1rem;
    }

    /* Scroll chat */
    #chatMessages {
      scroll-behavior: smooth;
    }

    /* Prix accepté */
    @keyframes priceGlow {

      0%,
      100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.3);
      }

      50% {
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
      }
    }

    .price-card {
      animation: priceGlow 2s infinite;

      }

    /* ─── Responsive Mobile Overlays Redesign (max-width: 1024px) ─── */
    @media (max-width: 1024px) {
      /* Make the outer container fit the screen below the site header */
      .mobile-page-container {
        min-height: auto !important;
        height: calc(100dvh - 64px - 80px) !important;
       /* margin-top: 64px !important; /* Fixed site header height (64px) */
        padding: 12px !important;
        display: flex !important;
        flex-direction: column !important;
        background-color: rgb(248, 250, 252) !important;
      }
      .dark .mobile-page-container {
        background-color: rgb(9, 15, 29) !important;
      }

      /* Inner layout wrapper */
      .mobile-page-inner {
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 12px !important;
        margin: 0 !important;
        max-width: 100% !important;
      }
      .mobile-page-inner > :not([hidden]) ~ :not([hidden]) {
        margin-top: 0 !important;
      }

      /* Header (1) */
      .header-info-wrapper {
        order: 1 !important;
        position: static !important;
        background: rgb(255, 255, 255) !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: 20px !important;
        padding: 14px 16px !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05) !important;
        width: 100% !important;
      }
      .dark .header-info-wrapper {
        background: rgb(30, 41, 59) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.2) !important;
      }

      /* Stepper (3) */
      .stepper-container-wrapper {
        order: 3 !important;
        position: static !important;
        background: rgb(255, 255, 255) !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        border-radius: 20px !important;
        padding: 12px 16px !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05) !important;
        overflow-x: visible !important;
        width: 100% !important;
      }
      .dark .stepper-container-wrapper {
        background: rgb(30, 41, 59) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.2) !important;
      }

      .stepper-inner-container {
        min-width: 0 !important;
        width: 100% !important;
        padding-left: 4px !important;
        padding-right: 4px !important;
      }

      .stepper-progress-line {
        top: 13px !important;
        left: 12px !important;
        right: 12px !important;
        height: 2px !important;
      }

      .stepper-circle {
        width: 26px !important;
        height: 26px !important;
        border-width: 2px !important;
      }

      .stepper-circle i {
        width: 12px !important;
        height: 12px !important;
      }

      .stepper-label {
        display: none !important; /* Hide labels under circles on mobile for clean look */
      }

      /* Grid and Column expansion on mobile (2) */
      .mobile-page-inner > .grid {
        order: 2 !important;
        flex-grow: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
        gap: 0 !important;
        margin: 0 !important;
      }
      .mobile-page-inner > .grid > div:first-child {
        flex-grow: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
        margin-top: 0 !important;
      }
      .mobile-page-inner > .grid > div:first-child > :not([hidden]) ~ :not([hidden]) {
        margin-top: 0 !important;
      }

      /* Map container takes all the remaining space in the middle */
      .map-container-wrapper {
        position: relative !important;
        flex-grow: 1 !important;
        height: 100% !important;
        width: 100% !important;
        border-radius: 20px !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        background: rgb(255, 255, 255) !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05) !important;
        margin: 0 !important;
        z-index: 10 !important;
      }
      .dark .map-container-wrapper {
        background: rgb(15, 23, 42) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
      }
      #suivi-map {
        height: 100% !important;
        width: 100% !important;
        border-radius: 20px !important;
      }

      /* Floating action buttons panel absolute inside the map container */
      .mobile-fabs-panel {
        position: absolute !important;
        bottom: 16px !important;
        right: 16px !important;
        z-index: 1000 !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 10px !important;
      }
      .mobile-fabs-panel button {
        width: 48px !important;
        height: 48px !important;
        border-radius: 9999px !important;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease, background-color 0.2s ease !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15) !important;
      }
      .mobile-fabs-panel button:active {
        transform: scale(0.9) !important;
      }

      /* Drawers/Modals (3, 4, 5) */
      .details-container-wrapper,
      .driver-container-wrapper,
      .chat-container-wrapper {
        position: fixed !important;
        left: 12px !important;
        right: 12px !important;
        bottom: -100% !important; /* Hidden off-screen */
        z-index: 2010 !important;
        max-height: 65vh !important;
        overflow-y: auto;
        border-radius: 24px !important;
        box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.15) !important;
        transition: bottom 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        background: rgb(255, 255, 255) !important;
      }
      .dark .details-container-wrapper,
      .dark .driver-container-wrapper,
      .dark .chat-container-wrapper {
        background: rgb(15, 23, 42) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 -10px 35px rgba(0, 0, 0, 0.4) !important;
      }

      /* Active Modals State */
      .details-container-wrapper.active-modal,
      .driver-container-wrapper.active-modal,
      .chat-container-wrapper.active-modal {
        bottom: 80px !important; /* Align just above the nav bar */
      }

      /* Chat Sizing */
      .chat-container-wrapper {
        height: 60vh !important;
      }
      .chat-container-wrapper > div {
        height: 100% !important;
        border: none !important;
      }
    }
  </style>
@endsection

@section('content')
  <div class="min-h-[calc(100vh-80px)] mobile-page-container bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 py-8 px-4 transition-colors duration-300">
    <div class="max-w-5xl mx-auto space-y-6 mobile-page-inner">


        {{-- ─── Header (1) ─── --}}
        <div class="header-info-wrapper flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <a href="{{ route('transport.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-amber-500 text-xs mb-3 transition lg:flex hidden">
              ← Nouvelle réservation
            </a>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Suivi de votre transport</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Réf : <span class="font-mono text-amber-500 dark:text-amber-400 font-bold">{{ $reservation->tracking_number }}</span></p>
          </div>
          <div class="flex items-center gap-3">
            @php
              $statusColors = [
                'en_attente' => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-500/20',
                'accepte' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20',
                'chauffeur_en_route' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
                'chauffeur_arrive' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                'en_cours' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-200 dark:border-purple-500/20',
                'termine' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                'annule' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-500/20',
              ];
              $sc = $statusColors[$reservation->statut] ?? 'bg-slate-500/10 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-800';
            @endphp
            <span id="statutBadge" class="px-4 py-2 rounded-full text-xs font-bold border uppercase tracking-widest {{ $sc }}">
              {{ $reservation->statut_label }}
            </span>
          </div>
        </div>

        {{-- ─── Stepper (2) ─── --}}
        @php
          $steps = [
            ['key' => 'en_attente', 'label' => 'En attente', 'icon' => 'clock'],
            ['key' => 'accepte', 'label' => 'Accepté', 'icon' => 'check-circle'],
            ['key' => 'chauffeur_en_route', 'label' => 'En route', 'icon' => 'navigation'],
            ['key' => 'chauffeur_arrive', 'label' => 'Arrivé', 'icon' => 'map-pin'],
            ['key' => 'en_cours', 'label' => 'En cours', 'icon' => 'play-circle'],
            ['key' => 'termine', 'label' => 'Terminé', 'icon' => 'check-square'],
          ];
          $statutOrder = ['en_attente' => 0, 'accepte' => 1, 'chauffeur_en_route' => 2, 'chauffeur_arrive' => 3, 'en_cours' => 4, 'termine' => 5, 'annule' => -1];
          $currentIdx = $statutOrder[$reservation->statut] ?? 0;
        @endphp

        <div class="stepper-container-wrapper bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm overflow-x-auto custom-scrollbar">
          <div class="stepper-inner-container relative flex justify-between items-center w-full min-w-[600px] px-8 py-2">
            <!-- Progress Bar Background -->
            <div class="stepper-progress-line absolute top-[21px] left-12 right-12 h-0.5 bg-slate-200 dark:bg-slate-800 z-0">
              <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $currentIdx * 20 }}%"></div>
            </div>

            @foreach($steps as $i => $s)
              @php 
                            $done = $currentIdx > $i;
                $active = $currentIdx === $i; 
              @endphp
              <div class="relative z-10 flex flex-col items-center">
                <div class="stepper-circle w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 border-2
                  {{ $done ? 'bg-emerald-500 border-emerald-500 text-white' : ($active ? 'bg-amber-500 border-amber-500 text-slate-950' : 'bg-white dark:bg-slate-950 border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-600') }}">
                  <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4"></i>
                </div>
                <span class="stepper-label text-[9px] mt-2 font-bold uppercase tracking-wider
                  {{ $done ? 'text-emerald-500' : ($active ? 'text-amber-500' : 'text-slate-400 dark:text-slate-600') }}">
                  {{ $s['label'] }}
                </span>
              </div>
            @endforeach
          </div>
        </div>


      <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ─── Left Column: Map + Info ─── --}}
        <div class="lg:col-span-3 space-y-5">

          {{-- Map --}}
          <div class="map-container-wrapper bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800 lg:flex hidden">
              <h2 class="text-slate-900 dark:text-white font-bold text-sm flex items-center gap-2">
                <i data-lucide="map" class="w-4 h-4 text-amber-500"></i> Votre trajet
              </h2>
              <div id="driverStatus" class="hidden items-center gap-2 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full">
                <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                <span class="text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider">Chauffeur en direct</span>
              </div>
            </div>
            <div id="suivi-map" class="border-t border-slate-100 dark:border-slate-800/50"></div>

            <!-- Floating Action Buttons Panel (Mobile viewports only) -->
            <div class="mobile-fabs-panel lg:hidden fixed bottom-24 right-4 z-[2000] flex flex-col gap-3">
              <!-- Button 3: Chauffeur Modal Toggle -->
              @if($reservation->driver)
                <button onclick="openMobileModal('driver')" class="w-14 h-14 bg-emerald-500 hover:bg-emerald-400 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/25 transition active:scale-95 border border-emerald-400/20">
                  <i data-lucide="user-check" class="w-6 h-6"></i>
                </button>
              @endif

              <!-- Button 4: Course Details Modal Toggle -->
              <button onclick="openMobileModal('details')" class="w-14 h-14 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-full flex items-center justify-center shadow-lg transition active:scale-95 border border-slate-200 dark:border-slate-800">
                <i data-lucide="list-todo" class="w-6 h-6"></i>
              </button>

              <!-- Button 5: Live Chat Toggle (with Unread message badge) -->
              <button onclick="openMobileModal('chat')" class="relative w-14 h-14 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-full flex items-center justify-center shadow-lg shadow-amber-500/20 transition active:scale-95 border border-amber-400/20">
                <i data-lucide="message-square" class="w-6 h-6"></i>
                <span id="mobile-chat-badge" class="hidden absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[10px] font-black h-6 w-6 rounded-full flex items-center justify-center animate-bounce border-2 border-white dark:border-slate-950">0</span>
              </button>
            </div>
          </div>

          {{-- Trip details (4) --}}
          <div class="details-container-wrapper bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4 gap-2">
              <h2 class="text-slate-900 dark:text-white font-bold text-sm flex items-center gap-2">
                <i data-lucide="list-todo" class="w-4 h-4 text-amber-500"></i> Détails de la course
              </h2>
              <div class="flex items-center gap-2">
                @if($reservation->statut === 'en_attente')
                  <button onclick="openEditTrajetModal()" class="flex items-center gap-1.5 px-3 py-1 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-lg text-xs font-bold transition shadow shadow-amber-500/10 hover:-translate-y-0.5">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                    <span>Modifier mon trajet</span>
                  </button>
                @endif
                <!-- Dismiss Modal Button for Mobile viewports -->
                <button onclick="closeMobileModal('details')" class="lg:hidden w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-700 flex items-center justify-center transition border border-slate-200/50 dark:border-slate-700">
                  <i data-lucide="x" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
            <div class="space-y-3 text-sm">
              <div class="flex items-start gap-3 pb-3 border-b border-slate-100 dark:border-slate-800/50">
                <span class="w-8 h-8 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                  <i data-lucide="map-pin" class="w-4 h-4"></i>
                </span>
                <div>
                  <div class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Départ</div>
                  <div id="display_lieu_depart" class="text-slate-800 dark:text-slate-100 text-xs leading-relaxed font-semibold">{{ $reservation->lieu_depart }}</div>
                </div>
              </div>
              <div class="flex items-start gap-3 pb-3 border-b border-slate-100 dark:border-slate-800/50">
                <span class="w-8 h-8 bg-rose-500/10 rounded-xl flex items-center justify-center text-rose-600 dark:text-rose-400 flex-shrink-0">
                  <i data-lucide="flag" class="w-4 h-4"></i>
                </span>
                <div>
                  <div class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Arrivée</div>
                  <div id="display_lieu_arrivee" class="text-slate-800 dark:text-slate-100 text-xs leading-relaxed font-semibold">{{ $reservation->lieu_arrivee }}</div>
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

          @if($reservation->driver)
            {{-- Your Driver (3) --}}
            <div class="driver-container-wrapper bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm relative overflow-hidden">
              <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-[4rem]"></div>
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-slate-900 dark:text-white font-bold text-sm flex items-center gap-2">
                  <i data-lucide="user-check" class="w-4 h-4 text-amber-500"></i> Votre chauffeur & Véhicule
                </h2>
                <!-- Dismiss Modal Button for Mobile viewports -->
                <button onclick="closeMobileModal('driver')" class="lg:hidden z-10 w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-700 flex items-center justify-center transition border border-slate-200/50 dark:border-slate-700">
                  <i data-lucide="x" class="w-4 h-4"></i>
                </button>
              </div>
              <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                  <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden flex-shrink-0 shadow-md">
                    @if($reservation->driver->photo)
                      <img src="{{ $reservation->driver->photo }}" class="w-full h-full object-cover">
                    @else
                      <div class="w-full h-full flex items-center justify-center font-bold text-xl uppercase bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                        {{ substr($reservation->driver->prenom, 0, 1) }}{{ substr($reservation->driver->nom, 0, 1) }}
                      </div>
                    @endif
                  </div>
                  <div>
                    <div class="text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider mb-0.5">Votre chauffeur</div>
                    <div class="text-base font-extrabold text-slate-900 dark:text-white">{{ $reservation->driver->fullname }}</div>
                    <div class="text-xs text-amber-500 font-semibold mt-1 flex items-center gap-1">
                      <i data-lucide="car" class="w-3.5 h-3.5"></i>
                      <span>{{ $reservation->driver->vehicule_couleur }} {{ $reservation->driver->vehicule_marque }} {{ $reservation->driver->vehicule_modele }}</span>
                    </div>
                  </div>
                </div>

                <div class="flex flex-col sm:items-end gap-2 w-full sm:w-auto">
                  <div class="px-3 py-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-sm text-slate-800 dark:text-white uppercase tracking-wider font-bold w-fit">
                    {{ $reservation->driver->vehicule_immatriculation }}
                  </div>
                  <a href="tel:{{ $reservation->driver->telephone }}" class="flex items-center justify-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-emerald-500/10 w-full sm:w-auto">
                    <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                    <span>Appeler le chauffeur</span>
                  </a>
                </div>
              </div>
            </div>
          @endif

          {{-- Proposed Price --}}
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

        {{-- ─── Chat Column (5) ─── --}}
        <div class="chat-container-wrapper lg:col-span-2 flex flex-col">
          <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col shadow-sm" style="height: 600px;">
            <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3 flex-shrink-0">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-amber-500 rounded-xl flex items-center justify-center text-slate-950 shadow-md">
                  <i data-lucide="message-square" class="w-4 h-4"></i>
                </div>
                <div>
                  <div class="text-slate-900 dark:text-white font-bold text-sm">Chat avec notre équipe</div>
                  <div class="text-slate-500 dark:text-slate-400 text-xs">Temps de réponse : &lt; 5 min</div>
                </div>
              </div>
              <!-- Dismiss Modal Button for Mobile viewports -->
              <button onclick="closeMobileModal('chat')" class="lg:hidden w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-700 flex items-center justify-center transition border border-slate-200/50 dark:border-slate-700">
                <i data-lucide="x" class="w-4 h-4"></i>
              </button>
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

  {{-- ─── Overlay "Chauffeur Arrivé" ─── --}}
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

  @if($reservation->statut === 'en_attente')
    {{-- Modal Modification Trajet --}}
    <div id="editTrajetModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
      <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditTrajetModal()"></div>
      <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
          <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
            <div>
              <h2 class="text-lg font-bold text-slate-900 dark:text-white">Modifier mon trajet</h2>
              <p class="text-xs text-slate-500 mt-0.5">Mettez à jour vos adresses de départ et de destination</p>
            </div>
            <button onclick="closeEditTrajetModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>

          <form id="editTrajetForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="tracking" value="{{ $reservation->tracking_number }}">
            <input type="hidden" name="lat_depart" id="edit_lat_depart" value="{{ $reservation->lat_depart }}">
            <input type="hidden" name="lng_depart" id="edit_lng_depart" value="{{ $reservation->lng_depart }}">
            <input type="hidden" name="lat_arrivee" id="edit_lat_arrivee" value="{{ $reservation->lat_arrivee }}">
            <input type="hidden" name="lng_arrivee" id="edit_lng_arrivee" value="{{ $reservation->lng_arrivee }}">

            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Point de départ
              </label>
              <div class="relative">
                <input type="text" id="edit_search_depart" placeholder="Rechercher ou saisir l'adresse de départ..." required
                  class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500/50 transition pr-8"
                  autocomplete="off" value="{{ $reservation->lieu_depart }}">
                <div id="edit_results_depart" class="absolute left-0 right-0 mt-1 bg-slate-900 border border-white/10 rounded-xl max-h-48 overflow-y-auto hidden z-[999] shadow-2xl"></div>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span> Destination / Arrivée
              </label>
              <div class="relative">
                <input type="text" id="edit_search_arrivee" placeholder="Rechercher ou saisir l'adresse d'arrivée..." required
                  class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500/50 transition pr-8"
                  autocomplete="off" value="{{ $reservation->lieu_arrivee }}">
                <div id="edit_results_arrivee" class="absolute left-0 right-0 mt-1 bg-slate-900 border border-white/10 rounded-xl max-h-48 overflow-y-auto hidden z-[999] shadow-2xl"></div>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
              <button type="button" onclick="closeEditTrajetModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-medium transition">
                Annuler
              </button>
              <button type="submit" id="saveTrajetBtn" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2">
                <span>Enregistrer les modifications</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('scripts')
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
  const TRACKING  = '{{ $reservation->tracking_number }}';
  const LAT_DEP   = {{ $reservation->lat_depart ?? 6.1372 }};
  const LNG_DEP   = {{ $reservation->lng_depart ?? 1.2125 }};
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

  // ─── Live Chat Overlay state tracking ───
  let unreadMessageCount = 0;

  function openMobileModal(type) {
    closeAllMobileModals();
    const wrapper = document.querySelector(`.${type}-container-wrapper`);
    if (wrapper) {
      wrapper.classList.add('active-modal');
    }

    if (type === 'chat') {
      markChatAsRead();
      setTimeout(scrollChatBottom, 100);
    }
  }

  function closeMobileModal(type) {
    const wrapper = document.querySelector(`.${type}-container-wrapper`);
    if (wrapper) {
      wrapper.classList.remove('active-modal');
    }
  }

  function closeAllMobileModals() {
    document.querySelectorAll('.details-container-wrapper, .driver-container-wrapper, .chat-container-wrapper').forEach(el => {
      el.classList.remove('active-modal');
    });
  }

  function updateUnreadBadge() {
    const badge = document.getElementById('mobile-chat-badge');
    if (!badge) return;
    if (unreadMessageCount > 0) {
      badge.innerText = unreadMessageCount;
      badge.classList.remove('hidden');
      badge.classList.add('flex');
    } else {
      badge.classList.remove('flex');
      badge.classList.add('hidden');
    }
  }

  function markChatAsRead() {
    localStorage.setItem('last_read_msg_id_' + TRACKING, LAST_MSG_ID);
    unreadMessageCount = 0;
    updateUnreadBadge();
  }

  // Initial count of unread messages on page load based on localStorage
  document.addEventListener('DOMContentLoaded', () => {
    const lastReadId = parseInt(localStorage.getItem('last_read_msg_id_' + TRACKING) || '0');
    let count = 0;
    document.querySelectorAll('#chatMessages [data-msg-id]').forEach(el => {
      const msgId = parseInt(el.getAttribute('data-msg-id') || '0');
      if (msgId > lastReadId) {
        if (el.querySelector('.bubble-admin') || el.querySelector('.bubble-system')) {
          count++;
        }
      }
    });

    const chatWrapper = document.querySelector('.chat-container-wrapper');
    const isMobile = window.innerWidth < 1024;
    const isChatOpen = chatWrapper && chatWrapper.classList.contains('active-modal');

    if (!isMobile || isChatOpen) {
      markChatAsRead();
    } else {
      unreadMessageCount = count;
      updateUnreadBadge();
    }
  });

  // ─── Map ───
  const map = L.map('suivi-map').setView([LAT_DEP, LNG_DEP], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

  // Departure marker (6) is Green dot, Destination marker (7) is Orange dot
  const iconDepart = L.divIcon({ html: `<div style="width:24px;height:24px;background:#10b981;border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.4)"></div>`, iconSize:[24,24], iconAnchor:[12,12], className:'' });
  const iconArrivee = L.divIcon({ html: `<div style="width:24px;height:24px;background:#f59e0b;border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.4)"></div>`, iconSize:[24,24], iconAnchor:[12,12], className:'' });
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
    const statusEl = document.getElementById('driverStatus');
    if (statusEl) {
      statusEl.classList.remove('hidden');
      statusEl.classList.add('flex');
    }
  }

  // Invalidate Leaflet bounds on window resize/mobile layout transition
  setTimeout(() => {
    map.invalidateSize();
  }, 400);

  // ─── Real OSRM Routing ───
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

  // ─── Chat scroll bottom ───
  function scrollChatBottom() {
    const c = document.getElementById('chatMessages');
    if (c) c.scrollTop = c.scrollHeight;
  }
  scrollChatBottom();

  // ─── Add Message to Chat ───
  function appendMessage(msg) {
    if (msg.id && document.querySelector(`[data-msg-id="${msg.id}"]`)) {
      return;
    }
    if (msg.id) {
      LAST_MSG_ID = Math.max(LAST_MSG_ID, msg.id);
    }

    // Increment unread count if chat drawer modal is closed on mobile
    const chatWrapper = document.querySelector('.chat-container-wrapper');
    const isMobile = window.innerWidth < 1024;
    const isChatOpen = chatWrapper && chatWrapper.classList.contains('active-modal');
    if (!isMobile || isChatOpen) {
      markChatAsRead();
    } else if (msg.id && (msg.auteur === 'admin' || msg.auteur === 'systeme' || msg.type === 'notification_systeme')) {
      unreadMessageCount++;
      updateUnreadBadge();
    }

    const c = document.getElementById('chatMessages');
    if (!c) return;
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

  // ─── Driver Arrived overlay ───
  function triggerArrivedNotification() {
    if (notifiedArrival) return;
    notifiedArrival = true;

    document.getElementById('arrivedOverlay').style.display = 'flex';

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

    if (Notification.permission === 'granted') {
      new Notification('Votre chauffeur est arrivé !', {
        body: 'Il vous attend à votre point de départ.',
        icon: '/favicon.ico'
      });
    } else if (Notification.permission !== 'denied') {
      Notification.requestPermission();
    }
  }

  // ─── Polling ───
  let pollingActive = false;
  function pollMessages() {
    if (pollingActive) return;
    pollingActive = true;

    fetch(`/transport/messages/${TRACKING}?since_id=${LAST_MSG_ID}`)
      .then(r => r.json())
      .then(data => {
        data.messages.forEach(msg => {
          appendMessage(msg);
        });

        if (data.statut && data.statut !== STATUT) {
          STATUT = data.statut;
          location.reload();
        }

        if (data.prix_propose && data.prix_propose !== currentPrix) {
          currentPrix = data.prix_propose;
          const card = document.getElementById('priceCard');
          const dynPrice = document.getElementById('dynamicPrice');
          if (dynPrice) dynPrice.innerHTML = new Intl.NumberFormat('fr-FR').format(currentPrix) + ' <span class="text-sm font-medium text-slate-500 dark:text-slate-400">FCFA</span>';
          if (card) card.classList.remove('hidden');
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
        if (data.trackable && data.chauffeur_lat && data.chauffeur_lng) {
          const pos = [data.chauffeur_lat, data.chauffeur_lng];
          if (driverMarker) {
            driverMarker.setLatLng(pos);
          } else {
            driverMarker = L.marker(pos, { icon: iconDriver, zIndexOffset: 1000 }).addTo(map);
            driverMarker.bindPopup('<b>🚗 Votre chauffeur</b>');
            const statusEl = document.getElementById('driverStatus');
            if (statusEl) {
              statusEl.classList.remove('hidden');
              statusEl.classList.add('flex');
            }
          }
        }

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

  setInterval(pollMessages, 5000);
  setInterval(pollDriverLocation, 5000);

  if (Notification.permission === 'default') {
    setTimeout(() => Notification.requestPermission(), 3000);
  }

  if (notifiedArrival) {
    const arrivedEl = document.getElementById('arrivedOverlay');
    if (arrivedEl) arrivedEl.style.display = 'flex';
  }

  // ─── Message submission ───
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

  // ─── Accept Price ───
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

  // ─── Travel modification modal ───
  function openEditTrajetModal() {
    const modal = document.getElementById('editTrajetModal');
    if (modal) modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeEditTrajetModal() {
    const modal = document.getElementById('editTrajetModal');
    if (modal) modal.classList.add('hidden');
    document.body.style.overflow = '';
  }

  @if($reservation->statut === 'en_attente')
    function initEditSearch(inputId, resultsId, type) {
      const input = document.getElementById(inputId);
      const results = document.getElementById(resultsId);
      let timeout = null;

      input.addEventListener('input', function() {
        const query = input.value.trim();

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
                const short = item.display_name.substring(0, 80);
                div.innerHTML = `<strong>${item.name}</strong><br><span class="text-[10px] text-slate-500">${short}</span>`;

                div.addEventListener('click', () => {
                  input.value = short;
                  results.innerHTML = '';
                  results.classList.add('hidden');

                  const lat = parseFloat(item.lat);
                  const lng = parseFloat(item.lon);

                  if (type === 'depart') {
                    document.getElementById('edit_lat_depart').value = lat;
                    document.getElementById('edit_lng_depart').value = lng;
                  } else {
                    document.getElementById('edit_lat_arrivee').value = lat;
                    document.getElementById('edit_lng_arrivee').value = lng;
                  }
                });
                results.appendChild(div);
              });
              results.classList.remove('hidden');
            })
            .catch(() => {});
        }, 400);
      });

      document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !results.contains(e.target)) {
          results.classList.add('hidden');
        }
      });
    }

    initEditSearch('edit_search_depart', 'edit_results_depart', 'depart');
    initEditSearch('edit_search_arrivee', 'edit_results_arrivee', 'arrivee');

    document.getElementById('editTrajetForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = document.getElementById('saveTrajetBtn');
      const originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 animate-spin"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="6.34" y1="17.66" x2="8.46" y2="15.54"/><line x1="15.54" y1="8.46" x2="17.66" y2="6.34"/></svg> Enregistrement...';

      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      const tracking = '{{ $reservation->tracking_number }}';
      const lieu_depart = document.getElementById('edit_search_depart').value;
      const lieu_arrivee = document.getElementById('edit_search_arrivee').value;
      const lat_depart = document.getElementById('edit_lat_depart').value;
      const lng_depart = document.getElementById('edit_lng_depart').value;
      const lat_arrivee = document.getElementById('edit_lat_arrivee').value;
      const lng_arrivee = document.getElementById('edit_lng_arrivee').value;

      fetch('{{ route("transport.update-trajet") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
          tracking, lieu_depart, lieu_arrivee,
          lat_depart, lng_depart, lat_arrivee, lng_arrivee
        })
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          document.getElementById('display_lieu_depart').innerText = data.lieu_depart;
          document.getElementById('display_lieu_arrivee').innerText = data.lieu_arrivee;
          closeEditTrajetModal();
          location.reload();
        } else {
          alert(data.message || 'Une erreur est survenue lors de la modification.');
          btn.disabled = false;
          btn.innerHTML = originalHtml;
        }
      })
      .catch(() => {
        alert('Erreur réseau. Veuillez réessayer.');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
      });
    });
  @endif
  </script>
  <style>
  .custom-chat-scroll::-webkit-scrollbar { width: 4px; }
  .custom-chat-scroll::-webkit-scrollbar-thumb { background: rgba(245,158,11,0.2); border-radius: 4px; }
  </style>
@endsection
