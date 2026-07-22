<x-app-layout>
    <x-slot name="header">{{-- empty --}}</x-slot>

    @push('head')
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    @endpush

    <style>
        :root {
            --navy:   #0a1628;
            --navy-2: #112240;
            --blue:   #2563eb;
            --sky:    #93c5fd;
            --indigo: #4f46e5;
            --cream:  #f8f5f0;
            --slate:  #64748b;
        }
        * { box-sizing: border-box; }

        .pb-root {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            padding: 2rem 0 4rem;
        }

        /* ── WRAP ── */
        .pb-wrap { max-width: 900px; margin: 0 auto; padding: 0 1.5rem; }

        /* ── BREADCRUMB ── */
        .pb-breadcrumb {
            font-size: .8rem; color: var(--slate);
            margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .pb-breadcrumb a { color: var(--blue); text-decoration: none; }
        .pb-breadcrumb a:hover { text-decoration: underline; }

        /* ── CARD ── */
        .pb-card {
            background: rgba(255,255,255,.98);
            border: 1px solid rgba(15,23,42,.08);
            border-radius: 2rem;
            box-shadow: 0 28px 80px rgba(15,23,42,.08), 0 6px 24px rgba(15,23,42,.05);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        /* ── HEADER ── */
        .pb-header {
            background: linear-gradient(135deg, var(--indigo), #3730a3);
            padding: 2rem 2.5rem;
        }
        .pb-header-badge {
            display: inline-block;
            background: rgba(255,255,255,.2); color: #fff;
            font-size: .7rem; font-weight: 700;
            letter-spacing: .14em; text-transform: uppercase;
            padding: .3rem .9rem; border-radius: 99px;
            margin-bottom: 1rem;
        }
        .pb-header h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem; color: #fff; margin: 0 0 .4rem;
        }
        .pb-header p { color: rgba(255,255,255,.85); font-size: .85rem; line-height: 1.6; margin: 0; }

        /* ── PAINTING BAR ── */
        .pb-painting-bar {
            display: flex; align-items: center; gap: 1.25rem;
            padding: 1.25rem 2.5rem;
            background: #f8fafc;
            border-bottom: 1.5px solid #e2e8f0;
        }
        .pb-painting-thumb {
            width: 68px; height: 68px; border-radius: .875rem;
            overflow: hidden; flex-shrink: 0; background: #e2e8f0;
        }
        .pb-painting-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .pb-painting-info h3 {
            font-family: 'DM Serif Display', serif;
            font-size: .975rem; color: var(--navy); margin: 0 0 .2rem;
        }
        .pb-painting-info p { font-size: .78rem; color: var(--slate); margin: 0; }

        /* ── FORM BODY ── */
        .pb-form-body { padding: 2rem 2.5rem; }

        /* ── SECTION LABEL ── */
        .pb-section-label {
            display: flex; align-items: center; gap: .75rem;
            margin-bottom: 1.75rem; margin-top: 2rem;
        }
        .pb-section-label:first-child { margin-top: 0; }
        .pb-section-label::before {
            content: ''; width: 4px; height: 24px;
            background: linear-gradient(180deg, var(--indigo), var(--sky));
            border-radius: 99px; flex-shrink: 0;
        }
        .pb-section-label h2 {
            font-size: .8rem; font-weight: 700;
            letter-spacing: .15em; text-transform: uppercase; color: var(--navy); margin: 0;
        }
        .pb-section-label span {
            margin-left: auto; background: #eef2ff; color: var(--indigo);
            font-size: .72rem; font-weight: 600;
            padding: .25rem .8rem; border-radius: 99px;
        }

        /* ── GRID ── */
        .pb-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
        .pb-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
        .pb-span-2 { grid-column: span 2; }
        @media (max-width: 640px) {
            .pb-grid { grid-template-columns: 1fr; }
            .pb-grid-3 { grid-template-columns: 1fr 1fr; }
            .pb-span-2 { grid-column: span 1; }
            .pb-form-body { padding: 1.5rem; }
            .pb-header { padding: 1.75rem 1.5rem; }
            .pb-painting-bar { padding: 1rem 1.5rem; }
        }

        /* ── FIELD ── */
        .pb-field { display: flex; flex-direction: column; gap: .45rem; }
        .pb-field label {
            font-size: .82rem; font-weight: 600; color: var(--navy);
            display: flex; align-items: center; gap: .25rem;
        }
        .pb-field label .req { color: #ef4444; font-size: .9em; }
        .pb-input-wrap { position: relative; }
        .pb-icon {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            color: #94a3b8; display: flex; align-items: center; pointer-events: none;
        }
        .pb-icon svg { width: 16px; height: 16px; }
        .pb-icon.top { top: 1rem; transform: none; }

        .pb-field input,
        .pb-field select,
        .pb-field textarea {
            width: 100%; background: #f8fafc;
            border: 1.5px solid #e2e8f0; border-radius: .875rem;
            padding: .875rem 1rem .875rem 2.75rem;
            font-size: .875rem; font-family: 'DM Sans', sans-serif;
            color: var(--navy); outline: none; appearance: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .pb-field textarea { padding-top: .875rem; min-height: 80px; resize: vertical; }
        .pb-field input::placeholder,
        .pb-field textarea::placeholder { color: #94a3b8; }
        .pb-field input:focus,
        .pb-field select:focus,
        .pb-field textarea:focus {
            border-color: var(--indigo); background: #fff;
            box-shadow: 0 0 0 4px rgba(79,70,229,.08);
        }
        .pb-field select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 1rem center;
            background-size: 18px; padding-right: 2.5rem;
        }
        .pb-field .pb-hint { font-size: .75rem; color: #94a3b8; line-height: 1.5; }

        /* ── DIVIDER ── */
        .pb-divider { border: none; border-top: 1.5px dashed #e2e8f0; margin: 1.75rem 0; }

        /* ── UPLOAD ── */
        .pb-upload-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        @media (max-width: 500px) { .pb-upload-grid { grid-template-columns: 1fr; } }

        .pb-upload-card {
            background: #f8fafc; border: 1.5px solid #e2e8f0;
            border-radius: 1.25rem; padding: 1.25rem;
            position: relative; transition: border-color .2s;
        }
        .pb-upload-card:hover { border-color: var(--sky); }
        .pb-upload-card.has-file { border-color: #6ee7b7; background: #f8fffe; }
        .pb-upload-card input[type="file"] { position: absolute; width: 0; height: 0; opacity: 0; }
        .pb-upload-top { display: flex; align-items: flex-start; gap: .75rem; margin-bottom: .875rem; }
        .pb-upload-icon {
            width: 38px; height: 38px; flex-shrink: 0;
            border-radius: .75rem; background: #e2e8f0;
            display: flex; align-items: center; justify-content: center;
        }
        .pb-upload-icon svg { width: 17px; height: 17px; color: var(--slate); }
        .pb-upload-card.has-file .pb-upload-icon { background: #d1fae5; }
        .pb-upload-card.has-file .pb-upload-icon svg { color: #059669; }
        .pb-upload-meta h4 { font-size: .85rem; font-weight: 700; color: var(--navy); margin: 0 0 .25rem; }
        .pb-upload-badge {
            display: inline-block; font-size: .68rem; font-weight: 700;
            padding: .15rem .55rem; border-radius: 99px;
        }
        .pb-upload-badge.wajib    { background: #fef2f2; color: #b91c1c; }
        .pb-upload-badge.opsional { background: #f1f5f9; color: var(--slate); }
        .pb-upload-specs { font-size: .73rem; color: #94a3b8; margin-bottom: .875rem; }
        .pb-upload-action { display: flex; align-items: center; gap: .75rem; }
        .pb-upload-trigger {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .45rem .9rem; border-radius: .75rem;
            font-size: .78rem; font-weight: 600; cursor: pointer;
            border: 1.5px solid #cbd5e1; color: var(--navy);
            background: #fff; transition: all .18s;
            font-family: 'DM Sans', sans-serif; white-space: nowrap;
        }
        .pb-upload-trigger:hover { border-color: var(--indigo); color: var(--indigo); background: #eef2ff; }
        .pb-upload-trigger svg { width: 13px; height: 13px; }
        .pb-upload-filename {
            font-size: .76rem; color: #94a3b8;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px;
        }
        .pb-upload-filename.selected { color: #059669; font-weight: 600; }

        /* ── COST SUMMARY ── */
        .pb-cost-card {
            background: #fff; border: 1.5px solid #e2e8f0;
            border-radius: 1.5rem; overflow: hidden; margin-bottom: 1.5rem;
        }
        .pb-cost-header {
            padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: .5rem;
        }
        .pb-cost-header h3 { font-size: .8rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .pb-cost-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: .875rem 1.5rem; border-bottom: 1px solid #f8fafc;
            font-size: .875rem;
        }
        .pb-cost-row:last-child { border-bottom: none; }
        .pb-cost-row .label { color: var(--slate); }
        .pb-cost-row .value { font-weight: 700; color: var(--navy); }
        .pb-cost-total {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.1rem 1.5rem;
            background: linear-gradient(135deg, var(--navy), #112240);
        }
        .pb-cost-total .label { color: rgba(255,255,255,.65); font-size: .8rem; font-weight: 600; }
        .pb-cost-total .value { font-family: 'DM Serif Display', serif; font-size: 1.35rem; color: #fff; }

        /* ── ACTIONS ── */
        .pb-actions {
            display: flex; align-items: center;
            justify-content: space-between; gap: 1rem;
            padding: 1.5rem 2.5rem;
            border-top: 1.5px solid #f1f5f9;
            background: #fafbfc; flex-wrap: wrap;
        }
        .pb-btn {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 1.5rem; border-radius: .875rem;
            font-size: .875rem; font-family: 'DM Sans', sans-serif;
            font-weight: 600; cursor: pointer; text-decoration: none;
            transition: all .2s; border: none;
        }
        .pb-btn svg { width: 16px; height: 16px; }
        .pb-btn-ghost { background: transparent; border: 1.5px solid #e2e8f0; color: var(--slate); }
        .pb-btn-ghost:hover { background: #f8fafc; }
        .pb-btn-submit {
            background: var(--indigo); color: #fff;
            box-shadow: 0 4px 14px rgba(79,70,229,.35);
            padding: .85rem 2rem;
        }
        .pb-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); }
    </style>

    <div class="pb-root">
        <div class="pb-wrap">

            {{-- Breadcrumb --}}
            <nav class="pb-breadcrumb">
                <a href="{{ route('gallery') }}">Galeri</a>
                <span>/</span>
                <a href="{{ route('gallery.show', $koleksi) }}">{{ $painting->title }}</a>
                <span>/</span>
                <span>Ajukan Pembelian</span>
            </nav>

            {{-- Error --}}
            @if($errors->any())
            <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:1.25rem;padding:1.25rem 1.5rem;color:#be123c;margin-bottom:1.5rem;">
                <h3 style="font-weight:700;font-size:.9rem;margin:0 0 .5rem;">⚠ Periksa kembali data Anda</h3>
                <ul style="padding-left:1.25rem;font-size:.82rem;line-height:1.8;margin:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="pb-card">

                {{-- Header --}}
                <div class="pb-header">
                    <div class="pb-header-badge">Formulir Pembelian</div>
                    <h1>Pengajuan Pembelian Koleksi</h1>
                    <p>Isi data dengan benar sesuai KTP. Pengajuan akan diverifikasi pengelola sebelum lanjut ke pembayaran.</p>
                </div>

                {{-- Painting bar --}}
                <div class="pb-painting-bar">
                    <div class="pb-painting-thumb">
                        @if($painting->image_url)
                            <img src="{{ $painting->image_url }}" alt="{{ $painting->title }}">
                        @endif
                    </div>
                    <div class="pb-painting-info">
                        <h3>{{ $painting->title }}</h3>
                        <p>{{ $painting->artist }} &mdash; {{ $painting->category }}</p>
                    </div>
                </div>

                <form action="{{ route('pembelian.store', $koleksi) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <section class="pb-card" style="margin-bottom:1.5rem; padding:1.75rem;">
                        <div class="pb-section-label" style="margin-top:0;">
                            <h2>Pilih Jenis Pembeli</h2>
                            <span>Formulir B2C / B2B</span>
                        </div>
                        <div class="pb-grid" style="grid-template-columns:1fr 1fr; gap:1rem;">
                            <label class="pb-field buyer-type-option {{ old('buyer_type', 'b2c') === 'b2c' ? 'active' : '' }}" style="padding:1rem; border:1px solid #e2e8f0; border-radius:1rem; cursor:pointer; background:#f8fafc;">
                                <input type="radio" name="buyer_type" value="b2c" class="sr-only" {{ old('buyer_type', 'b2c') === 'b2c' ? 'checked' : '' }}>
                                <span class="font-semibold text-slate-900">Pembelian Perseorangan (B2C)</span>
                                <p class="text-sm text-slate-600 mt-2">Formulir untuk pembeli perorangan dengan data KTP dan NPWP pribadi opsional.</p>
                            </label>
                            <label class="pb-field buyer-type-option {{ old('buyer_type') === 'b2b' ? 'active' : '' }}" style="padding:1rem; border:1px solid #e2e8f0; border-radius:1rem; cursor:pointer; background:#fff;">
                                <input type="radio" name="buyer_type" value="b2b" class="sr-only" {{ old('buyer_type') === 'b2b' ? 'checked' : '' }}>
                                <span class="font-semibold text-slate-900">Pembelian Instansi/Perusahaan (B2B)</span>
                                <p class="text-sm text-slate-600 mt-2">Formulir untuk instansi/perusahaan dengan data perusahaan, PIC, dan dokumen pembelian.</p>
                            </label>
                        </div>
                    </section>

                    <div class="pb-form-body">
                        <section id="b2b-fields" style="display: {{ old('buyer_type') === 'b2b' ? 'block' : 'none' }}; margin-top:1.5rem;">
                            <div class="pb-section-label">
                                <h2>Data Instansi / Perusahaan</h2>
                                <span>B2B</span>
                            </div>

                            <div class="pb-grid">
                                <div class="pb-field pb-span-2">
                                    <label for="company_name">Nama Instansi / Perusahaan <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 3.75H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25zM7.5 9.75h9"/></svg></span>
                                        <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" placeholder="Nama instansi/perusahaan">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="company_type">Jenis Instansi <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9H3"/></svg></span>
                                        <input id="company_type" name="company_type" type="text" value="{{ old('company_type') }}" placeholder="Perusahaan / Yayasan / Lembaga">
                                    </div>
                                </div>
                                <div class="pb-field pb-span-2">
                                    <label for="business_field">Bidang Usaha <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5h18M6.75 6.75h.008v.008H6.75V6.75zm0 10.5h.008v.008H6.75V17.25zm10.5-10.5h.008v.008H17.25V6.75zm0 10.5h.008v.008H17.25V17.25z"/></svg></span>
                                        <input id="business_field" name="business_field" type="text" value="{{ old('business_field') }}" placeholder="Galeri seni, Kurator, Restorasi">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="company_npwp">NPWP Perusahaan <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></span>
                                        <input id="company_npwp" name="company_npwp" type="text" value="{{ old('company_npwp') }}" placeholder="Nomor NPWP perusahaan">
                                    </div>
                                    <span class="pb-hint">Dicatat untuk keperluan administratif.</span>
                                </div>
                                <div class="pb-field pb-span-2">
                                    <label for="company_address">Alamat Perusahaan <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg></span>
                                        <textarea id="company_address" name="company_address" rows="3" placeholder="Alamat lengkap perusahaan">{{ old('company_address') }}</textarea>
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="company_rt">RT <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                        <input id="company_rt" name="company_rt" type="text" inputmode="numeric" value="{{ old('company_rt') }}" placeholder="001" maxlength="5">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="company_rw">RW <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                        <input id="company_rw" name="company_rw" type="text" inputmode="numeric" value="{{ old('company_rw') }}" placeholder="005" maxlength="5">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="company_kelurahan_desa">Kelurahan / Desa <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                        <input id="company_kelurahan_desa" name="company_kelurahan_desa" type="text" value="{{ old('company_kelurahan_desa') }}" placeholder="Nama kelurahan" maxlength="255">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="company_city">Kota / Kabupaten <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 008.716-6.747M12 21a9 9 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg></span>
                                        <input id="company_city" name="company_city" type="text" value="{{ old('company_city') }}" placeholder="Kota / Kabupaten">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="company_province">Provinsi <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 008.716-6.747M12 21a9 9 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg></span>
                                        <input id="company_province" name="company_province" type="text" value="{{ old('company_province') }}" placeholder="Provinsi">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="company_postal_code">Kode Pos <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                        <input id="company_postal_code" name="company_postal_code" type="text" inputmode="numeric" value="{{ old('company_postal_code') }}" placeholder="Kode pos" maxlength="5" pattern="[0-9]{5}">
                                    </div>
                                </div>
                                <div class="pb-field pb-span-2">
                                    <label class="pb-checkbox" for="same_as_company_address" style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                                        <input type="checkbox" id="same_as_company_address" name="same_as_company_address" value="1" {{ old('same_as_company_address') ? 'checked' : '' }}>
                                        <span style="font-size:.82rem;color:#334155;">Alamat pengiriman sama dengan alamat perusahaan</span>
                                    </label>
                                </div>
                            </div>

                            <div class="pb-section-label">
                                <h2>Data PIC</h2>
                                <span>Penanggung Jawab</span>
                            </div>
                            <div class="pb-grid">
                                <div class="pb-field">
                                    <label for="pic_name">Nama PIC <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                        <input id="pic_name" name="pic_name" type="text" value="{{ old('pic_name') }}" placeholder="Nama penanggung jawab">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="pic_position">Jabatan PIC <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a4.5 4.5 0 100 9 4.5 4.5 0 000-9zm0 9.75c-4.97 0-9 2.238-9 5v.75h18V21.5c0-2.762-4.03-5-9-5z"/></svg></span>
                                        <input id="pic_position" name="pic_position" type="text" value="{{ old('pic_position') }}" placeholder="Jabatan PIC">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="pic_nik">NIK PIC <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg></span>
                                        <input id="pic_nik" name="pic_nik" type="text" inputmode="numeric" value="{{ old('pic_nik') }}" placeholder="16 digit NIK PIC" maxlength="16" pattern="[0-9]{16}">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="pic_phone">Nomor HP PIC <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 9h3"/></svg></span>
                                        <input id="pic_phone" name="pic_phone" type="text" value="{{ old('pic_phone') }}" placeholder="Nomor HP PIC">
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="pic_email">Email PIC <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span>
                                        <input id="pic_email" name="pic_email" type="email" value="{{ old('pic_email') }}" placeholder="Email PIC">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div id="b2c-personal">
                            <div class="pb-section-label">
                                <h2>Data Pribadi</h2>
                                <span>Sesuai KTP</span>
                            </div>
                            <div class="pb-grid">
                                <div class="pb-field pb-span-2">
                                    <label for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                        <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap sesuai KTP" required>
                                    </div>
                                </div>
                                <div class="pb-field pb-span-2">
                                    <label for="nik">NIK <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg></span>
                                        <input id="nik" name="nik" type="text" inputmode="numeric" value="{{ old('nik') }}" placeholder="16 digit NIK sesuai KTP" pattern="[0-9]{16}" maxlength="16" required>
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="tempat_lahir">Tempat Lahir <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                        <input id="tempat_lahir" name="tempat_lahir" type="text" value="{{ old('tempat_lahir') }}" placeholder="Contoh: Bandung" required>
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="tanggal_lahir">Tanggal Lahir <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg></span>
                                        <input id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}" required>
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="jenis_kelamin">Jenis Kelamin <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                        <select id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">Pilih jenis kelamin</option>
                                            <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="pekerjaan">Pekerjaan <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></span>
                                        <input id="pekerjaan" name="pekerjaan" type="text" value="{{ old('pekerjaan') }}" placeholder="Wiraswasta, PNS, Seniman" required>
                                    </div>
                                </div>
                                <div class="pb-field pb-span-2">
                                    <label for="npwp">NPWP</label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></span>
                                        <input id="npwp" name="npwp" type="text" value="{{ old('npwp') }}" placeholder="Nomor NPWP (opsional)">
                                    </div>
                                    <span class="pb-hint">Opsional. Dicatat untuk keperluan administratif museum.</span>
                                </div>
                            </div>
                        </div>

                        <hr class="pb-divider">

                        <div id="b2c-contact">
                            <div class="pb-section-label">
                                <h2>Informasi Kontak</h2>
                                <span>Wajib diisi</span>
                            </div>
                            <div class="pb-grid">
                                <div class="pb-field">
                                    <label for="nomor_hp">Nomor HP <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 9h3"/></svg></span>
                                        <input id="nomor_hp" name="nomor_hp" type="tel" inputmode="numeric" value="{{ old('nomor_hp') }}" placeholder="08xxxxxxxxxx" maxlength="15" required>
                                    </div>
                                </div>
                                <div class="pb-field">
                                    <label for="email">Email <span class="req">*</span></label>
                                    <div class="pb-input-wrap">
                                        <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span>
                                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" placeholder="email@domain.com" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="pb-divider">

                        <div class="pb-section-label">
                            <h2>Alamat Pengiriman</h2>
                            <span>Wajib diisi</span>
                        </div>
                        <div class="pb-field" style="margin-bottom:1.25rem;">
                            <label for="alamat_pengiriman">Alamat Lengkap <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg></span>
                                <textarea id="alamat_pengiriman" name="alamat_pengiriman" rows="3" placeholder="Jl. Contoh No. 10..." required>{{ old('alamat_pengiriman') }}</textarea>
                            </div>
                        </div>
                        <div class="pb-grid-3" style="margin-bottom:1.25rem;">
                            <div class="pb-field">
                                <label for="rt">RT <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rt" name="rt" type="text" inputmode="numeric" value="{{ old('rt') }}" placeholder="001" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="rw">RW <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rw" name="rw" type="text" inputmode="numeric" value="{{ old('rw') }}" placeholder="005" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="kelurahan_desa">Kelurahan / Desa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <input id="kelurahan_desa" name="kelurahan_desa" type="text" value="{{ old('kelurahan_desa') }}" placeholder="Nama kelurahan" required>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid-3">
                            <div class="pb-field">
                                <label for="kota_kabupaten">Kota / Kabupaten <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6 12.75h12"/></svg></span>
                                    <input id="kota_kabupaten" name="kota_kabupaten" type="text" value="{{ old('kota_kabupaten') }}" placeholder="Bandung, Surabaya..." required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="provinsi">Provinsi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg></span>
                                    @php
                                        $provinsiList = ['Aceh','Sumatera Utara','Sumatera Barat','Riau','Kepulauan Riau','Jambi','Sumatera Selatan','Kepulauan Bangka Belitung','Bengkulu','Lampung','DKI Jakarta','Jawa Barat','Banten','Jawa Tengah','DI Yogyakarta','Jawa Timur','Bali','Nusa Tenggara Barat','Nusa Tenggara Timur','Kalimantan Barat','Kalimantan Tengah','Kalimantan Selatan','Kalimantan Timur','Kalimantan Utara','Sulawesi Utara','Gorontalo','Sulawesi Tengah','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tenggara','Maluku','Maluku Utara','Papua Barat','Papua','Papua Selatan','Papua Tengah','Papua Pegunungan','Papua Barat Daya'];
                                    @endphp
                                    <select id="provinsi" name="provinsi" required>
                                        <option value="">Pilih provinsi</option>
                                        @foreach($provinsiList as $prov)
                                            <option value="{{ $prov }}" {{ old('provinsi') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="kode_pos">Kode Pos <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                    <input id="kode_pos" name="kode_pos" type="text" inputmode="numeric" value="{{ old('kode_pos') }}" placeholder="40xxx" maxlength="5" pattern="[0-9]{5}" required>
                                </div>
                            </div>
                        </div>

                        <hr class="pb-divider">

                        <div class="pb-section-label">
                            <h2>Dokumen Verifikasi</h2>
                            <span>KTP Wajib</span>
                        </div>
                        <div id="b2c-documents" class="pb-upload-grid">
                            <div class="pb-upload-card" id="card-ktp">
                                <input type="file" id="upload_ktp" name="upload_ktp" accept=".pdf" required onchange="handleFile(this,'lbl-ktp','card-ktp')">
                                <div class="pb-upload-top">
                                    <div class="pb-upload-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>
                                    </div>
                                    <div class="pb-upload-meta">
                                        <h4>Foto KTP</h4>
                                        <span class="pb-upload-badge wajib">Wajib</span>
                                    </div>
                                </div>
                                <div class="pb-upload-specs">PDF &bull; Maks. 2 MB</div>
                                <div class="pb-upload-action">
                                    <label for="upload_ktp" class="pb-upload-trigger">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                        Pilih File
                                    </label>
                                    <span class="pb-upload-filename" id="lbl-ktp">Belum ada file</span>
                                </div>
                            </div>
                            <div class="pb-upload-card" id="card-npwp">
                                <input type="file" id="upload_npwp" name="upload_npwp" accept=".pdf" onchange="handleFile(this,'lbl-npwp','card-npwp')">
                                <div class="pb-upload-top">
                                    <div class="pb-upload-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg>
                                    </div>
                                    <div class="pb-upload-meta">
                                        <h4>NPWP</h4>
                                        <span class="pb-upload-badge opsional">Opsional</span>
                                    </div>
                                </div>
                                <div class="pb-upload-specs">PDF &bull; Maks. 2 MB</div>
                                <div class="pb-upload-action">
                                    <label for="upload_npwp" class="pb-upload-trigger">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                        Pilih File
                                    </label>
                                    <span class="pb-upload-filename" id="lbl-npwp">Belum ada file</span>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /pb-form-body --}}

                    {{-- ══ RINGKASAN BIAYA ══ --}}
                    <div style="padding: 0 2.5rem 1.5rem;">
                        <div class="pb-cost-card">
                            <div class="pb-cost-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;color:#64748b"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                <h3>Ringkasan Biaya</h3>
                            </div>

                            {{-- Harga beli --}}
                            <div class="pb-cost-row">
                                <span class="label">Harga Koleksi</span>
                                <span class="value">Rp {{ number_format($harga_beli, 0, ',', '.') }}</span>
                            </div>

                            {{-- Total final --}}
                            <div class="pb-cost-total">
                                <span class="label">Total Bayar</span>
                                <span class="value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Keterangan tidak ada pajak --}}
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:1rem;padding:.875rem 1.25rem;margin-top:.75rem;">
                            <p style="font-size:.82rem;color:#1e40af;margin:0;line-height:1.6;">
                                <strong>ℹ️ Harga sudah final.</strong>
                                Tidak ada PPN atau PPh yang dikenakan dalam transaksi ini.
                                Museum MK Lesmana belum berstatus PKP, sehingga tidak memungut PPN.
                                Koleksi seni juga tidak termasuk kategori barang sangat mewah (PMK 92/2019).
                            </p>
                        </div>

                        {{-- Keterangan NPWP --}}
                        <p style="font-size:.79rem;color:#94a3b8;margin-top:.6rem;line-height:1.5;">
                            📋 NPWP yang Anda isi (jika ada) hanya dicatat untuk keperluan administratif museum dan tidak mempengaruhi harga.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="pb-actions">
                        <a href="{{ route('gallery.show', $koleksi) }}" class="pb-btn pb-btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali
                        </a>
                        <button type="button" onclick="openModal()" class="pb-btn pb-btn-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Ajukan Pembelian
                        </button>
                        <button id="btn-submit-real" type="submit" style="display:none;"></button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi --}}
    <div id="confirm-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(10,22,40,.5);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:1.75rem;padding:2.5rem;max-width:420px;width:calc(100% - 2rem);box-shadow:0 32px 80px rgba(10,22,40,.2);text-align:center;">
            <div style="width:60px;height:60px;border-radius:50%;background:#eef2ff;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#4f46e5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
            </div>
            <h3 style="font-family:'DM Serif Display',serif;font-size:1.25rem;color:#0a1628;margin:0 0 .75rem;">Konfirmasi Pengajuan</h3>
            <p style="font-size:.875rem;color:#64748b;line-height:1.7;margin:0 0 1.75rem;">Setelah diajukan, data tidak dapat diubah. Pastikan semua informasi sudah benar sebelum melanjutkan.</p>
            <div style="display:flex;gap:.75rem;justify-content:center;">
                <button onclick="closeModal()" style="padding:.7rem 1.5rem;border-radius:.875rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#334155;font-size:.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">Periksa Lagi</button>
                <button onclick="submitForm()" style="padding:.7rem 1.75rem;border-radius:.875rem;background:#4f46e5;color:#fff;border:none;font-size:.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;box-shadow:0 4px 14px rgba(79,70,229,.35);">Ya, Ajukan</button>
            </div>
        </div>
    </div>

    <script>
        function handleFile(input, labelId, cardId) {
            const label = document.getElementById(labelId);
            const card  = document.getElementById(cardId);
            if (input.files && input.files[0]) {
                const name = input.files[0].name;
                label.textContent = name.length > 22 ? name.slice(0, 19) + '...' : name;
                label.classList.add('selected');
                card.classList.add('has-file');
            } else {
                label.textContent = 'Belum ada file';
                label.classList.remove('selected');
                card.classList.remove('has-file');
            }
        }
        function openModal()  { document.getElementById('confirm-modal').style.display = 'flex'; }
        function closeModal() { document.getElementById('confirm-modal').style.display = 'none'; }
        function submitForm() { document.getElementById('btn-submit-real').click(); }

        function toggleBuyerTypeSection() {
            const selected = document.querySelector('input[name="buyer_type"]:checked');
            const b2bSection = document.getElementById('b2b-fields');
            const b2cPersonal = document.getElementById('b2c-personal');
            const b2cDocs = document.getElementById('b2c-documents');
            const b2cContact = document.getElementById('b2c-contact');
            if (!selected) return;
            const isB2b = selected.value === 'b2b';
            b2bSection.style.display = isB2b ? 'block' : 'none';
            b2cPersonal.style.display = isB2b ? 'none' : 'block';
            b2cDocs.style.display = isB2b ? 'none' : 'grid';
            b2cContact.style.display = isB2b ? 'none' : 'block';

            document.querySelectorAll('.buyer-type-option').forEach(option => {
                const isChecked = option.querySelector('input[name="buyer_type"]').checked;
                option.style.background = isChecked ? '#f8fafc' : '#fff';
                option.style.borderColor = isChecked ? '#a5b4fc' : '#e2e8f0';
            });
        }

        function syncShippingAddress() {
            const sameCheckbox = document.getElementById('same_as_company_address');
            if (!sameCheckbox) return;
            const fieldPairs = [
                ['company_address', 'alamat_pengiriman'],
                ['company_rt', 'rt'], ['company_rw', 'rw'],
                ['company_kelurahan_desa', 'kelurahan_desa'],
                ['company_city', 'kota_kabupaten'],
                ['company_province', 'provinsi'],
                ['company_postal_code', 'kode_pos'],
            ];
            fieldPairs.forEach(([src, dst]) => {
                const s = document.getElementById(src);
                const d = document.getElementById(dst);
                if (!s || !d) return;
                if (sameCheckbox.checked) { d.value = s.value; d.setAttribute('readonly', 'readonly'); }
                else { d.removeAttribute('readonly'); }
            });
        }

        document.querySelectorAll('input[name="buyer_type"]').forEach(el => el.addEventListener('change', toggleBuyerTypeSection));
        document.getElementById('same_as_company_address')?.addEventListener('change', syncShippingAddress);
        document.getElementById('confirm-modal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
        ['nik','rt','rw','kode_pos','nomor_hp'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', () => { el.value = el.value.replace(/[^0-9]/g, ''); });
        });

        toggleBuyerTypeSection();
        syncShippingAddress();
    </script>
</x-app-layout>