<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RKPINIT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:rkpd {tahun}';

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
         $tahun=$this->argument('tahun');
    
         $d= \App\Http\Controllers\SIPD\RKPD\InitCtrl::init($tahun,true);
         $this->info("Building {$d}");
    }
}
