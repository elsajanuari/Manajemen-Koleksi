<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use App\Models\Pembelian;
use App\Models\PembelianPayment;
use App\Services\BinderbyteService;
use App\Services\MidtransService;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PembelianController extends Controller
{
    public function step1(Koleksi $koleksi)
    {
        if (! $koleksi->isForSale()) {
            return redirect()->route('gallery.show', $koleksi)
                ->with('error', 'Koleksi ini tidak tersedia untuk dibeli.');
        }

        $painting = $koleksi;

        return view('pembelian.step1', compact('painting', 'koleksi'));
    }

    public function storeStep1(Request $request, Koleksi $koleksi)
    {
        if (! $koleksi->isForSale()) {
            return redirect()->route('gallery.show', $koleksi)
                ->with('error', 'Koleksi ini tidak tersedia untuk dibeli.');
        }

        $request->validate(['buyer_type' => ['required', Rule::in(['b2c', 'b2b'])]]);
        session(['pembelian_step1' => ['buyer_type' => $request->input('buyer_type')]]);
        session()->forget('pembelian_step2');

        return redirect()->route('pembelian.step2', $koleksi);
    }

    public function step2(Koleksi $koleksi)
    {
        if (! $koleksi->isForSale()) {
            return redirect()->route('gallery.show', $koleksi)
                ->with('error', 'Koleksi ini tidak tersedia untuk dibeli.');
        }

        $step1 = session('pembelian_step1');
        if (! $step1 || ! isset($step1['buyer_type'])) {
            return redirect()->route('pembelian.step1', $koleksi)
                ->with('error', 'Silakan pilih jenis pembeli terlebih dahulu.');
        }

        $painting   = $koleksi;
        $buyerType  = $step1['buyer_type'];
        $harga_beli = $koleksi->sale_price;

        $binderbyte = app(BinderbyteService::class);
        $provinces  = $binderbyte->getProvinces();

        $citiesGrouped = [];
        foreach ($provinces as $prov) {
            $citiesGrouped[(string) $prov['id']] = $binderbyte->getCitiesByProvince($prov['id']);
        }

        return view('pembelian.step2', compact(
            'painting', 'koleksi', 'buyerType', 'harga_beli', 'provinces', 'citiesGrouped'
        ));
    }

    public function store(Request $request, Koleksi $koleksi, ShippingService $shippingService)
    {
        if (! $koleksi->isForSale()) {
            return redirect()->route('gallery.show', $koleksi)
                ->with('error', 'Koleksi ini tidak tersedia untuk dibeli.');
        }

        $addressRules = [
            'rt'                => ['required', 'string', 'max:10'],
            'rw'                => ['required', 'string', 'max:10'],
            'kelurahan_desa'    => ['required', 'string', 'max:255'],
            'kecamatan'         => ['required', 'string', 'max:255'],
            'kota_kabupaten'    => ['required', 'string', 'max:255'],
            'provinsi'          => ['required', 'string', 'max:255'],
            'kode_pos'          => ['required', 'string', 'max:10', 'regex:/^[0-9]{5}$/'],
            'city_name'         => ['nullable', 'string', 'max:100'],
            'province_id'       => ['nullable', 'string', 'max:10'],
            'destination_city_id' => ['required', 'integer', 'min:1'],
        ];

        $domisiliRules = [
            'alamat_domisili'   => ['required', 'string', 'max:1000'],
            'dom_provinsi'      => ['required', 'string', 'max:255'],
            'dom_kota_kabupaten'=> ['required', 'string', 'max:255'],
            'dom_kecamatan'     => ['required', 'string', 'max:255'],
            'dom_kelurahan_desa'=> ['required', 'string', 'max:255'],
            'dom_rt'            => ['required', 'string', 'max:10'],
            'dom_rw'            => ['required', 'string', 'max:10'],
            'dom_kode_pos'      => ['required', 'string', 'max:10', 'regex:/^[0-9]{5}$/'],
        ];

        $companyAddressRules = [
            'company_address'        => ['required', 'string', 'max:1000'],
            'company_province'       => ['required', 'string', 'max:255'],
            'company_city'           => ['required', 'string', 'max:255'],
            'company_kecamatan'      => ['required', 'string', 'max:255'],
            'company_kelurahan_desa' => ['required', 'string', 'max:255'],
            'company_rt'             => ['required', 'string', 'max:10'],
            'company_rw'             => ['required', 'string', 'max:10'],
            'company_postal_code'    => ['required', 'string', 'max:10', 'regex:/^[0-9]{5}$/'],
        ];

        $picDomisiliRules = [
            'pic_alamat_domisili'   => ['required', 'string', 'max:1000'],
            'pic_provinsi'          => ['required', 'string', 'max:255'],
            'pic_kota_kabupaten'    => ['required', 'string', 'max:255'],
            'pic_kecamatan'         => ['required', 'string', 'max:255'],
            'pic_kelurahan_desa'    => ['required', 'string', 'max:255'],
            'pic_rt'                => ['required', 'string', 'max:10'],
            'pic_rw'                => ['required', 'string', 'max:10'],
            'pic_kode_pos'          => ['required', 'string', 'max:10', 'regex:/^[0-9]{5}$/'],
        ];

        $commonRules = [
            'buyer_type'        => ['required', Rule::in(['b2c', 'b2b'])],
            'nomor_hp'          => ['required', 'string', 'max:25'],
            'email'             => ['required', 'email', 'max:255'],
            'alamat_pengiriman' => ['required', 'string', 'max:1000'],
        ];

        $buyerType = $request->input('buyer_type');
        $rules     = array_merge($commonRules, $addressRules);

        if ($buyerType === 'b2b') {
            $rules = array_merge($rules, $companyAddressRules, $picDomisiliRules, [
                'company_name'                   => ['required', 'string', 'max:255'],
                'company_type'                   => ['required', 'string', 'max:255'],
                'business_field'                 => ['required', 'string', 'max:255'],
                'company_npwp'                   => ['required', 'string', 'max:25'],
                'company_website'                => ['nullable', 'url', 'max:255'],
                'pic_name'                       => ['required', 'string', 'max:255'],
                'pic_position'                   => ['required', 'string', 'max:255'],
                'pic_nik'                        => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
                'pic_phone'                      => ['required', 'string', 'max:25'],
                'pic_email'                      => ['required', 'email', 'max:255'],
                'upload_npwp_company'            => ['required', 'file', 'mimes:pdf', 'max:2048'],
                'upload_purchase_request_letter' => ['required', 'file', 'mimes:pdf', 'max:2048'],
                'upload_pic_ktp'                 => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            ]);
        } else {
            $rules = array_merge($rules, $domisiliRules, [
                'nama_lengkap'  => ['required', 'string', 'max:255'],
                'nik'           => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
                'tempat_lahir'  => ['required', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'date'],
                'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                'pekerjaan'     => ['required', 'string', 'max:255'],
                'npwp'          => ['nullable', 'string', 'max:25'],
                'upload_ktp'    => ['required', 'file', 'mimes:pdf', 'max:2048'],
                'upload_npwp'   => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            ]);
        }

        $validated = $request->validate($rules);

        $uploadKtp = $uploadNpwp = $uploadNpwpCompany = null;
        $uploadPurchaseRequestLetter = $uploadPicKtp = null;

        if ($buyerType === 'b2c') {
            $uploadKtp  = $request->hasFile('upload_ktp')
                ? $request->file('upload_ktp')->store('pembelian-documents', 'public') : null;
            $uploadNpwp = $request->hasFile('upload_npwp')
                ? $request->file('upload_npwp')->store('pembelian-documents', 'public') : null;
        }
        if ($buyerType === 'b2b') {
            $uploadNpwpCompany           = $request->file('upload_npwp_company')->store('pembelian-documents', 'public');
            $uploadPurchaseRequestLetter = $request->file('upload_purchase_request_letter')->store('pembelian-documents', 'public');
            $uploadPicKtp                = $request->file('upload_pic_ktp')->store('pembelian-documents', 'public');
        }

        $zone = $shippingService->resolveZone($validated['provinsi'], $validated['kota_kabupaten']);

        $pembelian = Pembelian::create([
            'user_id'     => Auth::id(),
            'koleksi_id'  => $koleksi->id,
            'status'      => 'menunggu_verifikasi',
            'buyer_type'  => $validated['buyer_type'],

            'nama_lengkap'  => $buyerType === 'b2c' ? $validated['nama_lengkap'] : null,
            'nik'           => $buyerType === 'b2c' ? $validated['nik'] : ($buyerType === 'b2b' ? $validated['pic_nik'] : null),
            'tempat_lahir'  => $buyerType === 'b2c' ? $validated['tempat_lahir'] : null,
            'tanggal_lahir' => $buyerType === 'b2c' ? $validated['tanggal_lahir'] : null,
            'jenis_kelamin' => $buyerType === 'b2c' ? $validated['jenis_kelamin'] : null,
            'pekerjaan'     => $buyerType === 'b2c' ? $validated['pekerjaan'] : null,
            'npwp'          => $buyerType === 'b2c' ? ($validated['npwp'] ?? null) : null,

            'company_name'    => $buyerType === 'b2b' ? $validated['company_name'] : null,
            'company_type'    => $buyerType === 'b2b' ? $validated['company_type'] : null,
            'business_field'  => $buyerType === 'b2b' ? $validated['business_field'] : null,
            'company_npwp'    => $buyerType === 'b2b' ? $validated['company_npwp'] : null,
            'company_address' => $buyerType === 'b2b' ? $validated['company_address'] : null,
            'company_province'       => $buyerType === 'b2b' ? $validated['company_province'] : null,
            'company_city'           => $buyerType === 'b2b' ? $validated['company_city'] : null,
            'company_kecamatan'      => $buyerType === 'b2b' ? $validated['company_kecamatan'] : null,
            'company_kelurahan_desa' => $buyerType === 'b2b' ? $validated['company_kelurahan_desa'] : null,
            'company_rt'             => $buyerType === 'b2b' ? $validated['company_rt'] : null,
            'company_rw'             => $buyerType === 'b2b' ? $validated['company_rw'] : null,
            'company_postal_code'    => $buyerType === 'b2b' ? $validated['company_postal_code'] : null,
            'company_website' => $buyerType === 'b2b' ? ($validated['company_website'] ?? null) : null,
            'pic_name'        => $buyerType === 'b2b' ? $validated['pic_name'] : null,
            'pic_position'    => $buyerType === 'b2b' ? $validated['pic_position'] : null,
            'pic_nik'         => $buyerType === 'b2b' ? $validated['pic_nik'] : null,
            'pic_phone'       => $buyerType === 'b2b' ? $validated['pic_phone'] : null,
            'pic_email'       => $buyerType === 'b2b' ? $validated['pic_email'] : null,
            'pic_alamat_domisili'   => $buyerType === 'b2b' ? $validated['pic_alamat_domisili'] : null,
            'pic_provinsi'          => $buyerType === 'b2b' ? $validated['pic_provinsi'] : null,
            'pic_kota_kabupaten'    => $buyerType === 'b2b' ? $validated['pic_kota_kabupaten'] : null,
            'pic_kecamatan'         => $buyerType === 'b2b' ? $validated['pic_kecamatan'] : null,
            'pic_kelurahan_desa'    => $buyerType === 'b2b' ? $validated['pic_kelurahan_desa'] : null,
            'pic_rt'                => $buyerType === 'b2b' ? $validated['pic_rt'] : null,
            'pic_rw'                => $buyerType === 'b2b' ? $validated['pic_rw'] : null,
            'pic_kode_pos'          => $buyerType === 'b2b' ? $validated['pic_kode_pos'] : null,

            'alamat_domisili'    => $buyerType === 'b2c' ? $validated['alamat_domisili'] : null,
            'dom_provinsi'       => $buyerType === 'b2c' ? $validated['dom_provinsi'] : null,
            'dom_kota_kabupaten' => $buyerType === 'b2c' ? $validated['dom_kota_kabupaten'] : null,
            'dom_kecamatan'      => $buyerType === 'b2c' ? $validated['dom_kecamatan'] : null,
            'dom_kelurahan_desa' => $buyerType === 'b2c' ? $validated['dom_kelurahan_desa'] : null,
            'dom_rt'             => $buyerType === 'b2c' ? $validated['dom_rt'] : null,
            'dom_rw'             => $buyerType === 'b2c' ? $validated['dom_rw'] : null,
            'dom_kode_pos'       => $buyerType === 'b2c' ? $validated['dom_kode_pos'] : null,

            'nomor_hp'          => $validated['nomor_hp'],
            'email'             => $validated['email'],
            'alamat_pengiriman' => $validated['alamat_pengiriman'],
            'rt'                => $validated['rt'],
            'rw'                => $validated['rw'],
            'kelurahan_desa'    => $validated['kelurahan_desa'],
            'kecamatan'         => $validated['kecamatan'],
            'kota_kabupaten'    => $validated['kota_kabupaten'],
            'provinsi'          => $validated['provinsi'],
            'kode_pos'          => $validated['kode_pos'],

            'upload_ktp'                     => $uploadKtp,
            'upload_npwp'                    => $uploadNpwp,
            'upload_npwp_company'            => $uploadNpwpCompany,
            'upload_purchase_request_letter' => $uploadPurchaseRequestLetter,
            'upload_pic_ktp'                 => $uploadPicKtp,

            'harga_beli'       => $koleksi->sale_price,
            'shipping_cost'    => 0,
            'total_bayar'      => $koleksi->sale_price,
            'shipping_zone_id' => $zone->id,

            'city_name'   => $request->input('city_name') ?: null,
            'province_id' => $request->input('province_id') ?: null,

            'destination_city_id' => $request->input('destination_city_id') ? (int) $request->input('destination_city_id') : null,

            'submitted_at' => now(),
        ]);

        return redirect()->route('pembelian.show', $pembelian)
            ->with('success', 'Pengajuan pembelian berhasil dikirim. Menunggu verifikasi pengelola.');
    }

    public function show(Pembelian $pembelian)
    {
        $this->authorizeOwner($pembelian);
        $pembelian->load(['painting', 'shippingZone']);
        return view('pembelian.show', compact('pembelian'));
    }

    public function index()
    {
        $pembelians = Auth::user()->pembelians()->with('painting')->latest()->get();
        return view('pembelian.index', compact('pembelians'));
    }

    public function cancel(Pembelian $pembelian)
    {
        $this->authorizeOwner($pembelian);
        if (! in_array($pembelian->status, ['menunggu_verifikasi'])) {
            return redirect()->route('pembelian.show', $pembelian)->with('error', 'Pengajuan tidak dapat dibatalkan.');
        }
        $pembelian->update(['status' => 'dibatalkan']);
        return redirect()->route('pembelian.index')->with('success', 'Pengajuan berhasil dibatalkan.');
    }

    public function downloadInvoice(Pembelian $pembelian)
    {
        $this->authorizeOwner($pembelian);
        if (! $pembelian->invoice_path || ! Storage::disk('public')->exists($pembelian->invoice_path)) {
            return redirect()->route('pembelian.show', $pembelian)->with('error', 'Invoice belum tersedia.');
        }
        return response()->file(
            Storage::disk('public')->path($pembelian->invoice_path),
            ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . $pembelian->invoice_number . '.pdf"']
        );
    }

    public function showPayment(Pembelian $pembelian, MidtransService $midtrans)
    {
        $this->authorizeOwner($pembelian);
        if ($pembelian->status !== 'menunggu_pembayaran') {
            return redirect()->route('pembelian.show', $pembelian)->with('error', 'Pembayaran hanya dapat dilakukan setelah pengajuan disetujui.');
        }
        $pembelian->load(['shippingZone']);
        $clientKey = $midtrans->getClientKey();
        return view('pembelian.payment', compact('pembelian', 'clientKey'));
    }

    public function processPayment(Pembelian $pembelian, MidtransService $midtrans)
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'menunggu_pembayaran')
            return response()->json(['error' => 'Status tidak valid.'], 400);

        $pembelian->payments()
            ->where('transaction_status', 'pending')
            ->where('created_at', '<', now()->subMinutes(60))
            ->update(['transaction_status' => 'expire']);

        if ($pembelian->payments()->where('transaction_status', 'pending')->where('created_at', '>=', now()->subMinutes(60))->exists())
            return response()->json(['error' => 'Transaksi sedang diproses.'], 400);

        $orderId     = 'BLI-' . $pembelian->id . '-' . time();
        $itemDetails = [[
            'id'       => 'koleksi-' . $pembelian->koleksi_id,
            'price'    => (int) $pembelian->harga_beli,
            'quantity' => 1,
            'name'     => Str::limit($pembelian->painting->title, 50),
        ]];

        if ((int) $pembelian->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $pembelian->shipping_cost,
                'quantity' => 1,
                'name'     => Str::limit(
                    $pembelian->shipping_method_type === 'courier'
                        ? 'Ongkos Kirim (' . ($pembelian->courier_name ?? 'Kurir') . ')'
                        : 'Ongkos Kirim (Pengiriman Pengelola)',
                    50
                ),
            ];
        }

        $params = [
            'transaction_details' => ['order_id' => $orderId, 'gross_amount' => (int) $pembelian->total_bayar],
            'customer_details'    => ['first_name' => $pembelian->nama_lengkap ?? $pembelian->pic_name, 'email' => $pembelian->email, 'phone' => $pembelian->nomor_hp],
            'item_details'        => $itemDetails,
        ];

        try {
            $snapToken = $midtrans->getSnapToken($params);
            PembelianPayment::create(['pembelian_id' => $pembelian->id, 'order_id' => $orderId, 'gross_amount' => $pembelian->total_bayar, 'transaction_status' => 'pending', 'payload' => $params]);
            $pembelian->update(['payment_status' => 'pending', 'payment_reference' => $orderId]);
            return response()->json(['snap_token' => $snapToken, 'order_id' => $orderId]);
        } catch (\Throwable $e) {
            \Log::error('Midtrans Pembelian Error: ' . $e->getMessage(), ['pembelian_id' => $pembelian->id]);
            return response()->json(['error' => 'Gagal membuat transaksi: ' . $e->getMessage()], 400);
        }
    }

    public function paymentSuccess(Pembelian $pembelian, MidtransService $midtrans)
    {
        $this->authorizeOwner($pembelian);
        $this->syncPaymentStatus($pembelian, $midtrans);
        return redirect()->route('pembelian.show', $pembelian)->with('success', 'Pembayaran berhasil. Terima kasih!');
    }

    public function paymentFailed(Pembelian $pembelian)
    {
        $this->authorizeOwner($pembelian);
        return redirect()->route('pembelian.show', $pembelian)->with('error', 'Pembayaran gagal atau dibatalkan.');
    }

    public function cancelPendingPayment(Pembelian $pembelian)
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'menunggu_pembayaran')
            return response()->json(['error' => 'Status tidak valid.'], 400);

        $pembelian->payments()
            ->where('transaction_status', 'pending')
            ->update(['transaction_status' => 'expire']);

        $pembelian->update(['payment_status' => null, 'payment_reference' => null]);

        return response()->json(['success' => true]);
    }

    public function webhook(Request $request, MidtransService $midtrans)
    {
        $orderId = $request->input('order_id');
        if (! $orderId || ! str_starts_with($orderId, 'BLI-')) return response()->json(['status' => 'skip'], 200);
        $payment = PembelianPayment::where('order_id', $orderId)->first();
        if (! $payment) return response()->json(['status' => 'not found'], 404);
        $this->syncPaymentStatus($payment->pembelian, $midtrans);
        return response()->json(['status' => 'ok']);
    }

    public function riwayat(Request $request)
    {
        $query = Pembelian::where('user_id', auth()->id())
            ->whereIn('status', ['selesai', 'ditolak', 'selesai_dengan_kompensasi', 'dibatalkan'])
            ->with('painting');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $riwayat = $query->latest()->get();

        return view('pembelian.riwayat', compact('riwayat'));
    }

    protected function syncPaymentStatus(Pembelian $pembelian, MidtransService $midtrans): void
    {
        if (! $pembelian->payment_reference) return;
        $payment = PembelianPayment::where('order_id', $pembelian->payment_reference)->first();
        if (! $payment) return;
        try { $tx = $midtrans->getTransactionStatus($payment->order_id); } catch (\Throwable $e) { \Log::error('Midtrans sync error: ' . $e->getMessage()); return; }

        $ts = $tx->transaction_status;
        $fs = $tx->fraud_status ?? null;
        $ps = 'failed';
        if (in_array($ts, ['capture', 'settlement'], true) && ($fs === 'accept' || $fs === null)) $ps = 'paid';
        elseif ($ts === 'pending') $ps = 'pending';
        elseif ($ts === 'expire')  $ps = 'expired';

        $payment->update(['transaction_status' => $ts, 'payment_type' => $tx->payment_type ?? null, 'transaction_id' => $tx->transaction_id ?? null, 'gross_amount' => $tx->gross_amount ?? $payment->gross_amount, 'paid_at' => $ps === 'paid' ? now() : $payment->paid_at, 'payload' => array_merge($payment->payload ?? [], (array) $tx)]);
        $pembelian->update(['payment_status' => $ps, 'paid_at' => $ps === 'paid' ? now() : $pembelian->paid_at, 'status' => $ps === 'paid' ? 'pembayaran_berhasil' : $pembelian->status]);
    }

    protected function authorizeOwner(Pembelian $pembelian): void
    {
        $user = Auth::user();
        if ($user->role === 'pengelola') return;
        if ($pembelian->user_id !== $user->id) abort(403, 'Akses ditolak.');
    }
}
