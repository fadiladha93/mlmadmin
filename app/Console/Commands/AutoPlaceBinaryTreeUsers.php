<?php

namespace App\Console\Commands;

use App\Facades\BinaryPlanManager;
use App\Models\BinaryPlanNode;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class AutoPlaceBinaryTreeUsers
 * @package App\Console\Commands
 */
class AutoPlaceBinaryTreeUsers extends Command
{
    const BATCH_SIZE = 100;

    const FIRST_ENROLLMENT = [
        'start' => '1970-01-01',
        'end' => '2019-05-05',
    ];

    const SECOND_ENROLLMENT = [
        // start date might be replaced with 2019-05-06 but we have to make sure
        // that all distributors will be placed to the binary tree
        'start' => '1970-01-01',
        'end' => '2019-05-12',
    ];

    /**
     * @var bool
     */
    private $interactiveMode = true;

    /**
     * Will show an additional information in the console output.
     *
     * @return bool
     */
    private function isInteractiveMode()
    {
        return $this->interactiveMode === true;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binary-tree:users:autoplace {--first-enrollment} {--second-enrollment} {--all} {--non-interactive}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-place agents to the binary tree structure.';

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

        $this->interactiveMode = !$this->option('non-interactive');

        // enrollment options
        $isFirstEnrollment = $this->option('first-enrollment');
        $isSecondEnrollment = $this->option('second-enrollment');
        $isAll = $this->option('all');

        if ($isAll) {
            $fromDate = self::FIRST_ENROLLMENT['start'];
            $now = Carbon::now();
            $now->tz('UTC');
            $toDate = $now->format('Y-m-d');
        } elseif ($isFirstEnrollment) {
            $fromDate = self::FIRST_ENROLLMENT['start'];
            $toDate = self::FIRST_ENROLLMENT['end'];
        } elseif ($isSecondEnrollment) {
            $fromDate = self::SECOND_ENROLLMENT['start'];
            $toDate = self::SECOND_ENROLLMENT['end'];
        } else {
            throw new \InvalidArgumentException('Enrollment flag should be specified.');
        }

        // parse end date to using the existing timezone and convert it to UTC
        $fromDateString = Carbon::parse($fromDate)->tz('UTC')->toDateString();
        $toDateString = Carbon::parse($toDate)->tz('UTC')->toDateString();

        $this->info(
            sprintf('Start the auto-place process from `%s` to `%s` period', $fromDateString, $toDateString)
        );

        // Do auto-place things
        $errors = $this->autoPlaceAgents($fromDateString, $toDateString);

        // Show all possible errors at the end of the script execution
        foreach ($errors as $message) {
            $this->error($message);
        }

        $this->info('Finish.');
    }

    /**
     * Do auto-place things within the date period
     *
     * @param $fromDateString
     * @param $toDateString
     * @return array
     */
    private function autoPlaceAgents($fromDateString, $toDateString)
    {
        $errors = [];

        if ($this->isInteractiveMode()) {
            $totalElements = $this->getBinaryQueryBuilder()->count();
            $this->line('Binary nodes processed:');
            $this->output->progressStart($totalElements);
        }

        $this->getBinaryQueryBuilder()
             // get a new batch of nodes (including early added during the auto-place legs method work
             // so this method will fetch new nodes as well we don't need to execute auto-placement recursively
            ->chunk(self::BATCH_SIZE, function ($nodes) use ($fromDateString, $toDateString) {
                foreach ($nodes as $node) {
                    try {
                        $this->autoPlaceTargetNode($node, $fromDateString, $toDateString);
                    } catch (\Exception $e) {
                        // Collect all errors during the script execution
                        $errors[]= sprintf(
                            'Cannot process the node #%s due to error: `%s`',
                            $node->id,
                            $e->getMessage()
                        );
                    }
                }

                // update progress bar
                if ($this->isInteractiveMode()) {
                    $this->output->progressAdvance(count($nodes));
                }
            });

        if ($this->isInteractiveMode()) {
            $this->output->progressFinish();
        }

        $this->line(sprintf('Total processed nodes: %s', $this->getBinaryQueryBuilder()->count()));

        return $errors;
    }

    /**
     * @param $node
     * @param $fromDateString
     * @param $toDateString
     * @return mixed
     * @throws \Exception
     */
    private function autoPlaceTargetNode($node, $fromDateString, $toDateString)
    {
        $isCorrectNode = BinaryPlanNode::where('user_id', $node->user_id)->count();

        if ($isCorrectNode > 1) {
            throw new \Exception(sprintf('user `%s` has many nodes.', $node->user_id));
        }

        $targetNode = BinaryPlanNode::where('user_id', $node->user_id)->first();
        User::whereIn('id', $this->getDistributorIds($targetNode, $fromDateString, $toDateString))
            ->chunk(self::BATCH_SIZE, function ($distributors) use ($targetNode, $fromDateString, $toDateString) {
                $nodesToPlace = BinaryPlanManager::createNodesByUsers($distributors);

                // it's safer to do an auto-placement within the transaction wrapper
                BinaryPlanManager::autoPlaceLegs($targetNode, $nodesToPlace, $targetNode->direction);
            });

        return $targetNode;
    }

    /**
     * @param $node
     * @param $fromDateString
     * @param $toDateString
     * @return array
     */
    private function getDistributorIds($node, $fromDateString, $toDateString): array
    {
        $distributorIds = DB::table('users')
            ->leftJoin('binary_plan', 'users.id', '=', 'binary_plan.user_id')
            ->select('users.id')
            ->where('users.sponsorid', $node->user->distid)
            ->whereDate('users.created_date', '>=', $fromDateString)
            ->whereDate('users.created_date', '<=', $toDateString)
            ->whereNull('binary_plan.id')
            ->orderBy('users.created_date', 'desc')
            ->pluck('id')
            ->toArray();

        return $distributorIds;
    }

    /**
     * @param $fromDateString
     * @param $toDateString
     * @return BinaryPlanNode|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    private function getBinaryQueryBuilder()
    {
        return DB::table('binary_plan')
            ->join('users', 'users.id', '=', 'binary_plan.user_id')
            // order by binary position from node to leafs
            ->orderBy('binary_plan.id');
    }
}
