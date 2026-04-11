@extends('layouts.admin')

@section('title', 'Configuration - ' . ($settings['general']->where('cle', 'site_name')->first()->valeur ?? 'AutoImport Hub'))

@section('content')
<div class="space-y-6">
 <!-- Header Area -->
 <form action="{{ route('admin.settings.update-bulk') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
   <div>
    <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Réglages Système</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Identité visuelle, finances et maintenance</p>
   </div>
   <div class="flex items-center gap-4">
    <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-amber-500 rounded-lg hover:bg-amber-600 shadow-sm">
     <i data-lucide="save" class="w-4 h-4"></i>
     <span>Enregistrer tout le système</span>
    </button>
   </div>
  </div>

  @if(session('success'))
   <div class="p-4 mb-6 text-sm font-medium text-emerald-800 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
    <i data-lucide="check-circle" class="w-4 h-4 inline-block mr-2"></i> {{ session('success') }}
   </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
   <!-- Navigation Sidebar -->
   <div class="lg:col-span-1 space-y-2">
    <div class="sticky top-24 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-2 shadow-sm space-y-1">
     <a href="#general" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium bg-slate-50 text-amber-600 dark:bg-slate-800/50 dark:text-amber-500 transition">
      <i data-lucide="layout" class="w-4 h-4"></i> Identité Globale
     </a>
     <a href="#branding" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition">
      <i data-lucide="palette" class="w-4 h-4"></i> Branding & Logos
     </a>
     <a href="#contact" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition">
      <i data-lucide="phone" class="w-4 h-4"></i> Coordonnées & Support
     </a>
     <a href="#finance" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition">
      <i data-lucide="banknote" class="w-4 h-4"></i> Paramètres Financiers
     </a>
    </div>
   </div>

   <!-- Settings Sections -->
   <div class="lg:col-span-3 space-y-6">
    
    <!-- General Identity -->
    <section id="general" class="scroll-mt-24 p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
     <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
      <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg text-amber-600 dark:text-amber-500">
       <i data-lucide="layout" class="w-5 h-5"></i>
      </div>
      <div>
       <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Identité du Portail</h2>
       <p class="text-sm text-slate-500">Configuration des méta-données globales</p>
      </div>
     </div>

     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach($settings['general'] ?? [] as $setting)
      <div>
       <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ $setting->titre }}</label>
       <input type="text" name="settings[{{ $setting->cle }}]" value="{{ $setting->valeur }}"
        class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
       <p class="text-xs text-slate-500 mt-1.5">{{ $setting->description }}</p>
      </div>
      @endforeach
     </div>
    </section>

    <!-- Branding & Visual Assets -->
    <section id="branding" class="scroll-mt-24 p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
     <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
      <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg text-amber-600 dark:text-amber-500">
       <i data-lucide="palette" class="w-5 h-5"></i>
      </div>
      <div>
       <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Branding & Assets</h2>
       <p class="text-sm text-slate-500">Logos, Favicons et identité visuelle</p>
      </div>
     </div>

     <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      @foreach($settings['branding'] ?? [] as $setting)
      <div class="space-y-3">
       <div class="flex items-center justify-between">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $setting->titre }}</label>
        @if(str_contains($setting->cle, 'logo') || str_contains($setting->cle, 'favicon'))
         <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-xs font-medium text-slate-500">
          {{ str_contains($setting->cle, 'logo') ? '512x512' : '64x64' }}
         </span>
        @endif
       </div>
       
       @if($setting->cle === 'site_display_mode')
        <select name="settings[{{ $setting->cle }}]" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
         <option value="logo" {{ $setting->valeur === 'logo' ? 'selected' : '' }}>Logo Uniquement</option>
         <option value="text" {{ $setting->valeur === 'text' ? 'selected' : '' }}>Texte Uniquement</option>
         <option value="both" {{ $setting->valeur === 'both' ? 'selected' : '' }}>Logo + Texte</option>
        </select>
       @elseif(str_contains($setting->cle, 'logo') || str_contains($setting->cle, 'favicon'))
        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg space-y-4">
         <div class="w-24 h-24 mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center justify-center p-2">
          <img id="preview_{{ $setting->cle }}" src="{{ $setting->valeur }}" alt="Preview" class="max-w-full max-h-full object-contain">
         </div>
         <div class="text-center">
          <div id="filename_{{ $setting->cle }}" class="text-xs text-slate-500 truncate mb-3">{{ basename($setting->valeur) }}</div>
          <input type="file" name="files[{{ $setting->cle }}]" id="file_{{ $setting->cle }}" class="hidden" accept="image/*" onchange="handleImagePreview(this, 'preview_{{ $setting->cle }}', 'filename_{{ $setting->cle }}')">
          <label for="file_{{ $setting->cle }}" class="cursor-pointer inline-flex items-center justify-center px-4 py-2 border border-slate-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-lg text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition">
           Changer l'image
          </label>
         </div>
         <p class="text-xs text-center text-slate-500">{{ $setting->description }}</p>
        </div>
       @else
        <input type="text" name="settings[{{ $setting->cle }}]" value="{{ $setting->valeur }}"
         class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
       @endif
      </div>
      @endforeach
     </div>
    </section>

    <!-- Contact & Support -->
    <section id="contact" class="scroll-mt-24 p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
     <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
      <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg text-amber-600 dark:text-amber-500">
       <i data-lucide="phone" class="w-5 h-5"></i>
      </div>
      <div>
       <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Support & Relation</h2>
       <p class="text-sm text-slate-500">Canaux de communication officiels</p>
      </div>
     </div>

     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach($settings['contact'] ?? [] as $setting)
      <div>
       <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ $setting->titre }}</label>
       <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
         <i data-lucide="{{ str_contains($setting->cle, 'email') ? 'mail' : 'phone' }}" class="w-4 h-4 text-slate-400"></i>
        </div>
        <input type="text" name="settings[{{ $setting->cle }}]" value="{{ $setting->valeur }}"
         class="w-full pl-10 pr-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 outline-none transition">
       </div>
      </div>
      @endforeach
     </div>
    </section>

    <!-- Finance Rules -->
    <section id="finance" class="scroll-mt-24 p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
     <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
      <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg text-amber-600 dark:text-amber-500">
       <i data-lucide="banknote" class="w-5 h-5"></i>
      </div>
      <div>
       <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Logistique Financière</h2>
       <p class="text-sm text-slate-500">Règles métier pour acomptes et transactions</p>
      </div>
     </div>

     <div class="space-y-4">
      @foreach($settings['finance'] ?? [] as $setting)
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700">
       <div class="flex-1">
        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $setting->titre }}</div>
        <div class="text-xs text-slate-500 mt-0.5">{{ $setting->description }}</div>
       </div>
       <div class="relative w-32 shrink-0">
        <input type="number" name="settings[{{ $setting->cle }}]" value="{{ $setting->valeur }}"
         class="w-full pl-3 pr-8 py-2 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-sm text-right focus:ring-1 focus:ring-amber-500 outline-none transition">
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
         <span class="text-sm text-slate-500">{{ str_contains($setting->cle, 'percent') || str_contains($setting->titre, '%') ? '%' : '' }}</span>
        </div>
       </div>
      </div>
      @endforeach
     </div>
    </section>
   </div>
  </div>
 </form>
