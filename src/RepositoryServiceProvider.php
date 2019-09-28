<?php

namespace Jenhacool\Repository;

use Illuminate\Support\ServiceProvider;
use Jenhacool\Repository\Console\RepositoryGeneratorCommand;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(RepositoryGeneratorCommand::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/repository.php' => config_path('repository.php')
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../config/repository.php', 'repository');
    }

    public function provides()
    {
        return [];
    }
}
