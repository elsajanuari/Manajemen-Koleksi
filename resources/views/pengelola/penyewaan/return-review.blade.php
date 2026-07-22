<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900">Review Dokumen Pengembalian</h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $penyewaan->nomor_pengajuan ?? 'SW-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
                    &mdash; {{ $penyewaan->status_label ?? ucfirst(str_replace('_', ' ', $penyewaan->status)) }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('pengelola.penyewaan.show', $penyewaan) }}"
                   class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                    ← Detail Penyewaan
                </a>
                <a href="{{ route('pengelola.penyewaan.index') }}"
                   class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                    Daftar Penyewaan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── ALERT ── --}}
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800">
                    ✗ {{ session('error') }}
                </div>
            @endif

            {{-- ══════════════════════════════════════════
                 INFORMASI PENYEWAAN
            ══════════════════════════════════════════ --}}
            <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Informasi Penyewaan</p>
                <h3 class="mt-3 text-2xl font-semibold text-slate-900">
                    {{ $penyewaan->painting->title ?? '-' }}
                </h3>
                <p class="mt-1 text-sm text-slate-500">{{ $penyewaan->painting->artist ?? '-' }}</p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Nomor Pengajuan</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">
                            {{ $penyewaan->nomor_pengajuan ?? 'SW-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Penyewa</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">
                            {{ $penyewaan->contact_name ?? $penyewaan->nama_instansi ?? '-' }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Periode Sewa</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">
                            {{ $penyewaan->start_date?->format('d M Y') }} –
                            {{ $penyewaan->end_date?->format('d M Y') }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Status Serah Terima</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">
                            {{ ucfirst(str_replace('_', ' ', $serahTerima->handover_status)) }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- ══════════════════════════════════════════
                 KONTEN UTAMA: 2-kolom (preview kiri, aksi kanan)
            ══════════════════════════════════════════ --}}
            <div class="grid gap-6 lg:grid-cols-[1fr_400px]">

                {{-- ── KIRI: Preview Dokumen ── --}}
                <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden flex flex-col">
                    <div class="flex items-center justify-between gap-4 p-6 border-b border-slate-100">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Dokumen Pengembalian</p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900">Diupload oleh Penyewa</h3>
                        </div>
                        @if($serahTerima->tenant_signed_return_document_path)
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('pengelola.penyewaan.return-document.preview', $penyewaan) }}"
                                   target="_blank"
                                   class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                    🔍 Tab Baru
                                </a>
                                <a href="{{ route('pengelola.penyewaan.return-document.download', $penyewaan) }}"
                                   class="inline-flex items-center rounded-full bg-slate-900 px-4 py-1.5 text-xs font-semibold text-white hover:bg-slate-700">
                                    ↓ Unduh
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        @if($serahTerima->tenant_signed_return_document_path)
                            <iframe
                                src="{{ route('pengelola.penyewaan.return-document.preview', $penyewaan) }}"
                                class="w-full border-0"
                                style="height: 560px;"
                                title="Preview Dokumen Pengembalian">
                            </iframe>
                        @else
                            <div class="flex flex-col items-center justify-center h-64 text-slate-400">
                                <p class="text-4xl mb-3">📄</p>
                                <p class="text-sm">Penyewa belum mengunggah dokumen pengembalian.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Meta dokumen --}}
                    @if($serahTerima->tenant_signed_return_document_path)
                        <div class="p-6 border-t border-slate-100 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-xs text-slate-500 uppercase tracking-wide">Nama File</p>
                                <p class="mt-2 text-sm font-semibold text-slate-900 break-all">
                                    📄 {{ basename($serahTerima->tenant_signed_return_document_path) }}
                                </p>
                            </div>
                            @if($serahTerima->tenant_signed_return_at)
                                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                    <p class="text-xs text-slate-500 uppercase tracking-wide">Diunggah Penyewa</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($serahTerima->tenant_signed_return_at)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            @endif
                            @if($serahTerima->return_reviewed_at)
                                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                    <p class="text-xs text-slate-500 uppercase tracking-wide">Diverifikasi Pada</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($serahTerima->return_reviewed_at)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            @endif
                            @if($serahTerima->return_review_notes)
                                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 sm:col-span-2">
                                    <p class="text-xs text-slate-500 uppercase tracking-wide">Catatan Pengelola</p>
                                    <p class="mt-2 text-sm text-slate-700">{{ $serahTerima->return_review_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </section>

                {{-- ── KANAN: Log + Form Verifikasi ── --}}
                <div class="space-y-6">

                    {{-- Riwayat Proses --}}
                    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Riwayat Proses</p>
                        <div class="mt-4 space-y-4 max-h-56 overflow-y-auto pr-1">
                            @forelse($serahTerima->logs()->latest()->get() as $log)
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 mt-1.5">
                                        <span class="block w-2.5 h-2.5 rounded-full
                                            @if(str_contains($log->status, 'selesai')) bg-emerald-500
                                            @elseif(str_contains($log->status, 'rejected')) bg-red-500
                                            @elseif(str_contains($log->status, 'menunggu')) bg-amber-400
                                            @else bg-slate-400
                                            @endif">
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $log->message }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            {{ $log->performed_by }} · {{ $log->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div class="border-t border-slate-100"></div>
                                @endif
                            @empty
                                <p class="text-sm text-slate-400 text-center py-4">Belum ada riwayat.</p>
                            @endforelse
                        </div>
                    </section>

                    {{-- Form Verifikasi --}}
                    @php
                        $canVerify = $serahTerima->tenant_signed_return_document_path &&
                                     ! in_array($serahTerima->handover_status, ['selesai']);
                        $isSelesai = $serahTerima->handover_status === 'selesai';
                    @endphp

                    @if($isSelesai)
                        <section class="rounded-3xl border border-green-200 bg-green-50 shadow-sm p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-green-700">Proses Selesai</p>
                            <h3 class="mt-2 text-lg font-semibold text-slate-900">Pengembalian Disetujui</h3>
                            <p class="mt-2 text-sm text-slate-600">
                                Dokumen pengembalian telah diverifikasi. Penyewaan ini sudah ditandai <strong>selesai</strong>.
                            </p>
                        </section>

                    @elseif(! $serahTerima->tenant_signed_return_document_path)
                        <section class="rounded-3xl border border-amber-200 bg-amber-50 shadow-sm p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Menunggu Penyewa</p>
                            <h3 class="mt-2 text-lg font-semibold text-slate-900">Dokumen Belum Diunggah</h3>
                            <p class="mt-2 text-sm text-slate-600">
                                Penyewa belum mengunggah dokumen pengembalian. Verifikasi tidak dapat dilakukan saat ini.
                            </p>
                        </section>

                    @else
                        <section class="rounded-3xl border border-violet-200 bg-violet-50 shadow-sm p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-700">Aksi Diperlukan</p>
                            <h3 class="mt-2 text-lg font-semibold text-slate-900">Verifikasi Dokumen Pengembalian</h3>
                            <p class="mt-2 text-sm text-slate-600">
                                Tinjau dokumen di sebelah kiri lalu pilih tindakan. Menyetujui akan menandai penyewaan sebagai <strong>selesai</strong>.
                            </p>

                            <form action="{{ route('pengelola.penyewaan.return-review.process', $penyewaan) }}"
                                  method="POST"
                                  class="mt-6 space-y-4">
                                @csrf

                                <div>
                                    <label for="review_notes" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                        Catatan <span class="font-normal text-slate-400">(opsional — dikirim ke penyewa)</span>
                                    </label>
                                    <textarea
                                        id="review_notes"
                                        name="review_notes"
                                        rows="4"
                                        placeholder="Kondisi barang yang dikembalikan, atau alasan jika menolak..."
                                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:ring-2 focus:ring-violet-300 focus:outline-none @error('review_notes') border-red-400 @enderror">{{ old('review_notes') }}</textarea>
                                    @error('review_notes')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid gap-3">
                                    <button
                                        type="submit"
                                        name="action"
                                        value="approved"
                                        onclick="return confirm('Setujui dokumen ini? Penyewaan akan ditandai SELESAI dan lukisan dibebaskan.')"
                                        class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition text-center">
                                        ✓ Setujui — Tandai Penyewaan Selesai
                                    </button>
                                    <button
                                        type="submit"
                                        name="action"
                                        value="rejected"
                                        onclick="return confirm('Tolak dokumen ini? Penyewa akan diminta mengunggah ulang.')"
                                        class="w-full rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-700 hover:bg-red-50 transition text-center">
                                        ✗ Tolak — Minta Upload Ulang
                                    </button>
                                </div>
                            </form>
                        </section>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>