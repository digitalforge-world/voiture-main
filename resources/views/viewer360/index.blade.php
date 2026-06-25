{{-- resources/views/viewer360/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie Viewers 360°</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #000;
            color: #e8e8e8;
            min-height: 100vh;
        }

        /* ─── Nav ─── */
        nav {
            padding: 28px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .nav-brand {
            font-size: 13px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
        }
        .btn-new {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: #000;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-decoration: none;
            padding: 10px 22px;
            border-radius: 99px;
            transition: opacity .2s;
        }
        .btn-new:hover { opacity: 0.85; }
        .btn-new svg { width: 14px; height: 14px; fill: none; stroke: #000; stroke-width: 2.5; }

        /* ─── Hero titre ─── */
        .hero {
            padding: 64px 48px 40px;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1;
            color: #fff;
        }
        .hero p {
            font-size: 14px;
            color: rgba(255,255,255,0.35);
            margin-top: 12px;
        }

        /* ─── Grid ─── */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2px;
            padding: 0 2px 2px;
        }

        .car-card {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: #0a0a0a;
            cursor: pointer;
        }
        .car-card img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .6s cubic-bezier(.22,1,.36,1), opacity .3s;
            opacity: 0.9;
        }
        .car-card:hover img { transform: scale(1.04); opacity: 1; }

        .card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 60%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px;
            pointer-events: none;
        }
        .card-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 9px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 4px 10px;
            border-radius: 99px;
            margin-bottom: 10px;
            width: fit-content;
        }
        .card-badge svg { width: 10px; height: 10px; fill: none; stroke: currentColor; stroke-width: 2; }
        .card-name {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
            line-height: 1.1;
        }
        .card-frames {
            font-size: 11px;
            color: rgba(255,255,255,0.35);
            margin-top: 4px;
        }

        /* Hover CTA */
        .card-cta {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .3s;
        }
        .car-card:hover .card-cta { opacity: 1; }
        .cta-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 12px 24px;
            border-radius: 99px;
        }
        .cta-pill svg { width: 14px; height: 14px; fill: none; stroke: #fff; stroke-width: 2; }

        /* Placeholder si pas de thumbnail */
        .card-placeholder {
            width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d0d0d;
        }
        .card-placeholder svg { width: 40px; height: 40px; stroke: rgba(255,255,255,0.1); fill: none; }

        /* Admin delete */
        .delete-btn {
            position: absolute;
            top: 12px; right: 12px;
            z-index: 5;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.4);
            border-radius: 8px;
            padding: 7px 9px;
            cursor: pointer;
            transition: color .2s, border-color .2s;
            opacity: 0;
            pointer-events: none;
        }
        .car-card:hover .delete-btn {
            opacity: 1;
            pointer-events: auto;
        }
        .delete-btn:hover { color: #ff4d4d; border-color: rgba(255,77,77,0.4); }
        .delete-btn svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2; display: block; }

        /* Empty state */
        .empty {
            padding: 100px 48px;
            text-align: center;
        }
        .empty h2 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #fff;
            margin-bottom: 12px;
        }
        .empty p { color: rgba(255,255,255,0.3); font-size: 14px; margin-bottom: 32px; }

        @media (max-width: 768px) {
            nav { padding: 20px 20px; }
            .hero { padding: 40px 20px 30px; }
            .grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 480px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<nav>
    <span class="nav-brand">Viewer 360°</span>
    @auth
        @if(auth()->user()->is_admin)
            <a href="{{ route('viewer.create') }}" class="btn-new">
                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Ajouter un viewer
            </a>
        @endif
    @endauth
</nav>

<div class="hero">
    <h1>Galerie<br>360°</h1>
    <p>{{ $cars->total() }} véhicule{{ $cars->total() > 1 ? 's' : '' }} en vue interactive</p>
</div>

@if($cars->count() > 0)
    <div class="grid">
        @foreach ($cars as $car)
            <div class="car-card">
                <a href="{{ route('viewer.show', $car->slug) }}" style="display:block;height:100%">
                    @if($car->thumbnail)
                        <img src="{{ $car->thumbnail }}" alt="{{ $car->name }}" loading="lazy">
                    @else
                        <div class="card-placeholder">
                            <svg viewBox="0 0 24 24" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>
                        </div>
                    @endif

                    <div class="card-overlay">
                        <div class="card-badge">
                            <svg viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-9-9 9 9 0 0 1 9 9z"/><path d="M9 12l2 2 4-4"/></svg>
                            360°
                        </div>
                        <p class="card-name">{{ $car->name }}</p>
                        <p class="card-frames">{{ $car->frame_count }} frames</p>
                    </div>

                    <div class="card-cta">
                        <div class="cta-pill">
                            <svg viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-9-9 9 9 0 0 1 9 9z"/><path d="M15 12H9m3-3-3 3 3 3"/></svg>
                            Explorer
                        </div>
                    </div>
                </a>

                @auth
                    @if(auth()->user()->is_admin)
                        <form action="{{ route('viewer.destroy', $car->slug) }}" method="POST"
                              onsubmit="return confirm('Supprimer « {{ $car->name }} » et toutes ses frames ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" title="Supprimer">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($cars->hasPages())
        <div style="padding: 40px 48px; display: flex; justify-content: center; gap: 8px;">
            {{ $cars->links() }}
        </div>
    @endif
@else
    <div class="empty">
        <h2>Aucun viewer</h2>
        <p>Commencez par créer votre premier viewer 360°.</p>
        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('viewer.create') }}" class="btn-new" style="display:inline-flex">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Créer le premier viewer
                </a>
            @endif
        @endauth
    </div>
@endif

</body>
</html>
