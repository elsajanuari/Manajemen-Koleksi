<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Tindakan Konservasi') }}</h2>
                <p class="mt-1 text-sm text-gray-500">Dokumentasi riwayat konservasi untuk koleksi dan jadwal yang terhubung.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('konservasi.tindakan.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                    Kembali ke Daftar
                </a>
                <a href="{{ route('jadwal-konservasi.show', $action->perawatanKoleksi) }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-200 transition">
                    Lihat Jadwal
                </a>
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mx-auto max-w-[1800px] px-4 sm:px-6 lg:px-8">
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-4 text-sm text-green-900 shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Informasi Koleksi</h3>
                    <dl class="grid gap-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nama Koleksi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->koleksi->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nomor Inventaris</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->koleksi->nomor_inventaris ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kategori Koleksi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->koleksi->kategori }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Informasi Pemeriksaan</h3>
                    <dl class="grid gap-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal Pemeriksaan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->kondisiKoleksi->tanggal_periksa->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kondisi Sebelum Konservasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->kondisiKoleksi->label_kondisi }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Temuan Pemeriksaan</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $action->kondisiKoleksi->catatan ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rekomendasi Tindak Lanjut</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->kondisiKoleksi->label_rekomendasi }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Informasi Jadwal</h3>
                    <dl class="grid gap-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal Konservasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ optional($action->perawatanKoleksi->jadwal_tanggal)->format('d M Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Penanggung Jawab</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->perawatanKoleksi->penanggung_jawab ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Prioritas</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->jenis_konservasi === \App\Models\ConservationAction::TYPE_PREVENTIF ? 'Rendah / Preventif' : 'Tinggi / Kuratif' }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-4">Informasi Konservasi</h3>
                    <dl class="grid gap-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Jenis Konservasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->jenis_konservasi_label }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $action->status_label }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Konservasi</h3>
                @php
                    $implementation = $action->implementations->first();
                    $result = $action->result;
                    $jadwal = $action->perawatanKoleksi;
                    $evaluationLabel = $result ? (\App\Models\ConservationResult::EVALUATION_OPTIONS[$result->evaluasi] ?? $result->evaluasi) : null;
                    $recommendationLabel = $result?->rekomendasi_penyimpanan || $result?->rekomendasi_penanganan_khusus
                        ? trim(implode(' / ', array_filter([$result->rekomendasi_penyimpanan, $result->rekomendasi_penanganan_khusus])))
                        : null;
                @endphp
                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rencana</p>
                        <dl class="mt-3 space-y-3 text-sm text-slate-800">
                            <div>
                                <dt class="font-medium text-slate-600">Tindakan</dt>
                                <dd>{{ optional($action->plan)->jenis_tindakan ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-600">Target selesai</dt>
                                <dd>{{ optional(optional($action->plan)->target_penyelesaian)->format('d M Y') ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-600">Bahan / material</dt>
                                <dd>{{ optional($action->plan)->bahan_material ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pelaksanaan</p>
                        <dl class="mt-3 space-y-3 text-sm text-slate-800">
                            <div>
                                <dt class="font-medium text-slate-600">Tanggal pelaksanaan</dt>
                                <dd>{{ optional($implementation?->tanggal_pelaksanaan)->format('d M Y') ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-600">Pelaksana</dt>
                                <dd>{{ $implementation?->petugas ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-600">Durasi</dt>
                                <dd>{{ $implementation?->durasi ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-600">Perubahan dari rencana</dt>
                                <dd>{{ $implementation?->catatan_perubahan ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hasil</p>
                        <dl class="mt-3 space-y-3 text-sm text-slate-800">
                            <div>
                                <dt class="font-medium text-slate-600">Kondisi setelah</dt>
                                <dd>{{ $result->kondisi_setelah ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-600">Evaluasi</dt>
                                <dd>{{ $evaluationLabel ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-600">Rekomendasi</dt>
                                <dd>{{ $recommendationLabel ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-600">Catatan akhir</dt>
                                <dd>{{ $result?->catatan_akhir ?: '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Konservasi</h3>
                <div class="grid gap-3 sm:grid-cols-3">
                    <a href="{{ route('konservasi.tindakan.plan', $action) }}" class="rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-center text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                        Rencana Konservasi
                    </a>
                    <a href="{{ route('konservasi.tindakan.pelaksanaan', $action) }}" class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-center text-sm font-semibold text-amber-700 hover:bg-amber-100 transition">
                        Pelaksanaan Konservasi
                    </a>
                    <a href="{{ route('konservasi.tindakan.hasil', $action) }}" class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-center text-sm font-semibold text-green-700 hover:bg-green-100 transition">
                        Hasil Konservasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
