{{-- resources/views/viewer360/create.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Viewer 360° — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:      #000;
            --surface: #0d0d0d;
            --border:  rgba(255,255,255,0.08);
            --text:    #e8e8e8;
            --muted:   rgba(255,255,255,0.35);
            --accent:  #fff;
            --danger:  #ff4d4d;
            --success: #34d399;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 16px;
        }

        /* ─── Card ─── */
        .card {
            width: 100%;
            max-width: 640px;
        }

        /* Header */
        .card-header {
            margin-bottom: 40px;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            text-decoration: none;
            margin-bottom: 24px;
            transition: color .2s;
        }
        .back-link:hover { color: #fff; }
        .back-link svg { width: 12px; height: 12px; fill: none; stroke: currentColor; stroke-width: 2; }

        .card-header h1 {
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1;
            color: #fff;
        }
        .card-header p {
            font-size: 13px;
            color: var(--muted);
            margin-top: 10px;
            line-height: 1.6;
        }

        /* ─── Séparateur section ─── */
        .section-title {
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        /* ─── Champs ─── */
        .field { margin-bottom: 20px; }
        .field label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 8px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .field input[type="text"],
        .field textarea {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
            padding: 14px 16px;
            outline: none;
            transition: border-color .2s;
        }
        .field input[type="text"]:focus,
        .field textarea:focus { border-color: rgba(255,255,255,0.4); }
        .field textarea { min-height: 80px; resize: vertical; }

        /* ─── Zone drop ─── */
        #drop-zone {
            border: 1px dashed rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 3rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: border-color .25s, background .25s;
            position: relative;
            background: var(--surface);
        }
        #drop-zone.dragover {
            border-color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.03);
        }
        #drop-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .drop-icon {
            width: 48px; height: 48px;
            margin: 0 auto 16px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .drop-icon svg { width: 22px; height: 22px; stroke: rgba(255,255,255,0.5); fill: none; }
        .drop-title { font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 6px; }
        .drop-sub { font-size: 12px; color: var(--muted); line-height: 1.6; }

        /* ─── Compteur ─── */
        #count-badge {
            display: none;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            font-size: 12px;
            color: var(--muted);
        }
        #count-badge.show { display: flex; }
        #count-badge .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--success);
        }
        #count-badge .dot.warn { background: var(--danger); }

        /* ─── Preview grid ─── */
        #preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(64px, 1fr));
            gap: 5px;
            margin-top: 14px;
        }
        .preview-thumb {
            aspect-ratio: 1;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--border);
            position: relative;
            background: var(--surface);
        }
        .preview-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .preview-thumb .num {
            position: absolute;
            bottom: 2px; right: 3px;
            font-size: 8px;
            color: rgba(255,255,255,0.5);
            background: rgba(0,0,0,0.6);
            padding: 1px 3px;
            border-radius: 3px;
        }
        .preview-thumb .err-badge {
            position: absolute;
            inset: 0;
            background: rgba(255,77,77,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #fff;
            font-weight: 600;
        }

        /* ─── Tip ─── */
        .tip {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 10px;
            padding: 14px 16px;
            margin: 20px 0;
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            line-height: 1.6;
        }
        .tip svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 2px; fill: none; stroke: rgba(255,255,255,0.4); }
        .tip strong { color: rgba(255,255,255,0.75); }

        /* ─── Upload progress ─── */
        #upload-progress {
            display: none;
            margin-top: 16px;
        }
        .progress-bar-track {
            height: 1px;
            background: var(--border);
        }
        .progress-bar-fill {
            height: 100%;
            background: #fff;
            width: 0%;
            transition: width .3s;
        }
        #progress-text {
            font-size: 11px;
            color: var(--muted);
            margin-top: 8px;
            letter-spacing: 0.05em;
        }

        /* ─── Erreurs ─── */
        .errors {
            background: rgba(255,77,77,0.06);
            border: 1px solid rgba(255,77,77,0.2);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }
        .errors p { font-size: 12px; color: #ff8080; line-height: 1.8; }

        /* ─── Submit ─── */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: #fff;
            color: #000;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 28px;
            transition: opacity .2s, transform .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover   { opacity: 0.88; }
        .btn-submit:active  { transform: scale(0.99); }
        .btn-submit:disabled {
            opacity: 0.25;
            cursor: not-allowed;
            transform: none;
        }
        .btn-submit svg { width: 16px; height: 16px; fill: none; stroke: #000; stroke-width: 2.5; }

        @media (max-width: 480px) {
            body { padding: 24px 12px; }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <a href="{{ route('viewer.index') }}" class="back-link">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Galerie viewers
        </a>
        <h1>Nouveau<br>Viewer 360°</h1>
        <p>Uploadez entre 8 et 72 photos prises en tournant autour du véhicule.</p>
    </div>

    @if ($errors->any())
        <div class="errors">
            @foreach ($errors->all() as $error)
                <p>→ {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('viewer.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
        @csrf

        <p class="section-title">Informations</p>

        <div class="field">
            <label for="name">Nom du véhicule</label>
            <input type="text" id="name" name="name"
                   placeholder="Ex : Toyota GR Supra 2024"
                   value="{{ old('name') }}" required>
        </div>

        <div class="field">
            <label for="description">Description <span style="color:var(--muted);font-size:10px">(optionnel)</span></label>
            <textarea id="description" name="description"
                      placeholder="Caractéristiques, couleur, motorisation…">{{ old('description') }}</textarea>
        </div>

        <p class="section-title" style="margin-top:32px">Photos 360°</p>

        <div class="tip">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            <span>
                Nommez vos photos <strong>frame_001.jpg, frame_002.jpg…</strong> en tournant
                <strong>dans le sens horaire</strong>. L'ordre alphabétique détermine la fluidité.
            </span>
        </div>

        <div id="drop-zone">
            <input type="file" name="frames[]" id="frames-input"
                   accept="image/jpeg,image/png,image/webp" multiple required>
            <div class="drop-icon">
                <svg viewBox="0 0 24 24" stroke-width="1.5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
            </div>
            <p class="drop-title">Glisser les photos ici</p>
            <p class="drop-sub">ou cliquer pour sélectionner<br>JPG · PNG · WEBP · max 5 Mo / photo</p>
        </div>

        <div id="count-badge">
            <span class="dot" id="count-dot"></span>
            <span id="count-text">0 photos</span>
        </div>

        <div id="preview-grid"></div>

        <div id="upload-progress">
            <div class="progress-bar-track">
                <div class="progress-bar-fill" id="bar-fill"></div>
            </div>
            <p id="progress-text">Envoi en cours…</p>
        </div>

        <button type="submit" class="btn-submit" id="btn-submit" disabled>
            <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12l7-7 7 7"/></svg>
            <span id="btn-label">Créer le viewer 360°</span>
        </button>
    </form>
</div>

<script>
const MAX_FILE_SIZE_MB = 5;
const MAX_FILE_SIZE_B  = MAX_FILE_SIZE_MB * 1024 * 1024;
const MIN_FRAMES = 8;
const MAX_FRAMES = 72;

const dropZone  = document.getElementById('drop-zone');
const fileInput = document.getElementById('frames-input');
const grid      = document.getElementById('preview-grid');
const badge     = document.getElementById('count-badge');
const dot       = document.getElementById('count-dot');
const countText = document.getElementById('count-text');
const btnSubmit = document.getElementById('btn-submit');
const btnLabel  = document.getElementById('btn-label');
const form      = document.getElementById('upload-form');

// ─── Drag & drop visual ───────────────────────
dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop',      e => { e.preventDefault(); dropZone.classList.remove('dragover'); });

// ─── Preview ──────────────────────────────────
fileInput.addEventListener('change', () => renderPreviews(fileInput.files));

function renderPreviews(files) {
    // ✅ Révoquer les anciens Object URLs pour éviter la fuite mémoire
    grid.querySelectorAll('img').forEach(img => {
        if (img.src.startsWith('blob:')) URL.revokeObjectURL(img.src);
    });
    grid.innerHTML = '';

    const sorted = Array.from(files).sort((a, b) => a.name.localeCompare(b.name));
    let hasErrors = false;

    sorted.forEach((file, i) => {
        const thumb = document.createElement('div');
        thumb.className = 'preview-thumb';

        // ✅ Validation poids côté JS
        if (file.size > MAX_FILE_SIZE_B) {
            hasErrors = true;
            const errBadge = document.createElement('div');
            errBadge.className = 'err-badge';
            errBadge.textContent = '+5Mo';
            thumb.appendChild(errBadge);
        } else {
            const img = document.createElement('img');
            const url = URL.createObjectURL(file);
            img.src   = url;
            // ✅ Révoquer dès que l'image est affichée
            img.onload = () => URL.revokeObjectURL(url);
            thumb.appendChild(img);
        }

        const num = document.createElement('span');
        num.className   = 'num';
        num.textContent = i + 1;
        thumb.appendChild(num);
        grid.appendChild(thumb);
    });

    const n = files.length;
    badge.classList.add('show');
    const valid = !hasErrors && n >= MIN_FRAMES && n <= MAX_FRAMES;
    dot.className  = 'dot' + (valid ? '' : ' warn');
    countText.textContent = `${n} photo${n > 1 ? 's' : ''} sélectionnée${n > 1 ? 's' : ''}${n < MIN_FRAMES ? ` (min. ${MIN_FRAMES} requises)` : ''}${n > MAX_FRAMES ? ` (max. ${MAX_FRAMES})` : ''}${hasErrors ? ' — certaines dépassent 5 Mo' : ''}`;

    btnSubmit.disabled = !valid || hasErrors;
}

// ─── Progression envoi ────────────────────────
form.addEventListener('submit', e => {
    if (btnSubmit.disabled) { e.preventDefault(); return; }
    document.getElementById('upload-progress').style.display = 'block';
    btnSubmit.disabled = true;
    // ✅ innerHTML préservé — on modifie seulement le span texte
    btnLabel.textContent = 'Envoi en cours…';

    let pct = 0;
    const bar = document.getElementById('bar-fill');
    const txt = document.getElementById('progress-text');
    const iv = setInterval(() => {
        pct = Math.min(pct + Math.random() * 6, 90);
        bar.style.width = pct + '%';
        txt.textContent = `Envoi en cours… ${Math.round(pct)}%`;
        if (pct >= 90) clearInterval(iv);
    }, 400);
});
</script>

</body>
</html>
