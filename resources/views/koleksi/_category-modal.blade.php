<div id="category-notification" class="mt-3 hidden rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"></div>

<x-modal name="tambah-kategori" focusable maxWidth="md">
    <div class="bg-white px-6 py-5 sm:p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Tambah Kategori</h3>
                <p class="mt-1 text-sm text-gray-500">Masukkan nama kategori baru, lalu simpan tanpa meninggalkan halaman.</p>
            </div>
            <button type="button" class="rounded-md bg-gray-100 p-2 text-gray-500 hover:bg-gray-200 hover:text-gray-700" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'tambah-kategori' }))" aria-label="Tutup modal">
                ×
            </button>
        </div>

        <div class="mt-6">
            <label for="new-category-name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input id="new-category-name" name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off" />
            <p id="new-category-name-error" class="mt-2 hidden text-sm text-red-600"></p>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'tambah-kategori' }))">
                Batal
            </button>
            <button type="button" id="save-category-button" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                Simpan
            </button>
        </div>
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('kategori');
        const saveButton = document.getElementById('save-category-button');
        const nameInput = document.getElementById('new-category-name');
        const errorMessage = document.getElementById('new-category-name-error');
        const notificationBox = document.getElementById('category-notification');
        const route = '{{ route('koleksi.categories.store') }}';
        const csrfToken = '{{ csrf_token() }}';

        if (!categorySelect || !saveButton || !nameInput || !errorMessage || !notificationBox) {
            return;
        }

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        function clearError() {
            errorMessage.textContent = '';
            errorMessage.classList.add('hidden');
        }

        function showSuccess(message) {
            notificationBox.textContent = message;
            notificationBox.classList.remove('hidden');
            setTimeout(() => notificationBox.classList.add('hidden'), 5000);
        }

        saveButton.addEventListener('click', async function () {
            clearError();
            const newCategoryName = nameInput.value.trim();

            if (!newCategoryName) {
                showError('Nama kategori wajib diisi.');
                return;
            }

            saveButton.disabled = true;
            saveButton.classList.add('opacity-70', 'cursor-not-allowed');

            try {
                const response = await fetch(route, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ name: newCategoryName }),
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422 && data.errors && data.errors.name) {
                        showError(data.errors.name[0]);
                    } else {
                        showError(data.message || 'Terjadi kesalahan saat menyimpan kategori.');
                    }
                    return;
                }

                const categoryValue = data.data.name;
                let option = Array.from(categorySelect.options).find(opt => opt.value === categoryValue);

                if (!option) {
                    option = document.createElement('option');
                    option.value = categoryValue;
                    option.textContent = categoryValue;
                    categorySelect.appendChild(option);
                }

                categorySelect.value = categoryValue;
                categorySelect.dispatchEvent(new Event('change'));
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'tambah-kategori' }));
                nameInput.value = '';
                showSuccess(data.message || 'Kategori berhasil ditambahkan.');
            } catch (error) {
                console.error(error);
                showError('Gagal menyimpan kategori. Silakan coba lagi.');
            } finally {
                saveButton.disabled = false;
                saveButton.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });
    });
</script>
