<?php

namespace App\Http\Controllers;

use App\Models\Painting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KatalogMuseumController extends Controller
{
    public function index(Request $request): View
    {
        $query = Painting::query()
            ->with('linkedKoleksi')
            ->orderByDesc('available')
            ->orderBy('title');

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('artist', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $kategori = trim((string) $request->query('kategori', ''));
        if ($kategori !== '') {
            $query->where('category', $kategori);
        }

        $paintings = $query->paginate(12)->withQueryString();

        $categories = Painting::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter();

        return view('katalog-museum.index', [
            'paintings' => $paintings,
            'categories' => $categories,
            'search' => $search,
            'kategori' => $kategori,
            'pageTitle' => 'Katalog Koleksi Museum',
        ]);
    }

    public function show(string $slug): View
    {
        $painting = Painting::query()
            ->where('slug', $slug)
            ->with('linkedKoleksi')
            ->firstOrFail();

        $related = Painting::query()
            ->where('id', '!=', $painting->id)
            ->orderByDesc('available')
            ->orderBy('title')
            ->limit(4)
            ->get();

        return view('katalog-museum.show', [
            'painting' => $painting,
            'related' => $related,
            'pageTitle' => $painting->title,
        ]);
    }

    public function ajukanPenyewaan(string $slug): RedirectResponse
    {
        $painting = Painting::query()->where('slug', $slug)->with('linkedKoleksi')->firstOrFail();
        $koleksi  = $painting->linkedKoleksi;

        if (Auth::user()->role !== 'pengguna') {
            return redirect()->route('gallery.show', $koleksi ?? $slug)
                ->with('error', 'Pengajuan penyewaan hanya untuk akun pengunjung.');
        }

        if (! $koleksi || ! $koleksi->dapatDisewa()) {
            return redirect()->route($koleksi ? 'gallery.show' : 'katalog-museum.show', $koleksi ?? $slug)
                ->with('error', 'Koleksi ini sedang tidak tersedia untuk disewa.');
        }

        return redirect()->route('penyewaan.step1', $koleksi);
    }

    public function beliKoleksi(string $slug): RedirectResponse
    {
        $painting = Painting::query()->where('slug', $slug)->with('linkedKoleksi')->firstOrFail();
        $koleksi  = $painting->linkedKoleksi;

        if (! $koleksi || ! $koleksi->isForSale()) {
            return redirect()->route($koleksi ? 'gallery.show' : 'katalog-museum.show', $koleksi ?? $slug)
                ->with('error', 'Koleksi ini tidak tersedia untuk dibeli. Silakan hubungi museum.');
        }

        if (Auth::user()->role !== 'pengguna') {
            return redirect()->route('gallery.show', $koleksi)
                ->with('error', 'Pengajuan pembelian hanya untuk akun pengunjung.');
        }

        return redirect()->route('pembelian.step1', $koleksi);
    }
}
