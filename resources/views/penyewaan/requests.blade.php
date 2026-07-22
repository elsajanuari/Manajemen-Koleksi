<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-3">
                <h1 class="text-4xl font-bold tracking-tight text-slate-900">Kelola Pengajuan</h1>
                <p class="max-w-2xl text-lg text-slate-600">Pantau status penyewaan koleksi Anda, dari draft hingga pembayaran dan serah terima.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">Total: {{ $requests->count() }} pengajuan</span>
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">Draft: {{ $requests->where('submission_status', 'draft')->count() }}</span>
                    <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-sm font-semibold text-sky-700">Proses: {{ $requests->where('submission_status', 'submitted')->count() }}</span>
                </div>
                <a href="{{ route('penyewaan.index') }}" class="inline-flex items-center rounded-full bg-gradient-to-r from-blue-600 to-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-sky-700 transition-all">
                    + Pengajuan Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-3xl border border-emerald-300 bg-emerald-50 p-5 text-emerald-700 shadow-sm font-medium">
                    <span class="inline-block mr-2">✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-3xl border border-red-300 bg-red-50 p-5 text-red-700 shadow-sm font-medium">
                    <span class="inline-block mr-2">❌</span> {{ session('error') }}
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex border-b border-slate-200">
                    <button onclick="showTab('draft')" class="tab-button active flex-1 px-6 py-4 font-semibold text-center transition border-b-2 border-blue-600 text-blue-600 bg-blue-50" id="tab-draft-btn">
                        <span class="inline-block mr-2">📋</span> Draft ({{ $requests->where('submission_status', 'draft')->count() }})
                    </button>
                    <button onclick="showTab('submitted')" class="tab-button flex-1 px-6 py-4 font-semibold text-center transition border-b-2 border-transparent text-slate-600 hover:text-slate-900" id="tab-submitted-btn">
                        <span class="inline-block mr-2">✉️</span> Pengajuan ({{ $requests->where('submission_status', 'submitted')->count() }})
                    </button>
                </div>
            <!-- Tab: Draft -->
            <div id="tab-draft" class="tab-content space-y-4">
                @forelse($requests->where('submission_status', 'draft') as $draft)
                    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $draft->painting->title }}</span>
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                        📍 Langkah {{ $draft->current_step ?? 1 }}/5
                                    </span>
                                </div>
                                <h3 class="mt-3 text-2xl font-bold text-slate-900">{{ $draft->painting->artist }}</h3>
                                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 p-4 text-sm border border-slate-200">
                                        <p class="text-xs uppercase tracking-[0.16em] font-semibold text-slate-500">Tipe Penyewa</p>
                                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $draft->rental_type === 'instansi' ? '🏢 Instansi' : '👤 Pribadi' }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-gradient-to-br from-blue-50 to-sky-100 p-4 text-sm border border-blue-200">
                                        <p class="text-xs uppercase tracking-[0.16em] font-semibold text-blue-600">Dibuat</p>
                                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $draft->created_at->format('d M') }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 p-4 text-sm border border-slate-200">
                                        <p class="text-xs uppercase tracking-[0.16em] font-semibold text-slate-500">Terakhir diperbarui</p>
                                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $draft->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3 sm:flex-nowrap">
                                <a href="{{ route('penyewaan.step' . min($draft->current_step ?? 1, 5), ['koleksi' => $draft->painting->id]) }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-sky-600 px-6 py-3 text-sm font-semibold text-white hover:from-blue-700 hover:to-sky-700 shadow-md hover:shadow-lg transition-all">
                                    Lanjutkan ➜
                                </a>
                                <form action="{{ route('penyewaan.requests.cancel', $draft) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Hapus draft ini? Tindakan ini tidak dapat dibatalkan.')" class="inline-flex items-center justify-center rounded-full border-2 border-red-300 px-6 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border-2 border-dashed border-slate-300 bg-gradient-to-br from-slate-50 to-slate-100 p-12 text-center">
                        <p class="text-3xl">📭</p>
                        <p class="mt-3 text-lg font-semibold text-slate-700">Tidak ada draft</p>
                        <p class="mt-2 text-slate-600">Mulai pengajuan baru dari halaman katalog untuk membuat draft penyewaan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Tab: Submitted -->
            <div id="tab-submitted" class="tab-content hidden space-y-4">
                @forelse($requests->where('submission_status', 'submitted') as $request)
                    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $request->painting->title }}</span>
                                    @php
                                        $statusClasses = match ($request->status) {
                                            'waiting_payment' => 'bg-amber-100 text-amber-800',
                                            'preparing_delivery' => 'bg-emerald-100 text-emerald-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            default => 'bg-slate-100 text-slate-800',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                        {{ $request->status_label }}
                                    </span>
                                </div>
                                <h3 class="mt-3 text-2xl font-bold text-slate-900">{{ $request->painting->artist }}</h3>
                                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-2xl bg-gradient-to-br from-blue-50 to-sky-100 p-4 text-sm border border-blue-200">
                                        <p class="text-xs uppercase tracking-[0.16em] font-semibold text-blue-600">Pengajuan sejak</p>
                                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $request->created_at->format('d M Y') }}</p>
                                    </div>
                                    @if($request->start_date && $request->end_date)
                                        <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-100 p-4 text-sm border border-emerald-200">
                                            <p class="text-xs uppercase tracking-[0.16em] font-semibold text-emerald-600">📅 Periode Sewa</p>
                                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $request->duration_days }} hari</p>
                                        </div>
                                    @endif
                                    <div class="rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 p-4 text-sm border border-slate-200">
                                        <p class="text-xs uppercase tracking-[0.16em] font-semibold text-slate-500">Ref ID</p>
                                        <p class="mt-2 text-lg font-semibold text-slate-900 font-mono">{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('penyewaan.requests.show', ['penyewaan' => $request->id]) }}" class="inline-flex items-center justify-center rounded-full border-2 border-blue-600 px-6 py-3 text-sm font-semibold text-blue-600 hover:bg-blue-50 transition-colors">
                                    Lihat Detail →
                                </a>
                                @if($request->status === 'waiting_payment' && $request->payment_status !== 'paid')
                                    <a href="{{ route('penyewaan.requests.payment', ['penyewaan' => $request->id]) }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 shadow-md hover:shadow-lg transition-all">
                                        💳 Bayar Sekarang
                                    </a>
                                @elseif($request->payment_status === 'paid')
                                    <span class="inline-flex items-center justify-center rounded-full bg-emerald-100 px-6 py-3 text-sm font-semibold text-emerald-700">
                                        ✅ Pembayaran Lunas
                                    </span>
                                @elseif(in_array($request->payment_status, ['pending', 'failed', 'expired']))
                                    <a href="{{ route('penyewaan.requests.payment.status', $request) }}" class="inline-flex items-center justify-center rounded-full bg-amber-500 px-6 py-3 text-sm font-semibold text-white hover:bg-amber-600 shadow-md hover:shadow-lg transition-all">
                                        📊 Cek Status
                                    </a>
                                @endif
                                @if($request->status === 'waiting_payment')
                                    <form action="{{ route('penyewaan.requests.cancel', $request) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Batalkan pengajuan ini?')" class="text-sm font-semibold text-red-600 hover:text-red-700 hover:underline">
                                            Batalkan Pengajuan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border-2 border-dashed border-slate-300 bg-gradient-to-br from-slate-50 to-slate-100 p-12 text-center">
                        <p class="text-3xl">📬</p>
                        <p class="mt-3 text-lg font-semibold text-slate-700">Belum ada pengajuan</p>
                        <p class="mt-2 text-slate-600">Setiap pengajuan yang lengkap dan dikirimkan akan ditampilkan di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });
            
            // Remove active state from all buttons
            document.querySelectorAll('.tab-button').forEach(el => {
                el.classList.remove('active', 'border-blue-600', 'text-blue-600', 'bg-blue-50');
                el.classList.add('border-transparent', 'text-slate-600');
            });
            
            // Show selected tab with animation
            const selectedTab = document.getElementById('tab-' + tabName);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
                selectedTab.style.animation = 'fadeIn 0.3s ease-in';
            }
            
            // Add active state to clicked button
            const selectedBtn = document.getElementById('tab-' + tabName + '-btn');
            if (selectedBtn) {
                selectedBtn.classList.add('active', 'border-blue-600', 'text-blue-600', 'bg-blue-50');
                selectedBtn.classList.remove('border-transparent', 'text-slate-600');
            }
        }

        // Add fade-in animation
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .tab-content {
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>
