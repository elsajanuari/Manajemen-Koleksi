<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<style>
    :root {
        --gg-navy: #0a1628;
        --gg-navy-2: #112240;
        --gg-blue: #2563eb;
        --gg-blue-dark: #1d4ed8;
        --gg-sky: #93c5fd;
        --gg-cream: #f8f5f0;
        --gg-slate: #64748b;
    }

    * { box-sizing: border-box; }

    .gg-root {
        font-family: 'DM Sans', system-ui, sans-serif;
        background: var(--gg-cream);
        min-height: 100vh;
        margin: 0;
        color: var(--gg-navy);
    }

    .gg-wrap {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
    }

    .gg-hero {
        background: linear-gradient(135deg, var(--gg-blue), var(--gg-blue-dark));
        border-radius: 2rem;
        padding: 2.25rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 50px rgba(37, 99, 235, 0.25);
        position: relative;
        overflow: hidden;
    }
    .gg-hero::after {
        content: '';
        position: absolute;
        right: -20%;
        top: -40%;
        width: 55%;
        height: 180%;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .gg-hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        padding: 0.35rem 0.9rem;
        border-radius: 99px;
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
    }
    .gg-hero h1 {
        font-family: 'DM Serif Display', serif;
        font-size: clamp(1.75rem, 4vw, 2.35rem);
        color: #fff;
        margin: 0 0 0.5rem;
        line-height: 1.15;
        position: relative;
        z-index: 1;
    }
    .gg-hero p {
        color: rgba(255, 255, 255, 0.92);
        font-size: 0.9rem;
        line-height: 1.65;
        max-width: 36rem;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .gg-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .gg-card {
        background: rgba(255, 255, 255, 0.98);
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 28px 80px rgba(15, 23, 42, 0.08), 0 6px 24px rgba(15, 23, 42, 0.05);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }
    a.gg-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 32px 90px rgba(15, 23, 42, 0.12), 0 12px 32px rgba(37, 99, 235, 0.12);
    }

    .gg-card-media {
        position: relative;
        aspect-ratio: 4 / 3;
        background: #f1f5f9;
        overflow: hidden;
    }
    .gg-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }
    a.gg-card:hover .gg-card-media img { transform: scale(1.06); }

    .gg-card-badges {
        position: absolute;
        left: 0.75rem;
        top: 0.75rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        z-index: 2;
    }
    .gg-badge {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.3rem 0.65rem;
        border-radius: 99px;
        background: rgba(255, 255, 255, 0.95);
        color: var(--gg-navy);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .gg-badge--ok { background: #ecfdf5; color: #047857; }
    .gg-badge--off { background: #fff7ed; color: #c2410c; }

    .gg-card-body { padding: 1.35rem 1.5rem 1.5rem; flex: 1; display: flex; flex-direction: column; }
    .gg-card-title {
        font-family: 'DM Serif Display', serif;
        font-size: 1.15rem;
        font-weight: 600;
        margin: 0 0 0.25rem;
        color: var(--gg-navy);
        line-height: 1.25;
    }
    a.gg-card:hover .gg-card-title { color: var(--gg-blue); }
    .gg-card-artist {
        font-size: 0.8rem;
        color: var(--gg-slate);
        margin-bottom: 0.65rem;
    }
    .gg-card-desc {
        font-size: 0.82rem;
        color: var(--gg-slate);
        line-height: 1.55;
        flex: 1;
        margin-bottom: 1rem;
    }
    .gg-card-price {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gg-navy);
        margin-bottom: 1rem;
    }
    .gg-card-price strong { font-size: 1rem; }

    .gg-btn-row { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: auto; }

    .gg-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.65rem 1.15rem;
        border-radius: 0.875rem;
        font-size: 0.8rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .gg-btn-primary {
        background: var(--gg-blue);
        color: #fff;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    }
    .gg-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
    }
    .gg-btn-ghost {
        background: transparent;
        border: 1.5px solid #e2e8f0;
        color: var(--gg-slate);
    }
    .gg-btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; }

    .gg-detail-layout {
        display: grid;
        gap: 2rem;
    }
    @media (min-width: 900px) {
        .gg-detail-layout { grid-template-columns: 1.05fr 0.95fr; align-items: start; }
    }

    .gg-panel {
        background: rgba(255, 255, 255, 0.98);
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 28px 80px rgba(15, 23, 42, 0.08), 0 6px 24px rgba(15, 23, 42, 0.05);
    }
    .gg-panel-img {
        aspect-ratio: 4 / 3;
        background: #f1f5f9;
    }
    .gg-panel-img img { width: 100%; height: 100%; object-fit: cover; }

    .gg-panel-body { padding: 2rem 2rem 2.25rem; }
    .gg-panel-body h1 {
        font-family: 'DM Serif Display', serif;
        font-size: clamp(1.6rem, 3vw, 2.1rem);
        margin: 0 0 0.75rem;
        line-height: 1.2;
    }
    .gg-meta {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 0.65rem 1rem;
        font-size: 0.85rem;
        margin-top: 1.25rem;
    }
    .gg-meta dt { font-weight: 600; color: var(--gg-navy); }
    .gg-meta dd { margin: 0; color: var(--gg-slate); }
    .gg-prose {
        font-size: 0.9rem;
        line-height: 1.7;
        color: var(--gg-slate);
        margin-top: 1.25rem;
    }

    .gg-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.75rem;
        padding-top: 1.5rem;
        border-top: 1.5px solid #f1f5f9;
    }

    .gg-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 2rem;
        border: 1.5px dashed #e2e8f0;
        color: var(--gg-slate);
        font-size: 0.9rem;
    }

    .gg-pagination {
        margin-top: 2.5rem;
        display: flex;
        justify-content: center;
    }
    .gg-pagination nav[role="navigation"] a,
    .gg-pagination nav[role="navigation"] span {
        font-family: 'DM Sans', sans-serif;
    }
</style>
