<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display catalog of collections
     */
    public function catalog(Request $request)
    {
        $query = Koleksi::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('artist_name', 'like', "%{$search}%");
        }

        // Filter by category
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by availability
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            if ($status === 'tersedia') {
                $query->where('status_sewa', 'tidak');
            } elseif ($status === 'dipesan') {
                $query->where('status_sewa', 'ya');
            }
        }

        // Sort
        $sortBy = $request->input('sort', 'terbaru');
        if ($sortBy === 'harga_terendah') {
            $query->orderBy('price', 'asc');
        } elseif ($sortBy === 'harga_tertinggi') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderByDesc('created_at');
        }

        // Get categories for filter
        $categories = Koleksi::distinct()->pluck('kategori')->filter()->sort();

        // Paginate
        $koleksis = $query->paginate(12)->appends($request->query());

        return view('penjualan.katalog', compact('koleksis', 'categories'));
    }

    /**
     * Display collection details
     */
    public function show(Koleksi $koleksi)
    {
        // Get related collections
        $relatedCollections = Koleksi::where('kategori', $koleksi->kategori)
            ->where('id', '!=', $koleksi->id)
            ->limit(4)
            ->get();

        return view('penjualan.detail-koleksi', compact('koleksi', 'relatedCollections'));
    }

    /**
     * Get availability status badge
     */
    public function getAvailabilityStatus(Koleksi $koleksi): array
    {
        $isAvailable = $koleksi->isAvailableForPurchase();
        
        return [
            'status' => $isAvailable ? 'tersedia' : 'dipesan',
            'label' => $isAvailable ? 'Tersedia' : 'Dipesan',
            'badge_class' => $isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
        ];
    }
}
