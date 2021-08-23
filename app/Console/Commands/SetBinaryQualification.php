<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class SetBinaryQualification extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set_binary_qualification';

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        set_time_limit(0);
        DB::select("select * from binary_qualification()");
    }

}
