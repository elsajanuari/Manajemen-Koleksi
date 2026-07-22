<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Penyewaan;
use App\Notifications\PenyewaanStatusNotification;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\DocumentService;
use App\Services\BinderbyteService;


class PenyewaanController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $requests = Auth::user()->penyewaan()->with('painting')->latest()->get();
        $allRequests = Auth::user()->penyewaan()->with('painting')->latest()->get();


        // ✅ FIX: Hapus duplikasi key, gunakan kolom 'status' yang baru secara konsisten
        $counts = [
            'all'                  => $allRequests->count(),
            'draft'                => $allRequests->where('status', 'draft')->count(),
            'menunggu_verifikasi'  => $allRequests->where('status', 'menunggu_verifikasi')->count(),
            'menunggu_pembayaran'  => $allRequests->where('status', 'menunggu_pembayaran')->count(),
            'aktif'                => $allRequests->where('status', 'aktif')->count(),
            'ditolak'              => $allRequests->where('status', 'ditolak')->count(),
            'selesai'              => $allRequests->where('status', 'selesai')->count(),
            'dibatalkan'           => $allRequests->where('status', 'dibatalkan')->count(),
        ];

        $requests = $allRequests;

        // ✅ FIX: Semua filter pakai kolom 'status' baru, bukan submission_status lama
        $filteredRequests = match ($statusFilter) {
            'draft'               => $requests->where('status', 'draft'),
            'menunggu_verifikasi' => $requests->where('status', 'menunggu_verifikasi'),
            'menunggu_pembayaran' => $requests->where('status', 'menunggu_pembayaran'),
            'aktif', 'active'     => $requests->where('status', 'aktif'),
            'ditolak'             => $requests->where('status', 'ditolak'),
            default               => $requests,
        };

        return view('penyewaan.index', compact('requests', 'filteredRequests', 'statusFilter', 'counts'));
    }

    public function showPainting(Koleksi $koleksi)
    {
        $painting = $koleksi;

        return view('penyewaan.show', compact('painting'));
    }

    // ──────────────────────────────────────────
    //  STEP 1 — Jenis Penyewa
    // ──────────────────────────────────────────

    public function step1(Koleksi $koleksi)
    {
        $painting = $koleksi;

        return view('penyewaan.step1', compact('painting', 'koleksi'));
    }
    public function storeStep1(Request $request, Koleksi $koleksi)
    {
        $request->validate([
            'rental_type' => ['required', 'in:perseorangan,instansi'],
        ]);

        $penyewaan = Penyewaan::firstOrCreate(
            [
                'user_id'           => Auth::id(),
                'koleksi_id'        => $koleksi->id,
                'submission_status' => 'draft',
            ],
            [
                'rental_type'  => $request->rental_type,
                'current_step' => 1,
                'status'       => 'draft', // ✅ Pastikan status juga diset
            ]
        );

        $penyewaan->update([
            'rental_type'  => $request->rental_type,
            'current_step' => 1,
        ]);

        session(['penyewaan_step1' => [
            'koleksi_id'  => $koleksi->id,
            'rental_type'  => $request->rental_type,
            'penyewaan_id' => $penyewaan->id,
        ]]);

        return redirect()->route('penyewaan.step2', $koleksi);
    }

    // ──────────────────────────────────────────
    //  STEP 2 — Informasi Pribadi / Instansi
    // ──────────────────────────────────────────

    public function step2(Koleksi $koleksi)
    {
        if (! session()->has('penyewaan_step1') || session('penyewaan_step1')['koleksi_id'] !== $koleksi->id) {
            return redirect()->route('penyewaan.step1', $koleksi)
                ->with('error', 'Silakan pilih jenis penyewa terlebih dahulu.');
        }

        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->first();

        if ($penyewaan && $penyewaan->current_step >= 2) {
            [$jenisInstansi, $jenisInstansiLain] = $this->resolveStoredSelect(
                $penyewaan->jenis_instansi,
                $this->jenisInstansiOptions()
            );

            session(['penyewaan_step2' => [
                // Perseorangan
                'contact_name'             => $penyewaan->contact_name,
                'nik'                      => $penyewaan->nik,
                'tempat_lahir'             => $penyewaan->tempat_lahir,
                'tanggal_lahir'            => $penyewaan->tanggal_lahir ? $penyewaan->tanggal_lahir->format('Y-m-d') : null,
                'jenis_kelamin'            => $penyewaan->jenis_kelamin,
                'pekerjaan'                => $penyewaan->pekerjaan,
                'npwp'                     => $penyewaan->npwp,
                'contact_phone'            => $penyewaan->contact_phone,
                'contact_email'            => $penyewaan->contact_email,
                // Instansi
                'nama_instansi'            => $penyewaan->nama_instansi,
                'jenis_instansi'           => $jenisInstansi,
                'jenis_instansi_lain'      => $jenisInstansiLain,
                'bidang_usaha'             => $penyewaan->bidang_usaha,
                'email_instansi'           => $penyewaan->email_instansi,
                'telepon_kantor'           => $penyewaan->telepon_kantor,
                'website_instansi'         => $penyewaan->website_instansi,
                'alamat_instansi'          => $penyewaan->alamat_instansi,
                'provinsi_instansi'        => $penyewaan->provinsi_instansi,
                'kota_instansi'            => $penyewaan->kota_instansi,
                'kode_pos_instansi'        => $penyewaan->kode_pos_instansi,
                'kecamatan_instansi'       => $penyewaan->kecamatan_instansi,       // ← BARU
                'kelurahan_desa_instansi'  => $penyewaan->kelurahan_desa_instansi,
                'rt_instansi'              => $penyewaan->rt_instansi,
                'rw_instansi'              => $penyewaan->rw_instansi,
                'npwp_instansi'            => $penyewaan->npwp_instansi,
                'nomor_nib'                => $penyewaan->nomor_nib,
                // PIC
                'pic_name'                 => $penyewaan->nama_pic,
                'pic_jabatan'              => $penyewaan->jabatan_pic,
                'pic_nik'                  => $penyewaan->nik_pic,
                'pic_phone'                => $penyewaan->hp_pic,
                'pic_email'                => $penyewaan->email_pic,
                // Alamat domisili
                'alamat_domisili'          => $penyewaan->alamat_domisili,
                'provinsi'                 => $penyewaan->provinsi,
                'kota_kabupaten'           => $penyewaan->kota_kabupaten,
                'kode_pos'                 => $penyewaan->kode_pos,
                'kecamatan'                => $penyewaan->kecamatan,                // ← BARU
                'kelurahan_desa'           => $penyewaan->kelurahan_desa,
                'rt'                       => $penyewaan->rt,
                'rw'                       => $penyewaan->rw,
                // Emsifa IDs
                'dom_province_id'          => $penyewaan->province_id_domisili,
                'dom_city_id'              => $penyewaan->city_id_domisili,
                'dom_district_id'   => $penyewaan->kecamatan_id_domisili,   // ✅
                'inst_province_id'         => $penyewaan->province_id_instansi,
                'inst_city_id'             => $penyewaan->city_id_instansi,
                'inst_district_id'  => $penyewaan->kecamatan_id_instansi,   // ✅
            ]]);
        }

        $rentalType = session('penyewaan_step1.rental_type', 'perseorangan');

        $painting = $koleksi;

        // ✅ Step 2 pakai emsifa (client-side), TIDAK perlu Binderbyte
        return view('penyewaan.step2', compact('painting', 'rentalType'));
    }

