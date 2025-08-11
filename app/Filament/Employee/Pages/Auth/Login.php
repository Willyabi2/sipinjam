<?php

namespace App\Filament\Employee\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;
use App\Models\Employee;
use Filament\Forms\Components\View;
use Illuminate\Support\Facades\Blade;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->autofocus(),
                    
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                    
                // Add admin login button below the form
                View::make('filament.employee.components.admin-login-button')
            ])
            ->statePath('data');
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'nip' => $data['nip'],
            'name' => $data['name']
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        $employee = Employee::where('nip', $data['nip'])
                     ->where('name', $data['name'])
                     ->first();

        if (!$employee) {
            throw ValidationException::withMessages([
                'data.nip' => __('NIP atau Nama tidak valid'),
                'data.name' => __('NIP atau Nama tidak valid'),
            ]);
        }

        auth('employee')->login($employee);
        session()->regenerate();
    
        return app(LoginResponse::class);
    }
    
    // Override the default view to include our admin login button
    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
            // Add any additional form actions here
        ];
    }
}