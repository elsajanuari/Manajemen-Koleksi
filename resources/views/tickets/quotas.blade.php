<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('tickets.index') }}" class="hover:text-gray-700 transition">Tiket</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('tickets.show', $ticket->id) }}" class="hover:text-gray-700 transition">{{ $ticket->nama_tiket }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Kelola Kuota Tiket</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Kelola Kuota Tiket</h1>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $ticket->nama_tiket }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <form action="{{ route('tickets.regenerate-quota', ['ticket' => $ticket->id]) }}" method="POST" class="inline" id="regenerate-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    id="regenerate-btn"
                                    onclick="return confirmRegenerate()"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Regenerasi Kuota
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6">

                <div class="mb-6 flex items-start gap-3 bg-blue-50/50 rounded-xl p-4 border border-blue-200">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-800">
                            <span class="font-semibold">Periode Tiket:</span>
                            {{ \Carbon\Carbon::parse($ticket->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }} 
                            @if($ticket->tanggal_selesai)
                                - {{ \Carbon\Carbon::parse($ticket->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}
                            @else
                                - seterusnya
                            @endif
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            <span class="font-semibold">Kuota Default:</span> {{ $ticket->kuota }} orang/hari
                        </p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3.5 text-sm text-green-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5 text-sm text-amber-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ session('warning') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Harga Tiket</p>
                            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($ticket->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Kuota Default</p>
                            <p class="text-xl font-bold text-gray-900">{{ $ticket->kuota }}</p>
                            <p class="text-xs text-gray-400">orang/hari</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total Terjual</p>
                            <p class="text-xl font-bold text-blue-600">{{ $quotas->sum('kuota_terjual') }}</p>
                            <p class="text-xs text-gray-400">tiket</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total Tanggal</p>
                            <p class="text-xl font-bold text-gray-900">{{ $quotas->total() }}</p>
                            <p class="text-xs text-gray-400">hari</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Hari</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Terjual</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Sisa</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Okupansi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($quotas as $quota)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <div>
                                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($quota->tanggal)->locale('id')->translatedFormat('d M Y') }}</span>
                                            <span class="block sm:hidden text-xs text-gray-400">{{ \Carbon\Carbon::parse($quota->tanggal)->locale('id')->translatedFormat('l') }}</span>
                                            @if($quota->is_holiday ?? false)
                                                <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-600">
                                                    🏛️ Libur
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">
                                        {{ \Carbon\Carbon::parse($quota->tanggal)->locale('id')->translatedFormat('l') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('tickets.quotas.update', [$ticket->id, $quota->id]) }}" 
                                              method="POST" 
                                              class="flex items-center gap-1"
                                              onsubmit="return validateQuota(this, {{ $quota->kuota_terjual }})">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" 
                                                   name="kuota_max" 
                                                   value="{{ $quota->kuota_max }}" 
                                                   min="{{ $quota->kuota_terjual }}"
                                                   class="w-16 h-8 text-sm border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition text-center quota-input"
                                                   data-terjual="{{ $quota->kuota_terjual }}">
                                            <button type="submit" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Simpan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                        @if($quota->kuota_terjual > 0)
                                            <span class="text-[10px] text-gray-400 block mt-0.5">min {{ $quota->kuota_terjual }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 hidden md:table-cell">
                                        {{ $quota->kuota_terjual }}
                                    </td>
                                    <td class="px-4 py-3 hidden lg:table-cell">
                                        <span class="px-2 py-1 rounded-md text-sm font-medium {{ $quota->kuota_sisa > 0 ? 'text-emerald-600 bg-emerald-50' : 'text-red-600 bg-red-50' }}">
                                            {{ $quota->kuota_sisa }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 hidden md:table-cell">
                                        <div class="flex flex-col gap-1 w-24">
                                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-blue-500 h-full rounded-full transition-all duration-500" 
                                                     style="width: {{ min($quota->persentase, 100) }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-medium text-gray-500">{{ $quota->persentase }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusLabels = [
                                                'expired' => 'Kadaluarsa',
                                                'penuh' => 'Penuh',
                                                'low_stock' => 'Hampir Penuh',
                                                'available' => 'Tersedia'
                                            ];
                                            $statusColors = [
                                                'expired' => 'bg-gray-100 text-gray-500',
                                                'penuh' => 'bg-red-100 text-red-700',
                                                'low_stock' => 'bg-amber-100 text-amber-700',
                                                'available' => 'bg-emerald-100 text-emerald-700'
                                            ];
                                            $currentStyle = $statusColors[$quota->status] ?? 'bg-blue-100 text-blue-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium uppercase tracking-wider {{ $currentStyle }}">
                                            {{ $statusLabels[$quota->status] ?? str_replace('_', ' ', $quota->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center">
                                        <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-900">Belum Ada Data Kuota</h3>
                                        <p class="text-sm text-gray-500 max-w-xs mx-auto mt-1">
                                            Klik tombol <span class="font-medium text-amber-600">Regenerasi Kuota</span> untuk membuat jadwal ketersediaan tiket secara otomatis.
                                        </p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($quotas->hasPages())
                        <div class="px-4 py-3 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-2">
                            <span class="text-xs text-gray-500 text-center sm:text-left">
                                Menampilkan {{ $quotas->firstItem() ?? 0 }} - {{ $quotas->lastItem() ?? 0 }} 
                                dari {{ $quotas->total() }} data
                            </span>
                            {{ $quotas->links() }}
                        </div>
                    @endif
                </div>

                <div class="mt-6 text-xs text-gray-400 border-t border-gray-100 pt-4">
                    <p>* Kuota minimum adalah jumlah tiket yang sudah terjual pada tanggal tersebut.</p>
                    <p>* Regenerasi kuota akan menyesuaikan semua tanggal dengan jadwal dan hari libur nasional.</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        function confirmRegenerate() {
            const btn = document.getElementById('regenerate-btn');
            const totalTerjual = {{ $quotas->sum('kuota_terjual') }};
            
            if (totalTerjual > 0) {
                return confirm(
                    '⚠️ Peringatan!\n\n' +
                    'Regenerasi kuota akan:\n' +
                    '1. Menyesuaikan semua tanggal dengan jadwal\n' +
                    '2. Menyesuaikan hari libur nasional\n' +
                    '3. Kuota yang sudah terjual akan tetap dipertahankan\n\n' +
                    'Total tiket sudah terjual: ' + totalTerjual + ' tiket\n\n' +
                    'Apakah Anda yakin ingin melanjutkan?'
                );
            }
            
            return confirm(
                'Regenerasi kuota akan menyesuaikan semua tanggal dengan jadwal dan hari libur nasional.\n\n' +
                'Apakah Anda yakin ingin melanjutkan?'
            );
        }

        function validateQuota(form, terjual) {
            const input = form.querySelector('input[name="kuota_max"]');
            const value = parseInt(input.value);
            
            if (isNaN(value) || value < terjual) {
                alert('❌ Kuota tidak boleh kurang dari ' + terjual + ' (tiket sudah terjual pada tanggal ini)');
                input.focus();
                input.select();
                return false;
            }
            
            if (value < 1) {
                alert('❌ Kuota minimal adalah 1');
                input.focus();
                input.select();
                return false;
            }
            
            return true;
        }

        document.querySelectorAll('.quota-input').forEach(input => {
            const terjual = parseInt(input.dataset.terjual);
            
            input.addEventListener('blur', function() {
                const value = parseInt(this.value);
                if (!isNaN(value) && value < terjual) {
                    this.value = terjual;
                    this.style.borderColor = '#ef4444';
                    setTimeout(() => {
                        this.style.borderColor = '';
                    }, 2000);
                }
            });

            input.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (!isNaN(value) && value < terjual) {
                    this.style.borderColor = '#ef4444';
                } else {
                    this.style.borderColor = '';
                }
            });
        });
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
        
        .quota-input:invalid {
            border-color: #ef4444;
        }
    </style>
</x-app-layout>