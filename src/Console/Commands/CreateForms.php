<?php

namespace NedeljkoKuzmanovic\DbForms\Console\Commands;

use Illuminate\Console\Command;
use NedeljkoKuzmanovic\DbForms\Classes\Generator;

class CreateForms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nk-dbforms:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates form classes for all database tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            echo Generator::generateAllForms();
        }catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
