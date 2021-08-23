<?php

namespace App\Console\Commands;

use App\Models\BinaryPlanNode;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class FixTreeDuplicates
 * @package App\Console\Commands
 */
class ReleaseHoldingTankItems extends Command
{
    const ROOT_TSA = 'TSA0002566';
    const TARGET_TSA = 'TSA1234351';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binary-tree:users:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release users from the binary tree hierarchy';

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

        $this->info('Start the release process');

        try {
            $this->releaseNodes();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $this->info('Finish.');
    }

    private function releaseNodes(): void
    {
        $this->deleteOutOfTreeItems();
        $this->deleteChildren();
    }

    private function deleteChildren()
    {
        $targetUser = User::where('distid', self::TARGET_TSA)->first();

        if (!$targetUser) {
            throw new \Exception('Target user does not exist.');
        }

        $targetNode = BinaryPlanNode::where('user_id', $targetUser->id)->first();

        if (!$targetNode) {
            throw new \Exception('Target user binary node does not exist.');
        }

        // all users are not involved to the root binary structure
        $holdingTankItems = BinaryPlanNode::where('_lft', '>', $targetNode->_lft)
            ->where('_rgt', '<', $targetNode->_rgt)
            ->get();

        // flatten existing structure
        foreach ($holdingTankItems as $node) {
            $node->makeRoot();
            $node->depth = 0;
            $node->save();
        }

        // delete following nodes
        foreach ($holdingTankItems as $node) {
            if ($node->user->distid === self::TARGET_TSA) {
                // do nothing
            } else {
                $node->delete();
            }
        }
    }

    private function deleteOutOfTreeItems()
    {
        $rootUser = User::where('distid', self::ROOT_TSA)->first();

        if (!$rootUser) {
            throw new \Exception('Root user does not exist.');
        }

        $rootNode = BinaryPlanNode::where('user_id', $rootUser->id)->first();

        if (!$rootNode) {
            throw new \Exception('Root user binary node does not exist.');
        }

        // all users are not involved to the root binary structure
        $holdingTankItems = BinaryPlanNode::where('_lft', '>', $rootNode->_rgt)->get();

        // flatten extisting structure
        foreach ($holdingTankItems as $node) {
            $node->makeRoot();
            $node->depth = 0;
            $node->save();
        }

        // delete following nodes
        foreach ($holdingTankItems as $node) {
            if ($node->user->distid === self::TARGET_TSA) {
                // do nothing
            } else {
                $node->delete();
            }
        }
    }


}
