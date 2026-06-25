{{-- resources/views/viewer360/show.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $car->name }} — Viewer 360°</title>
    <meta name="description" content="{{ $car->description ?? 'Explorez le ' . $car->name . ' en vue 360° interactive.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { width: 100%; height: 100%; overflow: hidden; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #000;
            color: #fff;
            cursor: none;
        }

        /* ─── VIEWER FULLSCREEN ─── */
        #viewer {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: grab;
            user-select: none;
        }
        #viewer:active { cursor: grabbing; }

        /* Frames empilées */
        .frame-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            pointer-events: none;
            will-change: opacity;
        }
        .frame-img.active { opacity: 1; }

        /* ─── OVERLAY LOADING ─── */
        #overlay-load {
            position: fixed;
            inset: 0;
            background: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            z-index: 100;
            transition: opacity .6s ease;
        }
        #overlay-load.fade-out { opacity: 0; pointer-events: none; }

        .load-logo {
            font-size: 11px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 10px;
        }
        .load-name {
            font-size: clamp(1.8rem, 5vw, 3.5rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            color: #fff;
        }
        .load-bar-track {
            width: 200px;
            height: 1px;
            background: rgba(255,255,255,0.12);
            margin-top: 12px;
        }
        .load-bar-fill {
            height: 100%;
            width: 0%;
            background: #fff;
            transition: width .2s linear;
        }
        .load-pct {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            letter-spacing: 0.1em;
            margin-top: 8px;
        }

        /* ─── HEADER OVERLAY ─── */
        #header {
            position: fixed;
            top: 0; left: 0; right: 0;
            padding: 32px 48px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            z-index: 10;
            pointer-events: none;
            background: linear-gradient(to bottom, rgba(0,0,0,0.55) 0%, transparent 100%);
        }

        .car-name {
            font-size: clamp(1.4rem, 3.5vw, 2.8rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 1;
            color: #fff;
        }
        .car-desc {
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            margin-top: 6px;
            font-weight: 400;
            letter-spacing: 0.02em;
            max-width: 340px;
            line-height: 1.5;
        }

        .frame-counter {
            font-size: 11px;
            color: rgba(255,255,255,0.25);
            letter-spacing: 0.12em;
            font-variant-numeric: tabular-nums;
            margin-top: 4px;
        }

        /* ─── BOTTOM CONTROLS ─── */
        #controls {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            padding: 0 48px 36px;
            z-index: 10;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
            pointer-events: auto;
        }

        /* Scrub bar — style XPENG: thin, subtle */
        #scrub-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        #scrub {
            flex: 1;
            -webkit-appearance: none;
            height: 1px;
            background: rgba(255,255,255,0.2);
            outline: none;
            cursor: pointer;
            transition: height .2s;
        }
        #scrub:hover { height: 2px; }
        #scrub::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 12px; height: 12px;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.15);
        }
        .scrub-time {
            font-size: 10px;
            color: rgba(255,255,255,0.3);
            letter-spacing: 0.08em;
            min-width: 48px;
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        /* Boutons contrôles */
        .btn-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ctrl-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.75);
            font-family: inherit;
            font-size: 11px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 8px 18px;
            border-radius: 99px;
            cursor: pointer;
            transition: background .2s, border-color .2s, color .2s;
            backdrop-filter: blur(8px);
        }
        .ctrl-btn:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.3);
            color: #fff;
        }
        .ctrl-btn.active {
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.5);
            color: #fff;
        }
        .ctrl-btn svg {
            width: 14px; height: 14px;
            fill: none; stroke: currentColor; stroke-width: 2;
            flex-shrink: 0;
        }

        /* Speed pill */
        .speed-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            padding: 6px 14px;
            border-radius: 99px;
            backdrop-filter: blur(8px);
        }
        .speed-pill span {
            font-size: 10px;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        input[type="range"].speed-range {
            -webkit-appearance: none;
            width: 64px; height: 1px;
            background: rgba(255,255,255,0.2);
            outline: none;
        }
        input[type="range"].speed-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: rgba(255,255,255,0.7);
            cursor: pointer;
        }

        /* Spacer */
        .flex-1 { flex: 1; }

        /* ─── CUSTOM CURSOR ─── */
        #cursor {
            position: fixed;
            width: 40px; height: 40px;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 50%;
            pointer-events: none;
            z-index: 999;
            transform: translate(-50%, -50%);
            transition: width .25s, height .25s, opacity .25s, border-color .2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #cursor.drag {
            width: 60px; height: 60px;
            border-color: rgba(255,255,255,0.8);
        }
        #cursor svg {
            width: 14px; height: 14px;
            fill: none; stroke: rgba(255,255,255,0.6);
            stroke-width: 2;
        }
        #cursor.drag svg { stroke: #fff; }

        /* Hint */
        #hint {
            position: fixed;
            bottom: 130px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            pointer-events: none;
            transition: opacity 1.5s;
            z-index: 20;
        }
        #hint.hidden { opacity: 0; }

        @media (max-width: 768px) {
            #header { padding: 20px 24px; }
            #controls { padding: 0 24px 24px; }
            #cursor { display: none; }
            body { cursor: auto; }
        }
    </style>
