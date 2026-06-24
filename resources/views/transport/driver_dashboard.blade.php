<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Tableau de bord Chauffeur — AutoImport</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
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
<body class="bg-slate-950 text-white font-sans min-h-screen flex flex-col">
  <!-- Top Header -->
  <header class="bg-slate-900 border-b border-slate-800 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 bg-amber-500/10 border border-amber-500/20 rounded-lg flex items-center justify-center text-amber-500">
        <i data-lucide="navigation" class="w-4 h-4"></i>
      </div>
      <span class="font-extrabold text-sm tracking-tight uppercase">Espace Chauffeur</span>
    </div>
    <form action="{{ route('driver.logout') }}" method="POST" onsubmit="return confirm('Voulez-vous vous déconnecter ?')">
      @csrf
      <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg text-xs font-bold transition">
        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
        <span>Déconnexion</span>
      </button>
    </form>
  </header>

  <main class="flex-grow p-4 max-w-lg mx-auto w-full space-y-6">
    <!-- Profile Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 relative overflow-hidden shadow-xl">
      <div class="absolute top-0 right-0 w-20 h-20 bg-amber-500/5 rounded-bl-[3rem]"></div>
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden flex-shrink-0 flex items-center justify-center">
          @if($driver->photo)
            <img src="{{ $driver->photo }}" class="w-full h-full object-cover">
          @else
            <span class="text-xl font-bold uppercase text-slate-400">{{ substr($driver->prenom,0,1) }}{{ substr($driver->nom,0,1) }}</span>
          @endif
        </div>
        <div>
          <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-0.5">Chauffeur</div>
          <div class="text-lg font-bold text-white">{{ $driver->fullname }}</div>
          <div class="text-xs text-amber-500 font-semibold mt-1 flex items-center gap-1">
            <i data-lucide="car" class="w-3.5 h-3.5"></i>
            <span>{{ $driver->vehicule_couleur }} {{ $driver->vehicule_marque }} {{ $driver->vehicule_modele }}</span>
          </div>
        </div>
      </div>
      <div class="border-t border-slate-800 mt-4 pt-3 flex items-center justify-between text-xs text-slate-500">
        <span>Matricule : <strong class="font-mono text-slate-300 uppercase">{{ $driver->vehicule_immatriculation }}</strong></span>
        <span>ID : <strong class="font-mono text-slate-300">{{ $driver->identifiant }}</strong></span>
      </div>
    </div>

    <!-- Active Reservations Section -->
    <div class="space-y-3">
      <h2 class="text-xs font-bold uppercase text-slate-500 tracking-wider flex items-center gap-2">
        <i data-lucide="route" class="w-4 h-4 text-amber-500"></i>
        <span>Courses Actives ({{ $activeReservations->count() }})</span>
      </h2>

      @forelse($activeReservations as $res)
        <a href="{{ route('driver.show', $res->driver_token) }}" class="block bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-4 transition-all hover:scale-[1.01] shadow">
          <div class="flex items-center justify-between gap-3 mb-3">
            <div class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/20 text-amber-500 font-mono text-[10px] font-bold rounded">
              {{ $res->reference }}
            </div>
            @php
              $statusLabels = [
                'en_attente'          => 'En attente',
                'accepte'             => 'Accepté',
                'chauffeur_en_route'  => 'En route',
                'chauffeur_arrive'    => 'Arrivé',
                'en_cours'            => 'En cours',
              ];
              $statusColors = [
                'en_attente'          => 'text-slate-400 bg-slate-400/5 border-slate-400/10',
                'accepte'             => 'text-blue-400 bg-blue-400/5 border-blue-400/10',
                'chauffeur_en_route'  => 'text-amber-400 bg-amber-400/5 border-amber-400/10',
                'chauffeur_arrive'    => 'text-emerald-400 bg-emerald-400/5 border-emerald-400/10',
                'en_cours'            => 'text-indigo-400 bg-indigo-400/5 border-indigo-400/10',
              ];
            @endphp
            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold border {{ $statusColors[$res->statut] ?? 'text-slate-400' }}">
              {{ $statusLabels[$res->statut] ?? $res->statut }}
            </span>
          </div>

          <div class="space-y-2 text-xs border-b border-slate-800/50 pb-3 mb-3">
            <div class="flex items-start gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-1.5 flex-shrink-0"></span>
              <span class="text-slate-300 leading-tight">Départ : {{ $res->lieu_depart }}</span>
            </div>
            <div class="flex items-start gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-1.5 flex-shrink-0"></span>
              <span class="text-slate-300 leading-tight">Arrivée : {{ $res->lieu_arrivee }}</span>
            </div>
          </div>

          <div class="flex items-center justify-between text-xs">
            <div class="text-slate-500">
              📅 {{ $res->date_prise_en_charge->format('d/m/Y à H:i') }}
            </div>
            <div class="flex items-center gap-1.5 font-bold text-amber-500 uppercase tracking-wider text-[10px]">
              <span>Démarrer</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </div>
          </div>
        </a>
      @empty
        <div class="bg-slate-900/50 border border-slate-900 border-dashed rounded-2xl p-8 text-center text-slate-500 text-xs">
          <i data-lucide="calendar-x" class="w-8 h-8 mx-auto mb-2 text-slate-600"></i>
          <span>Aucune course active affectée pour le moment.</span>
        </div>
      @endforelse
    </div>

    <!-- Completed Reservations Section -->
    @if($completedReservations->count() > 0)
    <div class="space-y-3">
      <h2 class="text-xs font-bold uppercase text-slate-500 tracking-wider flex items-center gap-2">
        <i data-lucide="check-square" class="w-4 h-4 text-emerald-500"></i>
        <span>Courses Terminées ({{ $completedReservations->count() }})</span>
      </h2>

      <div class="divide-y divide-slate-800 bg-slate-900/40 border border-slate-900 rounded-2xl overflow-hidden shadow-inner">
        @foreach($completedReservations as $res)
          <div class="p-4 flex items-center justify-between gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-400">{{ $res->reference }}</span>
                <span class="text-[10px] text-emerald-500 font-bold bg-emerald-500/5 px-1.5 py-0.5 rounded border border-emerald-500/10">Terminé</span>
              </div>
              <p class="text-[11px] text-slate-500 line-clamp-1">De : {{ $res->lieu_depart }}</p>
              <p class="text-[11px] text-slate-500 line-clamp-1">Vers : {{ $res->lieu_arrivee }}</p>
            </div>
            <div class="text-right text-xs text-slate-600 flex-shrink-0">
              {{ $res->date_prise_en_charge->format('d/m/Y') }}
            </div>
          </div>
        @endforeach
      </div>
    </div>
    @endif
  </main>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
