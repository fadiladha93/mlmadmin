<?php

namespace App\Console\Commands;

use App\Models\BinaryPlanNode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class FixTreeDuplicates
 * @package App\Console\Commands
 */
class FixTreeDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binary-tree:fix:duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix duplicates for the existing tree.';

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

        $this->info('Start the fix process of tree agent duplicates');

        $this->fixDuplicates();

        $this->info('Finish.');
    }

    private function fixDuplicates(): void
    {
        $brokenUserIds = DB::table('binary_plan')
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(user_id) > 1')
            ->pluck('user_id')->toArray();

        foreach ($brokenUserIds as $id) {
            $duplicates = BinaryPlanNode::where('user_id', $id)->get();

            foreach ($duplicates as $duplicate) {
                // try to get sub-node of the current node
                $parentNode = BinaryPlanNode::where('parent_id', $duplicate->id)
                    ->where('_lft', '>', $duplicate->_lft)
                    ->where('_rgt', '<', $duplicate->_rgt)
                    ->get();
                // if the node has sub-nodes put it to process array for next checks
                if ($parentNode->count() > 0) {
                    // find the same records as duplicate node and force delete it
                    $duplicatesForExisting = BinaryPlanNode::where('parent_id', $duplicate->parent_id)
                        ->where('_lft', $duplicate->_lft)
                        ->where('_rgt', $duplicate->_rgt)
                        ->where('id', '<>', $duplicate->id)
                        ->get();

                    foreach ($duplicatesForExisting as $dup) {
                        BinaryPlanNode::where('id', $dup->id)->delete();
                    }
                } else {
                    // delete leaves by the model logic (it can be processed manually later)
                    if ($duplicate->_rgt - $duplicate->_lft === 1) {
                        // try to get sub-node of the current node
                        $parentLeafNode = BinaryPlanNode::where('parent_id', $duplicate->id)
                            ->where('_lft', '>', $duplicate->_lft)
                            ->where('_rgt', '<', $duplicate->_rgt)
                            ->get();

                        if ($parentLeafNode->count() === 0) {
                            $duplicate->delete();
                        }
                    }
                }
            }
        }
    }
}
