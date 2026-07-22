{{-- ── partial: _return-inspection-summary.blade.php ── --}}
@php
    $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();
    $damageCost    = (int) ($st->final_damage_cost ?? $st->damage_cost ?? 0);
    $sisaRefund    = max(0, $depositAmount - $damageCost);
    $lebihDari     = max(0, $damageCost - $depositAmount);
    $hasDamage     = $st->has_damage || $damageCost > 0;
@endphp

<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-accent"></div>
        <h3>Hasil Pemeriksaan Kondisi Koleksi</h3>
        @if($st->final_inspection_at)
            <span style="margin-left:auto;font-size:.71rem;color:#94a3b8;">
                Diperiksa {{ $st->final_inspection_at->format('d M Y, H:i') }}
            </span>
        @endif
    </div>
    <div class="st-card-body">

        {{-- Status kerusakan --}}
        @if(!$hasDamage)
            <div style="display:flex;align-items:center;gap:.75rem;padding:.875rem 1rem;
                        background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:1rem;margin-bottom:1.1rem;">
                <span style="font-size:1.5rem;">✅</span>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:#065f46;">Tidak Ditemukan Kerusakan</div>
                    <div style="font-size:.78rem;color:#166534;margin-top:.15rem;">
                        Koleksi dikembalikan dalam kondisi baik sesuai saat diserahkan.
                    </div>
                </div>
            </div>
        @else
            <div style="display:flex;align-items:center;gap:.75rem;padding:.875rem 1rem;
                        background:#fef2f2;border:1.5px solid #fecaca;border-radius:1rem;margin-bottom:1.1rem;">
                <span style="font-size:1.5rem;">⚠️</span>
                <div>
                    <div style="font-size:.88rem;font-weight:700;color:#991b1b;">Ditemukan Kerusakan pada Koleksi</div>
                    <div style="font-size:.78rem;color:#dc2626;margin-top:.15rem;">
                        Detail kerusakan tercantum di bawah ini.
                    </div>
                </div>
            </div>

            {{-- Detail tiap kerusakan (dari damage_items_detail jika ada) --}}
            @if(!empty($st->damage_items_detail))
                <div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.1rem;">
                    @foreach($st->damage_items_detail as $item)
                        <div style="display:flex;align-items:center;gap:.75rem;
                                    background:var(--white);border:1.5px solid #fecaca;
                                    border-radius:.875rem;padding:.75rem 1rem;">
                            <span style="font-size:1.1rem;">🔴</span>
                            <div style="flex:1;">
                                <div style="font-size:.83rem;font-weight:600;color:#334155;">
                                    {{ $item['label'] }}
                                </div>
                                <div style="font-size:.74rem;color:#64748b;margin-top:.1rem;">
                                    Tingkat: <strong>{{ ucfirst($item['level']) }}</strong>
                                    @if(!empty($item['note'])) · {{ $item['note'] }} @endif
                                </div>
                            </div>
                            <div style="font-size:.88rem;font-weight:700;color:#dc2626;white-space:nowrap;">
                                Rp {{ number_format($item['cost'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($st->final_damage_type)
                {{-- Fallback jika tidak ada damage_items_detail --}}
                <div style="background:var(--white);border:1.5px solid #fecaca;border-radius:.875rem;
                            padding:.875rem 1rem;margin-bottom:1.1rem;">
                    <div style="font-size:.83rem;font-weight:600;color:#334155;">
                        {{ $st->final_damage_type }}
                        @if($st->final_damage_level)
                            <span style="font-size:.74rem;font-weight:400;color:#64748b;">
                                · Tingkat: {{ ucfirst($st->final_damage_level) }}
                            </span>
                        @endif
                    </div>
                    @if($st->damage_notes)
                        <div style="font-size:.78rem;color:#64748b;margin-top:.3rem;">{{ $st->damage_notes }}</div>
                    @endif
                </div>
            @endif
        @endif

        {{-- Catatan pemeriksaan --}}
        @if($st->return_condition_notes)
            <div class="st-catatan" style="margin-bottom:1.1rem;">
                <div class="lbl">Catatan Pemeriksaan Pengelola</div>
                <div class="val">{{ $st->return_condition_notes }}</div>
            </div>
        @endif

        {{-- Kalkulasi deposit --}}
        <div class="st-cost-wrap">
            <div class="st-cost-row">
                <span class="lbl">Deposit yang Dibayarkan</span>
                <span class="val">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
            </div>
            @if($hasDamage)
                <div class="st-cost-row">
                    <span class="lbl">Total Biaya Kerusakan</span>
                    <span class="val" style="color:#f87171;">- Rp {{ number_format($damageCost, 0, ',', '.') }}</span>
                </div>
            @endif

            @if(!$hasDamage || $sisaRefund > 0)
                {{-- Tidak ada kerusakan atau ada sisa kembalian --}}
                <div class="st-cost-total">
                    <span class="lbl">{{ !$hasDamage ? 'Deposit Dikembalikan Penuh' : 'Sisa Deposit Dikembalikan' }}</span>
                    <span class="val" style="color:#34d399;">Rp {{ number_format($hasDamage ? $sisaRefund : $depositAmount, 0, ',', '.') }}</span>
                </div>
                <div style="padding:.5rem 0 0;font-size:.74rem;color:rgba(255,255,255,.5);">
                    ✅ Pengelola akan mentransfer ke rekening Anda.
                </div>

            @elseif($damageCost === $depositAmount)
                {{-- Pas habis --}}
                <div class="st-cost-total">
                    <span class="lbl">Sisa Kembalian</span>
                    <span class="val" style="color:#94a3b8;">Rp 0</span>
                </div>
                <div style="padding:.5rem 0 0;font-size:.74rem;color:rgba(255,255,255,.5);">
                    ℹ️ Deposit habis digunakan untuk menutup biaya kerusakan.
                </div>

            @else
                {{-- Kerusakan lebih mahal dari deposit --}}
                <div class="st-cost-total">
                    <span class="lbl">Deposit Hangus Seluruhnya</span>
                    <span class="val" style="color:#f87171;">- Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                </div>
                <div class="st-cost-row" style="border-top:1px solid rgba(255,255,255,.15);padding-top:.625rem;margin-top:.25rem;">
                    <span class="lbl" style="color:#fca5a5;font-weight:700;">Tagihan Tambahan yang Harus Dibayar</span>
                    <span class="val" style="color:#f87171;font-size:1.05rem;">Rp {{ number_format($lebihDari, 0, ',', '.') }}</span>
                </div>
                <div style="padding:.5rem 0 0;font-size:.74rem;color:rgba(255,255,255,.5);">
                    ⚠️ Invoice pembayaran akan dikirimkan ke email Anda.
                </div>
            @endif
        </div>

    </div>
</div>