</head>
<body>

{{-- Curseur personnalisé --}}
<div id="cursor">
    <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
</div>

{{-- Overlay de chargement --}}
<div id="overlay-load">
    <p class="load-logo">Viewer 360°</p>
    <p class="load-name">{{ $car->name }}</p>
    <div class="load-bar-track">
        <div class="load-bar-fill" id="load-bar"></div>
    </div>
    <p class="load-pct" id="load-pct">0%</p>
</div>

{{-- Viewer fullscreen --}}
<div id="viewer">
    @foreach ($frames as $index => $url)
        <img
            class="frame-img {{ $index === 0 ? 'active' : '' }}"
            data-src="{{ $url }}"
            src="{{ $index === 0 ? $url : '' }}"
            alt="Frame {{ $index + 1 }}"
            draggable="false"
        >
    @endforeach
</div>

{{-- Hint drag --}}
<div id="hint">← Glisser pour faire pivoter →</div>

{{-- Header --}}
<header id="header">
    <div>
        <h1 class="car-name">{{ $car->name }}</h1>
        @if($car->description)
            <p class="car-desc">{{ $car->description }}</p>
        @endif
        <p class="frame-counter" id="frame-counter">001 / {{ str_pad(count($frames), 3, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div style="pointer-events:auto">
        <a href="{{ route('viewer.index') }}" style="
            display:inline-flex;align-items:center;gap:6px;
            font-size:11px;letter-spacing:0.08em;text-transform:uppercase;
            color:rgba(255,255,255,0.4);text-decoration:none;
            border:1px solid rgba(255,255,255,0.1);padding:8px 16px;border-radius:99px;
            transition:color .2s,border-color .2s;backdrop-filter:blur(8px)
        " onmouseover="this.style.color='#fff';this.style.borderColor='rgba(255,255,255,0.3)'"
           onmouseout="this.style.color='rgba(255,255,255,0.4)';this.style.borderColor='rgba(255,255,255,0.1)'">
            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Galerie
        </a>
    </div>
</header>

{{-- Contrôles bottom --}}
<div id="controls">
    <div id="scrub-wrap">
        <input type="range" id="scrub" min="0" max="{{ count($frames) - 1 }}" value="0" step="1">
        <span class="scrub-time" id="scrub-label">{{ str_pad(count($frames), 3, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="btn-row">
        <button class="ctrl-btn active" id="btn-play" onclick="togglePlay()">
            <svg id="icon-play" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
            Auto
        </button>

        <button class="ctrl-btn" onclick="prevFrame()">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </button>

        <button class="ctrl-btn" onclick="nextFrame()">
            <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </button>

        <button class="ctrl-btn" id="btn-dir" onclick="toggleDirection()">
            <svg viewBox="0 0 24 24"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
            Horaire
        </button>

        <div class="flex-1"></div>

        <div class="speed-pill">
            <span>Vitesse</span>
            <input type="range" class="speed-range" min="1" max="10" value="5" id="speed-range" oninput="updateSpeed(this.value)">
        </div>
    </div>
</div>

{{-- Données JSON PHP → JS --}}
<script>
const FRAMES   = @json($frames);
const TOTAL    = FRAMES.length;

let current      = 0;
let playing      = true;
let direction    = 1;
let interval     = null;
let speedMs      = 70;
let loaded       = 0;
let isDragging   = false;
let dragStartX   = 0;
let dragStartFrame = 0;

const imgs       = document.querySelectorAll('.frame-img');
const scrub      = document.getElementById('scrub');
const scrubLabel = document.getElementById('scrub-label');
const counter    = document.getElementById('frame-counter');
const loadBar    = document.getElementById('load-bar');
const loadPct    = document.getElementById('load-pct');
const overlayLoad = document.getElementById('overlay-load');

// ─── Curseur custom ───────────────────────────────
const cursor = document.getElementById('cursor');
document.addEventListener('mousemove', e => {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top  = e.clientY + 'px';
});

// ─── 1. Chargement progressif ────────────────────
function preloadFrames() {
    imgs.forEach((img, i) => {
        if (i === 0) { onLoaded(); return; }
        const tmp = new Image();
        tmp.onload = () => { img.src = FRAMES[i]; onLoaded(); };
        tmp.onerror = onLoaded;
        tmp.src = FRAMES[i];
    });
}

function onLoaded() {
    loaded++;
    const pct = Math.round(loaded / TOTAL * 100);
    loadBar.style.width = pct + '%';
    loadPct.textContent = pct + '%';
    if (loaded >= TOTAL) {
        setTimeout(() => {
            overlayLoad.classList.add('fade-out');
            setTimeout(() => overlayLoad.remove(), 700);
            startPlay();
            setTimeout(() => document.getElementById('hint')?.classList.add('hidden'), 4000);
        }, 400);
    }
}

// ─── 2. Afficher une frame ───────────────────────
function showFrame(index) {
    current = ((index % TOTAL) + TOTAL) % TOTAL;
    imgs.forEach(img => img.classList.remove('active'));
    imgs[current].classList.add('active');
    const pad = String(current + 1).padStart(3, '0');
    const tot = String(TOTAL).padStart(3, '0');
    scrub.value          = current;
    scrubLabel.textContent = tot;
    counter.textContent    = `${pad} / ${tot}`;
}

// ─── 3. Lecture auto ─────────────────────────────
function startPlay() {
    if (interval) clearInterval(interval);
    interval = setInterval(() => showFrame(current + direction), speedMs);
}
function stopPlay() {
    if (interval) { clearInterval(interval); interval = null; }
}
function togglePlay() {
    playing = !playing;
    const btn = document.getElementById('btn-play');
    const ico = document.getElementById('icon-play');
    if (playing) {
        btn.classList.add('active');
        ico.innerHTML = '<rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>';
        startPlay();
    } else {
        btn.classList.remove('active');
        ico.innerHTML = '<polygon points="5,3 19,12 5,21"/>';
        stopPlay();
    }
}

// ─── 4. Contrôles manuels ────────────────────────
function nextFrame() {
    stopPlay(); playing = false;
    document.getElementById('btn-play').classList.remove('active');
    document.getElementById('icon-play').innerHTML = '<polygon points="5,3 19,12 5,21"/>';
    showFrame(current + 1);
}
function prevFrame() {
    stopPlay(); playing = false;
    document.getElementById('btn-play').classList.remove('active');
    document.getElementById('icon-play').innerHTML = '<polygon points="5,3 19,12 5,21"/>';
    showFrame(current - 1);
}
function toggleDirection() {
    direction *= -1;
    document.getElementById('btn-dir').lastChild.textContent = direction === 1 ? ' Horaire' : ' Anti-horaire';
    if (playing) startPlay();
}
function updateSpeed(val) {
    speedMs = Math.round(180 - val * 16);
    if (playing) startPlay();
}

// ─── 5. Drag souris ──────────────────────────────
const viewer = document.getElementById('viewer');
viewer.addEventListener('mousedown', e => {
    isDragging     = true;
    dragStartX     = e.clientX;
    dragStartFrame = current;
    cursor.classList.add('drag');
    stopPlay();
});
window.addEventListener('mouseup', () => {
    if (!isDragging) return;
    isDragging = false;
    cursor.classList.remove('drag');
    if (playing) startPlay();
});
window.addEventListener('mousemove', e => {
    if (!isDragging) return;
    const delta = e.clientX - dragStartX;
    const step  = Math.round(delta / 9) * direction;
    showFrame(dragStartFrame - step);
});

// ─── 6. Touch (mobile) ───────────────────────────
viewer.addEventListener('touchstart', e => {
    isDragging     = true;
    dragStartX     = e.touches[0].clientX;
    dragStartFrame = current;
    stopPlay();
}, { passive: true });
window.addEventListener('touchend', () => {
    if (!isDragging) return;
    isDragging = false;
    if (playing) startPlay();
});
window.addEventListener('touchmove', e => {
    if (!isDragging) return;
    const delta = e.touches[0].clientX - dragStartX;
    const step  = Math.round(delta / 8) * direction;
    showFrame(dragStartFrame - step);
}, { passive: true });

// ─── 7. Scrub ────────────────────────────────────
scrub.addEventListener('input', () => {
    stopPlay(); playing = false;
    document.getElementById('btn-play').classList.remove('active');
    document.getElementById('icon-play').innerHTML = '<polygon points="5,3 19,12 5,21"/>';
    showFrame(parseInt(scrub.value));
});

// ─── 8. Clavier ──────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft')  prevFrame();
    if (e.key === 'ArrowRight') nextFrame();
    if (e.key === ' ')          { e.preventDefault(); togglePlay(); }
});

// ─── Init ─────────────────────────────────────────
preloadFrames();
</script>

</body>
</html>
