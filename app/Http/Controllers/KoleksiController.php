<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Koleksi;
use Illuminate\Http\JsonResponse;
use App\Models\Painting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KoleksiController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $kategoriFilter = $request->query('kategori');
        $statusFilter = $request->query('status');
        $lokasiFilter = $request->query('lokasi');
        $sort = $request->query('sort', 'newest');

        $kategoriOptions = Category::orderBy('name')->pluck('name');
        $sortOptions = collect([
            'newest' => 'Terbaru',
            'oldest' => 'Terlama',
            'nama_asc' => 'Nama A-Z',
            'nama_desc' => 'Nama Z-A',
        ]);

        $koleksiQuery = Koleksi::with('kondisiTerakhir')
            ->when($search, fn ($query) => $query->where(fn ($inner) => $inner
                ->where('nama', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%")
                ->orWhere('tahun', 'like', "%{$search}%")
                ->orWhere('status_sewa', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%")
                ->orWhere('nomor_inventaris', 'like', "%{$search}%")
                ->orWhere('seniman', 'like', "%{$search}%")
            ))
            ->when($kategoriFilter, fn ($query) => $query->where('kategori', $kategoriFilter))
            ->when($statusFilter, fn ($query) => $query->where('status_sewa', $statusFilter))
            ->when($lokasiFilter, fn ($query) => $query->where('lokasi', $lokasiFilter));

        match ($sort) {
            'oldest' => $koleksiQuery->orderBy('created_at'),
            'nama_asc', 'kategori_asc' => $koleksiQuery->orderBy('nama'),
            'nama_desc', 'kategori_desc' => $koleksiQuery->orderByDesc('nama'),
            default => $koleksiQuery->orderByDesc('created_at'),
        };

        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $koleksis = $koleksiQuery
            ->paginate($perPage)
            ->withQueryString();

        return view('koleksi.index', compact(
            'koleksis',
            'search',
            'kategoriFilter',
            'statusFilter',
            'lokasiFilter',
            'sort',
            'kategoriOptions',
            'sortOptions',
            'perPageOptions',
            'perPage'
        ));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->pluck('name');
        return view('koleksi.create', compact('categories'));
    }

    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        $category = Category::create(['name' => $validated['name']]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => ['id' => $category->id, 'name' => $category->name],
        ], 201);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            $this->koleksiValidationRules($request, true),
            $this->koleksiValidationMessages()
        );
        $validated = $this->normalizeKoleksiCommerceFields($validated);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('koleksi', 'public');
        }

        $koleksi = Koleksi::create($validated);

        $year = $koleksi->created_at?->format('Y') ?? date('Y');
        $sequence = Koleksi::getNextSequenceForCategory($koleksi->kategori, $year);
        $nomorInventaris = Koleksi::generateNomorInventaris($koleksi->kategori, $year, $sequence);

        $koleksi->update(['nomor_inventaris' => $nomorInventaris]);

        return redirect()->route('koleksi.index')->with('success', 'Koleksi berhasil ditambahkan.');
    }

    public function show(Koleksi $koleksi): View
    {
        $koleksi->load(['conservationActions.perawatanKoleksi']);
        return view('koleksi.show', compact('koleksi'));
    }

    public function konservasi(Koleksi $koleksi): View
    {
        $perawatans = $koleksi->perawatans()
            ->with('koleksi')
            ->orderByDesc('jadwal_tanggal')
            ->paginate(20);

        $stats = [
            'total' => $koleksi->perawatans()->count(),
            'selesai' => $koleksi->perawatans()->where('status', 'selesai')->count(),
            'terjadwal' => $koleksi->perawatans()->where('status', 'terjadwal')->count(),
            'dibatalkan' => $koleksi->perawatans()->where('status', 'dibatalkan')->count(),
        ];

        return view('koleksi.konservasi', compact('koleksi', 'perawatans', 'stats'));
    }

    public function konservasiList(Request $request): View
    {
        $search = $request->query('search');
        
        $koleksis = Koleksi::query()
            ->with(['perawatans' => function ($query) {
                $query->latest('jadwal_tanggal')->limit(1);
            }])
            ->when($search, fn ($query) => $query
                ->where('nama', 'like', "%{$search}%")
                ->orWhere('nomor_inventaris', 'like', "%{$search}%")
                ->orWhere('seniman', 'like', "%{$search}%")
            )
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('koleksi.konservasi-list', compact('koleksis', 'search'));
    }

    public function edit(Koleksi $koleksi): View
    {
        $categories = Category::orderBy('name')->pluck('name');
        return view('koleksi.edit', compact('koleksi', 'categories'));
    }

    public function update(Request $request, Koleksi $koleksi): RedirectResponse
    {
        $validated = $request->validate(
            $this->koleksiValidationRules($request, false),
            $this->koleksiValidationMessages()
        );
        $validated = $this->normalizeKoleksiCommerceFields($validated);

        if ($request->hasFile('foto')) {
            if ($koleksi->foto) {
                Storage::disk('public')->delete($koleksi->foto);
            }
            $validated['foto'] = $request->file('foto')->store('koleksi', 'public');
        }

        $koleksi->update($validated);

        return redirect()->route('koleksi.index')->with('success', 'Koleksi berhasil diperbarui.');
    }

    /**
     * @return array<string, mixed>
     */
    private function koleksiValidationRules(Request $request, bool $fotoRequired): array
    {
        $statusSewa = $request->input('status_sewa');
        $needsRent = in_array($statusSewa, ['sewa', 'sewa_beli'], true);
        $needsSale = in_array($statusSewa, ['beli', 'sewa_beli'], true);

        return [
            'nama' => ['required', 'string', 'max:255'],
            'seniman' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255', 'exists:categories,name'],
            'tahun' => [$fotoRequired ? 'required' : 'nullable', 'integer', 'min:1500', 'max:' . date('Y')],
            'teknik_media' => ['nullable', 'required_if:kategori,lukisan', 'string', 'max:255'],
            'ukuran_lukisan' => ['nullable', 'required_if:kategori,lukisan', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status_sewa' => ['required', 'in:tidak,sewa,beli,sewa_beli'],
            'lokasi' => ['required', 'in:dipamerkan,disimpan'],
            'foto' => [$fotoRequired ? 'required' : 'nullable', 'image', 'max:2048'],
            'daily_rate' => [
                Rule::requiredIf($needsRent),
                'nullable',
                'integer',
                'min:0',
            ],
            'sale_price' => [
                Rule::requiredIf($needsSale),
                'nullable',
                'integer',
                'min:0',
            ],
            'weight_gram' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function koleksiValidationMessages(): array
    {
        return [
            'tahun.integer' => 'Tahun pembuatan harus berupa angka.',
            'tahun.min' => 'Tahun pembuatan tidak boleh sebelum tahun 1500.',
            'tahun.max' => 'Tahun pembuatan tidak boleh melebihi tahun ' . date('Y') . '.',
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function normalizeKoleksiCommerceFields(array $validated): array
    {
        $statusSewa = $validated['status_sewa'] ?? 'tidak';

        $validated['for_rent'] = in_array($statusSewa, ['sewa', 'sewa_beli'], true);
        $validated['for_sale'] = in_array($statusSewa, ['beli', 'sewa_beli'], true);

        // Tarif sewa hanya bermakna bila koleksi memang disewakan; selain itu paksa 0.
        $validated['daily_rate'] = $validated['for_rent']
            && array_key_exists('daily_rate', $validated) && $validated['daily_rate'] !== null && $validated['daily_rate'] !== ''
                ? (int) $validated['daily_rate']
                : 0;

        // Harga jual hanya bermakna bila koleksi memang dijual; selain itu kosongkan.
        $validated['sale_price'] = $validated['for_sale']
            && array_key_exists('sale_price', $validated) && $validated['sale_price'] !== null && $validated['sale_price'] !== ''
                ? (int) $validated['sale_price']
                : null;

        $validated['weight_gram'] = array_key_exists('weight_gram', $validated) && $validated['weight_gram'] !== null && $validated['weight_gram'] !== ''
            ? (int) $validated['weight_gram']
            : null;

        return $validated;
    }

    public function destroy(Koleksi $koleksi): RedirectResponse
    {
        if ($koleksi->foto) {
            Storage::disk('public')->delete($koleksi->foto);
        }

        $koleksi->delete();

        return redirect()->route('koleksi.index')->with('success', 'Koleksi berhasil dihapus.');
    }

    public function gallery(Request $request): View
    {
        $search = $request->query('search');
        $kategoriFilter = $request->query('kategori');
        $statusFilter = $request->query('status');

        $koleksiQuery = Koleksi::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('tahun', 'like', "%{$search}%")
                        ->orWhere('seniman', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when($kategoriFilter, fn ($query) => $query->where('kategori', $kategoriFilter))
            ->when($statusFilter, fn ($query) => $query->where('status_sewa', $statusFilter))
            ->orderByDesc('created_at');

        $koleksis = $koleksiQuery
            ->paginate(50)
            ->withQueryString();

        $heroPhotoPath = collect(Storage::disk('public')->files('galeri'))
            ->first(fn (string $path) => preg_match('/\.(jpe?g|png|gif|webp)$/i', $path));

        $heroPhotoUrl = $heroPhotoPath
            ? asset('storage/' . $heroPhotoPath)
            : null;

        return view('gallery.index', compact('koleksis', 'search', 'kategoriFilter', 'statusFilter', 'heroPhotoUrl'));
    }

    public function showPublic(Koleksi $koleksi): View
    {

        $koleksiLain = Koleksi::query()
            ->whereKeyNot($koleksi->id)
            ->latest()
            ->limit(16)
            ->get();

        return view('gallery.show', compact('koleksi', 'koleksiLain'));
    }

    /**
     * Detail koleksi publik — sumber data: tabel paintings (sama dengan katalog penyewaan).
     */
    public function showPublicPainting(Painting $painting): View
    {
        $koleksi = $painting->linkedKoleksi;

        if (! $koleksi) {
            abort(404);
        }

        $koleksiLain = Koleksi::query()
            ->whereKeyNot($koleksi->id)
            ->latest()
            ->limit(16)
            ->get();

        return view('gallery.show', compact('koleksi', 'painting', 'koleksiLain'));
    }
}