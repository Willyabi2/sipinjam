<x-filament-panels::page>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">
            Selamat Datang, {{ auth('employee')->user()->name }}!
        </h2>
        <p class="text-gray-600 dark:text-gray-300">
            NIP Anda: {{ auth('employee')->user()->nip }}
        </p>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200">Informasi Penting</h3>
                <p class="mt-2 text-blue-600 dark:text-blue-300">Lihat informasi terbaru dari perusahaan</p>
            </div>

            <div class="p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">Dokumen</h3>
                <p class="mt-2 text-green-600 dark:text-green-300">Akses dokumen-dokumen penting</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
