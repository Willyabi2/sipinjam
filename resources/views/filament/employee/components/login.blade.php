<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <div class="flex items-center justify-between">
            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </div>

        <!-- Tambahkan tombol admin login di sini -->
        <div class="mt-4 text-center">
            <a 
                href="{{ route('filament.admin.auth.login') }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                Login sebagai Admin
            </a>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page.simple>