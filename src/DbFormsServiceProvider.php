<?php

namespace NedeljkoKuzmanovic\DbForms;

use Illuminate\Support\ServiceProvider;
use \NedeljkoKuzmanovic\DbForms\Console\Commands\CreateForms;

class DbFormsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->commands([
            CreateForms::class
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/nk-dbforms.php', 'nedeljko-kuzmanovic.dbforms'
        );

        $this->publishes([
            __DIR__.'/config/nk-dbforms.php' => config_path('nedeljko-kuzmanovic/dbforms.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
