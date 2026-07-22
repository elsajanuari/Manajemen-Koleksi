<style>
    :root {
        --brand: #5b79b6;
        --brand-dark: #3d5a8c;
        --brand-deep: #2c4368;
        --accent-sewa: #2563eb;
        --accent-beli: #7c3aed;
        --bg: #f5f3ef;
        --surface: #ffffff;
        --text: #1a1a1a;
        --muted: #5c5c5c;
        --border: rgba(0, 0, 0, 0.08);
        --page-gutter: clamp(1.25rem, 4vw, 3.75rem);
        font-family: 'Poppins', sans-serif;
    }

    *, *::before, *::after { box-sizing: border-box; }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    .page-gallery-index,
    .page-gallery-detail {
        color: var(--text);
        min-height: 100%;
        width: 100%;
        background: #f3f4f6;
    }

    .page-wrap {
        width: 100%;
        padding: 0 var(--page-gutter);
    }

    .gallery-main {
        padding-bottom: 56px;
    }

    /* —— Hero (referensi: banner + search) —— */
    .gallery-hero {
        position: relative;
        min-height: 280px;
        display: grid;
        place-items: center;
        text-align: center;
        color: #fff;
        overflow: hidden;
    }

    .gallery-hero__bg {
        position: absolute;
        inset: 0;
        background: var(--brand-deep);
    }

    .gallery-hero__photo {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .gallery-hero__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to bottom,
            rgba(20, 30, 48, 0.55) 0%,
            rgba(20, 30, 48, 0.42) 100%
        );
    }

    .gallery-hero__inner {
        position: relative;
        z-index: 1;
        width: min(720px, 92vw);
        padding: 48px 16px 40px;
    }

    .gallery-hero h1 {
        margin: 0;
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .gallery-hero__lead {
        margin: 10px 0 24px;
        font-size: 0.95rem;
        opacity: 0.92;
        line-height: 1.6;
    }

    .hero-search {
        display: flex;
        align-items: stretch;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    }

    .hero-search__icon {
        flex-shrink: 0;
        margin: 14px 0 14px 16px;
        color: #94a3b8;
    }

    .hero-search input {
        flex: 1;
        border: none;
        padding: 14px 12px;
        font: inherit;
        font-size: 0.95rem;
        color: var(--text);
        min-width: 0;
        appearance: none;
        -webkit-appearance: none;
        box-shadow: none;
    }

    .hero-search input:focus {
        outline: none;
        box-shadow: none;
        border-color: transparent;
        --tw-ring-shadow: 0 0 #0000;
        --tw-ring-offset-shadow: 0 0 #0000;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font: inherit;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
    }

    .btn:hover { opacity: 0.92; transform: translateY(-1px); }

    .btn-hero {
        padding: 0 28px;
        background: var(--brand-dark);
        color: #fff;
        font-size: 0.9rem;
        border-radius: 0;
        white-space: nowrap;
    }

    .btn-hero:hover { background: var(--brand-deep); }

    /* —— Filter bar —— */
    .filter-bar {
        background: #f3f4f6;
        border-bottom: 1px solid var(--border);
        padding: 14px 0;
    }

    .filter-bar__inner {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .filter-select { flex: 1; min-width: 160px; max-width: 280px; }

    .filter-select select {
        width: 100%;
        padding: 11px 36px 11px 14px;
        border: 1px solid #d4d0c8;
        border-radius: 6px;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none;
        font: inherit;
        font-size: 0.88rem;
        color: #333;
        cursor: pointer;
    }

    .filter-reset {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--brand-dark);
        text-decoration: none;
        padding: 10px 4px;
    }

    .filter-reset:hover { text-decoration: underline; }

    /* —— Masonry grid —— */
    .gallery-meta-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 24px 0 8px;
    }

    .gallery-count {
        margin: 0;
        font-size: 0.88rem;
        color: var(--muted);
    }

    .gallery-legend {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .gallery-legend .image-badge {
        position: static;
        box-shadow: none;
    }

    .gallery-count__hint { color: var(--brand); }

    .masonry-grid {
        column-count: 4;
        column-gap: 12px;
    }

    @media (max-width: 1100px) { .masonry-grid { column-count: 3; } }
    @media (max-width: 768px) { .masonry-grid { column-count: 2; } }
    @media (max-width: 480px) { .masonry-grid { column-count: 1; } }

    .gallery-tile {
        position: relative;
        display: block;
        break-inside: avoid;
        margin-bottom: 12px;
        border-radius: 6px;
        overflow: hidden;
        background: #e2e8f0;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.25s, transform 0.25s;
    }

    .gallery-tile:hover,
    .gallery-tile:focus-visible {
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.14);
        transform: translateY(-2px);
        outline: none;
    }

    .gallery-tile img {
        width: 100%;
        height: auto;
        display: block;
        vertical-align: middle;
    }

    .tile-placeholder {
        display: block;
        min-height: 200px;
        background: linear-gradient(145deg, #cbd5e1, #e2e8f0);
    }

    .tile--tall .tile-placeholder,
    .tile--tall img { min-height: 300px; object-fit: cover; }

    .tile--wide img { min-height: 180px; object-fit: cover; }

  /* Label kategori & ketersediaan di atas gambar */
    .image-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        z-index: 2;
        pointer-events: none;
    }

    .image-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.96);
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1.2;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        letter-spacing: 0.01em;
    }

    .image-badge--category { color: #1d4ed8; }
    .image-badge--sewa { color: #1e40af; }
    .image-badge--beli { color: #6d28d9; }
    .image-badge--display { color: #475569; font-weight: 600; }

    .tile-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(26, 32, 44, 0.78);
        opacity: 0;
        transition: opacity 0.28s ease;
        text-align: center;
        z-index: 1;
    }

    .gallery-tile:hover .tile-overlay,
    .gallery-tile:focus-visible .tile-overlay { opacity: 1; }

    @media (hover: none) {
        .tile-overlay {
            opacity: 1;
            align-items: flex-end;
            justify-content: flex-start;
            padding: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.88) 0%, rgba(15, 23, 42, 0.35) 55%, transparent 100%);
        }
        .tile-overlay-inner { text-align: left; padding: 16px; width: 100%; }
    }

    .tile-overlay-inner {
        display: flex;
        flex-direction: column;
        gap: 6px;
        color: #fff;
    }

    .tile-title {
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.35;
    }

    .tile-sub {
        font-size: 0.82rem;
        opacity: 0.88;
    }

    .tile-avail {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        justify-content: center;
        margin-top: 8px;
    }

    @media (hover: none) {
        .tile-avail { justify-content: flex-start; }
    }

    .tile-avail span {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    /* —— Load more —— */
    .load-more-wrap {
        text-align: center;
        margin-top: 40px;
    }

    .btn-load-more {
        padding: 14px 40px;
        background: var(--brand-dark);
        color: #fff;
        border-radius: 6px;
        font-size: 0.95rem;
    }

    .pagination-subtle {
        margin-top: 20px;
        opacity: 0.85;
    }

    .gallery-pagination {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .gallery-pagination .page-btn,
    .gallery-pagination .page-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        color: #444;
        background: #fff;
        border: 1px solid var(--border);
    }

    .gallery-pagination .page-num.current {
        background: var(--brand);
        color: #fff;
        border-color: var(--brand);
    }

    .gallery-pagination .page-btn.disabled { opacity: 0.4; cursor: not-allowed; }
    .gallery-pagination .page-numbers { display: flex; gap: 4px; }

    .empty-state {
        text-align: center;
        padding: 64px 24px;
        background: #fff;
        border-radius: 8px;
        border: 1px dashed #ccc;
        margin-top: 24px;
    }

    .empty-state h2 { margin: 0 0 8px; font-size: 1.25rem; }
    .empty-state p { color: var(--muted); margin: 0 0 20px; }

    .btn-outline {
        padding: 10px 20px;
        border: 1px solid var(--border);
        border-radius: 6px;
        color: #444;
        background: #fff;
    }

    .site-footer {
        padding: 28px 16px;
        text-align: center;
        font-size: 0.82rem;
        color: var(--muted);
        border-top: 1px solid var(--border);
        background: #fff;
    }

    .site-footer--compact {
        margin-top: 48px;
        background: transparent;
        border: none;
    }

    /* —— Detail page —— */
    .breadcrumb {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        font-size: 0.85rem;
        color: var(--muted);
        padding: 24px 0 0;
    }

    .breadcrumb a { color: var(--brand); text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .detail-layout {
        display: grid;
        gap: 32px;
        padding: 28px 0 0;
    }

    @media (min-width: 900px) {
        .detail-layout {
            grid-template-columns: 1.1fr 1fr;
            align-items: start;
        }
    }

    .detail-page { display: block; }

    .detail-gallery {
        border-radius: 8px;
        overflow: hidden;
        background: #e8e8e8;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .detail-gallery__trigger {
        display: block;
        width: 100%;
        padding: 0;
        border: none;
        background: #f1f1f1;
        cursor: zoom-in;
        position: relative;
        font-family: inherit;
    }

    .detail-gallery__trigger img {
        width: 100%;
        max-height: 560px;
        object-fit: contain;
        display: block;
    }

    .detail-gallery__trigger:hover img {
        opacity: 0.92;
    }

    .detail-gallery__zoom {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.75), transparent);
        color: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        opacity: 0;
        transition: opacity 0.25s;
    }

    .detail-gallery__trigger:hover .detail-gallery__zoom,
    .detail-gallery__trigger:focus-visible .detail-gallery__zoom {
        opacity: 1;
    }

    .detail-gallery .no-photo {
        min-height: 320px;
        display: grid;
        place-items: center;
        color: var(--muted);
        margin: 0;
    }

    .detail-panel { display: grid; gap: 18px; }

    .detail-kategori {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--brand);
        margin-bottom: 6px;
    }

    .detail-panel h1 {
        margin: 0;
        font-size: clamp(1.6rem, 3vw, 2.25rem);
        font-weight: 700;
        line-height: 1.2;
    }

    .detail-artist {
        margin: 0;
        font-size: 0.95rem;
        color: var(--muted);
    }

    .detail-desc {
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.85;
        color: #444;
        padding: 16px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .info-item {
        padding: 14px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
    }

    .info-item .label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted);
        margin-bottom: 4px;
    }

    .info-item .value {
        font-size: 0.95rem;
        font-weight: 600;
    }

    .detail-availability {
        padding: 16px;
        background: #f8f7f5;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .avail-intro {
        margin: 0 0 10px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #333;
    }

    .avail-intro--muted { font-weight: 400; color: var(--muted); margin: 0; }

    .availability { display: flex; flex-wrap: wrap; gap: 8px; }

    .avail-tag {
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .avail-sewa { background: #dbeafe; color: #1e40af; }
    .avail-beli { background: #ede9fe; color: #6d28d9; }

    .detail-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 8px;
    }

    .detail-actions .btn {
        min-height: 46px;
        padding: 0 22px;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .btn-sewa { background: var(--accent-sewa); color: #fff; }
    .btn-beli { background: var(--accent-beli); color: #fff; }
    .detail-actions .btn-ghost {
        background: #fff;
        color: #334155;
        border: 1px solid #cbd5e1;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    }

    .detail-actions .btn-ghost:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #1e293b;
    }

    /* —— Lightbox —— */
    .lightbox {
        margin: auto;
        padding: 0;
        border: none;
        max-width: min(96vw, 1200px);
        width: max-content;
        background: transparent;
    }

    .lightbox::backdrop {
        background: rgba(15, 23, 42, 0.88);
    }

    .lightbox__close {
        position: fixed;
        top: 16px;
        right: 20px;
        z-index: 2;
        width: 44px;
        height: 44px;
        border: none;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        font-size: 1.75rem;
        line-height: 1;
        cursor: pointer;
        transition: background 0.2s;
    }

    .lightbox__close:hover { background: rgba(255, 255, 255, 0.28); }

    .lightbox__figure {
        margin: 0;
        text-align: center;
    }

    .lightbox__figure img {
        max-width: min(92vw, 1100px);
        max-height: 85vh;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.45);
    }

    .lightbox__figure figcaption {
        margin-top: 14px;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* —— Related horizontal scroll (dalam konten detail) —— */
    .related-section--inline {
        margin-top: 48px;
        padding-top: 32px;
        border-top: 1px solid var(--border);
        background: transparent;
    }

    .related-section {
        padding: 0 0 8px;
    }

    .related-header {
        margin: 0 0 20px;
        padding: 0;
    }

    .related-header h2 {
        margin: 0;
        font-size: 1.35rem;
        color: var(--brand-deep);
    }

    .related-header p {
        margin: 6px 0 0;
        font-size: 0.88rem;
        color: var(--muted);
    }

    .related-scroll-wrap {
        position: relative;
        margin: 0;
        padding: 0;
    }

    .related-section--inline .related-scroll-wrap {
        margin: 0 -4px;
    }

    .related-track {
        display: flex;
        gap: 14px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        padding: 8px 4px 16px;
        scrollbar-width: thin;
        scrollbar-color: var(--brand) #eee;
    }

    .related-track::-webkit-scrollbar { height: 6px; }
    .related-track::-webkit-scrollbar-thumb {
        background: var(--brand);
        border-radius: 3px;
    }

    .related-card {
        flex: 0 0 200px;
        width: 200px;
        position: relative;
        overflow: hidden;
        border-radius: 6px;
        overflow: hidden;
        scroll-snap-align: start;
        text-decoration: none;
        background: #e8e8e8;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s;
    }

    @media (min-width: 640px) {
        .related-card { flex: 0 0 220px; width: 220px; }
    }

    .related-card:hover { transform: scale(1.02); }

    .related-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }

    .related-placeholder {
        display: block;
        height: 200px;
        background: linear-gradient(145deg, #cbd5e1, #e2e8f0);
    }

    .related-card-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 12px;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.85), transparent 60%);
        opacity: 0;
        transition: opacity 0.25s;
    }

    .related-card:hover .related-card-overlay,
    .related-card:focus-visible .related-card-overlay { opacity: 1; }

    @media (hover: none) {
        .related-card-overlay { opacity: 1; }
    }

    .related-card-title {
        color: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .related-card-meta {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.72rem;
        margin-top: 2px;
    }

    .scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: var(--brand-dark);
        color: #fff;
        cursor: pointer;
        display: grid;
        place-items: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        transition: background 0.2s;
    }

    .scroll-btn:hover { background: var(--brand-deep); }

    .scroll-btn--prev { left: 4px; }
    .scroll-btn--next { right: 4px; }

    @media (max-width: 640px) {
        .scroll-btn { width: 34px; height: 34px; }
        .info-grid { grid-template-columns: 1fr; }
        .hero-search { flex-wrap: wrap; }
        .btn-hero { width: 100%; padding: 14px; border-radius: 0 0 8px 8px; }
        .hero-search__icon { display: none; }
        .hero-search input { padding-left: 16px; }
    }
</style>
