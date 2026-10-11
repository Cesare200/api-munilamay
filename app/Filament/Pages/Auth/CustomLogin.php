<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Sistema Integrado Municipal';
    }

    public function getSubheading(): ?string
    {
        return 'Acceso exclusivo para administradores y funcionarios de Lamay';
    }
}