public function storeStep2(Request $request, Koleksi $koleksi)
    {
        $rentalType = $request->get('rental_type');

        if ($rentalType === 'perseorangan') {
            $validated = $request->validate([
                'contact_name'  => ['required', 'string', 'max:255'],
                'nik'           => ['required', 'string', 'size:16'],
                'tempat_lahir'  => ['required', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'date'],
                'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                'pekerjaan'     => ['required', 'string', 'max:255'],
                'npwp' => 'nullable|string',
            ]);

            $data = array_merge($validated, [
                'current_step'      => 2,
                'submission_status' => 'draft',
                'kewarganegaraan'   => null,
                'negara_asal'       => null,
            ]);
        } else {
            $validated = $request->validate([
                'nama_instansi'       => ['required', 'string', 'max:255'],
                'jenis_instansi'      => ['required', 'string', 'max:100'],
                'jenis_instansi_lain' => ['required_if:jenis_instansi,Lainnya', 'nullable', 'string', 'max:100'],
                'bidang_usaha'        => ['required', 'string', 'max:255'],
                'email_instansi'      => ['required', 'email', 'max:255'],
                'telepon_kantor'      => ['required', 'string', 'max:25'],
                'website_instansi'    => ['nullable', 'url', 'max:255'],
                'alamat_instansi'     => ['required', 'string', 'max:1000'],
                'provinsi_instansi'   => ['required', 'string', 'max:255'],
                'kota_instansi'       => ['required', 'string', 'max:255'],
                'kode_pos_instansi'   => ['required', 'string', 'max:10'],
                'kelurahan_desa_instansi'  => ['required', 'string', 'max:255'],
                'kecamatan_instansi' => ['required', 'string', 'max:255'],
                'rt_instansi'              => ['required', 'string', 'max:10'],  
                'rw_instansi'              => ['required', 'string', 'max:10'],   
                'npwp_instansi'       => ['required', 'string', 'max:25'],
                'nomor_nib'           => ['nullable', 'string', 'max:50'],
            ]);

            $jenisInstansi = $validated['jenis_instansi'];
            if ($jenisInstansi === 'Lainnya') {
                $jenisInstansi = $validated['jenis_instansi_lain'] ?? null;
                if (! $jenisInstansi) {
                    return back()->withErrors(['jenis_instansi_lain' => 'Silakan isi jenis instansi lain jika memilih Lainnya.'])->withInput();
                }
            }
            unset($validated['jenis_instansi_lain']);
            $validated['jenis_instansi'] = $jenisInstansi;

            $data = array_merge($validated, [
                'current_step'      => 2,
                'submission_status' => 'draft',
            ]);


        }

        // Simpan emsifa IDs ke kolom DB

        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->firstOrFail();

        $penyewaan->update($data);

        // Simpan session step2
        $sessionStep2 = $request->only([
            'contact_name', 'nik', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'pekerjaan', 'npwp',
            'nama_instansi', 'bidang_usaha',
            'email_instansi', 'telepon_kantor', 'website_instansi',
            'alamat_instansi', 'provinsi_instansi', 'kota_instansi', 'kode_pos_instansi',
            'kelurahan_desa_instansi', 'rt_instansi', 'rw_instansi',
            'npwp_instansi', 'nomor_nib',
            'pic_name', 'pic_jabatan', 'pic_nik', 'pic_phone', 'pic_email',
        ]);
        if ($rentalType === 'instansi') {
            $sessionStep2['jenis_instansi']      = $request->input('jenis_instansi');
            $sessionStep2['jenis_instansi_lain'] = $request->input('jenis_instansi_lain');
            $sessionStep2['inst_province_id']     = $request->input('inst_province_id');
            $sessionStep2['inst_city_id']         = $request->input('inst_city_id');  
            $sessionStep2['kecamatan_instansi'] = $request->input('kecamatan_instansi');
            $sessionStep2['inst_district_id']   = $request->input('inst_district_id');
        }
        $sessionStep2['provinsi']       = $request->input('provinsi');
        $sessionStep2['kota_kabupaten'] = $request->input('kota_kabupaten');
        $sessionStep2['kelurahan_desa']   = $request->input('kelurahan_desa');  
        $sessionStep2['rt']               = $request->input('rt');           
        $sessionStep2['rw']               = $request->input('rw');             
        $sessionStep2['dom_province_id']  = $request->input('dom_province_id'); 
        $sessionStep2['dom_city_id']      = $request->input('dom_city_id');    
        $sessionStep2['kode_pos']         = $request->input('kode_pos');       
        $sessionStep2['alamat_domisili']  = $request->input('alamat_domisili'); 
        $sessionStep2['kecamatan']         = $request->input('kecamatan');
        $sessionStep2['dom_district_id']   = $request->input('dom_district_id');

        session(['penyewaan_step2' => $sessionStep2]);

        // Validasi & simpan kontak + alamat (gabungan step3 lama)
        $rulesStep3 = [
            'alamat_ktp' => ['nullable', 'string', 'max:1000'],
            'alamat_domisili' => ['required', 'string', 'max:1000'],
            'rt'              => ['required', 'string', 'max:10'],
            'rw'              => ['required', 'string', 'max:10'],
            'kelurahan_desa'  => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'provinsi'        => ['required', 'string', 'max:255'],
            'kota_kabupaten'  => ['required', 'string', 'max:255'],
            'kode_pos'        => ['required', 'string', 'max:10'],
        ];

        if ($rentalType === 'instansi') {
            $rulesStep3 = array_merge($rulesStep3, [
                'pic_name'    => ['required', 'string', 'max:255'],
                'pic_jabatan' => ['required', 'string', 'max:255'],
                'pic_nik'     => ['required', 'string', 'size:16'],
                'pic_phone'   => ['required', 'string', 'max:25'],
                'pic_email'   => ['required', 'email', 'max:255'],
            ]);
        } else {
            $rulesStep3['contact_phone'] = ['required', 'string', 'max:25'];
            $rulesStep3['contact_email'] = ['required', 'email', 'max:255'];
        }

        $validatedStep3 = $request->validate($rulesStep3);

        if ($rentalType === 'instansi') {
            // Map pic fields → contact fields untuk disimpan ke DB
            $validatedStep3['contact_phone'] = $validatedStep3['pic_phone'];
            $validatedStep3['contact_email'] = $validatedStep3['pic_email'];
            $validatedStep3['nama_pic']      = $validatedStep3['pic_name'];
            $validatedStep3['jabatan_pic']   = $validatedStep3['pic_jabatan'];
            $validatedStep3['nik_pic']       = $validatedStep3['pic_nik'];
            $validatedStep3['hp_pic']        = $validatedStep3['pic_phone'];
            $validatedStep3['email_pic']     = $validatedStep3['pic_email'];
        }

    // Simpan emsifa IDs ke kolom DB
    $emsifaIds = [
        'province_id_domisili' => $request->input('dom_province_id'),
        'city_id_domisili'     => $request->input('dom_city_id'),
        'kecamatan_id_domisili'  => $request->input('dom_district_id'), // ⬅️ ganti nama key
    ];
    if ($rentalType === 'instansi') {
        $emsifaIds['province_id_instansi'] = $request->input('inst_province_id');
        $emsifaIds['city_id_instansi']     = $request->input('inst_city_id');
        $emsifaIds['kecamatan_id_instansi'] = $request->input('inst_district_id'); // ⬅️ ganti nama key
    }

    $penyewaan->update(array_merge($validatedStep3, $emsifaIds, ['current_step' => 2]));
        // Simpan data lokasi & keamanan ke DB sejak step2
        $lokasiFilled = array_filter($request->only([
            'jenis_tempat', 'indoor_outdoor', 'alamat_lengkap',
            'tujuan_penyewaan',
            'cctv', 'keamanan', 'ber_ac', 'risiko_cuaca',
            'start_date', 'end_date',
            'bank_name', 'account_number', 'account_holder',
        ]), fn($v) => $v !== null && $v !== '');

        if (!empty($lokasiFilled)) {
            $penyewaan->update($lokasiFilled);
        }

        session(['penyewaan_step3' => $request->only(array_keys($rulesStep3))]);

        if ($request->has('save_draft')) {
            return redirect()->route('penyewaan.requests')
                ->with('success', 'Draft berhasil disimpan.');
        }

        return redirect()->route('penyewaan.step3', $koleksi);
    }

    // ──────────────────────────────────────────
    //  STEP 3 — Kontak & Alamat
    // ──────────────────────────────────────────

    public function step3(Koleksi $koleksi)
    {
        if (! session()->has('penyewaan_step1') || session('penyewaan_step1')['koleksi_id'] !== $koleksi->id) {
            return redirect()->route('penyewaan.step1', $koleksi)
                ->with('error', 'Silakan pilih jenis penyewa terlebih dahulu.');
        }

        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->first();

        $rentalType = session('penyewaan_step1.rental_type', 'perseorangan');

        // ✅ Inisialisasi WAJIB sebelum compact()
        $jenisTempatOptions = $this->jenisTempatOptions($rentalType);
        $tujuanOptions      = $this->tujuanPenyewaanOptions($rentalType);

        // ✅ Load provinces & cities (sama seperti di step4)
        $binderbyteService = app(BinderbyteService::class);
        $provinces         = $binderbyteService->getProvinces() ?? [];
        $citiesGrouped     = [];
        foreach ($provinces as $prov) {
            $cities = $binderbyteService->getCitiesByProvince($prov['id']) ?? [];
            $citiesGrouped[$prov['id']] = $cities;
        }

        if ($penyewaan && $penyewaan->current_step >= 3) {
            $step3 = [
                'alamat_ktp'      => $penyewaan->alamat_ktp,
                'alamat_domisili' => $penyewaan->alamat_domisili,
                'rt'              => $penyewaan->rt,
                'rw'              => $penyewaan->rw,
                'kecamatan'       => $penyewaan->kecamatan,                 // ⬅️ BARU
                'kecamatan_id'    => $penyewaan->kecamatan_id,              // ⬅️ BARU
                'kelurahan_desa'  => $penyewaan->kelurahan_desa,
                'provinsi'        => $penyewaan->provinsi,
                'kota_kabupaten'  => $penyewaan->kota_kabupaten,
                'kode_pos'        => $penyewaan->kode_pos,
                'province_id'     => $penyewaan->province_id,
                'city_name'       => $penyewaan->city_name,
            ];
            if ($rentalType === 'instansi') {
                $step3 = array_merge($step3, [
                    'nama_pic'    => $penyewaan->nama_pic,
                    'jabatan_pic' => $penyewaan->jabatan_pic,
                    'nik_pic'     => $penyewaan->nik_pic,
                    'hp_pic'      => $penyewaan->hp_pic,
                    'email_pic'   => $penyewaan->email_pic,
                ]);
            } else {
                $step3['contact_phone'] = $penyewaan->contact_phone;
                $step3['contact_email'] = $penyewaan->contact_email;
            }
            session(['penyewaan_step3' => $step3]);

            [$jenisTempat, $jenisTempatLain] = $this->resolveStoredSelect(
                $penyewaan->jenis_tempat,
                $jenisTempatOptions
            );
            [$sessionTujuan, $tujuanPenyewaanLain] = $this->resolveStoredSelect(
                $penyewaan->tujuan_penyewaan,
                $tujuanOptions
            );

            session(['penyewaan_step4' => [
                'start_date'            => $penyewaan->start_date ? $penyewaan->start_date->format('Y-m-d') : null,
                'end_date'              => $penyewaan->end_date   ? $penyewaan->end_date->format('Y-m-d')   : null,
                'jenis_tempat'          => $jenisTempat,
                'jenis_tempat_lain'     => $jenisTempatLain,
                'indoor_outdoor'        => $penyewaan->indoor_outdoor,
                'alamat_lengkap'        => $penyewaan->alamat_lengkap,
                'rt'                    => $penyewaan->rt,
                'rw'                    => $penyewaan->rw,
                'kecamatan'             => $penyewaan->kecamatan,        // ⬅️ BARU
                'kelurahan_desa'        => $penyewaan->kelurahan_desa,
                'kota_kabupaten'        => $penyewaan->kota_kabupaten,
                'kode_pos'              => $penyewaan->kode_pos,
                'tujuan_penyewaan'      => $sessionTujuan,
                'tujuan_penyewaan_lain' => $tujuanPenyewaanLain,
                'cctv'                  => $penyewaan->cctv,
                'keamanan'              => $penyewaan->keamanan,
                'ber_ac'                => $penyewaan->ber_ac,
                'risiko_cuaca'          => $penyewaan->risiko_cuaca,
                'bank_name'             => $penyewaan->bank_name,
                'account_number'        => $penyewaan->account_number,
                'account_holder'        => $penyewaan->account_holder,
                'agree_terms'           => $penyewaan->agree_terms,           // ⬅️ BARU
                'agree_responsibility'  => $penyewaan->agree_responsibility,  // ⬅️ BARU
                'agree_privacy'         => $penyewaan->agree_privacy, 
            ]]);
        }

        $costs = $this->calculateRentalCosts($koleksi, session('penyewaan_step4', []));

        $painting = $koleksi;

        return view('penyewaan.step3', compact(
            'painting', 'rentalType', 'jenisTempatOptions', 'tujuanOptions',
            'provinces', 'citiesGrouped', 'penyewaan'
        ) + $costs);
    }

    public function storeStep3(Request $request, Koleksi $koleksi)
    {
        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->firstOrFail();

        $rentalType = $request->input(
            'rental_type',
            session('penyewaan_step1.rental_type', $penyewaan->rental_type)
        );

        // ── Save draft (tanpa validasi penuh) ──
        if ($request->has('save_draft') || $request->input('back')) {
            $fileFields = $rentalType === 'instansi'
                ? ['upload_surat_pengajuan','upload_ktp_pic','upload_npwp_instansi',
                'upload_foto_lokasi','upload_denah']
                : ['upload_ktp','upload_npwp','upload_foto_lokasi','upload_denah'];

            $uploadedFiles = [];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $uploadedFiles[$field] = $request->file($field)
                        ->store('penyewaan-documents', 'public');
                }
            }

            // SESUDAH — tambahkan province_id dan destination_city_id ke $draftData,
            // dan simpan juga ke session penyewaan_step3:
            $draftData = array_filter(
                $request->only([
                    'start_date','end_date',
                    'jenis_tempat','jenis_tempat_lain',
                    'indoor_outdoor','alamat_lengkap',
                    'rt','rw','kecamatan','kelurahan_desa','kota_kabupaten','kode_pos','provinsi',
                    'city_name','destination_city_id',
                    'tujuan_penyewaan','tujuan_penyewaan_lain',
                    'cctv','keamanan','ber_ac','risiko_cuaca',
                    'bank_name','account_number','account_holder',
                ]),
                fn($v) => $v !== null && $v !== ''
            );

            $draftExtra = [
                'province_id_lokasi'   => $request->input('province_id') ?: null,
                'agree_terms'          => $request->boolean('agree_terms'),
                'agree_responsibility' => $request->boolean('agree_responsibility'),
                'agree_privacy'        => $request->boolean('agree_privacy'),
            ];

            // TAMBAH: simpan wilayah ke session step3 agar restore saat kembali
            session(['penyewaan_step3' => array_merge(session('penyewaan_step3', []), array_filter([
                'province_id'         => $request->input('province_id'),
                'city_name'           => $request->input('city_name'),
                'kota_kabupaten'      => $request->input('kota_kabupaten'),
                'provinsi'            => $request->input('provinsi'),
                'kecamatan'           => $request->input('kecamatan'),
                'kelurahan_desa'      => $request->input('kelurahan_desa'),
                'kode_pos'            => $request->input('kode_pos'),
                'destination_city_id' => $request->input('destination_city_id'),
                'rt'                  => $request->input('rt'),
                'rw'                  => $request->input('rw'),
            ], fn($v) => $v !== null && $v !== ''))]);

            $penyewaan->update(array_merge($draftData, $draftExtra, $uploadedFiles, ['current_step' => 3]));

            if ($request->input('back')) {
                // Simpan data step3 ke session agar tidak hilang saat balik ke step3
                session(['penyewaan_step4' => array_merge(session('penyewaan_step4', []), array_filter([
                    'jenis_tempat'          => $request->input('jenis_tempat'),
                    'jenis_tempat_lain'     => $request->input('jenis_tempat_lain'),
                    'indoor_outdoor'        => $request->input('indoor_outdoor'),
                    'alamat_lengkap'        => $request->input('alamat_lengkap'),
                    'rt'                    => $request->input('rt'),
                    'rw'                    => $request->input('rw'),
                    'kecamatan'             => $request->input('kecamatan'),        // ⬅️ BARU
                    'kelurahan_desa'        => $request->input('kelurahan_desa'),
                    'kota_kabupaten'        => $request->input('kota_kabupaten'),
                    'kode_pos'              => $request->input('kode_pos'),
                    'provinsi'              => $request->input('provinsi'),
                    'tujuan_penyewaan'      => $request->input('tujuan_penyewaan'),
                    'tujuan_penyewaan_lain' => $request->input('tujuan_penyewaan_lain'),
                    'cctv'                  => $request->input('cctv'),
                    'keamanan'              => $request->input('keamanan'),
                    'ber_ac'                => $request->input('ber_ac'),
                    'risiko_cuaca'          => $request->input('risiko_cuaca'),
                    'start_date'            => $request->input('start_date'),
                    'end_date'              => $request->input('end_date'),
                    'bank_name'             => $request->input('bank_name'),
                    'account_number'        => $request->input('account_number'),
                    'account_holder'        => $request->input('account_holder'),
                ], fn($v) => $v !== null && $v !== ''))]);

                session(['penyewaan_step3' => array_merge(session('penyewaan_step3', []), array_filter([
                    'province_id'    => $request->input('province_id'),
                    'city_name'      => $request->input('city_name'),
                    'kota_kabupaten' => $request->input('kota_kabupaten'),
                    'provinsi'       => $request->input('provinsi'),
                    'kecamatan'      => $request->input('kecamatan'),       // ⬅️ BARU
                ], fn($v) => $v !== null && $v !== ''))]);

                // Update session step2 dengan province_id domisili
                session(['penyewaan_step2' => array_merge(session('penyewaan_step2', []), [
                    'province_id'    => $request->input('province_id'),
                    'kota_kabupaten' => $request->input('kota_kabupaten'),
                    'provinsi'       => $request->input('provinsi'),
                ])]);

                return redirect()->route('penyewaan.step2', $koleksi);
            }

            return redirect()->route('penyewaan.requests')
                ->with('success', 'Draft berhasil disimpan.');
        }

        // ── Submit pengajuan — validasi penuh ──
        $startMinimum = Carbon::today()->addDays(7)->format('Y-m-d');

        $validated = $request->validate([
            'start_date'            => ['required','date','after_or_equal:'.$startMinimum],
            'end_date'              => ['required','date','after_or_equal:start_date'],
            'jenis_tempat'          => ['required','string','max:100'],
            'jenis_tempat_lain'     => ['required_if:jenis_tempat,Lainnya','nullable','string','max:100'],
            'indoor_outdoor'        => ['required','in:Indoor,Outdoor'],
            'alamat_lengkap'        => ['required','string','max:1000'],
            'rt'                    => ['required','string','max:10'],
            'rw'                    => ['required','string','max:10'],
            'kecamatan'             => ['required','string','max:255'],
            'kelurahan_desa'        => ['required','string','max:255'],
            'kota_kabupaten'        => ['required','string','max:255'],
            'kode_pos'              => ['required','string','max:10'],
            'provinsi'              => ['nullable','string','max:255'],
            'tujuan_penyewaan'      => ['required','string','max:100'],
            'tujuan_penyewaan_lain' => ['required_if:tujuan_penyewaan,Lainnya','nullable','string','max:100'],
            'cctv'                  => ['required','in:ya,tidak'],
            'keamanan'              => ['required','in:ya,tidak'],
            'ber_ac'                => ['nullable','in:ya,tidak'],
            'risiko_cuaca'          => ['required','in:ya,tidak'],
            'bank_name'             => ['required','string','max:100'],
            'account_number'        => ['required','string','max:50'],
            'account_holder'        => ['required','string','max:255'],
            'agree_terms'           => ['required','accepted'],
            'agree_responsibility'  => ['required','accepted'],
            'agree_privacy'         => ['required','accepted'],
            'city_name'             => ['nullable','string','max:100'],
            'province_id'           => ['nullable','string','max:10'],
            'destination_city_id'   => ['nullable','integer'],
        ]);

        // Resolve jenis_tempat
        if ($validated['jenis_tempat'] === 'Lainnya') {
            if (empty($validated['jenis_tempat_lain'])) {
                return back()->withErrors(['jenis_tempat_lain' => 'Isi jenis tempat lainnya.'])->withInput();
            }
            $validated['jenis_tempat'] = $validated['jenis_tempat_lain'];
        }
        unset($validated['jenis_tempat_lain']);

        // Resolve tujuan_penyewaan
        if ($validated['tujuan_penyewaan'] === 'Lainnya') {
            if (empty($validated['tujuan_penyewaan_lain'])) {
                return back()->withErrors(['tujuan_penyewaan_lain' => 'Isi tujuan lainnya.'])->withInput();
            }
            $validated['tujuan_penyewaan'] = $validated['tujuan_penyewaan_lain'];
        }
        unset($validated['tujuan_penyewaan_lain']);

        // Cek durasi maks 1 bulan
        $start = Carbon::parse($validated['start_date']);
        $end   = Carbon::parse($validated['end_date']);
        if ($end->gt($start->copy()->addMonth())) {
            return back()->withErrors(['end_date' => 'Durasi sewa maksimal 1 bulan.'])->withInput();
        }

        // Upload dokumen
        if ($rentalType === 'perseorangan') {
            $request->validate([
                'upload_ktp'         => $penyewaan->upload_ktp
                    ? ['nullable','file','mimes:pdf','max:2048']
                    : ['required','file','mimes:pdf','max:2048'],
                'upload_npwp'        => ['nullable','file','mimes:pdf','max:2048'],
                'upload_foto_lokasi' => ['nullable','file','mimes:pdf','max:5120'],
                'upload_denah'       => ['nullable','file','mimes:pdf','max:5120'],
            ]);
            $fileFields = ['upload_ktp','upload_npwp','upload_foto_lokasi','upload_denah'];
        } else {
            $request->validate([
                'upload_surat_pengajuan' => $penyewaan->upload_surat_pengajuan
                    ? ['nullable','file','mimes:pdf','max:5120']
                    : ['required','file','mimes:pdf','max:5120'],
                'upload_ktp_pic'       => $penyewaan->upload_ktp_pic
                    ? ['nullable','file','mimes:pdf','max:2048']
                    : ['required','file','mimes:pdf','max:2048'],
                'upload_npwp_instansi' => $penyewaan->upload_npwp_instansi
                    ? ['nullable','file','mimes:pdf','max:2048']
                    : ['required','file','mimes:pdf','max:2048'],
                'upload_proposal'      => ['nullable','file','mimes:pdf','max:10240'],
                'upload_foto_lokasi'   => ['nullable','file','mimes:pdf','max:5120'],
                'upload_denah'         => ['nullable','file','mimes:pdf','max:5120'],
            ]);
            $fileFields = [
                'upload_surat_pengajuan','upload_ktp_pic','upload_npwp_instansi',
                'upload_proposal','upload_foto_lokasi','upload_denah',
            ];
        }

        $uploadedFiles = [];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $uploadedFiles[$field] = $request->file($field)
                    ->store('penyewaan-documents', 'public');
            }
        }

        // Hitung biaya
        $durationDays = $start->diffInDays($end) + 1;
        $subtotal     = ($koleksi->daily_rate ?? 0) * $durationDays;
        $deposit      = (int) round($subtotal * 0.5);
        $total        = $subtotal + $deposit;

        $invoiceData = $this->invoiceDataFromPenyewaan($penyewaan, $rentalType);

        if (array_key_exists('province_id', $validated)) {
            $validated['province_id_lokasi'] = $validated['province_id'];
            unset($validated['province_id']);
        }
        // Hapus field agreement sebelum update DB
        unset(
            $validated['agree_terms'],
            $validated['agree_responsibility'],
            $validated['agree_privacy']
        );

        // Di storeStep3, setelah validasi, sebelum $penyewaan->update(...)
        // Jika destination_city_id kosong, coba resolve dari city_name
        if (empty($validated['destination_city_id']) && ! empty($validated['city_name'])) {
            try {
                $response = \Illuminate\Support\Facades\Http::get(
                    config('app.url') . '/api/rajaongkir/find-city',
                    [
                        'city_name'     => $validated['city_name'],
                        'province_name' => $validated['provinsi'] ?? '',
                    ]
                );
                if ($response->ok() && $response->json('city_id')) {
                    $validated['destination_city_id'] = $response->json('city_id');
                }
            } catch (\Exception $e) {
                // biarkan kosong, fallback tetap ada di view pengelola
            }
        }

        $penyewaan->update(array_merge($validated, $uploadedFiles, $invoiceData, [
            'current_step'      => 3,
            'submission_status' => 'submitted',
            'submitted_at'      => now(),
            'status'            => 'menunggu_verifikasi',
            'subtotal_amount'   => $subtotal,
            'deposit_amount'    => $deposit,
            'total_bayar'       => $total,
            'agree_terms'          => true,            // ⬅️ BARU
            'agree_responsibility' => true,            // ⬅️ BARU
            'agree_privacy'        => true,      
        ]));

        session()->forget([
            'penyewaan_step1','penyewaan_step2',
            'penyewaan_step3','penyewaan_step4','penyewaan_step5',
        ]);

        return redirect()->route('penyewaan.requests')
            ->with('success', 'Pengajuan berhasil dikirim. Menunggu verifikasi pengelola.');
    }

    // ──────────────────────────────────────────
    //  STEP 4 — Data Penyewaan
    // ──────────────────────────────────────────

    public function step4(Koleksi $koleksi)
    {
        if (! session()->has('penyewaan_step1') || session('penyewaan_step1')['koleksi_id'] !== $koleksi->id) {
            return redirect()->route('penyewaan.step1', $koleksi)
                ->with('error', 'Silakan pilih jenis penyewa terlebih dahulu.');
        }

        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->first();

        $rentalType = session('penyewaan_step1.rental_type', 'perseorangan');
        $jenisTempatOptions = $this->jenisTempatOptions($rentalType);
        $tujuanOptions = $this->tujuanPenyewaanOptions($rentalType);

        if ($penyewaan && $penyewaan->current_step >= 4) {
            [$jenisTempat, $jenisTempatLain] = $this->resolveStoredSelect($penyewaan->jenis_tempat, $jenisTempatOptions);
            [$sessionTujuan, $tujuanPenyewaanLain] = $this->resolveStoredSelect($penyewaan->tujuan_penyewaan, $tujuanOptions);

            session(['penyewaan_step4' => [
                'start_date'            => $penyewaan->start_date ? $penyewaan->start_date->format('Y-m-d') : null,
                'end_date'              => $penyewaan->end_date ? $penyewaan->end_date->format('Y-m-d') : null,
                'jenis_tempat'          => $jenisTempat,
                'jenis_tempat_lain'     => $jenisTempatLain,
                'indoor_outdoor'        => $penyewaan->indoor_outdoor,
                'alamat_lengkap' => $penyewaan->alamat_lengkap,
                'tujuan_penyewaan'      => $sessionTujuan,
                'tujuan_penyewaan_lain' => $tujuanPenyewaanLain,
                'cctv'                  => $penyewaan->cctv,
                'keamanan'              => $penyewaan->keamanan,
                'ber_ac'                => $penyewaan->ber_ac,
                'risiko_cuaca'          => $penyewaan->risiko_cuaca,
            ]]);
        }

        $costs = $this->calculateRentalCosts($koleksi, session('penyewaan_step4', []));

        $painting = $koleksi;

        return view('penyewaan.step4', compact('painting', 'rentalType', 'jenisTempatOptions', 'tujuanOptions') + $costs);
    }

    public function storeStep4(Request $request, Koleksi $koleksi)
    {
        $startMinimum = Carbon::today()->addDays(7)->format('Y-m-d');

        $validated = $request->validate([
            'start_date'            => ['required', 'date', 'after_or_equal:'.$startMinimum],
            'end_date'              => ['required', 'date', 'after_or_equal:start_date'],
            'jenis_tempat'          => ['required', 'string', 'max:100'],
            'jenis_tempat_lain'     => ['required_if:jenis_tempat,Lainnya', 'nullable', 'string', 'max:100'],
            'indoor_outdoor'        => ['required', 'in:Indoor,Outdoor'],
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'tujuan_penyewaan'      => ['required', 'string', 'max:100'],
            'tujuan_penyewaan_lain' => ['required_if:tujuan_penyewaan,Lainnya', 'nullable', 'string', 'max:100'],
            'cctv'                  => ['required', 'in:ya,tidak'],
            'keamanan'              => ['required', 'in:ya,tidak'],
            'ber_ac'                => ['nullable', 'in:ya,tidak'],
            'risiko_cuaca'          => ['required', 'in:ya,tidak'],
        ]);

        $dbTujuan = $validated['tujuan_penyewaan'];
        $sessionTujuan = $validated['tujuan_penyewaan'];
        if ($validated['tujuan_penyewaan'] === 'Lainnya') {
            $dbTujuan = $validated['tujuan_penyewaan_lain'];
            $sessionTujuan = 'Lainnya';
        }
        unset($validated['tujuan_penyewaan_lain']);
        $validated['tujuan_penyewaan'] = $dbTujuan;

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        if ($end->gt($start->copy()->addMonth())) {
            return back()->withErrors(['end_date' => 'Durasi sewa maksimal 1 bulan dari tanggal mulai.'])->withInput();
        }

        $jenisTempat = $validated['jenis_tempat'];
        if ($jenisTempat === 'Lainnya') {
            $jenisTempat = $validated['jenis_tempat_lain'] ?? null;
            if (! $jenisTempat) {
                return back()->withErrors(['jenis_tempat_lain' => 'Silakan isi jenis tempat lain jika memilih Lainnya.'])->withInput();
            }
        }
        unset($validated['jenis_tempat_lain']);
        $validated['jenis_tempat'] = $jenisTempat;

        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->firstOrFail();

        $penyewaan->update(array_merge($validated, [
            'current_step' => 4,
        ]));

        $sessionData = $validated;
        $sessionData['tujuan_penyewaan'] = $sessionTujuan;
        if ($sessionTujuan === 'Lainnya') {
            $sessionData['tujuan_penyewaan_lain'] = $dbTujuan;
        }
        $sessionData['jenis_tempat'] = $request->input('jenis_tempat');
        $sessionData['jenis_tempat_lain'] = $request->input('jenis_tempat_lain');
        session(['penyewaan_step4' => $sessionData]);

        if ($request->has('save_draft')) {
            return redirect()->route('penyewaan.requests')
                ->with('success', 'Draft berhasil disimpan. Anda dapat melanjutkan kapan saja.');
        }

        return redirect()->route('penyewaan.step5', $koleksi);
    }

    // ──────────────────────────────────────────
    //  STEP 5 — Upload Dokumen & Persetujuan
    // ──────────────────────────────────────────

    public function step5(Koleksi $koleksi)
    {
        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->first();

        if (! session()->has('penyewaan_step1') || session('penyewaan_step1')['koleksi_id'] !== $koleksi->id) {
            if (! $penyewaan) {
                return redirect()->route('penyewaan.step1', $koleksi)
                    ->with('error', 'Silakan pilih jenis penyewa terlebih dahulu.');
            }

            session(['penyewaan_step1' => [
                'koleksi_id' => $koleksi->id,
                'rental_type' => $penyewaan->rental_type,
            ]]);
        }

        $rentalType = session('penyewaan_step1.rental_type', $penyewaan?->rental_type ?? 'perseorangan');

        $painting = $koleksi;

        return view('penyewaan.step5', compact('painting', 'rentalType', 'penyewaan'));
    }

    public function step6(Koleksi $koleksi)
    {
        return redirect()->route('penyewaan.step5', $koleksi);
    }

    public function store(Request $request, Koleksi $koleksi)
    {
        // ✅ Simpan draft ke database tanpa validasi file
        if ($request->has('save_draft')) {
            $rentalType = $request->input('rental_type', session('penyewaan_step1.rental_type', 'perseorangan'));
            $fileFields = $rentalType === 'instansi'
                ? ['upload_surat_pengajuan', 'upload_ktp_pic', 'upload_npwp_instansi', 'upload_proposal', 'upload_foto_lokasi', 'upload_denah']
                : ['upload_ktp', 'upload_npwp', 'upload_foto_lokasi', 'upload_denah'];

            $uploadedFiles = [];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $uploadedFiles[$field] = $request->file($field)->store('penyewaan-documents', 'public');
                }
            }

            session(['penyewaan_step5' => $request->only([
                'agree_terms',
                'agree_responsibility',
                'agree_privacy',
            ])]);

            $penyewaan = Penyewaan::where('user_id', Auth::id())
                ->where('koleksi_id', $koleksi->id)
                ->where('submission_status', 'draft')
                ->first();

            if ($penyewaan) {
                $penyewaan->update(array_merge($uploadedFiles, ['current_step' => 5]));
            }

            return redirect()->route('penyewaan.requests')
                ->with('success', 'Draft berhasil disimpan. Anda dapat melanjutkan kapan saja.');
        }

        // ✅ Submit pengajuan — validasi penuh
        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->firstOrFail();

        $rentalType = $request->input('rental_type', session('penyewaan_step1.rental_type', $penyewaan->rental_type));

        if ($rentalType === 'perseorangan') {
            $request->validate([
                'upload_ktp'           => $penyewaan->upload_ktp ? ['nullable', 'file', 'mimes:pdf', 'max:2048'] : ['required', 'file', 'mimes:pdf', 'max:2048'],
                'upload_npwp' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
                'upload_foto_lokasi'   => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
                'upload_denah'         => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
                'agree_terms'          => ['required', 'accepted'],
                'agree_responsibility' => ['required', 'accepted'],
                'agree_privacy'        => ['required', 'accepted'],
            ]);

            $fileFields = [
                'upload_ktp', 'upload_npwp',
                'upload_foto_lokasi', 'upload_denah',
            ];
        } else {
            $request->validate([
                'upload_surat_pengajuan' => $penyewaan->upload_surat_pengajuan ? ['nullable', 'file', 'mimes:pdf', 'max:5120'] : ['required', 'file', 'mimes:pdf', 'max:5120'],
                'upload_ktp_pic'         => $penyewaan->upload_ktp_pic ? ['nullable', 'file', 'mimes:pdf', 'max:2048'] : ['required', 'file', 'mimes:pdf', 'max:2048'],
                'upload_npwp_instansi'   => $penyewaan->upload_npwp_instansi ? ['nullable', 'file', 'mimes:pdf', 'max:2048'] : ['required', 'file', 'mimes:pdf', 'max:2048'],
                'upload_proposal'        => $penyewaan->upload_proposal ? ['nullable', 'file', 'mimes:pdf', 'max:10240'] : ['required', 'file', 'mimes:pdf', 'max:10240'],
                'upload_foto_lokasi'     => $penyewaan->upload_foto_lokasi ? ['nullable', 'file', 'mimes:pdf', 'max:5120'] : ['required', 'file', 'mimes:pdf', 'max:5120'],
                'upload_denah'           => $penyewaan->upload_denah ? ['nullable', 'file', 'mimes:pdf', 'max:5120'] : ['required', 'file', 'mimes:pdf', 'max:5120'],
                'agree_terms'            => ['required', 'accepted'],
                'agree_responsibility'   => ['required', 'accepted'],
                'agree_privacy'          => ['required', 'accepted'],
            ]);

            $fileFields = [
                'upload_surat_pengajuan', 'upload_ktp_pic', 'upload_npwp_instansi',
                'upload_proposal', 'upload_foto_lokasi', 'upload_denah',
            ];
        }

        $uploadedFiles = [];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $uploadedFiles[$field] = $request->file($field)->store('penyewaan-documents', 'public');
            }
        }

        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->firstOrFail();

        $invoiceData = $this->invoiceDataFromPenyewaan($penyewaan, $rentalType);

        // ✅ Status konsisten: draft → menunggu_verifikasi saat submit
        $penyewaan->update(array_merge($uploadedFiles, $invoiceData, [
            'submission_status' => 'submitted',
            'submitted_at'      => now(),
            'current_step'      => 5,
            'status'            => 'menunggu_verifikasi',
        ]));

        session()->forget([
            'penyewaan_step1', 'penyewaan_step2', 'penyewaan_step3',
            'penyewaan_step4', 'penyewaan_step5',
        ]);

        return redirect()->route('penyewaan.requests')
            ->with('success', 'Pengajuan penyewaan berhasil dikirim. Menunggu verifikasi pengelola.');
    }

    // ──────────────────────────────────────────
    //  Riwayat & Detail Pengajuan
    // ──────────────────────────────────────────

    public function requests(Request $request)
    {
        return redirect()->route('penyewaan.index', ['status' => $request->query('status', 'all')]);
    }

    public function show(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);
        $penyewaan->loadMissing('serahTerima');
        $penyewaan->syncLegacyShippingStatus();
        $penyewaan->refresh();
        $serahTerima = $penyewaan->serahTerima;
        
         return view('penyewaan.request-show', compact('penyewaan', 'serahTerima'));
    }

    public function riwayat(Request $request)
    {
        $filterStatus = $request->get('status');
        $filterDari   = $request->get('dari');
        $filterSampai = $request->get('sampai');

        $query = Penyewaan::with('painting')
            ->where('user_id', auth()->id())
            ->whereIn('status', ['selesai', 'ditolak', 'dibatalkan'])
            ->latest();

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        if ($filterDari) {
            $query->whereDate('created_at', '>=', $filterDari);
        }

        if ($filterSampai) {
            $query->whereDate('created_at', '<=', $filterSampai);
        }

        $riwayat = $query->get();

        return view('penyewaan.riwayat', compact('riwayat'));
    }

    public function edit(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->status !== 'menunggu_verifikasi') {
            return redirect()->route('penyewaan.requests')
                ->with('error', 'Pengajuan tidak dapat diubah setelah diverifikasi atau dibatalkan.');
        }

        return view('penyewaan.edit', compact('penyewaan'));
    }

    public function update(Request $request, Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->status !== 'menunggu_verifikasi') {
            return redirect()->route('penyewaan.requests')
                ->with('error', 'Pengajuan tidak dapat diubah setelah diverifikasi atau dibatalkan.');
        }

        $validated = $this->validatePenyewaan($request);
        $penyewaan->update($validated);

        return redirect()->route('penyewaan.requests')
            ->with('success', 'Data pengajuan berhasil diperbarui.');
    }

    public function cancel(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        // ✅ Pakai status baru 'dibatalkan'
        $penyewaan->update(['status' => 'dibatalkan']);

        return redirect()->route('penyewaan.requests')
            ->with('success', 'Pengajuan berhasil dibatalkan.');
    }

    public function destroy(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->submission_status !== 'draft') {
            return redirect()->route('penyewaan.requests')
                ->with('error', 'Hanya draft yang dapat dihapus.');
        }

        $penyewaan->delete();

        return redirect()->route('penyewaan.index')
            ->with('success', 'Draft berhasil dihapus.');
    }

    public function downloadAgreement(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        if (! $penyewaan->agreement_document_path) {
            abort(404);
        }

        // Generate ulang dari template terbaru
        $pdf = Pdf::loadView('documents.agreement', compact('penyewaan'))
            ->setPaper('a4', 'portrait');

        $path = 'agreements/' . now()->format('YmdHis') . '-' . $penyewaan->id . '-perjanjian.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        // Update path di DB agar selalu pakai yang terbaru
        $penyewaan->update(['agreement_document_path' => $path]);

        return Storage::disk('public')->download(
            $path,
            'Surat-Perjanjian-' . $penyewaan->id . '.pdf'
        );
    }

    public function downloadInvoice(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        // Siapkan base64 logo & tanda tangan
        $logoPath   = public_path('images/logo.png');
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : '';

        $ttdBase64 = '';
        $ttdFiles  = Storage::disk('public')->files('ttd');
        if (! empty($ttdFiles)) {
            $ttdBase64 = 'data:image/jpeg;base64,'
                . base64_encode(Storage::disk('public')->get($ttdFiles[0]));
        }

        // Generate ulang PDF dengan logo & ttd
        $pdf  = Pdf::loadView('documents.invoice', compact('penyewaan', 'logoBase64', 'ttdBase64'))
            ->setPaper('a4', 'portrait');
        $path = 'invoices/' . now()->format('YmdHis') . '-' . Str::slug($penyewaan->painting->title) . '-invoice.pdf';
        Storage::disk('public')->put($path, $pdf->output());
        $penyewaan->update(['invoice_document_path' => $path]);

        return Storage::disk('public')->download($path, 'Invoice-Penyewaan-' . $penyewaan->id . '.pdf');
    }

    public function uploadSignedAgreement(Request $request, Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->status !== 'menunggu_dokumen_perjanjian') {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('error', 'Perjanjian hanya dapat diunggah setelah dokumen perjanjian dibuat oleh pengelola.');
        }

        $request->validate([
            'signed_agreement' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $path = $request->file('signed_agreement')->store('signed-agreements', 'public');

        $penyewaan->update([
            'signed_agreement_path'   => $path,
            'signed_agreement_status' => 'uploaded',
            'status'                  => 'verifikasi_dokumen_perjanjian', // ✅ Update status
        ]);

        return redirect()->route('penyewaan.requests.show', $penyewaan)
            ->with('success', 'Dokumen perjanjian berhasil diunggah. Tunggu validasi pengelola.');
    }

    public function previewReturnDocument(Penyewaan $penyewaan)
    {
        $st = $penyewaan->serahTerima; // sesuaikan relasi
        abort_if(!$st?->tenant_signed_return_document_path, 404);

        $path = storage_path('app/private/' . $st->tenant_signed_return_document_path);
        abort_if(!file_exists($path), 404);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]);
    }

    public function downloadReturnDocument(Penyewaan $penyewaan)
    {
        $st = $penyewaan->serahTerima;
        abort_if(!$st?->tenant_signed_return_document_path, 404);

        $path = storage_path('app/private/' . $st->tenant_signed_return_document_path);
        abort_if(!file_exists($path), 404);

        return response()->download($path, basename($st->tenant_signed_return_document_path));
    }

    public function uploadReturnDocument(Request $request, Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);
 
        $penyewaan->loadMissing('serahTerima');
        $serahTerima = $penyewaan->serahTerima;
 
        if (! $serahTerima) {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('error', 'Data serah terima tidak ditemukan.');
        }
 
        // Status yang membolehkan upload dokumen pengembalian
        $allowedStatuses = [
            'waiting_return',
            'return_document_rejected',
            'return_shipped',       // sesuaikan dengan nilai enum di sistem Anda
        ];
 
        if (! in_array($serahTerima->handover_status, $allowedStatuses, true)
            && $penyewaan->status !== 'pengembalian') {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('error', 'Dokumen pengembalian hanya dapat diunggah saat proses pengembalian berlangsung.');
        }
 
        $request->validate([
            'return_document' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);
 
        $path = $request->file('return_document')->store('return-documents', 'public');
 
        // Pakai kolom yang sudah ada: tenant_signed_return_document_path & tenant_signed_return_at
        $serahTerima->update([
            'tenant_signed_return_document_path' => $path,
            'tenant_signed_return_at'            => now(),   // kolom ini sudah ada di DB
            'handover_status'                    => 'menunggu_konfirmasi_selesai',
        ]);
 
        // Catat log serah terima
        $serahTerima->logs()->create([
            'status'       => 'menunggu_konfirmasi_selesai',
            'performed_by' => 'Penyewa',
            'message'      => 'Dokumen pengembalian berhasil diunggah. Menunggu verifikasi pengelola.',
        ]);
 
        return redirect()->route('penyewaan.requests.show', $penyewaan)
            ->with('success', 'Dokumen pengembalian berhasil diunggah. Menunggu verifikasi dari pengelola.');
    }
 

    public function showPayment(Penyewaan $penyewaan, MidtransService $midtrans)
    {
        $this->authorizeOwner($penyewaan);

        // ✅ Sudah bayar — jangan tampilkan error, arahkan saja dengan info netral
        if ($penyewaan->payment_status === PaymentStatus::PAID->value) {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('success', 'Pembayaran Anda sudah berhasil.');
        }

        // ✅ Pakai status baru
        if ($penyewaan->status !== 'menunggu_pembayaran') {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('error', 'Pembayaran dapat dilakukan setelah pengajuan disetujui.');
        }

        if ($penyewaan->signed_agreement_status !== 'accepted') {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('error', 'Pembayaran hanya bisa dilakukan setelah dokumen perjanjian disetujui oleh pengelola.');
        }

        $subtotal   = ($penyewaan->painting->daily_rate ?? 0) * $penyewaan->duration_days;
        $deposit    = (int) round($subtotal * 0.5);
        $ongkir     = (int) ($penyewaan->shipping_cost ?? 0);
        $total      = $subtotal + $deposit + $ongkir;
        $clientKey = $midtrans->getClientKey();

        return view('penyewaan.payment', compact('penyewaan', 'subtotal', 'deposit', 'total', 'clientKey'));
    }

    public function processPayment(Penyewaan $penyewaan, MidtransService $midtrans)
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->payment_status === PaymentStatus::PAID->value) {
            return response()->json([
                'message' => 'Pembayaran sudah berhasil sebelumnya.',
            ], 400);
        }

        // ✅ Pakai status baru
        if ($penyewaan->status !== 'menunggu_pembayaran') {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('error', 'Pembayaran dapat dilakukan setelah pengajuan disetujui.');
        }


        $subtotal   = ($penyewaan->painting->daily_rate ?? 0) * $penyewaan->duration_days;
        $deposit    = (int) round($subtotal * 0.5);
        $ongkir     = (int) ($penyewaan->shipping_cost ?? 0);
        $total      = $subtotal + $deposit + $ongkir;

        $transactionId = 'RNT-' . $penyewaan->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $transactionId,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $penyewaan->contact_name ?: $penyewaan->invoice_name ?: 'Customer',
                'email'      => $penyewaan->contact_email ?? $penyewaan->invoice_email ?? 'customer@example.com',
                'phone'      => $penyewaan->contact_phone ?? '081234567890',
            ],
            'item_details' => array_filter([
                [
                    'id'       => 'koleksi-' . $penyewaan->koleksi_id,
                    'price'    => (int) $subtotal,
                    'quantity' => 1,
                    'name'     => Str::limit($penyewaan->painting->title, 50),
                ],
                [
                    'id'       => 'deposit',
                    'price'    => (int) $deposit,
                    'quantity' => 1,
                    'name'     => 'Deposit Jaminan',
                ],
                $ongkir > 0 ? [
                    'id'       => 'ongkir',
                    'price'    => $ongkir,
                    'quantity' => 1,
                    'name'     => 'Ongkos Kirim',
                ] : null,
            ]),
        ];

        try {
            $snapToken = $midtrans->getSnapToken($params);

            $penyewaan->payments()
                ->where('transaction_status', 'pending')
                ->update(['transaction_status' => 'expire']);

            Payment::create([
                'penyewaan_id'       => $penyewaan->id,
                'invoice_id'         => $penyewaan->id,
                'order_id'           => $transactionId,
                'gross_amount'       => $total,
                'transaction_status' => PaymentStatus::PENDING->value,
                'payload'            => $params,
            ]);

            $penyewaan->update([
                'payment_status'    => PaymentStatus::PENDING->value,
                'payment_reference' => $transactionId,
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id'   => $transactionId,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage(), [
                'params'       => $params,
                'penyewaan_id' => $penyewaan->id,
            ]);
            return response()->json([
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function paymentGateway(Penyewaan $penyewaan, MidtransService $midtrans)
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->payment_status !== PaymentStatus::PENDING->value) {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('error', 'Silakan mulai pembayaran terlebih dahulu.');
        }

        $subtotal = ($penyewaan->painting->daily_rate ?? 0) * $penyewaan->duration_days;
        $deposit  = (int) round($subtotal * 0.5);
        $ongkir   = (int) ($penyewaan->shipping_cost ?? 0);
        $total    = $subtotal + $deposit + $ongkir;
        $clientKey = $midtrans->getClientKey();

        return view('penyewaan.payment-gateway', compact('penyewaan', 'total', 'clientKey'));
    }

    public function paymentCallback(Request $request)
    {
        $midtrans = new MidtransService();

        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;

        if (! $orderId) {
            return response()->json(['status' => 'error', 'message' => 'Order ID tidak ditemukan'], 400);
        }

        try {
            $transaction      = $midtrans->getTransactionStatus($orderId);
            $transactionStatus = $transaction->transaction_status;
            $paymentType      = $transaction->payment_type ?? null;
            $transactionId    = $transaction->transaction_id ?? null;
            $grossAmount      = $transaction->gross_amount ?? null;
            $fraudStatus      = $transaction->fraud_status ?? null;

            $paymentStatus = PaymentStatus::FAILED->value;
            if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
                if ($fraudStatus === 'accept' || $fraudStatus === null) {
                    $paymentStatus = PaymentStatus::PAID->value;
                }
            } elseif ($transactionStatus === 'pending') {
                $paymentStatus = PaymentStatus::PENDING->value;
            } elseif (in_array($transactionStatus, ['expire'], true)) {
                $paymentStatus = PaymentStatus::EXPIRED->value;
            }

            $payment = Payment::firstWhere('order_id', $orderId);
            if (! $payment) {
                $penyewaan = Penyewaan::where('payment_reference', $orderId)->first();
                if ($penyewaan) {
                    $payment = Payment::create([
                        'penyewaan_id'       => $penyewaan->id,
                        'invoice_id'         => $penyewaan->id,
                        'order_id'           => $orderId,
                        'gross_amount'       => $grossAmount,
                        'transaction_status' => $transactionStatus,
                        'payment_type'       => $paymentType,
                        'transaction_id'     => $transactionId,
                        'paid_at'            => $paymentStatus === PaymentStatus::PAID->value ? now() : null,
                        'payload'            => $payload,
                    ]);
                }
            }

            if ($payment) {
                $payment->update([
                    'transaction_status' => $transactionStatus,
                    'payment_type'       => $paymentType,
                    'transaction_id'     => $transactionId,
                    'gross_amount'       => $grossAmount,
                    'paid_at'            => $paymentStatus === PaymentStatus::PAID->value ? now() : $payment->paid_at,
                    'payload'            => array_merge($payment->payload ?? [], $payload),
                ]);
                $penyewaan = $payment->penyewaan;
            } else {
                $penyewaan = Penyewaan::where('payment_reference', $orderId)->first();
            }

            if (! $penyewaan) {
                return response()->json(['status' => 'error', 'message' => 'Penyewaan tidak ditemukan untuk order ini'], 404);
            }

            $update = ['payment_status' => $paymentStatus];
            // ✅ FIX: Gunakan status baru yang konsisten
            if ($paymentStatus === PaymentStatus::PAID->value) {
                $update['status'] = 'pengiriman';
            } else {
                $update['status'] = 'menunggu_pembayaran';
            }

            $penyewaan->update($update);
            $penyewaan->refresh();

            if ($paymentStatus === PaymentStatus::PAID->value) {
                $this->createHandoverIfNeeded($penyewaan);
            }

            $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function paymentSuccess(Penyewaan $penyewaan, MidtransService $midtrans)
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->payment_status !== PaymentStatus::PAID->value) {
            $this->syncMidtransPaymentStatus($penyewaan, $midtrans);
        }

        if ($penyewaan->payment_status !== PaymentStatus::PAID->value) {
            return redirect()->route('penyewaan.requests.show', $penyewaan)
                ->with('error', 'Pembayaran belum dikonfirmasi.');
        }

        return redirect()->route('penyewaan.requests.show', $penyewaan)
            ->with('success', 'Pembayaran berhasil. Silakan tunggu konfirmasi pengelola.');
    }

    public function paymentFailed(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        // ✅ Status konsisten
        $penyewaan->update([
            'payment_status' => PaymentStatus::FAILED->value,
            'status'         => 'menunggu_pembayaran',
        ]);
        $penyewaan->user->notify(new PenyewaanStatusNotification($penyewaan));

        return redirect()->route('penyewaan.requests.show', $penyewaan)
            ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
    }

    public function paymentHistory(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        $payments = $penyewaan->payments()->latest()->get();

        return view('penyewaan.payment-history', compact('penyewaan', 'payments'));
    }

    public function paymentStatus(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        return view('penyewaan.payment-status', compact('penyewaan'));
    }

    public function checkPaymentStatus(Penyewaan $penyewaan, MidtransService $midtrans)
    {
        $this->authorizeOwner($penyewaan);

        $this->syncMidtransPaymentStatus($penyewaan, $midtrans);

        return response()->json([
            'payment_status'     => $penyewaan->payment_status,
            'status'             => $penyewaan->status,
            'transaction_status' => $penyewaan->payments()->latest()->first()->transaction_status ?? null,
            'payment_type'       => $penyewaan->payments()->latest()->first()->payment_type ?? null,
        ]);
    }

    protected function syncMidtransPaymentStatus(Penyewaan $penyewaan, MidtransService $midtrans): void
    {
        if (! $penyewaan->payment_reference) {
            return;
        }

        $payment = Payment::firstWhere('order_id', $penyewaan->payment_reference);
        if (! $payment) {
            return;
        }

        try {
            $transaction = $midtrans->getTransactionStatus($payment->order_id);
        } catch (\Exception $e) {
            if ($payment->transaction_id) {
                try {
                    $transaction = $midtrans->getTransactionStatus($payment->transaction_id);
                } catch (\Exception $e2) {
                    \Log::error('Midtrans status check failed: ' . $e2->getMessage(), [
                        'order_id'       => $payment->order_id,
                        'transaction_id' => $payment->transaction_id,
                        'penyewaan_id'   => $penyewaan->id,
                    ]);
                    return;
                }
            } else {
                \Log::error('Midtrans status check failed: ' . $e->getMessage(), [
                    'order_id'     => $payment->order_id,
                    'penyewaan_id' => $penyewaan->id,
                ]);
                return;
            }
        }

        $transactionStatus = $transaction->transaction_status;
        $paymentType       = $transaction->payment_type ?? null;
        $transactionId     = $transaction->transaction_id ?? null;
        $grossAmount       = $transaction->gross_amount ?? null;
        $fraudStatus       = $transaction->fraud_status ?? null;

        $paymentStatus = PaymentStatus::FAILED->value;
        if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                $paymentStatus = PaymentStatus::PAID->value;
            }
        } elseif ($transactionStatus === 'pending') {
            $paymentStatus = PaymentStatus::PENDING->value;
        } elseif (in_array($transactionStatus, ['expire'], true)) {
            $paymentStatus = PaymentStatus::EXPIRED->value;
        }

        $payment->update([
            'transaction_status' => $transactionStatus,
            'payment_type'       => $paymentType,
            'transaction_id'     => $transactionId,
            'gross_amount'       => $grossAmount,
            'paid_at'            => $paymentStatus === PaymentStatus::PAID->value ? now() : $payment->paid_at,
            'payload'            => array_merge($payment->payload ?? [], (array) $transaction),
        ]);

        $update = ['payment_status' => $paymentStatus];
        // ✅ FIX: Status baru yang konsisten (tidak lagi pakai 'preparing_delivery' / 'waiting_payment')
        if ($paymentStatus === PaymentStatus::PAID->value) {
            $update['status'] = 'pengiriman';
        } else {
            $update['status'] = 'menunggu_pembayaran';
        }

        $penyewaan->update($update);
        $penyewaan->refresh();

        if ($paymentStatus === PaymentStatus::PAID->value) {
            $this->createHandoverIfNeeded($penyewaan);
        }
    }

    protected function createHandoverIfNeeded(Penyewaan $penyewaan): void
    {
        if ($penyewaan->serahTerima ||
            $penyewaan->payment_status !== PaymentStatus::PAID->value ||
            ! in_array($penyewaan->status, ['pengiriman', 'aktif'], true)) {
            return;
        }

        $documentService = new DocumentService();

        $serahTerima = $penyewaan->serahTerima()->create([
            'document_number'        => 'HT-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
            'handover_status'        => 'waiting_handover',
            'handover_document_path' => '',
        ]);

        if ($serahTerima) {
            $handoverPath = $documentService->generateHandoverDocument($penyewaan, $serahTerima);
            $serahTerima->update(['handover_document_path' => $handoverPath]);

            $serahTerima->logs()->create([
                'status'       => 'waiting_handover',
                'performed_by' => 'Sistem',
                'message'      => 'Dokumen serah terima awal dibuat otomatis setelah pembayaran berhasil.',
            ]);
        }
    }

    // ──────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────

    protected function validatePenyewaan(Request $request): array
    {
        return $request->validate([
            'contact_name'     => ['required', 'string', 'max:255'],
            'contact_email'    => ['required', 'email', 'max:255'],
            'full_address'     => ['nullable', 'string', 'max:1000'],
            'rental_type'      => ['required', 'in:perseorangan,instansi'],
            'institution_name' => ['nullable', 'string', 'max:255', 'required_if:rental_type,instansi'],
            'purpose'          => ['nullable', 'string', 'max:1000'],
            'start_date'       => ['required', 'date', 'after_or_equal:today'],
            'end_date'         => ['required', 'date', 'after_or_equal:start_date'],
            'contact_phone'    => ['required', 'string', 'max:25'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);
    }

    protected function authorizeOwner(Penyewaan $penyewaan): void
    {
        if ($penyewaan->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak');
        }
    }

    protected function jenisInstansiOptions(): array
    {
        return [
            'Perusahaan',
            'Hotel',
            'Kampus',
            'Event Organizer',
            'Dinas Pemerintahan',
            'Bank',
            'Restoran Premium',
            'Lainnya',
        ];
    }

    protected function jenisTempatOptions(string $rentalType): array
    {
        if ($rentalType === 'instansi') {
            return [
                'Kantor Perusahaan',
                'Hotel',
                'Kampus',
                'Convention Hall',
                'Restoran',
                'Gedung Pemerintah',
                'Event Venue',
                'Lainnya',
            ];
        }

        return [
            'Rumah',
            'Villa',
            'Studio Foto',
            'Apartemen',
            'Ballroom Kecil',
            'Cafe',
            'Lainnya',
        ];
    }

    protected function tujuanPenyewaanOptions(string $rentalType): array
    {
        if ($rentalType === 'instansi') {
            return [
                'Event Perusahaan',
                'Dekorasi Kantor',
                'Seminar',
                'Event Budaya',
                'Lainnya',
            ];
        }

        return [
            'Dekorasi interior rumah',
            'Photoshoot prewedding',
            'Acara ulang tahun',
            'Konten fotografi',
            'Lainnya',
        ];
    }

    protected function resolveStoredSelect(?string $stored, array $options): array
    {
        if (! $stored) {
            return ['', null];
        }

        if (in_array($stored, $options, true)) {
            return [$stored, null];
        }

        return ['Lainnya', $stored];
    }

    protected function lainnyaSessionValue(?string $stored, array $options): ?string
    {
        if (! $stored || in_array($stored, $options, true)) {
            return null;
        }

        return $stored;
    }

    protected function calculateRentalCosts(Koleksi $koleksi, array $step4 = []): array
    {
        $penyewaan = Penyewaan::where('user_id', Auth::id())
            ->where('koleksi_id', $koleksi->id)
            ->where('submission_status', 'draft')
            ->first();

        $startDate    = $step4['start_date'] ?? ($penyewaan?->start_date?->format('Y-m-d'));
        $endDate      = $step4['end_date'] ?? ($penyewaan?->end_date?->format('Y-m-d'));
        $durationDays = 0;

        if ($startDate && $endDate) {
            $durationDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        }

        $subtotal = ($koleksi->daily_rate ?? 0) * $durationDays;
        $deposit  = $subtotal > 0 ? (int) round($subtotal * 0.5) : 0;
        $total = $subtotal + $deposit;

        return compact('durationDays', 'subtotal', 'deposit', 'total');
    }

    protected function invoiceDataFromPenyewaan(Penyewaan $penyewaan, string $rentalType): array
    {
        if ($rentalType === 'instansi') {
            return [
                'invoice_name'  => $penyewaan->nama_instansi,
                'invoice_email' => $penyewaan->email_pic ?: $penyewaan->email_instansi,
            ];
        }

        return [
            'invoice_name'  => $penyewaan->contact_name,
            'invoice_email' => $penyewaan->contact_email,
        ];
    }
}