</div>

@section('scripts')
<script>
 function handleImagePreview(input, previewId, filenameId) {
  if (input.files && input.files[0]) {
   const reader = new FileReader();
   const filename = input.files[0].name;
   
   reader.onload = function(e) {
    document.getElementById(previewId).src = e.target.result;
    document.getElementById(filenameId).textContent = filename;
   };
   
   reader.readAsDataURL(input.files[0]);
  }
 }

 // Sidebar active state highlighting
 document.addEventListener('DOMContentLoaded', () => {
  const links = document.querySelectorAll('.sticky a');
  const sections = document.querySelectorAll('section');

  const observerOptions = {
   root: null,
   rootMargin: '0px',
   threshold: 0.5
  };

  const observer = new IntersectionObserver((entries) => {
   entries.forEach(entry => {
    if (entry.isIntersecting) {
     links.forEach(link => {
      link.classList.remove('bg-slate-50', 'text-amber-600', 'dark:bg-slate-800/50', 'dark:text-amber-500');
      link.classList.add('text-slate-600', 'dark:text-slate-400');
      if (link.getAttribute('href') === `#${entry.target.id}`) {
       link.classList.add('bg-slate-50', 'text-amber-600', 'dark:bg-slate-800/50', 'dark:text-amber-500');
       link.classList.remove('text-slate-600', 'dark:text-slate-400');
      }
     });
    }
   });
  }, observerOptions);

  sections.forEach(section => observer.observe(section));
 });
</script>
@endsection
@endsection
