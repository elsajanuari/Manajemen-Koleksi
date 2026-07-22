@php
    $items = collect($timeline ?? [])->reverse();
@endphp

@if($items->isNotEmpty())
<div class="st-card" style="margin-top:1rem;">
    <div class="st-card-header">
        <div class="st-card-header-accent"></div>
        <h3>Status Pengiriman</h3>
    </div>
    <div class="st-card-body">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.65rem;">
            📋 Riwayat Status
        </div>
        <div style="display:flex;flex-direction:column;gap:.65rem;">
            @foreach($items as $item)
                <div style="display:flex;gap:.75rem;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:.875rem;padding:.85rem 1rem;">
                    <div style="width:8px;height:8px;border-radius:50%;background:#1d4ed8;flex-shrink:0;margin-top:.4rem;"></div>
                    <div style="flex:1;">
                        <div style="font-size:.83rem;font-weight:700;color:#0b1d35;margin-bottom:.15rem;">
                            {{ $item['label'] ?? ucfirst(str_replace('_', ' ', $item['status'] ?? '-')) }}
                        </div>
                        @if(!empty($item['catatan']))
                            <div style="font-size:.78rem;color:#475569;margin-bottom:.3rem;line-height:1.5;">
                                {{ $item['catatan'] }}
                            </div>
                        @endif
                        <div style="font-size:.71rem;color:#94a3b8;">
                            @if(!empty($item['timestamp']))
                                {{ \Carbon\Carbon::parse($item['timestamp'])->format('d M Y, H:i') }}
                            @endif
                            @if(!empty($item['by']))
                                &middot; oleh {{ $item['by'] }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif