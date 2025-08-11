<x-filament::page class="login-bg flex items-center justify-center min-h-screen">
    <div class="bg-white/30 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Sistem Peminjaman</h1>
            <p class="text-lg text-gray-700 font-semibold">Masuk ke Akun Anda</p>
        </div>

        <form method="POST" action="{{ route('filament.admin.auth.login') }}" class="space-y-4">
            @csrf

            <x-filament::form wire:submit.prevent="authenticate" id="login">
                <x-filament::form.fields>
                    <x-filament::form.field label="Email">
                        <x-filament::input type="email" name="email" required autofocus />
                    </x-filament::form.field>

                    <x-filament::form.field label="Password">
                        <x-filament::input type="password" name="password" required />
                    </x-filament::form.field>

                    <x-filament::form.field>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                    </x-filament::form.field>
                </x-filament::form.fields>

                <x-filament::button type="submit" class="w-full bg-orange-600 hover:bg-orange-700">
                    Masukan Akun Anda
                </x-filament::button>
            </x-filament::form>
        </form>
    </div>
</x-filament::page>
