<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use App\Models\PurchaseTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    /**
     * Show purchase form
     */
    public function create(Koleksi $koleksi)
    {
        // Check if collection is available
        if (!$koleksi->isAvailableForPurchase()) {
            return redirect()->route('penjualan.detail-koleksi', $koleksi)
                ->with('error', 'Koleksi ini tidak tersedia untuk pembelian.');
        }

        return view('penjualan.form-pembelian', compact('koleksi'));
    }

    /**
     * Store purchase transaction
     */
    public function store(Request $request, Koleksi $koleksi)
    {
        // Validate
        $validated = $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'required|string|max:20',
            'buyer_address' => 'required|string',
            'notes' => 'nullable|string',
            'agree_terms' => 'required|accepted',
        ], [
            'buyer_name.required' => 'Nama lengkap wajib diisi.',
            'buyer_email.required' => 'Email wajib diisi.',
            'buyer_email.email' => 'Format email tidak valid.',
            'buyer_phone.required' => 'Nomor telepon wajib diisi.',
            'buyer_address.required' => 'Alamat lengkap wajib diisi.',
            'agree_terms.required' => 'Anda harus menyetujui syarat pembelian.',
        ]);

        // Check again if collection is available
        if (!$koleksi->isAvailableForPurchase()) {
            throw ValidationException::withMessages([
                'koleksi' => 'Koleksi ini tidak tersedia untuk pembelian.',
            ]);
        }

        try {
            DB::beginTransaction();

            // Generate transaction code
            $transactionCode = PurchaseTransaction::generateTransactionCode();

            // Create purchase transaction
            $transaction = PurchaseTransaction::create([
                'transaction_code' => $transactionCode,
                'user_id' => auth()->id(),
                'koleksi_id' => $koleksi->id,
                'buyer_name' => $validated['buyer_name'],
                'buyer_email' => $validated['buyer_email'],
                'buyer_phone' => $validated['buyer_phone'],
                'buyer_address' => $validated['buyer_address'],
                'price' => $koleksi->price,
                'notes' => $validated['notes'] ?? null,
                'status' => 'menunggu_verifikasi',
            ]);

            // Mark collection as ordered
            $koleksi->markAsOrdered();

            DB::commit();

            return redirect()->route('penjualan.detail-transaksi', $transaction)
                ->with('success', 'Pemesanan berhasil dibuat. Nomor transaksi: ' . $transactionCode);

        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'general' => 'Terjadi kesalahan saat membuat pemesanan. Silakan coba lagi.',
            ]);
        }
    }

    /**
     * Show transaction details
     */
    public function showTransaction(PurchaseTransaction $transaction)
    {
        // Check authorization
        if ($transaction->user_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return view('penjualan.detail-transaksi', compact('transaction'));
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction(Request $request, PurchaseTransaction $transaction)
    {
        // Check authorization
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if can be cancelled
        if (!$transaction->canBeCancelled()) {
            return redirect()->route('penjualan.detail-transaksi', $transaction)
                ->with('error', 'Transaksi tidak dapat dibatalkan karena sudah diproses.');
        }

        try {
            DB::beginTransaction();

            // Cancel transaction
            $transaction->cancel();

            DB::commit();

            return redirect()->route('penjualan.detail-transaksi', $transaction)
                ->with('success', 'Transaksi berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('penjualan.detail-transaksi', $transaction)
                ->with('error', 'Terjadi kesalahan saat membatalkan transaksi.');
        }
    }

    /**
     * List user's transactions
     */
    public function myTransactions()
    {
        $transactions = auth()->user()
            ->purchaseTransactions()
            ->with('koleksi')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('penjualan.transaksi-saya', compact('transactions'));
    }
}
