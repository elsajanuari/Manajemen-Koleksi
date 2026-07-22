<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
const RENDER_W = 1060;

function setProgress(pct, t, s) {
    document.getElementById('p-title').textContent = t || '...';
    document.getElementById('p-sub').textContent = s || '';
    document.getElementById('p-bar').style.width = pct + '%';
}
function showOvl() { document.getElementById('progress-overlay').classList.add('show'); }
function hideOvl() { document.getElementById('progress-overlay').classList.remove('show'); }
function showTicket(i) {
    document.querySelectorAll('.ticket-wrap').forEach((w, x) => w.classList.toggle('visible', x === i));
    document.querySelectorAll('.tab-pill').forEach((p, x) => p.classList.toggle('active', x === i));
}

async function renderCard(idx) {
    const stage = document.getElementById('render-stage');
    const original = document.getElementById('ticket-card-' + idx);
    const clone = original.cloneNode(true);
    const h = original.offsetHeight || 420;
    
    clone.style.cssText = `width:${RENDER_W}px;min-height:${h}px;height:auto;max-width:none;display:flex;border-radius:18px;overflow:hidden;background:#fff;box-shadow:none`;
    clone.querySelector('.tk-stub').style.minHeight = h + 'px';
    clone.querySelector('.tk-perforation').style.minHeight = h + 'px';
    clone.querySelector('.tk-body').style.minHeight = h + 'px';
    
    stage.innerHTML = '';
    stage.appendChild(clone);
    await new Promise(r => requestAnimationFrame(() => requestAnimationFrame(r)));

    const canvas = await html2canvas(clone, {
        scale: 2.5, useCORS: true, allowTaint: true,
        width: RENDER_W, height: clone.offsetHeight,
        windowWidth: RENDER_W + 40, windowHeight: clone.offsetHeight + 100,
        backgroundColor: '#fff', logging: false,
        onclone: d => Promise.all(Array.from(d.querySelectorAll('img')).map(i => new Promise(r => { i.complete ? r() : (i.onload = i.onerror = r); })))
    });
    stage.innerHTML = '';
    return canvas;
}

function makePdf(canvas) {
    const { jsPDF } = window.jspdf;
    const w = 279, h = (canvas.height / canvas.width) * w;
    const pdf = new jsPDF({ orientation: 'landscape', unit: 'mm', format: [w, h + 3] });
    pdf.addImage(canvas.toDataURL('image/jpeg', .97), 'JPEG', 0, 0, w, h);
    return pdf;
}

async function downloadSingle(idx, slug, num, prefix) {
    const btn = document.getElementById('btn-dl-' + idx);
    const orig = btn.innerHTML;
    btn.innerHTML = '⏳...'; btn.disabled = true; showOvl();
    setProgress(20, 'Merender tiket ' + num, '');
    try {
        const canvas = await renderCard(idx);
        setProgress(70, 'Membuat PDF...', '');
        const pdf = makePdf(canvas);
        setProgress(95, 'Mengunduh...', '');
        pdf.save('e-tiket-' + prefix + '-' + num + '-' + slug + '.pdf');
        setProgress(100, 'Selesai!', '');
    } catch(e) { alert('Gagal mengunduh.'); console.error(e); }
    finally { setTimeout(hideOvl, 400); btn.innerHTML = orig; btn.disabled = false; }
}

async function downloadAll(total, prefix) {
    const btn = document.getElementById('btn-dl-all');
    btn.disabled = true; showOvl();
    try {
        const zip = new JSZip();
        const folder = zip.folder('e-tiket-' + prefix);
        for (let i = 0; i < total; i++) {
            setProgress(Math.round(i/total*80), 'Merender ' + (i+1) + '/' + total, '');
            folder.file('tiket-' + (i+1) + '.pdf', makePdf(await renderCard(i)).output('arraybuffer'));
        }
        setProgress(88, 'Membuat ZIP...', '');
        const blob = await zip.generateAsync({ type: 'blob' });
        setProgress(98, 'Mengunduh ZIP...', '');
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'e-tiket-semua-' + prefix + '.zip';
        a.click();
        URL.revokeObjectURL(a.href);
        setProgress(100, 'Selesai!', '');
    } catch(e) { alert('Gagal membuat ZIP.'); console.error(e); }
    finally { setTimeout(hideOvl, 500); btn.disabled = false; }
}
</script>