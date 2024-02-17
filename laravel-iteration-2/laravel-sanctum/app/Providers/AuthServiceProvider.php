<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    //* Model for mapping policy
    protected $policies = [
        //
    ];

    //* Register any authentication & authorization services
    public function boot(): void
    {
        //* SanctumServiceProvider
            Sanctum::usePersonalAccessTokenModel(\App\Models\PersonalAccessToken::class);
    }
}
