<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $serviceBindings = [
            // Repositories
            \App\Contracts\JobRepositoryInterface::class => \App\Repositories\JobRepository::class,

            // Services
            \App\Contracts\AiServiceInterface::class => \App\Services\OpenRouterService::class,
        ];

        foreach ($serviceBindings as $abstract => $concrete) {
            if (class_exists($concrete)) {
                $this->app->bind($abstract, $concrete);
            }
        }
    }

    public function boot(): void
    {
        //
    }
}
