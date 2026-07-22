@props(['timeline', 'currentStatus'])

@if($timeline && count($timeline) > 0)
    <div class="delivery-timeline">
        <div style="margin-bottom:1rem; font-size:0.75rem; font-weight:600; color:#64748b; display:flex; align-items:center; gap:0.5rem;">
            <span>📦</span> Riwayat Pengiriman
        </div>
        <div style="display:flex; flex-direction:column; gap:0.75rem;">
            @foreach(array_reverse($timeline) as $entry)
                <div style="display:flex; gap:0.75rem; align-items:flex-start;">
                    {{-- Icon status --}}
                    <div style="flex-shrink:0;">
                        @switch($entry['status'])
                            @case('dikemas')
                                <div style="width:32px;height:32px;background:#fef3c7;border-radius:50%;display:flex;align-items:center;justify-content:center;">📦</div>
                                @break
                            @case('siap_dikirim')
                                <div style="width:32px;height:32px;background:#dbeafe;border-radius:50%;display:flex;align-items:center;justify-content:center;">✅</div>
                                @break
                            @case('dalam_perjalanan')
                                <div style="width:32px;height:32px;background:#ede9fe;border-radius:50%;display:flex;align-items:center;justify-content:center;">🚚</div>
                                @break
                            @case('tiba_di_tujuan')
                                <div style="width:32px;height:32px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;">🏁</div>
                                @break
                            @default
                                <div style="width:32px;height:32px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;">📍</div>
                        @endswitch
                    </div>

                    {{-- Content --}}
                    <div style="flex:1;">
                        <div style="display:flex; flex-wrap:wrap; align-items:baseline; justify-content:space-between; gap:0.5rem;">
                            <span style="font-weight:700; color:#0b1d35;">{{ $entry['label'] }}</span>
                            <span style="font-size:0.7rem; color:#94a3b8;">{{ \Carbon\Carbon::parse($entry['timestamp'])->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        @if($entry['catatan'] ?? false)
                            <p style="margin-top:0.25rem; font-size:0.75rem; color:#64748b;">{{ $entry['catatan'] }}</p>
                        @endif
                        @if($entry['by'] ?? false)
                            <p style="margin-top:0.2rem; font-size:0.65rem; color:#94a3b8;">Oleh: {{ $entry['by'] }}</p>
                        @endif
                    </div>
                </div>
                @if(!$loop->last)
                    <div style="margin-left:1rem; border-left:2px dashed #e2e8f0; height:1rem;"></div>
                @endif
            @endforeach
        </div>
    </div>
@else
    <div style="padding:1.5rem; background:#f8fafc; border-radius:1rem; text-align:center; color:#94a3b8;">
        <span style="font-size:2rem;">📦</span>
        <p style="margin-top:0.5rem;">Belum ada update status pengiriman.</p>
        <p style="font-size:0.7rem;">Status akan muncul setelah pengelola mengupdate pengiriman.</p>
    </div>
@endif

{{-- Status Badge ringkasan --}}
@if($currentStatus)
    <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid #e2e8f0;">
        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
            <span style="font-size:0.7rem; font-weight:600; color:#64748b;">STATUS TERKINI:</span>
            <span style="
                display:inline-flex; align-items:center; gap:0.3rem;
                padding:0.3rem 0.8rem; border-radius:99px; font-size:0.75rem; font-weight:600;
                background: 
                    @switch($currentStatus)
                        @case('dikemas') #fef3c7 @break
                        @case('siap_dikirim') #dbeafe @break
                        @case('dalam_perjalanan') #ede9fe @break
                        @case('tiba_di_tujuan') #d1fae5 @break
                        @default #f1f5f9
                    @endswitch
                ;
                color:
                    @switch($currentStatus)
                        @case('dikemas') #b45309 @break
                        @case('siap_dikirim') #1d4ed8 @break
                        @case('dalam_perjalanan') #6d28d9 @break
                        @case('tiba_di_tujuan') #065f46 @break
                        @default #64748b
                    @endswitch
            ">
                @switch($currentStatus)
                    @case('dikemas') 📦 @break
                    @case('siap_dikirim') ✅ @break
                    @case('dalam_perjalanan') 🚚 @break
                    @case('tiba_di_tujuan') 🏁 @break
                    @default 📍
                @endswitch
                {{ match($currentStatus) {
                    'dikemas' => 'Sedang Dikemas',
                    'siap_dikirim' => 'Siap Dikirim',
                    'dalam_perjalanan' => 'Dalam Perjalanan',
                    'tiba_di_tujuan' => 'Tiba di Tujuan',
                    default => 'Menunggu Update'
                } }}
            </span>
        </div>
    </div>
@endif