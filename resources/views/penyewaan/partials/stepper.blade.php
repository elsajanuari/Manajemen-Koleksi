@php
    $currentStep = $currentStep ?? 1;
    $totalSteps  = 3;
    $labels = [
        1 => 'Jenis Penyewa',
        2 => session('penyewaan_step1.rental_type', 'perseorangan') === 'instansi'
                ? 'Identitas & PIC'
                : 'Info Pribadi & Kontak',
        3 => 'Data Penyewaan & Submit',
    ];
    $progress = round(($currentStep / $totalSteps) * 100);
@endphp

<style>
.sp-stepper-wrap {
    max-width: 1100px;
    margin: 0 auto 1.5rem;
    padding: 0 1.5rem;
}
.sp-stepper {
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 1.5rem;
    padding: 1.5rem 2rem;
    box-shadow: 0 4px 20px rgba(15,23,42,.06);
}
.sp-stepper-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}
.sp-stepper-header h3 {
    font-family: 'DM Sans', sans-serif;
    font-size: .82rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #0a1628;
    margin: 0;
}
.sp-stepper-header span {
    font-size: .78rem;
    font-weight: 600;
    color: #64748b;
    background: #f1f5f9;
    padding: .25rem .75rem;
    border-radius: 99px;
}
.sp-steps {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .75rem;
    margin-bottom: 1.25rem;
}
.sp-step {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .75rem 1rem;
    border-radius: 1rem;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    transition: all .2s;
}
.sp-step.done {
    border-color: #bbf7d0;
    background: #f0fdf4;
}
.sp-step.active {
    border-color: #2563eb;
    background: #eff6ff;
    box-shadow: 0 0 0 3px rgba(37,99,235,.08);
}
.sp-step.pending {
    opacity: .55;
}
.sp-step-num {
    width: 32px;
    height: 32px;
    flex-shrink: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .82rem;
    font-weight: 700;
    font-family: 'DM Sans', sans-serif;
    background: #e2e8f0;
    color: #64748b;
}
.sp-step.done   .sp-step-num { background: #16a34a; color: #fff; }
.sp-step.active .sp-step-num { background: #2563eb; color: #fff; }
.sp-step-label {
    font-family: 'DM Sans', sans-serif;
    font-size: .8rem;
    font-weight: 600;
    color: #0a1628;
    line-height: 1.3;
}
.sp-step.pending .sp-step-label { color: #94a3b8; }
.sp-step.active  .sp-step-label { color: #1d4ed8; }
.sp-step.done    .sp-step-label { color: #15803d; }

.sp-progress-bar {
    height: 6px;
    background: #e2e8f0;
    border-radius: 99px;
    overflow: hidden;
}
.sp-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #2563eb, #38bdf8);
    border-radius: 99px;
    transition: width .4s ease;
}

@media (max-width: 640px) {
    .sp-stepper { padding: 1.25rem; }
    .sp-steps { gap: .5rem; }
    .sp-step { padding: .625rem .75rem; gap: .5rem; }
    .sp-step-num { width: 28px; height: 28px; font-size: .75rem; }
    .sp-step-label { font-size: .72rem; }
}
</style>

<div class="sp-stepper-wrap">
    <div class="sp-stepper">
        <div class="sp-stepper-header">
            <h3>Progress Pengisian Form</h3>
            <span>Langkah {{ $currentStep }} dari {{ $totalSteps }}</span>
        </div>
        <div class="sp-steps">
            @foreach($labels as $n => $lbl)
                @php
                    $state = $n < $currentStep ? 'done' : ($n === $currentStep ? 'active' : 'pending');
                @endphp
                <div class="sp-step {{ $state }}">
                    <div class="sp-step-num">
                        @if($state === 'done')
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="3"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        @else
                            {{ $n }}
                        @endif
                    </div>
                    <span class="sp-step-label">{{ $lbl }}</span>
                </div>
            @endforeach
        </div>
        <div class="sp-progress-bar">
            <div class="sp-progress-fill" style="width: {{ $progress }}%"></div>
        </div>
    </div>
</div>