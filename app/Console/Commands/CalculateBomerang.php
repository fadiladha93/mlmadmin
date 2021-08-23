<?php

namespace App\Console\Commands;

use App\Models\BinaryPlanNode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class MigrateBinaryTree
 * @package App\Console\Commands
 */
class CalculateBomerang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:calculate_boomerang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate boomerang';

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
     * Execute the command.
     */
    public function handle()
    {
       DB::select('select calculate_bomerangs()');
    }


}
