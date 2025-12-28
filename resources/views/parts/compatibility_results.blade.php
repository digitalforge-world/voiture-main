@extends('layouts.app')

@section('title', 'Résultats de compatibilité')

@section('content')
<div class="page-header py-60">
    <div class="container">
        <div class="breadcrumb mb-20">
            <a href="{{ route('parts.index') }}">← Retour au catalogue</a>
        </div>
        <h1>Pièces compatibles</h1>
        <p>Résultats pour : <strong>{{ $search['marque'] }} {{ $search['modele'] }} ({{ $search['annee'] }})</strong></p>
    </div>
</div>

<section class="results-section py-60 bg-surface">
    <div class="container">
        @if($pieces->count() > 0)
            <div class="parts-grid">
                @foreach($pieces as $piece)
                    <div class="part-card">
                        <div class="part-img" style="background: rgba(255,255,255,0.05) url('https://source.unsplash.com/featured/?engine,part,{{ $piece->nom }}') no-repeat center / cover;">
                            <span class="badge-compat">100% Compatible</span>
                        </div>
                        <div class="part-info">
                            <span class="ref">Réf: {{ $piece->reference }}</span>
                            <h3>{{ $piece->nom }}</h3>
                            <div class="part-footer">
                                <span class="price">{{ number_format($piece->prix, 0, ',', ' ') }} XOF</span>
                                <form action="{{ route('parts.buy', $piece->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Acheter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-results-card text-center py-80 shadow-lg">
                <i class="icon-search-off mb-20" style="font-size: 4rem; opacity: 0.2"></i>
                <h2>Désolé, aucune pièce exacte trouvée</h2>
                <p>Nos techniciens peuvent vous aider à trouver une pièce équivalente.</p>
                <div class="mt-40">
                    <a href="#" class="btn btn-primary">Contacter un expert</a>
                    <a href="{{ route('parts.index') }}" class="btn btn-outline">Parcourir tout le catalogue</a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('styles')
<style>
    .badge-compat {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: var(--success);
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .no-results-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 80px 40px;
        border: 1px solid var(--border);
    }

    .parts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 25px;
    }

    .part-card {
        background: var(--bg-dark);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        overflow: hidden;
        position: relative;
    }

    .part-img { height: 160px; position: relative; }
    .part-info { padding: 20px; }
    .part-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; }
    .price { font-weight: 700; color: var(--primary); }
</style>
@endsection
