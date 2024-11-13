<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Snippet;
use App\Policies\SnippetPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
    protected $policies = [
        Snippet::class => SnippetPolicy::class,
    ];
}
