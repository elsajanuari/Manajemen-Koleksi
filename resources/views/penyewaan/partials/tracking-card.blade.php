{{-- resources/views/penyewaan/partials/tracking-card.blade.php --}}
<div class="st-card" id="tracking-card-st">
    <div class="st-card-header">
        <div class="st-card-header-accent"></div>
        <h3>Lacak Pengiriman</h3>
        <div style="margin-left:auto;">
            <button onclick="loadTrackingST(true)" id="btn-refresh-st"
                style="display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1.5px solid var(--border);border-radius:.6rem;background:var(--white);font-size:.73rem;font-weight:600;color:var(--slate);cursor:pointer;transition:all .15s;"
                onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--slate)'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                Refresh
            </button>
        </div>
    </div>
    <div class="st-card-body">
        <div id="tracking-loading-st" style="display:flex;flex-direction:column;gap:.75rem;">
            @for($i = 0; $i < 3; $i++)
            <div style="height:48px;background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);background-size:200% 100%;border-radius:.75rem;animation:shimmer 1.5s infinite;"></div>
            @endfor
        </div>
        <div id="tracking-error-st" style="display:none;" class="st-catatan">
            <div class="lbl" style="color:#dc2626;">Gagal Memuat</div>
            <div class="val" id="tracking-error-msg-st"></div>
        </div>
        <div id="tracking-content-st" style="display:none;">
            <div id="tracking-summary-st" style="margin-bottom:1.25rem;"></div>
            <div id="tracking-history-st"></div>
        </div>
    </div>
</div>

<script>
(function () {
    const TRACKING_URL_ST = "{{ $trackingUrl }}";

    window.loadTrackingST = async function (refresh = false) {
        const l = document.getElementById('tracking-loading-st');
        const e = document.getElementById('tracking-error-st');
        const c = document.getElementById('tracking-content-st');
        const b = document.getElementById('btn-refresh-st');

        l.style.display = 'flex';
        e.style.display = 'none';
        c.style.display = 'none';
        if (b) b.disabled = true;

        try {
            const res = await fetch(TRACKING_URL_ST + (refresh ? '?refresh=1' : ''), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const result = await res.json();
            l.style.display = 'none';

            if (!result.success || !result.data) {
                document.getElementById('tracking-error-msg-st').textContent =
                    result.message || 'Data tracking tidak tersedia saat ini.';
                e.style.display = '';
                return;
            }
            renderTrackingST(result.data);
            c.style.display = '';
        } catch (err) {
            l.style.display = 'none';
            document.getElementById('tracking-error-msg-st').textContent =
                'Gagal memuat data tracking. Coba klik tombol Refresh.';
            e.style.display = '';
        } finally {
            if (b) b.disabled = false;
        }
    };

    function renderTrackingST(data) {
        const isDelivered = data.delivered;
        document.getElementById('tracking-summary-st').innerHTML = `
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem;margin-bottom:1rem;">
                <div class="st-meta-cell">
                    <div class="lbl">Kurir</div>
                    <div class="val">${escST(data.courier)}${data.service ? ' <span style="font-weight:400;font-size:.78rem;color:var(--slate);">— ' + escST(data.service) + '</span>' : ''}</div>
                </div>
                <div class="st-meta-cell">
                    <div class="lbl">No. Resi</div>
                    <div class="val" style="font-family:monospace;font-size:.82rem;">${escST(data.awb)}</div>
                </div>
                <div class="st-meta-cell">
                    <div class="lbl">Status</div>
                    <div class="val">
                        <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.35rem 1rem;border-radius:99px;font-size:.75rem;font-weight:700;
                            background:${isDelivered ? '#d1fae5' : '#eff6ff'};
                            border:1.5px solid ${isDelivered ? '#6ee7b7' : '#bfdbfe'};
                            color:${isDelivered ? '#065f46' : '#1d4ed8'};">
                            ${isDelivered ? '✓' : '→'} ${escST(data.status)}
                        </span>
                    </div>
                </div>
                ${data.destination ? `<div class="st-meta-cell"><div class="lbl">Tujuan</div><div class="val">${escST(data.destination)}</div></div>` : ''}
            </div>`;

        const historyEl = document.getElementById('tracking-history-st');
        if (!data.history || data.history.length === 0) {
            historyEl.innerHTML = `<div class="st-catatan"><div class="lbl">Riwayat Pengiriman</div><div class="val">Belum ada update dari kurir.</div></div>`;
            return;
        }

        let html = `<div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">Riwayat Pengiriman</div>`;
        data.history.forEach((item, i) => {
            html += `<div style="display:flex;gap:.875rem;padding:.75rem 0;border-bottom:1px solid #f0f4f8;">
                <div style="width:8px;height:8px;border-radius:50%;background:${i === 0 ? '#1d4ed8' : '#38bdf8'};margin-top:.35rem;flex-shrink:0;${i === 0 ? 'box-shadow:0 0 0 3px rgba(29,78,216,.15);' : ''}"></div>
                <div style="flex:1;">
                    <div style="font-size:.83rem;font-weight:500;color:#0b1d35;line-height:1.4;">${escST(item.description)}</div>
                    <div style="font-size:.72rem;color:#94a3b8;margin-top:.2rem;">${item.city ? escST(item.city) + ' &bull; ' : ''}${escST(item.datetime)}</div>
                </div>
            </div>`;
        });
        historyEl.innerHTML = html;
    }

    function escST(str) {
        if (!str) return '';
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', () => loadTrackingST(false));
})();
</script>