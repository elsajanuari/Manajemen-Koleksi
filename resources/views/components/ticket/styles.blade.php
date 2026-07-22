<style>
:root {
    --tk-bg: #fdf6ec; --tk-ink: #1c1c2e; --tk-gold: #c4954a;
    --tk-border: #dedad0; --tk-ok-bg: #e8f7ee; --tk-ok-fg: #1e7a48;
    --tk-used-bg: #fff5e0; --tk-used-fg: #a06020;
}
body { background: var(--tk-bg); margin:0; }
.eti-page { min-height:100vh; padding:2.5rem 1rem; font-family:'DM Sans',sans-serif; }
.eti-container { max-width:1080px; margin:0 auto; }

/* actions */
.eti-actions { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:10px; }
.btn-back { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:10px; border:1px solid #ccc8be; background:#fff; font-size:13px; color:#555; text-decoration:none; }
.btn-back:hover { background:#f4f2ed; }
.btn-download { display:inline-flex; align-items:center; gap:7px; padding:11px 24px; border-radius:10px; border:none; background:var(--tk-ink); color:#fff; font-size:13px; font-weight:600; cursor:pointer; }
.btn-download:hover { opacity:.85; }

/* ticket */
.ticket-outer { overflow-x:auto; margin:0 auto; }
.ticket-card { width:1060px; min-height:420px; display:flex; border-radius:18px; overflow:hidden; box-shadow:0 8px 40px rgba(28,28,46,.13); background:#fff; }

/* stub */
.tk-stub { width:250px; min-height:420px; flex-shrink:0; background:var(--tk-ink); display:flex; flex-direction:column; justify-content:space-between; padding:28px 26px 24px; position:relative; overflow:hidden; }
.tk-stub::before { content:''; position:absolute; top:-60px; left:-60px; width:180px; height:180px; border-radius:50%; background:rgba(196,149,74,.18); }
.tk-stub::after { content:''; position:absolute; bottom:-50px; right:-50px; width:150px; height:150px; border-radius:50%; background:rgba(196,149,74,.12); }
.stub-label { font-family:'Syne',sans-serif; font-size:10px; font-weight:600; letter-spacing:.2em; text-transform:uppercase; color:rgba(255,255,255,.45); margin:0 0 10px; position:relative; z-index:1; }
.stub-title { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#fff; line-height:1.2; margin:0; position:relative; z-index:1; }
.stub-id { font-size:11px; color:rgba(255,255,255,.35); margin:6px 0 0; position:relative; z-index:1; }
.stub-date-label { font-size:9px; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.4); margin:0 0 3px; }
.stub-date-val { font-size:15px; font-weight:600; color:#fff; margin:0; }
.stub-badge { display:inline-flex; align-items:center; gap:5px; padding:5px 12px; border-radius:99px; font-size:11px; font-weight:600; border:1.5px solid; white-space:nowrap; }
.badge-ok { background:var(--tk-ok-bg); color:var(--tk-ok-fg); border-color:#9dd6b8; }
.badge-used { background:var(--tk-used-bg); color:var(--tk-used-fg); border-color:#f0cf84; }
.badge-dot { width:6px; height:6px; border-radius:50%; background:currentColor; display:inline-block; }

/* perforation */
.tk-perforation { width:0; border-left:2px dashed var(--tk-border); flex-shrink:0; min-height:420px; position:relative; }
.tk-perforation::before, .tk-perforation::after { content:''; position:absolute; left:50%; transform:translateX(-50%); width:22px; height:22px; border-radius:50%; background:var(--tk-bg); border:1px solid var(--tk-border); }
.tk-perforation::before { top:-11px; }
.tk-perforation::after { bottom:-11px; }

/* body */
.tk-body { flex:1; min-height:420px; display:flex; flex-direction:column; justify-content:space-between; padding:28px 30px 22px; background:#fff; }
.tk-body-top { display:flex; gap:20px; align-items:flex-start; flex:1; }
.tk-fields { flex:1; }
.tk-qr-panel { width:200px; flex-shrink:0; display:flex; flex-direction:column; align-items:center; padding-left:20px; border-left:1px dashed var(--tk-border); }
.qr-label { font-size:9px; letter-spacing:.16em; text-transform:uppercase; color:#bbb5aa; font-weight:600; margin:0 0 10px; }
.qr-img-wrap { background:#fff; border-radius:12px; padding:10px; border:1px solid var(--tk-border); }
.qr-img-wrap img { width:148px; height:148px; display:block; }
.qr-token { font-size:8.5px; color:#c0b8ac; text-align:center; max-width:180px; word-break:break-all; line-height:1.6; margin:8px 0 0; }

.field-row { display:flex; gap:10px; margin-bottom:10px; }
.field { flex:1; background:#f8f6f2; border-radius:10px; padding:10px 14px; }
.field.f2 { flex:2; }
.field-label { font-size:8.5px; text-transform:uppercase; letter-spacing:.13em; color:#bbb; margin:0 0 4px; }
.field-val { font-size:14px; font-weight:600; color:var(--tk-ink); margin:0; word-wrap:break-word; }
.field-val.lg { font-size:16px; }
.field-val.sm { font-size:11.5px; font-weight:500; }
.member-small { font-size:10px; color:#999; margin:3px 0 0; }

.tk-notice { border-radius:10px; padding:10px 14px; border-left:3px solid var(--tk-gold); background:#fdf6ec; font-size:12px; color:#7c6030; line-height:1.5; margin-top:10px; }
.tk-notice.used { border-color:#e4a940; background:#fff9ec; color:#9a6020; }
.notice-head { font-weight:600; margin:0 0 2px; }
.notice-body { margin:0; }

.tk-footer { border-top:1px dashed var(--tk-border); padding:9px 0 0; margin-top:12px; display:flex; justify-content:space-between; }
.foot-id, .foot-org { font-size:9.5px; color:#c0b8ac; }

/* visibility */
.ticket-wrap { visibility:hidden; height:0; overflow:hidden; pointer-events:none; }
.ticket-wrap.visible { visibility:visible; height:auto; overflow:visible; margin-bottom:1.5rem; pointer-events:auto; }

/* progress */
#progress-overlay { display:none; position:fixed; inset:0; z-index:9999; background:rgba(28,28,46,.55); align-items:center; justify-content:center; }
#progress-overlay.show { display:flex; }
.progress-box { background:#fff; border-radius:16px; padding:30px 40px; text-align:center; min-width:260px; }
.progress-title { font-size:15px; font-weight:600; color:var(--tk-ink); margin:0 0 6px; }
.progress-sub { font-size:12px; color:#999; margin:0; }
.progress-bar-wrap { margin-top:14px; background:#eee; border-radius:99px; height:6px; overflow:hidden; }
.progress-bar { height:100%; background:var(--tk-ink); border-radius:99px; transition:width .2s; }

/* render stage */
#render-stage { position:fixed; left:-9999px; top:0; width:1060px; min-height:420px; height:auto; overflow:visible; pointer-events:none; z-index:-1; }
#render-stage .ticket-card { width:1060px !important; min-height:420px !important; height:auto !important; }
#render-stage .tk-stub, #render-stage .tk-perforation, #render-stage .tk-body { min-height:420px !important; }

/* toolbar */
.multi-toolbar { background:#fff; border:1px solid #e8e5e0; border-radius:14px; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:1.5rem; }
.tab-scroll { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
.tab-lbl { font-size:12px; font-weight:600; color:#888; margin-right:4px; }
.tab-pill { padding:6px 16px; border-radius:99px; font-size:12px; font-weight:500; border:1.5px solid #e8e5e0; background:transparent; color:#666; cursor:pointer; transition:all .15s; }
.tab-pill:hover { border-color:#c4954a; color:#1c1c2e; }
.tab-pill.active { background:#1c1c2e; color:#fff; border-color:#1c1c2e; }
.tab-pill.used-pill { color:#a06020; border-color:#f0cf84; background:#fff9ec; }
.tab-pill.used-pill.active { background:#a06020; color:#fff; }
.tab-pill .badge { background:rgba(255,255,255,.2); border-radius:99px; padding:0 6px; font-size:10px; margin-left:4px; }

.toolbar-actions { display:flex; gap:10px; }
.btn-dl-all, .btn-dl-one { display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border-radius:10px; font-size:13px; font-weight:600; border:none; cursor:pointer; transition:all .15s; }
.btn-dl-all { background:#1c1c2e; color:#fff; }
.btn-dl-all:hover { background:#2d2d44; }
.btn-dl-all:disabled, .btn-dl-one:disabled { opacity:.5; cursor:not-allowed; }
.btn-dl-one { background:#fff; color:#1c1c2e; border:1.5px solid #e8e5e0; }
.btn-dl-one:hover { border-color:#c4954a; background:#fdf8f0; }
.ticket-dl-row { display:flex; justify-content:flex-end; margin-bottom:12px; }

@media (max-width:640px) {
    .multi-toolbar { flex-direction:column; }
    .tab-scroll, .toolbar-actions { justify-content:center; }
    .btn-dl-all, .btn-dl-one { flex:1; justify-content:center; }
}
</style>