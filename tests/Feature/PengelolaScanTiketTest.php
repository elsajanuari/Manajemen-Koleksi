<?php

use App\Models\User;

it('shows a clear warning when a scanned qr code is not from this e-ticket system', function () {
    $pengelola = User::factory()->create([
        'role' => 'pengelola',
    ]);

    $response = $this->actingAs($pengelola)
        ->post(route('pengelola.verifikasi-tiket.lookup'), [
            'kode' => 'https://qris.example/scan/123456',
        ]);

    $response->assertRedirect(route('pengelola.verifikasi-tiket.form'));
    $response->assertSessionHas('error', 'QR yang Anda pindai bukan hasil generate sistem e-tiket ini. Pastikan Anda memindai QR Code tiket dari sistem ini.');
});
