<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Halaman verifikasi publik sertifikat.
     * Bisa diakses siapapun tanpa login (via QR code scan).
     *
     * URL: GET /verify-certificate/{certId}
     */
    public function verify(string $certId)
    {
        // Cari pembelian berdasarkan certificate_document_path yang mengandung certId
        $pembelian = Pembelian::with('painting')
            ->where('certificate_document_path', 'LIKE', '%' . $certId . '%')
            ->whereIn('status', ['selesai', 'selesai_dengan_kompensasi']) // hanya yang sudah selesai            
            ->first();

        return view('pembelian.verify-certificate', [
            'certId'    => $certId,
            'valid'     => $pembelian !== null,
            'pembelian' => $pembelian,
        ]);
    }
}