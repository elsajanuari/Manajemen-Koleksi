<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <span class="text-gray-700 font-medium">Verifikasi Tiket</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Verifikasi Tiket</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Scan QR Code atau masukkan kode tiket untuk verifikasi</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Alert --}}
                @if(session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3.5 text-sm text-green-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5 text-sm text-amber-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Tabs --}}
                <div class="mb-6 flex gap-1 bg-gray-100 rounded-xl p-1">
                    <button type="button" id="btnScanMode" 
                            class="flex-1 py-2.5 px-4 rounded-lg text-sm font-medium bg-blue-600 text-white shadow-sm transition-all">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Scan QR Code
                        </span>
                    </button>
                    <button type="button" id="btnManualMode" 
                            class="flex-1 py-2.5 px-4 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 transition-all">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Manual Input
                        </span>
                    </button>
                </div>

                {{-- Mode Scan --}}
                <div id="scanMode" class="transition-opacity duration-300">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse" id="scanIndicator"></span>
                                <span class="text-sm text-gray-600" id="statusText">Meminta akses kamera...</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="relative bg-gray-900 rounded-lg overflow-hidden" style="min-height: 350px;">
                                <div id="reader" style="width: 100%; min-height: 350px;"></div>
                                <div id="scanOverlay" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-52 h-52 border-2 border-white/30 rounded-lg shadow-lg"></div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-center">
                                <button id="restartBtn" 
                                        class="hidden inline-flex items-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Coba Lagi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mode Manual --}}
                <div id="manualMode" class="hidden transition-opacity duration-300">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="p-4 bg-gray-50 border-b border-gray-200">
                            <p class="text-sm text-gray-600">Masukkan kode tiket atau URL yang tertera pada QR Code</p>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('pengelola.verifikasi-tiket.lookup') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Tiket / URL</label>
                                    <textarea name="kode" rows="4" 
                                              class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"
                                              placeholder="Tempel atau ketik kode tiket di sini..."></textarea>
                                    <p class="mt-1.5 text-xs text-gray-400">
                                        Masukkan token verifikasi tiket atau URL lengkap dari QR Code
                                    </p>
                                </div>
                                <button type="submit" 
                                        class="w-full flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Cari Data Tiket
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Feedback --}}
                <div id="feedbackMsg" class="mt-4 hidden rounded-xl p-4 text-sm font-medium"></div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrScanner = null;
        let isScanning = false;
        const statusText = document.getElementById('statusText');
        const scanIndicator = document.getElementById('scanIndicator');
        const feedbackMsg = document.getElementById('feedbackMsg');
        const restartBtn = document.getElementById('restartBtn');

        function extractTicketToken(scannedText) {
            let token = (scannedText || '').trim();
            const match = token.match(/\/scan-tiket\/([^/?\s]+)/i);
            if (match && match[1]) token = match[1];
            return token.split('?')[0].split('/')[0];
        }

        function isValidSystemTicketToken(token) {
            return /^[a-f0-9]{64}$/i.test((token || '').trim());
        }

        function onScanSuccess(decodedText) {
            if (!isScanning) return;

            const token = extractTicketToken(decodedText);
            if (!isValidSystemTicketToken(token)) {
                isScanning = false;
                statusText.textContent = '⚠️ QR tidak dikenali';
                scanIndicator.className = 'w-2 h-2 rounded-full bg-red-500';
                showFeedback('QR yang Anda pindai bukan hasil generate sistem e-tiket ini. Pastikan Anda memindai QR Code tiket dari sistem ini.', 'bg-red-50 text-red-700 border border-red-200');
                restartBtn.classList.remove('hidden');
                if (html5QrScanner) {
                    html5QrScanner.stop().catch(() => {});
                }
                return;
            }

            statusText.textContent = '✅ Berhasil Terdeteksi!';
            scanIndicator.className = 'w-2 h-2 rounded-full bg-green-500';

            if (html5QrScanner && isScanning) {
                isScanning = false;
                html5QrScanner.stop().then(() => {
                    processRedirect(decodedText);
                }).catch(() => processRedirect(decodedText));
            } else {
                processRedirect(decodedText);
            }
        }

        function processRedirect(scannedText) {
            const token = extractTicketToken(scannedText);
            if (!isValidSystemTicketToken(token)) {
                showFeedback('QR yang Anda pindai bukan hasil generate sistem e-tiket ini. Pastikan Anda memindai QR Code tiket dari sistem ini.', 'bg-red-50 text-red-700 border border-red-200');
                restartBtn.classList.remove('hidden');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('pengelola.verifikasi-tiket.lookup') }}";
            form.style.display = 'none';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const kodeInput = document.createElement('input');
            kodeInput.type = 'hidden';
            kodeInput.name = 'kode';
            kodeInput.value = token;

            form.appendChild(csrfInput);
            form.appendChild(kodeInput);
            document.body.appendChild(form);
            form.submit();
        }

        async function startScanner() {
            restartBtn.classList.add('hidden');
            feedbackMsg.classList.add('hidden');

            if (!navigator.mediaDevices?.getUserMedia) {
                statusText.textContent = '❌ Browser tidak mendukung kamera';
                scanIndicator.className = 'w-2 h-2 rounded-full bg-red-500';
                showFeedback('Browser Anda tidak mendukung akses kamera. Gunakan mode Manual Input.', 'bg-red-50 text-red-700 border border-red-200');
                restartBtn.classList.remove('hidden');
                return;
            }

            statusText.textContent = '📷 Meminta izin kamera...';
            scanIndicator.className = 'w-2 h-2 rounded-full bg-yellow-500';

            if (html5QrScanner && isScanning) {
                try { await html5QrScanner.stop(); } catch(e) {}
            }

            if (!html5QrScanner) {
                try {
                    html5QrScanner = new Html5Qrcode("reader");
                } catch(e) {
                    statusText.textContent = '❌ Gagal inisialisasi scanner';
                    scanIndicator.className = 'w-2 h-2 rounded-full bg-red-500';
                    showFeedback('Gagal memuat scanner. Refresh halaman.', 'bg-red-50 text-red-700 border border-red-200');
                    restartBtn.classList.remove('hidden');
                    return;
                }
            }

            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(d => d.kind === 'videoinput');
                if (videoDevices.length === 0) throw new Error('Tidak ada kamera');

                await html5QrScanner.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    onScanSuccess
                );
                isScanning = true;
                statusText.textContent = '📷 Arahkan ke QR Code';
                scanIndicator.className = 'w-2 h-2 rounded-full bg-green-500 animate-pulse';
            } catch (err) {
                statusText.textContent = '❌ ' + (err.message || 'Gagal akses kamera');
                scanIndicator.className = 'w-2 h-2 rounded-full bg-red-500';
                showFeedback('Izin kamera ditolak atau tidak ada kamera. Gunakan mode Manual Input.', 'bg-red-50 text-red-700 border border-red-200');
                restartBtn.classList.remove('hidden');
            }
        }

        function showFeedback(msg, classes) {
            feedbackMsg.innerHTML = msg;
            feedbackMsg.className = `mt-4 rounded-xl p-4 text-sm font-medium ${classes}`;
            feedbackMsg.classList.remove('hidden');
        }

        const btnScan = document.getElementById('btnScanMode');
        const btnManual = document.getElementById('btnManualMode');
        const scanMode = document.getElementById('scanMode');
        const manualMode = document.getElementById('manualMode');

        btnScan.addEventListener('click', () => {
            scanMode.classList.remove('hidden');
            manualMode.classList.add('hidden');
            btnScan.className = 'flex-1 py-2.5 px-4 rounded-lg text-sm font-medium bg-blue-600 text-white shadow-sm transition-all';
            btnManual.className = 'flex-1 py-2.5 px-4 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 transition-all';
            startScanner();
        });

        btnManual.addEventListener('click', async () => {
            manualMode.classList.remove('hidden');
            scanMode.classList.add('hidden');
            btnManual.className = 'flex-1 py-2.5 px-4 rounded-lg text-sm font-medium bg-blue-600 text-white shadow-sm transition-all';
            btnScan.className = 'flex-1 py-2.5 px-4 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 transition-all';

            if (html5QrScanner && isScanning) {
                try { await html5QrScanner.stop(); } catch(e) {}
                isScanning = false;
            }
        });

        restartBtn.addEventListener('click', startScanner);

        document.addEventListener('DOMContentLoaded', () => setTimeout(startScanner, 500));

        window.addEventListener('beforeunload', async () => {
            if (html5QrScanner && isScanning) {
                try { await html5QrScanner.stop(); } catch(e) {}
            }
        });
    </script>

    <style>
        #reader { border: none !important; }
        #reader video { object-fit: cover !important; border-radius: 0.5rem; width: 100% !important; }
        #reader__scan_region { background: #000; border-radius: 0.5rem; }
        #reader__dashboard_section_csr span { display: none; }
        #scanOverlay { pointer-events: none; }
        #scanOverlay .w-52 {
            border-radius: 12px;
            box-shadow: 0 0 0 9999px rgba(0,0,0,0.3), inset 0 0 30px rgba(0,0,0,0.1);
        }
        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
    </style>
</x-app-layout>