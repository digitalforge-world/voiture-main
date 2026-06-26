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
      $totalEarnings = \App\Models\ReservationTransport::where('driver_id', $driver->id)->where('statut', 'termine')->where('prix_accepte', true)->sum('prix_propose');
    @endphp
    
    <div class="grid grid-cols-3 gap-3">
      <!-- Total Completed Card -->
      <div class="bg-white border border-orange-100 rounded-2xl p-3 text-center shadow-sm">
        <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 mx-auto mb-1.5">
          <i data-lucide="check-circle" class="w-4 h-4"></i>
        </div>
        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Réussies</div>
        <div class="text-lg font-black text-emerald-600 mt-0.5">{{ $totalCompleted }}</div>
      </div>

      <!-- Total Cancelled Card -->
      <div class="bg-white border border-orange-100 rounded-2xl p-3 text-center shadow-sm">
        <div class="w-7 h-7 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 mx-auto mb-1.5">
          <i data-lucide="x-circle" class="w-4 h-4"></i>
        </div>
        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Annulées</div>
        <div class="text-lg font-black text-rose-600 mt-0.5">{{ $totalCancelled }}</div>
      </div>

      <!-- Total Earnings Card -->
      <div class="bg-white border border-orange-100 rounded-2xl p-3 text-center shadow-sm">
        <div class="w-7 h-7 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600 mx-auto mb-1.5">
          <i data-lucide="banknote" class="w-4 h-4"></i>
        </div>
        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Revenus (Est.)</div>
        <div class="text-xs font-black text-orange-600 mt-0.5 leading-tight pt-0.5">{{ number_format($totalEarnings, 0, ',', ' ') }} F</div>
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
              <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1 flex-shrink-0 border-2 border-emerald-400"></span>
              <span class="text-slate-600 leading-tight">Départ : {{ $res->lieu_depart }}</span>
            </div>
            <div class="flex items-start gap-2.5">
              <span class="w-2 h-2 rounded-full bg-rose-500 mt-1 flex-shrink-0 border-2 border-rose-400"></span>
              <span class="text-slate-600 leading-tight">Arrivée : {{ $res->lieu_arrivee }}</span>
            </div>
          </div>

          <!-- Date & Client info & Price -->
          <div class="flex items-center justify-between text-xs pt-2 border-t border-orange-50">
            <div class="text-slate-400 font-semibold">
              📅 {{ $res->date_prise_en_charge ? $res->date_prise_en_charge->format('d/m/Y à H:i') : '' }}
            </div>
            @if($res->prix_propose && $res->prix_accepte)
              <div class="text-orange-600 font-bold">
                {{ number_format($res->prix_propose, 0, ',', ' ') }} FCFA
              </div>
            @else
              <div class="text-slate-400 font-bold italic">
                Sans prix
              </div>
            @endif
          </div>

          <!-- Client details -->
          <div class="bg-orange-50/30 rounded-xl p-2.5 flex items-center justify-between text-[11px] text-slate-500 font-semibold">
            <span>Client : <strong class="text-slate-700 font-bold">{{ $res->client_nom }}</strong></span>
            <span>Tel : <strong class="text-slate-700 font-bold">{{ $res->client_telephone }}</strong></span>
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
  </script>
</body>
</html>
