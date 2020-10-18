<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RKPD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make-data:rkpd {tahun}';

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

        $d= \App\Http\Controllers\SIPD\RKPD\GETDATA::console_update($tahun);
        $this->info("Building {$d}");

    }
}
