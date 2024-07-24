<?php

declare(strict_types=1);

namespace Irlix\StubHelper;

use Illuminate\Support\ServiceProvider;

final class IrlixStubHelperProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerCommands();
    }

    public function register(): void
    {

    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\CreateBackendTemplate::class,
                Console\CreateResourceTemplate::class,
            ]);
        }
    }
}