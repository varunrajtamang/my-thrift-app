<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Support\Arr;

class AdminLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
{
    $data = $this->form->getState();

    // Remove 'remember' from credentials before passing to auth provider
    $credentials = Arr::except($data, ['remember']);

    $user = Auth::getProvider()->retrieveByCredentials($credentials);

    if (! $user || ! Auth::getProvider()->validateCredentials($user, $credentials)) {
        $this->addError('email', __('filament-panels::pages/auth/login.messages.failed'));
        return null;
    }

    if ($user->user_type !== 'admin') {
        Notification::make()
            ->title('Access Denied')
            ->body('Only administrators can log in here.')
            ->danger()
            ->send();

        return null;
    }

    Auth::login($user, $data['remember'] ?? false);
    session()->regenerate();

    return app(LoginResponse::class);
}
}

