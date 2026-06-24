<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Connexion Chauffeur — AutoImport</title>
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
<body class="bg-slate-950 text-white font-sans min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
    <!-- Design accents -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-bl-[4rem]"></div>
    
    <div class="text-center mb-8">
      <div class="w-16 h-16 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-amber-500 shadow-lg shadow-amber-500/5">
        <i data-lucide="navigation" class="w-8 h-8"></i>
      </div>
      <h1 class="text-2xl font-extrabold tracking-tight">Espace Chauffeur</h1>
      <p class="text-slate-500 text-xs mt-1.5 uppercase tracking-widest font-semibold">Portail d'authentification sécurisé</p>
    </div>

    @if(session('error'))
    <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4 mb-5 flex items-center gap-3 text-rose-400 text-xs">
      <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
      <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4 mb-5 text-rose-400 text-xs space-y-1">
      @foreach($errors->all() as $error)
        <div class="flex items-center gap-2">
          <i data-lucide="x-circle" class="w-3.5 h-3.5 flex-shrink-0"></i>
          <span>{{ $error }}</span>
        </div>
      @endforeach
    </div>
    @endif

    <form action="{{ route('driver.login.submit') }}" method="POST" class="space-y-4">
      @csrf
      
      <div>
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
          <i data-lucide="user" class="w-3.5 h-3.5"></i> Identifiant Chauffeur
        </label>
        <input type="text" name="identifiant" required placeholder="Ex: CH-0001" value="{{ old('identifiant') }}"
          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500/50 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 outline-none transition duration-300">
      </div>

      <div>
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
          <i data-lucide="lock" class="w-3.5 h-3.5"></i> Mot de passe
        </label>
        <input type="password" name="mot_de_passe" required placeholder="••••••••"
          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500/50 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 outline-none transition duration-300">
      </div>

      <button type="submit" class="w-full py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-sm uppercase tracking-widest transition duration-300 hover:-translate-y-0.5 shadow-lg shadow-amber-500/10 flex items-center justify-center gap-2">
        <i data-lucide="log-in" class="w-4 h-4"></i>
        <span>Se connecter</span>
      </button>
    </form>

    <div class="mt-8 text-center border-t border-slate-800 pt-6">
      <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-white transition">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        <span>Retour au site principal</span>
      </a>
    </div>
  </div>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
