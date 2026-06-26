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
</head><body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col transition-colors duration-300">
  <!-- Top Header -->
  <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 bg-amber-500/10 border border-amber-500/20 rounded-lg flex items-center justify-center text-amber-600">
        <i data-lucide="navigation" class="w-4 h-4"></i>
      </div>
      <span class="font-extrabold text-sm tracking-tight uppercase text-slate-800">Espace Chauffeur</span>
    </div>
    
    <!-- Dropdown Menu (2) -->
    <div class="relative">
      <button id="btn-dropdown" onclick="toggleDropdown()" class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold transition">
        <i data-lucide="menu" class="w-4 h-4"></i>
        <span>Menu</span>
        <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60"></i>
      </button>
      
      <!-- Dropdown Card -->
      <div id="header-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-2xl py-1.5 z-40 animate-in fade-in slide-in-from-top-2 duration-200">
        <button onclick="scrollToSection('profile-card')" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition text-left border-none outline-none cursor-pointer">
          <i data-lucide="user" class="w-3.5 h-3.5"></i>
          <span>Mon Profil</span>
        </button>
        <button onclick="scrollToSection('active-courses')" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition text-left border-none outline-none cursor-pointer">
          <i data-lucide="route" class="w-3.5 h-3.5"></i>
          <span>Courses Actives</span>
        </button>
        <button onclick="scrollToSection('completed-courses')" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition text-left border-none outline-none cursor-pointer">
          <i data-lucide="history" class="w-3.5 h-3.5"></i>
          <span>Historique des courses</span>
        </button>
        <div class="border-t border-slate-100 my-1.5"></div>
        <form action="{{ route('driver.logout') }}" method="POST" onsubmit="return confirm('Voulez-vous vous déconnecter ?')">
          @csrf
          <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition text-left border-none outline-none cursor-pointer">
            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
            <span>Déconnexion</span>
          </button>
        </form>
      </div>
    </div>
  </header>

  <main class="flex-grow p-4 max-w-lg mx-auto w-full space-y-6">
    <!-- Profile Card (3) -->
    <div id="profile-card" class="bg-white border border-slate-200 rounded-3xl p-5 relative overflow-hidden shadow-md">
      <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/[0.03] rounded-bl-[4rem]"></div>
      <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 bg-slate-100 border border-slate-200 rounded-2xl overflow-hidden flex-shrink-0 flex items-center justify-center shadow-inner">
            @if($driver->photo)
              <img src="{{ $driver->photo }}" class="w-full h-full object-cover">
            @else
              <span class="text-xl font-extrabold uppercase text-slate-400">{{ substr($driver->prenom,0,1) }}{{ substr($driver->nom,0,1) }}</span>
            @endif
          </div>
          <div>
            <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-0.5">Chauffeur</div>
            <div class="text-lg font-extrabold text-slate-900">{{ $driver->fullname }}</div>
            <div class="text-xs text-amber-600 font-bold mt-1.5 flex items-center gap-1 bg-amber-50 border border-amber-100/50 rounded-lg px-2.5 py-1 w-fit">
              <i data-lucide="car" class="w-3.5 h-3.5"></i>
              <span>{{ $driver->vehicule_couleur }} {{ $driver->vehicule_marque }} {{ $driver->vehicule_modele }}</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Successful rides today counter -->
      @php
        $todaySuccessCount = $completedReservations->filter(function($res) {
          $dateStr = is_string($res->date_prise_en_charge) ? substr($res->date_prise_en_charge, 0, 10) : $res->date_prise_en_charge->format('Y-m-d');
          return $res->statut === 'termine' && $dateStr === date('Y-m-d');
        })->count();
      @endphp
      <div class="mt-4 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-lg bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-600">
            <i data-lucide="trophy" class="w-4 h-4"></i>
          </div>
          <span class="text-xs font-bold text-emerald-800 uppercase tracking-wide">Courses réussies aujourd'hui</span>
        </div>
        <span class="text-2xl font-black text-emerald-600">{{ $todaySuccessCount }}</span>
      </div>

      <div class="border-t border-slate-100 mt-4 pt-3 flex items-center justify-between text-xs text-slate-500 font-semibold">
        <span>Matricule : <strong class="font-mono text-slate-700 uppercase">{{ $driver->vehicule_immatriculation }}</strong></span>
        <span>ID : <strong class="font-mono text-slate-700">{{ $driver->identifiant }}</strong></span>
      </div>
    </div>

    <!-- Active Reservations Section (4) -->
    <div id="active-courses" class="space-y-3">
      <h2 class="text-xs font-bold uppercase text-slate-400 tracking-widest flex items-center gap-2">
        <i data-lucide="route" class="w-4 h-4 text-amber-500"></i>
        <span>Courses Actives ({{ $activeReservations->count() }})</span>
      </h2>

      @forelse($activeReservations as $res)
        <a href="{{ route('driver.show', $res->driver_token) }}" class="block bg-white border border-slate-200 hover:border-amber-400 rounded-2xl p-4 transition-all hover:scale-[1.01] hover:shadow-xl hover:shadow-amber-500/5 group shadow-sm">
          <div class="flex items-center justify-between gap-3 mb-3">
            <div class="px-2 py-0.5 bg-amber-50 border border-amber-100 text-amber-700 font-mono text-[10px] font-bold rounded">
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
                'en_attente'          => 'text-slate-600 bg-slate-50 border-slate-200',
                'accepte'             => 'text-blue-700 bg-blue-50 border-blue-200',
                'chauffeur_en_route'  => 'text-amber-700 bg-amber-50 border-amber-200',
                'chauffeur_arrive'    => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                'en_cours'            => 'text-indigo-700 bg-indigo-50 border-indigo-200',
              ];
            @endphp
            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold border {{ $statusColors[$res->statut] ?? 'text-slate-400' }}">
              {{ $statusLabels[$res->statut] ?? $res->statut }}
            </span>
          </div>

          <div class="space-y-2 text-xs border-b border-slate-100 pb-3 mb-3">
            <div class="flex items-start gap-2.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1 flex-shrink-0 border-2 border-emerald-400"></span>
              <span class="text-slate-600 leading-tight">Départ : {{ $res->lieu_depart }}</span>
            </div>
            <div class="flex items-start gap-2.5">
              <span class="w-2 h-2 rounded-full bg-rose-500 mt-1 flex-shrink-0 border-2 border-rose-400"></span>
              <span class="text-slate-600 leading-tight">Arrivée : {{ $res->lieu_arrivee }}</span>
            </div>
          </div>

          <div class="flex items-center justify-between text-xs font-semibold">
            <div class="text-slate-400">
              📅 {{ $res->date_prise_en_charge->format('d/m/Y à H:i') }}
            </div>
            <div class="flex items-center gap-1 font-bold text-amber-600 group-hover:text-amber-700 uppercase tracking-wider text-[10px] transition-colors">
              <span>Démarrer</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform"></i>
            </div>
          </div>
        </a>
      @empty
        <div class="bg-white border border-slate-200 border-dashed rounded-2xl p-8 text-center text-slate-400 text-xs">
          <i data-lucide="calendar-x" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
          <span>Aucune course active affectée pour le moment.</span>
        </div>
      @endforelse
    </div>

    <!-- Completed Reservations Section -->
    @if($completedReservations->count() > 0)
    <div id="completed-courses" class="space-y-3">
      <h2 class="text-xs font-bold uppercase text-slate-400 tracking-widest flex items-center gap-2">
        <i data-lucide="check-square" class="w-4 h-4 text-emerald-600"></i>
        <span>Courses Terminées ({{ $completedReservations->count() }})</span>
      </h2>

      <div class="divide-y divide-slate-100 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm font-semibold">
        @foreach($completedReservations as $res)
          <div class="p-4 flex items-center justify-between gap-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="font-mono text-xs font-bold text-slate-500">{{ $res->reference }}</span>
                @if($res->statut === 'termine')
                  <span class="text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-100 font-bold px-1.5 py-0.5 rounded">Terminé</span>
                @else
                  <span class="text-[10px] text-rose-700 bg-rose-50 border border-rose-100 font-bold px-1.5 py-0.5 rounded">Annulé</span>
                @endif
              </div>
              <p class="text-[11px] text-slate-500 line-clamp-1">De : {{ $res->lieu_depart }}</p>
              <p class="text-[11px] text-slate-500 line-clamp-1">Vers : {{ $res->lieu_arrivee }}</p>
            </div>
            <div class="text-right text-xs text-slate-400 flex-shrink-0 font-semibold">
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

    function toggleDropdown() {
      const dropdown = document.getElementById('header-dropdown');
      dropdown.classList.toggle('hidden');
    }

    function closeDropdown() {
      const dropdown = document.getElementById('header-dropdown');
      dropdown.classList.add('hidden');
    }

    function scrollToSection(id) {
      closeDropdown();
      const target = document.getElementById(id);
      if (target) {
        const header = document.querySelector('header');
        const headerHeight = header ? header.offsetHeight : 72;
        const targetPosition = target.getBoundingClientRect().top + window.scrollY - headerHeight - 16;
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    }

    window.addEventListener('click', (e) => {
      const dropdown = document.getElementById('header-dropdown');
      const button = document.getElementById('btn-dropdown');
      if (dropdown && button && !dropdown.contains(e.target) && !button.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });
  </script>
</body>
</html>
