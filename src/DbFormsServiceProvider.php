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
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
