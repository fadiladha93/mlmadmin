<?php

namespace App\Console\Commands;

use App\Models\BinaryPlanNode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class MigrateBinaryTree
 * @package App\Console\Commands
 */
class CalculateDepthBinaryTree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binary-tree:users:depth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the depth of agents in binary tree';

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
        // make sure we have time to execute the script.
        set_time_limit(0);

        $this->info('Fix the binary tree structure.');

        // Fix the nested set structure (will update left and right values)
        DB::transaction(function () {
            BinaryPlanNode::fixTree();
        });

        $this->info('Start re-calculate a depth of nodes.');

        $this->calculateDepth();

        $this->info('Finish.');
    }

    /**
     * Calculates binary tree levels
     */
    private function calculateDepth()
    {
        DB::statement('
            UPDATE binary_plan bp
            SET depth = (SELECT (COUNT(parent.id) - 1)
                FROM binary_plan AS node,
                    binary_plan AS parent
                 WHERE node._lft BETWEEN parent._lft AND parent._rgt
                AND node.id = bp.id
                 GROUP BY node.id
                 ORDER BY node._lft
                );
        ');
    }
}
