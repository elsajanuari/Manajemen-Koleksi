<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketScheduleException;
use App\Models\TicketQuota;
use App\Models\PemesananTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TicketController extends Controller
{
    public function index(Request $request)
{
    $statusFilter = $request->query('status', 'all');
    $search = trim((string) $request->query('search', ''));
    
    $query = Ticket::withCount(['schedules', 'exceptions']);
    
    // Filter berdasarkan status display
    if ($statusFilter !== 'all') {
        switch ($statusFilter) {
            case 'aktif':
                $query->where('status', true)
                    ->where('tanggal_mulai', '<=', now()->toDateString())
                    ->where(function($q) {
                        $q->whereNull('tanggal_selesai')
                          ->orWhere('tanggal_selesai', '>=', now()->toDateString());
                    });
                break;
            case 'akan_datang':
                $query->where('status', true)
                    ->where('tanggal_mulai', '>', now()->toDateString());
                break;
            case 'berakhir':
                $query->where('status', true)
                    ->whereNotNull('tanggal_selesai')
                    ->where('tanggal_selesai', '<', now()->toDateString());
                break;
            case 'nonaktif':
                $query->where('status', false);
                break;
        }
    }
    
            // Filter pencarian
            if ($search !== '') {
                $query->where(function($q) use ($search) {
                    $q->where('nama_tiket', 'like', "%{$search}%")
                    ->orWhere('jenis_tiket', 'like', "%{$search}%")
                    ->orWhere('kategori_pengunjung', 'like', "%{$search}%");
                });
            }
            
            $tickets = $query->orderBy('created_at', 'desc')->paginate(10);
            
            // Hitung statistik untuk filter
            $countAll = Ticket::count();
            $countAktif = Ticket::where('status', true)
                ->where('tanggal_mulai', '<=', now()->toDateString())
                ->where(function($q) {
                    $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', now()->toDateString());
                })->count();
            $countAkanDatang = Ticket::where('status', true)
                ->where('tanggal_mulai', '>', now()->toDateString())->count();
            $countBerakhir = Ticket::where('status', true)
                ->whereNotNull('tanggal_selesai')
                ->where('tanggal_selesai', '<', now()->toDateString())->count();
            $countNonaktif = Ticket::where('status', false)->count();
            
            return view('tickets.index', compact(
                'tickets', 
                'statusFilter', 
                'search',
                'countAll',
                'countAktif',
                'countAkanDatang',
                'countBerakhir',
                'countNonaktif'
            ));
        }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateTicketRequest($request);
        $data = $this->ticketPayload($request, $validated);

        $scheduleConfig = json_decode($request->input('schedule_config', '{}'), true);
        if (empty($scheduleConfig['availableDates']) || count($scheduleConfig['availableDates']) == 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['schedule_config' => 'Silakan pilih minimal satu tanggal yang tersedia pada kalender!']);
        }

        $ticket = null;
        DB::transaction(function () use ($request, $data, $scheduleConfig, &$ticket): void {
            $ticket = Ticket::create($data);
            $this->generateSchedulesFromConfig($ticket, $scheduleConfig);
            $ticket->regenerateQuotas();
        });

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Tiket berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $ticket = Ticket::with(['schedules', 'exceptions', 'quotas'])->findOrFail($id);
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $validated = $this->validateTicketRequest($request, $ticket);
        $data = $this->ticketPayload($request, $validated, $ticket);

        $scheduleConfig = json_decode($request->input('schedule_config', '{}'), true);
        if (empty($scheduleConfig['availableDates']) || count($scheduleConfig['availableDates']) == 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['schedule_config' => 'Silakan pilih minimal satu tanggal yang tersedia pada kalender!']);
        }

        DB::transaction(function () use ($ticket, $request, $data, $scheduleConfig): void {
            $ticket->update($data);
            $this->generateSchedulesFromConfig($ticket, $scheduleConfig);

            if ($request->filled('exceptions')) {
                $ticket->exceptions()->delete();
                foreach ($request->input('exceptions') as $exc) {
                    if (!empty($exc['tanggal'])) {
                        $ticket->exceptions()->create([
                            'tanggal'      => $exc['tanggal'],
                            'is_tersedia'  => (bool) ($exc['is_tersedia'] ?? false),
                        ]);
                    }
                }
            }

            $ticket->regenerateQuotas();
        });

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Tiket berhasil diupdate');
    }

    public function destroy(int $id)
{
    $ticket = Ticket::findOrFail($id);
    
    $hasTransaction = PemesananTiket::where('ticket_id', $id)->exists();
    
    if ($hasTransaction) {
        return redirect()->route('tickets.index')
            ->with('error', 'Tiket tidak dapat dihapus karena sudah digunakan dalam transaksi.');
    }
    
    if ($ticket->isExpired()) {
        return redirect()->route('tickets.index')
            ->with('error', 'Tiket tidak dapat dihapus karena periode tiket sudah berakhir.');
    }
    
    $ticket->delete();
    
    return redirect()->route('tickets.index')
        ->with('success', 'Tiket berhasil dihapus.');
}

    public function show(int $id)
    {
        $ticket = Ticket::with(['schedules', 'exceptions', 'quotas'])->findOrFail($id);
        $holidays = $this->getIndonesianHolidays(now()->year);
        return view('tickets.show', compact('ticket', 'holidays'));
    }

    public function userIndex()
    {
        $tickets = Ticket::where('status', true)
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now()->toDateString());
            })
            ->with(['schedules', 'quotas'])
            ->orderBy('jenis_tiket')
            ->orderBy('sub_jenis')
            ->orderBy('sub_kategori')
            ->orderBy('kategori_pengunjung')
            ->get()
            ->groupBy(fn (Ticket $ticket) => strtolower($ticket->jenis_tiket) === 'reguler'
                ? 'reguler'
                : strtolower((string) $ticket->sub_jenis));

        return view('tiket-pengguna.index', compact('tickets'));
    }

    private function rules(): array
    {
        return [
            'nama_tiket' => ['required', 'string', 'max:255'],
            'jenis_tiket' => ['required', 'in:reguler,event'],
            'sub_jenis' => ['nullable', 'string', 'max:100'],
            'sub_kategori' => ['nullable', 'string', 'max:100'],
            'kategori_pengunjung' => ['nullable', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:1'],
            'kuota' => ['required', 'integer', 'min:1'],
            'minimal_anggota' => ['nullable', 'integer', 'min:1'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'schedules' => ['nullable', 'array'],
            'schedules.*.tanggal_mulai' => ['nullable', 'date'],
            'schedules.*.tanggal_selesai' => ['nullable', 'date'],
            'schedules.*.hari_tersedia' => ['nullable', 'array'],
            'schedules.*.hari_tersedia.*' => ['integer', 'between:0,6'],
            'exceptions' => ['nullable', 'array'],
            'exceptions.*.tanggal' => ['nullable', 'date'],
            'exceptions.*.is_tersedia' => ['nullable', 'boolean'],
            'boleh_reschedule' => ['nullable', 'boolean'],
            'boleh_cancel' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
            'jam_mulai' => ['nullable', 'date_format:H:i'],
            'schedule_config' => ['nullable', 'string'],
        ];
    }

    private function validateTicketRequest(Request $request, ?Ticket $existingTicket = null): array
    {
        $validator = Validator::make($request->all(), $this->rules(), [
            'nama_tiket.required' => 'Nama Tiket wajib diisi.',
            'nama_tiket.string' => 'Nama Tiket harus berupa teks.',
            'nama_tiket.max' => 'Nama Tiket maksimal 255 karakter.',
            
            'jenis_tiket.required' => 'Jenis Tiket wajib dipilih.',
            'jenis_tiket.in' => 'Jenis Tiket yang dipilih tidak valid.',
            
            'sub_jenis.string' => 'Sub Jenis harus berupa teks.',
            'sub_jenis.max' => 'Sub Jenis maksimal 100 karakter.',
            
            'sub_kategori.string' => 'Sub Kategori harus berupa teks.',
            'sub_kategori.max' => 'Sub Kategori maksimal 100 karakter.',
            
            'kategori_pengunjung.string' => 'Kategori Pengunjung harus berupa teks.',
            'kategori_pengunjung.max' => 'Kategori Pengunjung maksimal 100 karakter.',
            
            'harga.required' => 'Harga wajib diisi.',
            'harga.integer' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 1 (satu) Rupiah.',
            
            'kuota.required' => 'Kuota per Hari wajib diisi.',
            'kuota.integer' => 'Kuota per Hari harus berupa angka.',
            'kuota.min' => 'Kuota per Hari minimal 1 (satu) slot.',
            
            'minimal_anggota.integer' => 'Minimal Anggota Kelompok harus berupa angka.',
            'minimal_anggota.min' => 'Minimal Anggota Kelompok minimal 5 orang.',
            
            'tanggal_mulai.required' => 'Periode Mulai wajib dipilih.',
            'tanggal_mulai.date' => 'Periode Mulai harus berupa tanggal yang valid.',
            
            'tanggal_selesai.date' => 'Periode Selesai harus berupa tanggal yang valid.',
            'tanggal_selesai.after_or_equal' => 'Periode Selesai tidak boleh kurang dari Periode Mulai.',
            
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            
            'gambar.image' => 'File yang diupload harus berupa gambar.',
            'gambar.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WEBP.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            
            'schedules.*.tanggal_mulai.date' => 'Tanggal mulai jadwal harus berupa tanggal yang valid.',
            'schedules.*.tanggal_selesai.date' => 'Tanggal selesai jadwal harus berupa tanggal yang valid.',
            
            'exceptions.*.tanggal.date' => 'Tanggal pengecualian harus berupa tanggal yang valid.',
            'exceptions.*.is_tersedia.boolean' => 'Status ketersediaan pengecualian harus bernilai benar atau salah.',
            
            'jam_mulai.date_format' => 'Format Jam Mulai tidak valid (format yang benar: HH:MM).',
        ]);

        $validator->after(function ($validator) use ($request, $existingTicket): void {
            $jenis = strtolower((string) $request->input('jenis_tiket'));
            $subJenis = strtolower((string) $request->input('sub_jenis'));
            $subKategori = (string) $request->input('sub_kategori');
            $kategori = (string) $request->input('kategori_pengunjung');

            if ($jenis === 'reguler') {
                if (!in_array($kategori, ['Pelajar', 'Umum', 'WNA'], true)) {
                    $validator->errors()->add('kategori_pengunjung', 'Tiket reguler hanya boleh kategori Pelajar, Umum, atau WNA.');
                }
            }

            if ($jenis === 'event') {
                if (!in_array($subJenis, ['sunday painting', 'pameran', 'workshop', 'lainnya'], true)) {
                    $validator->errors()->add('sub_jenis', 'Sub jenis event harus Sunday Painting, Pameran, Workshop, atau Lainnya.');
                }

                if ($subJenis === 'sunday painting') {
                    if (!empty($subKategori)) {
                        $validator->errors()->add('sub_kategori', 'Sunday Painting tidak menggunakan sub kategori.');
                    }

                    if (!in_array($kategori, ['Individu', 'Kelompok', 'WNA'], true)) {
                        $validator->errors()->add('kategori_pengunjung', 'Sunday Painting hanya boleh kategori Individu, Kelompok, atau WNA.');
                    }
                }

                if ($subJenis === 'workshop') {
                    if (!empty($subKategori)) {
                        $validator->errors()->add('sub_kategori', 'Workshop tidak menggunakan sub kategori.');
                    }

                    if (!in_array($kategori, ['Pelajar', 'Umum', 'WNA'], true)) {
                        $validator->errors()->add('kategori_pengunjung', 'Kategori pengunjung untuk workshop hanya Pelajar, Umum, atau WNA.');
                    }
                }

                if ($subJenis === 'pameran') {
                    if (!in_array($subKategori, ['Pameran Rutin', 'Pameran Berkala', 'Pameran Museum'], true)) {
                        $validator->errors()->add('sub_kategori', 'Pameran harus memilih salah satu sub kategori yang tersedia.');
                    }

                    if (!in_array($kategori, ['Pelajar', 'Umum', 'WNA'], true)) {
                        $validator->errors()->add('kategori_pengunjung', 'Kategori pengunjung untuk pameran hanya Pelajar, Umum, atau WNA.');
                    }
                }
            }

            $tanggalMulai = $request->input('tanggal_mulai');
            $isCreate = $existingTicket === null;
            $tanggalBerubah = $existingTicket
                && $existingTicket->tanggal_mulai
                && (is_string($existingTicket->tanggal_mulai)
                    ? $existingTicket->tanggal_mulai
                    : $existingTicket->tanggal_mulai->toDateString()) !== $tanggalMulai;

            if ($request->filled('tanggal_mulai') && ($isCreate || $tanggalBerubah)) {
                if ($tanggalMulai < now()->toDateString()) {
                    $validator->errors()->add('tanggal_mulai', 'Tanggal mulai tidak boleh kurang dari hari ini.');
                }
            }
        });

        return $validator->validate();
    }

    private function ticketPayload(Request $request, array $validated, ?Ticket $ticket = null): array
    {
        $data = collect($validated)->only([
            'nama_tiket',
            'jenis_tiket',
            'sub_jenis',
            'sub_kategori',
            'kategori_pengunjung',
            'harga',
            'kuota',
            'minimal_anggota',
            'tanggal_mulai',
            'tanggal_selesai',
            'deskripsi',
            'jam_mulai',
        ])->all();

        if ($request->hasFile('gambar')) {
            if ($ticket?->gambar) {
                Storage::disk('public')->delete('gambar/' . $ticket->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('gambar', $filename, 'public');
            $data['gambar'] = $filename;
        }

        $jenis = strtolower((string) ($data['jenis_tiket'] ?? ''));
        $subJenis = strtolower((string) ($data['sub_jenis'] ?? ''));

        if ($jenis === 'reguler') {
            $data['sub_jenis'] = null;
            $data['sub_kategori'] = null;
            $data['minimal_anggota'] = null;
        }

        if ($jenis === 'event' && $subJenis === 'sunday painting') {
            $data['sub_kategori'] = null;
            if (($data['kategori_pengunjung'] ?? null) !== 'Kelompok') {
                $data['minimal_anggota'] = null;
            }
        }

        if ($jenis === 'event' && $subJenis === 'workshop') {
            $data['sub_kategori'] = null;
            $data['minimal_anggota'] = null;
        }

        if ($jenis === 'event' && $subJenis === 'pameran') {
            $data['minimal_anggota'] = null;
        }

        if ($jenis === 'event' && $subJenis === 'lainnya') {
            $data['sub_kategori'] = null;
            $data['minimal_anggota'] = null;
        }

        $data['boleh_reschedule'] = $request->boolean('boleh_reschedule');
        $data['boleh_cancel'] = $request->boolean('boleh_cancel');

        if ($request->has('status')) {
            $data['status'] = $request->boolean('status');
        } elseif ($ticket === null) {
            $data['status'] = true;
        }

        return $data;
    }

    private function generateSchedulesFromConfig(Ticket $ticket, array $scheduleConfig): void
    {
        if (empty($scheduleConfig['availableDates'])) {
            return;
        }

        $availableDates = $scheduleConfig['availableDates'];
        sort($availableDates);

        $ticket->schedules()->delete();

        foreach ($availableDates as $dateStr) {
            $dayNum = (int) date('N', strtotime($dateStr));
            $dayConverted = $dayNum === 7 ? 0 : $dayNum;

            $ticket->schedules()->create([
                'tanggal_mulai'   => $dateStr,
                'tanggal_selesai' => $dateStr,
                'hari_tersedia'   => [$dayConverted],
            ]);
        }
    }

    public function userShow(int $id)
    {
        $ticket = Ticket::with(['schedules', 'exceptions', 'quotas'])
            ->where('status', true)
            ->findOrFail($id);
        
        $startYear = $ticket->tanggal_mulai->year;
        $endYear = $ticket->tanggal_selesai ? $ticket->tanggal_selesai->year : $startYear;
        $holidays = [];
        for ($year = $startYear; $year <= $endYear; $year++) {
            $holidays[$year] = TicketQuota::getIndonesianHolidays($year);
        }

        return view('tiket-pengguna.show', compact('ticket', 'holidays'));
    }

    public function userCheckout(Request $request, int $id)
    {
        $ticket = Ticket::where('status', true)->findOrFail($id);

        $request->validate([
            'tanggal_pilih' => ['required', 'date'],
            'jumlah_tiket' => ['required', 'integer', 'min:1'],
        ]);

        $tanggalPilih = $request->input('tanggal_pilih');
        $jumlahTiket = (int) $request->input('jumlah_tiket');

        $quota = $ticket->quotas->firstWhere('tanggal', $tanggalPilih);
        if (!$quota || $quota->status !== 'available') {
            return back()->withErrors([
                'tanggal_pilih' => 'Tanggal yang dipilih tidak tersedia.',
            ])->withInput();
        }

        if ($jumlahTiket > $quota->kuota_sisa) {
            return back()->withErrors([
                'jumlah_tiket' => 'Kuota tidak mencukupi. Sisa kuota: ' . $quota->kuota_sisa,
            ])->withInput();
        }

        if (
            strtolower((string) $ticket->jenis_tiket) === 'event' &&
            strtolower((string) $ticket->sub_jenis) === 'sunday painting' &&
            (string) $ticket->kategori_pengunjung === 'Kelompok' &&
            $ticket->minimal_anggota &&
            $jumlahTiket < $ticket->minimal_anggota
        ) {
            return back()->withErrors([
                'jumlah_tiket' => 'Minimal pemesanan untuk kategori kelompok adalah '.$ticket->minimal_anggota.' orang.',
            ])->withInput();
        }

        $total = $jumlahTiket * (int) $ticket->harga;

        return back()->with('success', "Checkout berhasil untuk {$jumlahTiket} tiket pada tanggal " . \Carbon\Carbon::parse($tanggalPilih)->locale('id')->translatedFormat('d F Y') . ". Total: Rp ".number_format($total, 0, ',', '.').'.');
    }

    public function manageExceptions(Ticket $ticket)
    {
        $ticket->load('exceptions');
        return view('tickets.exceptions', compact('ticket'));
    }

    public function storeException(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'is_tersedia' => 'required|boolean',
            'alasan' => 'nullable|string|max:255',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
        ]);

        if ($validated['is_tersedia'] && $validated['jam_buka'] && !$validated['jam_tutup']) {
            return back()->withErrors(['jam_tutup' => 'Jam tutup harus diisi jika jam buka diisi.']);
        }

        TicketScheduleException::create(array_merge(
            $validated,
            ['ticket_id' => $ticket->id]
        ));

        $ticket->regenerateQuotas();

        return back()->with('success', 'Pengecualian jadwal berhasil ditambahkan!');
    }

    public function destroyException(Ticket $ticket, TicketScheduleException $exception)
    {
        $exception->delete();
        $ticket->regenerateQuotas();

        return back()->with('success', 'Pengecualian jadwal berhasil dihapus!');
    }

    public function manageQuotas(Ticket $ticket)
    {
        $quotas = $ticket->quotas()
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->paginate(20);

        return view('tickets.quotas', compact('ticket', 'quotas'));
    }

    public function updateQuota(Request $request, Ticket $ticket, TicketQuota $quota)
    {
        $validated = $request->validate([
            'kuota_max' => 'required|integer|min:1',
        ]);

        if ($validated['kuota_max'] < $quota->kuota_terjual) {
        return back()->withErrors([
            'kuota_max' => 'Kuota tidak boleh kurang dari ' . $quota->kuota_terjual . ' (tiket sudah terjual pada tanggal ' . \Carbon\Carbon::parse($quota->tanggal)->locale('id')->translatedFormat('d F Y') . ')'
        ])->withInput();
        }

        $quota->update($validated);

        return back()->with('success', 'Kuota untuk tanggal ' . \Carbon\Carbon::parse($quota->tanggal)->locale('id')->translatedFormat('d F Y') . ' berhasil diubah!');
    }

    public function regenerateQuota(Ticket $ticket)
    {
        try {
            $ticket->regenerateQuotas();
            return redirect()->back()->with('success', "Kuota untuk tiket {$ticket->nama_tiket} berhasil diregenerasi.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Gagal regenerasi: " . $e->getMessage());
        }
    }

    private function getIndonesianHolidays($year)
    {
        return Cache::remember("holidays_{$year}", 60 * 24 * 30, function () use ($year) {
            $apiKey = env('CALENDARIFIC_API_KEY');
            $response = Http::get('https://calendarific.com/api/v2/holidays', [
                'api_key' => $apiKey,
                'country' => 'ID',
                'year'    => $year,
                'type'    => 'national'
            ]);

            if ($response->successful()) {
                $data = $response->json()['response']['holidays'];
                $formattedHolidays = [];
                foreach ($data as $holiday) {
                    $formattedHolidays[$holiday['date']['iso']] = $holiday['name'];
                }
                return $formattedHolidays;
            }

            return [];
        });
    }

    public function manageHolidays()
    {
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;
        $previousYear = $currentYear - 1;
        
        $holidays = [];
        foreach ([$previousYear, $currentYear, $nextYear, $nextYear + 1] as $year) {
            $holidays[$year] = TicketQuota::getIndonesianHolidays($year);
        }
        
        $lastSync = Cache::get('holidays_last_sync', null);
        
        return view('tickets.holidays', compact('holidays', 'currentYear', 'lastSync'));
    }

    public function syncHolidays(Request $request)
    {
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;
        $nextNextYear = $currentYear + 2;
        
        $years = [$currentYear, $nextYear, $nextNextYear];
        $results = [];
        
        foreach ($years as $year) {
            TicketQuota::clearHolidayCache($year);
            $holidays = TicketQuota::getIndonesianHolidays($year);
            $results[$year] = count($holidays);
        }
        
        Cache::put('holidays_last_sync', now(), 60 * 24 * 365);
        
        $tickets = Ticket::where('status', true)->get();
        $regeneratedCount = 0;
        $errors = [];
        
        foreach ($tickets as $ticket) {
            try {
                $ticket->regenerateQuotas();
                $regeneratedCount++;
            } catch (\Exception $e) {
                $errors[] = "{$ticket->nama_tiket}: " . $e->getMessage();
            }
        }
        
        $message = "Sinkronisasi berhasil! Ditemukan " . implode(', ', array_map(fn($y, $c) => "{$y}: {$c} libur", array_keys($results), $results)) . ". ";
        $message .= "{$regeneratedCount} tiket berhasil diregenerasi.";
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'holidays_found' => $results,
                'tickets_regenerated' => $regeneratedCount,
                'errors' => $errors,
            ]);
        }
        
        return redirect()->route('tickets.holidays')
            ->with('success', $message . (count($errors) ? " (" . count($errors) . " gagal)" : ""));
    }

    public function regenerateAllQuotas()
    {
        $tickets = Ticket::where('status', true)->get();
        $count = 0;
        $errors = [];
        
        foreach ($tickets as $ticket) {
            try {
                $ticket->regenerateQuotas();
                $count++;
            } catch (\Exception $e) {
                $errors[] = $ticket->nama_tiket;
            }
        }
        
        if (count($errors)) {
            return redirect()->route('tickets.holidays')
                ->with('warning', "Regenerate kuota selesai: {$count} tiket berhasil. Gagal: " . implode(', ', $errors));
        }
        
        return redirect()->route('tickets.holidays')
            ->with('success', "Berhasil regenerate kuota untuk {$count} tiket");
    }

    public function previewHolidays(Request $request)
    {
        $year = $request->query('year', now()->year);
        $holidays = TicketQuota::getIndonesianHolidays($year);
        
        if ($request->wantsJson()) {
            return response()->json([
                'year' => $year,
                'total' => count($holidays),
                'holidays' => $holidays,
            ]);
        }
        
        return back();
    }
}