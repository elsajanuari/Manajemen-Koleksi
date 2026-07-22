@props(['detailPengunjung', 'pemesananTiket', 'qrUri', 'verificationUrl'])

<div class="tk-body">
    <div class="tk-body-top">
        <div class="tk-fields">
            <div class="field-row">
                <div class="field f2">
                    <p class="field-label">Nama Pengunjung</p>
                    <p class="field-val lg">{{ $detailPengunjung->getDisplayName() }}</p>
                    @if($detailPengunjung->tipe_pengunjung === 'kelompok' && $detailPengunjung->daftar_anggota)
                        <p class="member-small">Anggota: {{ implode(', ', $detailPengunjung->daftar_anggota) }}</p>
                    @endif
                </div>
                <div class="field">
                    <p class="field-label">Pemesan</p>
                    <p class="field-val">{{ $pemesananTiket->user->name }}</p>
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <p class="field-label">Email</p>
                    <p class="field-val sm">{{ $detailPengunjung->email }}</p>
                </div>
                <div class="field">
                    <p class="field-label">No. Ponsel</p>
                    <p class="field-val">{{ $detailPengunjung->nomor_ponsel }}</p>
                </div>
            </div>
            <div class="tk-notice {{ $detailPengunjung->tiket_terpakai_at ? 'used' : '' }}">
                @if($detailPengunjung->tiket_terpakai_at)
                    <p class="notice-head">Tiket telah digunakan</p>
                    <p class="notice-body">{{ $detailPengunjung->tiket_terpakai_at->locale('id')->translatedFormat('d F Y \p\u\k\u\l H:i') }}</p>
                @else
                    <p class="notice-head">Tunjukkan QR Code kepada petugas</p>
                    <p class="notice-body">QR hanya dapat digunakan satu kali untuk validasi masuk.</p>
                @endif
            </div>
        </div>
        <div class="tk-qr-panel">
            <p class="qr-label">Scan QR Code</p>
            <div class="qr-img-wrap">
                <img src="{{ $qrUri }}" alt="QR Tiket {{ $detailPengunjung->getDisplayName() }}">
            </div>
            <p class="qr-token">{{ $verificationUrl }}</p>
        </div>
    </div>
    <div class="tk-footer">
        <span class="foot-id">ID: {{ substr($detailPengunjung->tiket_verifikasi_token, 0, 22) }}...</span>
        <span class="foot-org">Museum MK Lesmana</span>
    </div>
</div>