<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Historique des courses — AutoImport</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Outfit', 'sans-serif'],
          },
        },
      },
    }
  </script>
</head>
<body class="bg-orange-50/20 text-slate-800 font-sans min-h-screen flex flex-col">
  <!-- Top Header -->
  <header class="bg-white border-b border-orange-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
    <div class="flex items-center gap-2">
      <a href="{{ route('driver.dashboard') }}" class="w-8 h-8 bg-orange-50 hover:bg-orange-100 border border-orange-100 rounded-lg flex items-center justify-center text-orange-600 transition">
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
      </a>
      <span class="font-extrabold text-sm tracking-tight uppercase text-slate-800">Historique</span>
    </div>
    
    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">
      Chauffeur : <span class="text-orange-600 font-extrabold">{{ $driver->fullname }}</span>
    </div>
  </header>

  <main class="flex-grow p-4 max-w-lg mx-auto w-full space-y-6">
    <!-- Statistics Cards -->
    @php
      $totalCompleted = \App\Models\ReservationTransport::where('driver_id', $driver->id)->where('statut', 'termine')->count();
      $totalCancelled = \App\Models\ReservationTransport::where('driver_id', $driver->id)->where('statut', 'annule')->count();
    @endphp
    
    <div class="grid grid-cols-2 gap-4">
      <!-- Total Completed Card -->
      <div class="bg-white border border-orange-100 rounded-2xl p-4 text-center shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 mx-auto mb-1.5">
          <i data-lucide="check-circle" class="w-4.5 h-4.5"></i>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wide">Réussies</div>
        <div class="text-2xl font-black text-emerald-600 mt-0.5">{{ $totalCompleted }}</div>
      </div>

      <!-- Total Cancelled Card -->
      <div class="bg-white border border-orange-100 rounded-2xl p-4 text-center shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 mx-auto mb-1.5">
          <i data-lucide="x-circle" class="w-4.5 h-4.5"></i>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wide">Annulées</div>
        <div class="text-2xl font-black text-rose-600 mt-0.5">{{ $totalCancelled }}</div>
      </div>
    </div>

    <!-- History list -->
    <div class="space-y-3">
      <h2 class="text-xs font-bold uppercase text-slate-400 tracking-widest flex items-center gap-2">
        <i data-lucide="history" class="w-4 h-4 text-orange-500"></i>
        <span>Toutes les courses ({{ $completedReservations->total() }})</span>
      </h2>

      @forelse($completedReservations as $res)
        <div class="bg-white border border-orange-100 rounded-2xl p-4 shadow-sm space-y-3">
          <div class="flex items-center justify-between gap-3 border-b border-orange-50 pb-2">
            <div class="px-2 py-0.5 bg-orange-50 border border-orange-100 text-orange-700 font-mono text-[10px] font-bold rounded">
              {{ $res->reference }}
            </div>
            
            @if($res->statut === 'termine')
              <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold border text-emerald-700 bg-emerald-50 border-emerald-200">
                Terminé
              </span>
            @else
              <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold border text-rose-700 bg-rose-50 border-rose-200">
                Annulé
              </span>
            @endif
          </div>

          <!-- Trip details -->
          <div class="space-y-2 text-xs">
            <div class="flex items-start gap-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mt-1 flex-shrink-0 border-2 border-emerald-400"></span>
              <span class="text-slate-600 leading-tight">Départ : {{ $res->lieu_depart }}</span>
            </div>
            <div class="flex items-start gap-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mt-1 flex-shrink-0 border-2 border-rose-400"></span>
              <span class="text-slate-600 leading-tight">Arrivée : {{ $res->lieu_arrivee }}</span>
            </div>
          </div>

          <!-- Date & Toggle Details Button -->
          <div class="flex items-center justify-between text-xs pt-2 border-t border-orange-50">
            <div class="text-slate-500 font-semibold">
              📅 {{ $res->date_prise_en_charge ? $res->date_prise_en_charge->format('d/m/Y à H:i') : '' }}
            </div>
            <button onclick="toggleDetails({{ $res->id }})" class="flex items-center gap-1 text-orange-600 hover:text-orange-700 font-bold transition">
              <span>Détails</span>
              <i data-lucide="chevron-down" id="chevron-{{ $res->id }}" class="w-3.5 h-3.5 transition-transform duration-200"></i>
            </button>
          </div>

          <!-- Collapsible details block -->
          <div id="details-{{ $res->id }}" class="hidden pt-3 border-t border-dashed border-orange-100 space-y-2.5 text-xs text-slate-600 animate-in fade-in duration-200">
            <!-- Client Info -->
            <div class="bg-orange-50/30 rounded-xl p-3 space-y-1.5 font-medium">
              <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider font-bold">Informations Client</div>
              <div class="flex justify-between">
                <span class="text-slate-400">Nom :</span>
                <span class="text-slate-900 font-bold">{{ $res->client_nom }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Téléphone :</span>
                <span class="text-slate-900 font-bold">{{ $res->client_telephone }}</span>
              </div>
              @if($res->client_email)
                <div class="flex justify-between">
                  <span class="text-slate-400">E-mail :</span>
                  <span class="text-slate-900 font-bold">{{ $res->client_email }}</span>
                </div>
              @endif
            </div>

            <!-- Ride Specifications -->
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 space-y-1.5 font-medium">
              <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider font-bold">Spécifications Course</div>
              <div class="flex justify-between">
                <span class="text-slate-400">Type de service :</span>
                <span class="text-slate-900 font-bold">{{ \App\Models\ReservationTransport::typeServiceLabels()[$res->type_service] ?? $res->type_service }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Nombre de personnes :</span>
                <span class="text-slate-900 font-bold">{{ $res->nombre_personnes }} passager(s)</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Date de réservation :</span>
                <span class="text-slate-900 font-bold">{{ $res->date_reservation ? $res->date_reservation->format('d/m/Y à H:i') : '' }}</span>
              </div>
              @if($res->chauffeur_arrived_at)
                <div class="flex justify-between">
                  <span class="text-slate-400">Arrivée chauffeur :</span>
                  <span class="text-slate-900 font-bold">{{ $res->chauffeur_arrived_at->format('d/m/Y à H:i') }}</span>
                </div>
              @endif
            </div>

            <!-- Client Notes -->
            @if($res->notes_client)
              <div class="bg-amber-50/50 border border-amber-100/50 rounded-xl p-3 space-y-1">
                <div class="text-[10px] text-amber-700 font-bold uppercase tracking-wider">Note du client :</div>
                <p class="text-slate-700 italic leading-relaxed">{{ $res->notes_client }}</p>
              </div>
            @endif
          </div>
        </div>
      @empty
        <div class="bg-white border border-orange-100 border-dashed rounded-2xl p-8 text-center text-slate-400 text-xs">
          <i data-lucide="history" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
          <span>Aucun historique disponible pour le moment.</span>
        </div>
      @endforelse

      <!-- Custom Pagination -->
      @if($completedReservations->hasPages())
        <div class="flex items-center justify-between pt-4 pb-6 font-semibold">
          @if($completedReservations->onFirstPage())
            <span class="px-4 py-2 bg-slate-100 border border-slate-200 text-slate-400 rounded-xl text-xs cursor-not-allowed">
              Précédent
            </span>
          @else
            <a href="{{ $completedReservations->previousPageUrl() }}" class="px-4 py-2 bg-white hover:bg-orange-50 border border-orange-100 text-orange-600 rounded-xl text-xs shadow-sm transition">
              Précédent
            </a>
          @endif

          <span class="text-xs text-slate-500">
            Page {{ $completedReservations->currentPage() }} sur {{ $completedReservations->lastPage() }}
          </span>

          @if($completedReservations->hasMorePages())
            <a href="{{ $completedReservations->nextPageUrl() }}" class="px-4 py-2 bg-white hover:bg-orange-50 border border-orange-100 text-orange-600 rounded-xl text-xs shadow-sm transition">
              Suivant
            </a>
          @else
            <span class="px-4 py-2 bg-slate-100 border border-slate-200 text-slate-400 rounded-xl text-xs cursor-not-allowed">
              Suivant
            </span>
          @endif
        </div>
      @endif
    </div>
  </main>

  <script>
    lucide.createIcons();

    function toggleDetails(id) {
      const details = document.getElementById(`details-${id}`);
      const chevron = document.getElementById(`chevron-${id}`);
      if (details && chevron) {
        const isHidden = details.classList.contains('hidden');
        if (isHidden) {
          details.classList.remove('hidden');
          chevron.classList.add('rotate-180');
        } else {
          details.classList.add('hidden');
          chevron.classList.remove('rotate-180');
        }
      }
    }
  </script>
</body>
</html>
