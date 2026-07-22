<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Jadwal Konservasi') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">Ringkasan jadwal, status, dan langkah tindak lanjut koleksi.</p>
            </div>
            <a href="{{ route('jadwal-konservasi.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Jadwal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            {{-- Ringkasan koleksi & status --}}
            <div class="rounded-xl border border-gray-200 bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Jadwal Konservasi Koleksi</p>
                        <h1 class="mt-1 text-xl sm:text-2xl font-bold text-gray-900 leading-tight">
                            <a href="{{ route('koleksi.show', $perawatan->koleksi) }}" class="hover:text-indigo-700 transition-colors" title="Lihat detail koleksi">
                                {{ $perawatan->koleksi->nama }}
                            </a>
                        </h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Nomor inventaris: {{ $perawatan->koleksi->nomor_inventaris ?? '-' }}
                        </p>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $perawatan->badge_class }}">
                                {{ $perawatan->label_status }}
                            </span>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $perawatan->prioritas_badge_class }}">
                                Prioritas {{ $perawatan->label_prioritas }}
                            </span>
                            @if ($perawatan->jadwal_indikator_waktu)
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $perawatan->jadwal_indikator_badge_class }}">
                                    {{ $perawatan->jadwal_indikator_waktu }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="shrink-0 rounded-lg border border-indigo-100 bg-white px-4 py-3 text-center lg:text-right">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Tanggal Jadwal</p>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $perawatan->jadwal_tanggal->format('d M Y') }}</p>
                        <p class="mt-0.5 text-sm font-medium text-indigo-700">{{ $perawatan->label_jenis }}</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">
                {{-- Informasi jadwal --}}
                <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-5">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Informasi Jadwal</h3>

                    <dl class="mt-4 grid grid-cols-2 gap-x-6 gap-y-4 sm:grid-cols-3">
                        <div>
                            <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Jenis Konservasi</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $perawatan->label_jenis }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Frekuensi</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $perawatan->label_frekuensi }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Estimasi Durasi</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $perawatan->estimasi_durasi_label ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Penanggung Jawab</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $perawatan->penanggung_jawab }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $perawatan->badge_class }}">
                                    {{ $perawatan->label_status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Prioritas</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $perawatan->prioritas_badge_class }}">
                                    {{ $perawatan->label_prioritas }}
                                </span>
                            </dd>
                        </div>
                    </dl>

                    @if ($perawatan->catatan)
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Catatan Jadwal</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-gray-800">{{ $perawatan->catatan }}</p>
                        </div>
                    @endif

                    @if ($perawatan->status !== 'terjadwal')
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Tanggal Selesai</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">
                                        @if ($perawatan->tanggal_selesai)
                                            @php
                                                $selesai = $perawatan->tanggal_selesai;
                                                $jadwal = $perawatan->jadwal_tanggal ?? null;
                                            @endphp
                                            {{ $selesai->format('d M Y') }}
                                            @if ($jadwal && $selesai->lt($jadwal))
                                                @php $days = $jadwal->diffInDays($selesai); @endphp
                                                <span class="ml-2 text-xs font-semibold text-amber-600">(selesai {{ $days }} hari lebih cepat)</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Catatan Penyelesaian</dt>
                                    <dd class="mt-1 text-sm text-gray-800">{{ $perawatan->catatan_penyelesaian ?? '-' }}</dd>
                                </div>
                                @if ($perawatan->conservationAction)
                                    @php
                                        $impl = $perawatan->conservationAction->implementations->first();
                                    @endphp
                                    @if ($impl && filled($impl->catatan_perubahan))
                                        <div>
                                            <dt class="text-[11px] font-semibold uppercase tracking-wide text-amber-600">Catatan Perubahan Dari Rencana</dt>
                                            <dd class="mt-1 text-sm text-amber-900 whitespace-pre-line">{{ $impl->catatan_perubahan }}</dd>
                                        </div>
                                    @endif
                                @endif
                            </dl>
                        </div>
                    @endif

                    @if ($perawatan->status === 'dibatalkan')
                        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-red-600">Alasan Pembatalan</p>
                            <p class="mt-1 text-sm text-red-900">{{ $perawatan->alasan_pembatalan ?? '-' }}</p>
                        </div>
                    @endif
                </div>

                {{-- Konteks pemeriksaan sumber --}}
                <div class="space-y-5">
                    @if ($perawatan->kondisiSumber)
                        <div class="rounded-xl border border-gray-200 bg-white p-5">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Pemeriksaan Sumber</h3>
                            <dl class="mt-4 space-y-3">
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Tanggal Pemeriksaan</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $perawatan->kondisiSumber->tanggal_periksa->format('d M Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Kondisi</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $perawatan->kondisiSumber->label_kondisi }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Rekomendasi</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $perawatan->kondisiSumber->label_rekomendasi }}</dd>
                                </div>
                            </dl>
                            <a href="{{ route('koleksi.kondisi.show', [$perawatan->koleksi_id, $perawatan->kondisiSumber]) }}"
                                class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-700 hover:text-indigo-900">
                                Lihat pemeriksaan sumber →
                            </a>
                        </div>
                    @endif

                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Koleksi</h3>
                        <dl class="mt-4 space-y-3">
                            <div>
                                <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Kategori</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 capitalize">{{ $perawatan->koleksi->kategori }}</dd>
                            </div>
                            @if ($perawatan->creator)
                                <div>
                                    <dt class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Dibuat Oleh</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $perawatan->creator->name }}</dd>
                                </div>
                            @endif
                        </dl>
                        <a href="{{ route('koleksi.show', $perawatan->koleksi) }}"
                            class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-700 hover:text-indigo-900">
                            Lihat detail koleksi →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Panel tindak lanjut --}}
            @if ($perawatan->requiresConservation())
                <div class="rounded-xl border border-blue-200 bg-blue-50 p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-blue-900">Dokumentasi via Tindakan Konservasi</h4>
                            <p class="mt-1.5 text-sm leading-relaxed text-blue-800">
                                Jadwal {{ strtolower($perawatan->label_jenis) }} didokumentasikan melalui alur konservasi:
                                rencana → pelaksanaan → hasil. Setelah hasil dicatat, status jadwal otomatis selesai.
                            </p>
                        </div>
                        @if ($perawatan->isScheduled())
                            <div class="flex shrink-0 flex-wrap items-center gap-2 sm:justify-end">
                                @if ($perawatan->conservation_workflow_label)
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">
                                        Langkah: {{ $perawatan->conservation_workflow_label }}
                                    </span>
                                @endif
                                @include('perawatan._conservation_action', ['perawatan' => $perawatan])
                            </div>
                        @elseif ($perawatan->conservationAction)
                            <a href="{{ route('konservasi.tindakan.show', $perawatan->conservationAction) }}"
                                class="inline-flex shrink-0 items-center text-sm font-semibold text-blue-700 hover:text-blue-900">
                                Lihat riwayat tindakan konservasi →
                            </a>
                        @endif
                    </div>
                </div>
            @elseif ($perawatan->isPemeliharaan())
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                    <h4 class="text-sm font-semibold text-emerald-900">Konservasi Preventif (Pemeliharaan Rutin)</h4>
                    <p class="mt-1.5 text-sm leading-relaxed text-emerald-800">
                        Pemeliharaan adalah kegiatan preventif rutin (pembersihan, kontrol suhu/kelembapan/cahaya, cek hama).
                        Tidak melalui alur dokumentasi konservasi. Selesaikan jadwal lewat tombol <strong>Catat Selesai</strong>
                        pada halaman daftar jadwal konservasi; jika frekuensinya berulang, sistem akan menawarkan jadwal berikutnya.
                    </p>
                </div>
            @elseif ($perawatan->isPemeriksaan())
                <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-indigo-900">Pemeriksaan Ulang Terjadwal</h4>
                            <p class="mt-1.5 text-sm leading-relaxed text-indigo-800">
                                Selesaikan jadwal ini dengan mengisi form pemeriksaan kondisi lengkap.
                                Hasil pemeriksaan akan tercatat di riwayat kondisi koleksi beserta rekomendasi tindak lanjut.
                            </p>
                        </div>
                        @if ($perawatan->isScheduled())
                            <a href="{{ route('koleksi.kondisi.create', ['koleksi' => $perawatan->koleksi_id, 'perawatan_id' => $perawatan->id]) }}"
                                class="inline-flex shrink-0 items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Catat Pemeriksaan
                            </a>
                        @elseif ($perawatan->kondisiHasil)
                            <a href="{{ route('koleksi.kondisi.show', [$perawatan->koleksi_id, $perawatan->kondisiHasil]) }}"
                                class="inline-flex shrink-0 items-center text-sm font-semibold text-indigo-700 hover:text-indigo-900">
                                Lihat hasil pemeriksaan →
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
