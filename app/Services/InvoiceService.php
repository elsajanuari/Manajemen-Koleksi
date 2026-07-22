<?php

namespace App\Services;

use App\Models\Pembelian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generate(Pembelian $pembelian): string
    {
        // Pastikan relasi ter-load
        $pembelian->loadMissing(['painting', 'shippingZone']);

        // Generate nomor invoice: INV-YYYYMMDD-{id}
        $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('invoice.pembelian', compact('pembelian'))
            ->setPaper('a4', 'portrait');

        $filename   = 'invoice/' . $invoiceNumber . '.pdf';
        $pdfContent = $pdf->output();

        Storage::disk('public')->put($filename, $pdfContent);

        $pembelian->update([
            'invoice_number'       => $invoiceNumber,
            'invoice_path'         => $filename,
            'invoice_generated_at' => now(),
        ]);

        return $filename;
    }
}