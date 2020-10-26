<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SATinit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:sat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $d=  \App\Http\Controllers\SAT\InitCtrl::init(2020);
         $this->info("Building {$d}");
    }
}
