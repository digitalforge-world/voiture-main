@extends('layouts.app')

@section('title', 'Commande Confirmée')

@section('content')
<div class="min-h-screen pt-24 pb-12 bg-slate-950 flex items-center justify-center">
    <div class="container px-4 mx-auto max-w-2xl text-center">
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden">
            {{-- Confetti effect background (simplified) --}}
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute top-10 left-10 w-32 h-32 bg-amber-500 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 right-10 w-32 h-32 bg-green-500 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <div class="w-20 h-20 bg-green-500/10 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="check-circle" class="w-10 h-10"></i>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Commande Confirmée !</h1>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">
                    Votre demande a bien été enregistrée. Voici votre numéro de suivi unique pour consulter l'avancement de votre commande.
                </p>

                <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 mb-8 transform hover:scale-105 transition-transform duration-300">
                    <p class="text-sm text-slate-500 uppercase tracking-widest mb-2 font-semibold">Votre Numéro de Tracking</p>
                    <div class="flex items-center justify-center gap-3">
                        <span class="text-3xl md:text-4xl font-black text-amber-500 font-mono tracking-wider" id="tracking-code">{{ session('tracking_number') }}</span>
                        <button onclick="copyToClipboard()" class="p-2 hover:bg-slate-800 rounded-lg transition-colors text-slate-400 hover:text-white" title="Copier">
                            <i data-lucide="copy" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <form action="{{ route('tracking.search') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="tracking_number" value="{{ session('tracking_number') }}">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            Suivre ma commande
                        </button>
                    </form>
                    
                    <a href="{{ route('home') }}" class="px-8 py-3 text-slate-400 hover:text-white font-semibold transition-colors">
                        Retour à l'accueil
                    </a>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-800/50 text-sm text-slate-500">
                    <p>⚠️ Important : Conservez ce numéro précieusement. Il est le seul moyen d'accéder aux détails de votre commande.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard() {
        const code = document.getElementById('tracking-code').innerText;
        navigator.clipboard.writeText(code).then(() => {
            alert('Numéro de tracking copié !');
        });
    }
</script>
@endsection
