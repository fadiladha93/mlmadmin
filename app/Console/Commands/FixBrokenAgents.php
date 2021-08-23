<?php

namespace App\Console\Commands;

use App\Exceptions\BinaryNodeInUseException;
use App\Facades\BinaryPlanManager;
use App\Models\BinaryPlanNode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class FixTreeDuplicates
 * @package App\Console\Commands
 */
class FixBrokenAgents extends Command
{
    const ROOT_ID = 242;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binary-tree:fix:agents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix agents within the binary tree.';

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

        $this->info('Start the fix agents process');

        // TODO: It has already processed on the live server
        //$this->fixBrokenAgents();

        $this->info('Finish.');
    }

    private function fixBrokenAgents()
    {
        // At first delete nodes

        DB::transaction(function () {
            // Move nodes

            // 24751
            $rootNode = $this->getNodeByAgentTsa('TSA5524585');
            $newNode = $this->getByUserId(24751);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 21786
            $rootNode = $this->getNodeByAgentTsa('TSA4224936');
            $newNode = $this->getByUserId(21786);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 25350
            $rootNode = $this->getNodeByAgentTsa('TSA7121786');
            $newNode = $this->getByUserId(25350);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 20348
            // kelvin dickson - 20348
            $rootNode = $this->getNodeByAgentTsa('TSA1520348');
            // add willy horn to kelly
            $this->addLeftLeg($rootNode, $this->getNodeByAgentTsa('TSA6419208'));
            $rasaanNode = $this->getNodeByAgentTsa('TSA6619189');
            $bernardNode = $this->getNodeByAgentTsa('TSA3719536');
            $this->addRightLeg($rasaanNode, $bernardNode);

            // 24035
            $rasaanNode = $this->getNodeByAgentTsa('TSA6619189');
            $newNode = $this->getByUserId(24035);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rasaanNode),
                $newNode
            );

            // 25003
            $rootNode = $this->getNodeByAgentTsa('TSA9618374');
            $newNode = $this->getByUserId(25003);
            $this->addRightLeg(
                BinaryPlanManager::getLastRightNode($rootNode),
                $newNode
            );


            // 18374
            $rootNode = $this->getNodeByAgentTsa('TSA7116435');
            $newNode = $this->getByUserId(18374);
            $this->addRightLeg(
                BinaryPlanManager::getLastRightNode($rootNode),
                $newNode
            );

            // 23837
            $rootNode = $this->getNodeByAgentTsa('TSA3319909');
            $newNode = $this->getByUserId(23837);
            $this->addRightLeg(
                BinaryPlanManager::getLastRightNode($rootNode),
                $newNode
            );

            // 19005
            $rootNode = $this->getNodeByAgentTsa('TSA6924197');
            $newNode = $this->getByUserId(19005);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 22645
            $rootNode = $this->getNodeByAgentTsa('TSA1218899');
            $newNode = $this->getByUserId(22645);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 24154
            $from = $this->getByUserId(24154);
            $to = $this->getNodeByAgentTsa('TSA4725350');
            BinaryPlanManager::moveNode($from, $to, BinaryPlanManager::DIRECTION_LEFT, false, true);

            // 21796
            $rootNode = $this->getNodeByAgentTsa('TSA8824514');
            $newNode = $this->getByUserId(21796);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 24692
            $rootNode = $this->getNodeByAgentTsa('TSA8824514');
            $newNode = $this->getByUserId(24692);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 14515
            $rootNode = $this->getNodeByAgentTsa('TSA4119395');
            $newNode = $this->getByUserId(14515);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 20752
            $rootNode = $this->getNodeByAgentTsa('TSA6923837');
            $newNode = $this->getByUserId(20752);
            $this->addRightLeg(
                BinaryPlanManager::getLastRightNode($rootNode),
                $newNode
            );

            // 2812
            $rootNode = $this->getNodeByAgentTsa('TSA9296164');
            $newNode = $this->getByUserId(2812);
            $this->addRightLeg(
                BinaryPlanManager::getLastRightNode($rootNode),
                $newNode
            );

            // 14726
            $rootNode = $this->getNodeByAgentTsa('TSA1012649');
            $newNode = $this->getByUserId(14726);
            $this->addLeftLeg(
                BinaryPlanManager::getLastLeftNode($rootNode),
                $newNode
            );

            // 20058
            $rootNode = $this->getNodeByAgentTsa('TSA4419945');
            $newNode = $this->getByUserId(20058);
            $this->addRightLeg(
                BinaryPlanManager::getLastRightNode($rootNode),
                $newNode
            );

            $this->removeNodes();

            BinaryPlanNode::fixTree();
        });
    }

    private function removeNodes()
    {
        $rootNode = BinaryPlanNode::where('user_id', self::ROOT_ID)->first();
        BinaryPlanNode::where('_lft', '<', $rootNode->_lft)->delete();
        BinaryPlanNode::where('_lft', '>', $rootNode->_rgt)->delete();
    }

    private function getByUserId($id)
    {
        return BinaryPlanNode::where('user_id', $id)->first();
    }

    public function getNodeByAgentTsa($tsaNumber)
    {
        $node = null;

        $recordId = DB::table('binary_plan')
            ->select('binary_plan.id')
            ->join('users', 'binary_plan.user_id', '=', 'users.id')
            ->where('users.distid', $tsaNumber)
            ->pluck('id')
            ->first();

        if ($recordId) {
            $node = BinaryPlanNode::where('id', $recordId)->first();
        }

        return $node;
    }

    public function addLeftLeg($rootNode, $newNode)
    {
        $legs = BinaryPlanNode::where('parent_id', $rootNode->id)->count();

        if ($legs > 2) {
            throw new BinaryNodeInUseException($rootNode);
        }
        // Set the default direction of next node insertions
        $newNode->setLeftDirection();
        $newNode->depth = $rootNode->depth + 1;

        // TODO: Add enrolled_at value

        $rootNode->appendNode($newNode);
    }

    public function addRightLeg($rootNode, $newNode)
    {
        $legs = BinaryPlanNode::where('parent_id', $rootNode->id)->count();

        if ($legs > 2) {
            throw new BinaryNodeInUseException($rootNode);
        }

        // Set the default direction of next node insertions
        $newNode->setRightDirection();
        $newNode->depth = $rootNode->depth + 1;

        // TODO: Add enrolled_at value

        $rootNode->appendNode($newNode);
    }
}
