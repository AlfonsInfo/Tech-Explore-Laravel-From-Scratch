<?php

namespace App\Providers;

use App\Data\Foo;
use App\Data\Bar;
use Illuminate\Support\ServiceProvider;

//* implements DeferableProvider -> provider yang tidak diload jika tidak dibutuhkan
//* Defeared vs Eager
class FooBarServiceProvider extends ServiceProvider
{

    //*Singleton properties
    public array $singletons = [
        // namaService
    ];

    public function register(): void
    {
        $this->app->singleton(Foo::class, function($app){
            return new Foo();
        });
        $this->app->singleton(Bar::class, function($app){
            return new Bar($app->make(Foo::class));
        });

    }

    public function boot(): void
    {

    }
}
