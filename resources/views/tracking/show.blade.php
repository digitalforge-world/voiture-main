@extends('layouts.app')

@section('title', 'Détails de la Commande ' . $tracking)

@section('content')
<div class="min-h-screen pb-12 bg-white dark:bg-slate-950 transition-colors duration-500">
    <div class="container px-4 mx-auto max-w-4xl">
        <div class="mb-8">
            <a href="{{ route('tracking.index') }}" class="inline-flex items-center text-sm text-slate-500 dark:text-slate-400 hover:text-amber-500 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Retour à la recherche
            </a>
        </div>

        @include('tracking.partials.details')
    </div>
</div>
@endsection
