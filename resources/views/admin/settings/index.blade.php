@extends('layouts.admin')

@section('title', 'Configuration - ' . ($settings['general']->where('cle', 'site_name')->first()->valeur ?? 'AutoImport Hub'))

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <form action="{{ route('admin.settings.update-bulk') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between mb-8 px-2">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase italic underline decoration-amber-500 decoration-4 underline-offset-8 transition-colors">Réglages Système</h1>
                <p class="text-slate-500 dark:text-slate-400 font-bold mt-2 uppercase tracking-widest text-[10px] italic transition-colors">Identité visuelle, finances et maintenance</p>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="flex items-center gap-3 px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-950 transition bg-amber-500 rounded-2xl hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-950 hover:scale-105 duration-300 shadow-xl shadow-amber-900/20 group transition-colors">
                    <i data-lucide="save" class="w-4 h-4 group-hover:rotate-12 transition"></i> Enregistrer tout le système
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-[2rem] text-emerald-500 text-xs font-black uppercase tracking-widest mb-8 animate-in fade-in slide-in-from-top duration-500">
                <i data-lucide="check-circle" class="w-4 h-4 inline-block mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Navigation Sidebar -->
            <div class="lg:col-span-3 space-y-4">
                <div class="sticky top-28 space-y-2 p-6 bg-white dark:bg-slate-900/30 border border-slate-100 dark:border-slate-900 rounded-[2.5rem] backdrop-blur-sm shadow-sm dark:shadow-none transition-colors">
                    <a href="#general" class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-widest border border-slate-100 dark:border-white/5 hover:border-amber-500/50 transition duration-300 transition-colors">
                        <i data-lucide="layout" class="w-4 h-4 text-amber-500"></i> Identité Generale
                    </a>
                    <a href="#branding" class="flex items-center gap-3 p-4 rounded-2xl text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest border border-transparent hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white transition duration-300 transition-colors">
                        <i data-lucide="palette" class="w-4 h-4"></i> Branding & Logos
                    </a>
                    <a href="#contact" class="flex items-center gap-3 p-4 rounded-2xl text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest border border-transparent hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white transition duration-300 transition-colors">
                        <i data-lucide="phone" class="w-4 h-4"></i> Coordonnées & Support
                    </a>
                    <a href="#finance" class="flex items-center gap-3 p-4 rounded-2xl text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest border border-transparent hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white transition duration-300 transition-colors">
                        <i data-lucide="banknote" class="w-4 h-4"></i> Paramètres Financiers
                    </a>
                </div>
            </div>

            <!-- Settings Sections -->
            <div class="lg:col-span-9 space-y-12">
                
                <!-- General Identity -->
                <section id="general" class="p-10 bg-white dark:bg-slate-950/50 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-lg dark:shadow-2xl transition-colors space-y-10">
                    <div class="flex items-center gap-4 border-b border-slate-50 dark:border-white/5 pb-8 transition-colors">
                        <div class="p-4 bg-amber-500/10 rounded-2xl text-amber-500 transition-colors">
                            <i data-lucide="layout" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter transition-colors">Identité du Portail</h2>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-[0.2em] mt-1 transition-colors">Configuration des meta-données globales</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        @foreach($settings['general'] ?? [] as $setting)
                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 italic transition-colors">{{ $setting->titre }}</label>
                            <div class="relative group">
                                <input type="text" name="settings[{{ $setting->cle }}]" value="{{ $setting->valeur }}" 
                                    class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-medium transition-colors">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition duration-500">
                                    <i data-lucide="edit-3" class="w-4 h-4 text-slate-300 dark:text-slate-700 transition-colors"></i>
                                </div>
                            </div>
                            <p class="text-[9px] text-slate-400 dark:text-slate-600 font-medium italic ml-1 transition-colors">{{ $setting->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- Branding & Visual Assets -->
                <section id="branding" class="p-10 bg-white dark:bg-slate-950/50 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tr-xl rounded-bl-xl shadow-lg dark:shadow-2xl transition-colors space-y-10">
                    <div class="flex items-center gap-4 border-b border-slate-50 dark:border-white/5 pb-8 transition-colors">
                        <div class="p-4 bg-amber-500/10 rounded-2xl text-amber-500 transition-colors">
                            <i data-lucide="palette" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter transition-colors">Branding & Assets</h2>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-[0.2em] mt-1 transition-colors">Logos, Favicons et identité visuelle</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        @foreach($settings['branding'] ?? [] as $setting)
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 ml-1 italic transition-colors">{{ $setting->titre }}</label>
                                @if(str_contains($setting->cle, 'logo') || str_contains($setting->cle, 'favicon'))
                                    <span class="px-2 py-0.5 bg-amber-500/10 border border-amber-500/20 rounded-md text-[8px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-tighter transition-colors">
                                        {{ str_contains($setting->cle, 'logo') ? '512 x 512' : '64 x 64' }} PX
                                    </span>
                                @endif
                            </div>
                            
                            @if($setting->cle === 'site_display_mode')
                                <div class="relative">
                                    <select name="settings[{{ $setting->cle }}]" class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 appearance-none transition font-black uppercase italic tracking-widest transition-colors">
                                        <option value="logo" class="bg-white dark:bg-slate-950" {{ $setting->valeur === 'logo' ? 'selected' : '' }}>Logo Uniquement</option>
                                        <option value="text" class="bg-white dark:bg-slate-950" {{ $setting->valeur === 'text' ? 'selected' : '' }}>Texte Uniquement</option>
                                        <option value="both" class="bg-white dark:bg-slate-950" {{ $setting->valeur === 'both' ? 'selected' : '' }}>Logo + Texte</option>
                                    </select>
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 dark:text-slate-500 transition-colors">
                                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            @elseif(str_contains($setting->cle, 'logo') || str_contains($setting->cle, 'favicon'))
                                <div class="group relative overflow-hidden bg-slate-50/50 dark:bg-slate-900/50 border border-slate-100 dark:border-white/5 rounded-[2.5rem] p-10 transition duration-500 hover:border-amber-500/30 transition-colors">
                                    <!-- Blueprint Background Effect -->
                                    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
                                    <script>
                                        if (document.documentElement.classList.contains('dark')) {
                                            document.currentScript.previousElementSibling.style.backgroundImage = 'radial-gradient(#fff 1px, transparent 1px)';
                                        }
                                    </script>
                                    
                                    <div class="relative flex flex-col items-center gap-10">
                                        <!-- Styled Preview Container -->
                                        <div class="relative group/preview">
                                            <div class="absolute -inset-4 bg-amber-500/5 rounded-full blur-2xl opacity-0 group-hover/preview:opacity-100 transition duration-700"></div>
                                            <div class="w-40 h-40 bg-white dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-white/10 flex items-center justify-center overflow-hidden shadow-lg dark:shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] relative z-10 group-hover:border-amber-500/50 transition duration-500 transition-colors">
                                                <img id="preview_{{ $setting->cle }}" src="{{ $setting->valeur }}" alt="Preview" class="max-w-[70%] max-h-[70%] object-contain group-hover:scale-110 transition duration-700">
                                                
                                                <!-- Guidelines overlay -->
                                                <div class="absolute inset-4 border border-slate-100 dark:border-white/5 pointer-events-none rounded-2xl transition-colors"></div>
                                                <div class="absolute top-1/2 left-0 w-full h-[1px] bg-slate-100 dark:bg-white/5 pointer-events-none transition-colors"></div>
                                                <div class="absolute top-0 left-1/2 w-[1px] h-full bg-slate-100 dark:bg-white/5 pointer-events-none transition-colors"></div>
                                            </div>
                                        </div>

                                        <div class="w-full space-y-4">
                                            <div id="filename_{{ $setting->cle }}" class="text-[9px] text-center text-slate-500 dark:text-slate-500 font-mono italic truncate bg-slate-100 dark:bg-slate-950/80 px-4 py-2 rounded-xl border border-slate-200 dark:border-white/5 transition-colors">
                                                {{ basename($setting->valeur) }}
                                            </div>
                                            
                                            <div class="flex flex-col gap-3">
                                                <input type="file" name="files[{{ $setting->cle }}]" 
                                                    onchange="handleImagePreview(this, 'preview_{{ $setting->cle }}', 'filename_{{ $setting->cle }}')"
                                                    class="block w-full text-[10px] text-slate-400 dark:text-slate-500
                                                        file:mr-4 file:py-3 file:px-8
                                                        file:rounded-2xl file:border-0
                                                        file:text-[10px] file:font-black
                                                        file:bg-slate-100 dark:bg-white/5 file:text-slate-900 dark:file:text-white
                                                        hover:file:bg-amber-500 hover:file:text-slate-950
                                                        file:transition file:duration-300
                                                        file:uppercase file:tracking-widest cursor-pointer transition-colors" 
                                                    accept="image/*">
                                                
                                                <div class="flex items-center justify-center gap-2 text-[9px] font-bold text-slate-400 dark:text-slate-600 uppercase tracking-widest transition-colors">
                                                    <i data-lucide="info" class="w-3 h-3"></i>
                                                    {{ $setting->description }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="relative group">
                                    <input type="text" name="settings[{{ $setting->cle }}]" value="{{ $setting->valeur }}" 
                                        class="w-full py-5 px-8 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-white/5 rounded-2xl text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition shadow-inner font-medium transition-colors">
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- Contact & Support -->
                <section id="contact" class="p-10 bg-white dark:bg-slate-950/50 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tl-xl rounded-br-xl shadow-lg dark:shadow-2xl transition-colors space-y-10">
                    <div class="flex items-center gap-4 border-b border-slate-50 dark:border-white/5 pb-8 transition-colors">
                        <div class="p-4 bg-amber-500/10 rounded-2xl text-amber-500 transition-colors">
                            <i data-lucide="phone" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter transition-colors">Support & Relation</h2>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-[0.2em] mt-1 transition-colors">Canaux de communication officiels</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        @foreach($settings['contact'] ?? [] as $setting)
                        <div class="space-y-3 p-8 bg-slate-50 dark:bg-slate-900/30 rounded-[3rem] border border-slate-100 dark:border-white/5 group hover:bg-white dark:hover:bg-slate-900 transition duration-500 transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-600 italic group-hover:text-amber-500 transition transition-colors">{{ $setting->titre }}</label>
                                <i data-lucide="{{ str_contains($setting->cle, 'email') ? 'mail' : 'phone' }}" class="w-4 h-4 text-slate-200 dark:text-slate-800 transition-colors"></i>
                            </div>
                            <input type="text" name="settings[{{ $setting->cle }}]" value="{{ $setting->valeur }}" 
                                class="w-full py-4 px-6 bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 text-slate-900 dark:text-white text-sm focus:ring-1 focus:ring-amber-500 transition font-black tracking-wide transition-colors">
                        </div>
                        @endforeach
                    </div>
                </section>

                <!-- Finance Rules -->
                <section id="finance" class="p-10 bg-white dark:bg-slate-950/50 border border-slate-100 dark:border-slate-900 rounded-[4rem] rounded-tr-xl rounded-bl-xl shadow-lg dark:shadow-2xl transition-colors space-y-10">
                    <div class="flex items-center gap-4 border-b border-slate-50 dark:border-white/5 pb-8 transition-colors">
                        <div class="p-4 bg-amber-500/10 rounded-2xl text-amber-500 transition-colors">
                            <i data-lucide="banknote" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter transition-colors">Logistique Financière</h2>
                            <p class="text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-[0.2em] mt-1 transition-colors">Règles métier pour acomptes et transactions</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach($settings['finance'] ?? [] as $setting)
                        <div class="flex items-center justify-between p-8 bg-slate-50 dark:bg-slate-900/50 rounded-[3rem] border border-slate-100 dark:border-white/5 hover:border-amber-500/20 transition duration-500 transition-colors">
                            <div class="max-w-md">
                                <div class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-wider italic transition-colors">{{ $setting->titre }}</div>
                                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest mt-1 italic leading-relaxed transition-colors">{{ $setting->description }}</div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="relative">
                                    <input type="number" name="settings[{{ $setting->cle }}]" value="{{ $setting->valeur }}" 
                                        class="w-32 py-4 px-6 bg-white dark:bg-slate-950 border border-slate-100 dark:border-white/5 text-amber-600 dark:text-amber-500 text-center font-black text-xl shadow-2xl transition-colors">
                                    <span class="absolute -right-8 top-1/2 -translate-y-1/2 text-xs font-black text-slate-300 dark:text-slate-700 italic uppercase transition-colors">
                                        {{ str_contains($setting->cle, 'percent') || str_contains($setting->titre, '%') ? '%' : 'UNI' }}
                                    </span>
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

<style>
    html { scroll-behavior: smooth; }
    input[type="number"]::-webkit-inner-spin-button, 
    input[type="number"]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>
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
</script>
@endsection

@endsection
