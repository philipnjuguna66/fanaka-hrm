<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Auth\Login;

class AdminLogin extends Login
{

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        if(app()->environment() === 'local'){

            $this->form->fill([
                'email' => User::first()?->email,
                'password' => 'password',
            ]);
        }

    }
}
