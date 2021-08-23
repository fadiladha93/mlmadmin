<?php

namespace App\Console\Commands;

use App\Models\BinaryPlanNode;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class FindActiveDescendantsCSV
 * @package App\Console\Commands
 */
class FindActiveDescendantsCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'distributor:activeDescendantsCSV';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds all active users in an organization and puts them in a CSV with a random filename';

    public function __construct()
    {
        parent::__construct();

        $this->addArgument('distId', InputArgument::REQUIRED, 'Organization (Top) Distributor ID');
        $this->addArgument('startDate', InputArgument::REQUIRED, 'Start date (YYYY-mm-dd)');
        $this->addArgument('endDate', InputArgument::REQUIRED, 'End date (YYYY-mm-dd)');
    }

    private function getArgumentsOrFail()
    {

        $startDate = $this->argument('startDate');
        $endDate = $this->argument('endDate');

        $startDateInt = strtotime($startDate);
        $endDateInt = strtotime($endDate);

        if ($startDateInt === false) {
            $this->error('Invalid start date!');
            exit(1);
        }

        if ($endDateInt === false) {
            $this->error('Invalid end date!');
            exit(1);
        }

        if ($startDateInt > $endDateInt) {
            $this->error('Start date is later than end date!');
            exit(1);
        }

        $distId = $this->argument('distId');
        $user = User::Where('distid', $distId)->first();

        if (!$user) {
            $this->error('Dist ID does not exist!');
            exit(1);
        }

        return array($startDate, $endDate, $user, $distId);
    }

    private function findActiveDescendants($startDate, $endDate, $user)
    {
        $binaryPlanNode = BinaryPlanNode::whereUserId($user->id)->with(['descendants'])->first();
        $descendants = $binaryPlanNode->descendants->all();

        $userIds = [];

        foreach ($descendants as $descendant) {
            $userIds[] = $descendant->user_id;
        }

        $results = DB::table('users')
            ->select('users.distid', 'users.firstname', 'users.lastname')
            ->join('user_activity_history', 'user_activity_history.user_id', 'users.id')
            ->whereIn('users.id', $userIds)
            ->where('users.account_status', User::ACC_STATUS_APPROVED)
            ->whereBetween('user_activity_history.created_at', [$startDate, $endDate])
            ->where('user_activity_history.is_active', false)
            ->get();

        return $results;
    }

    private function writeToTempCsvFile($results, $distId)
    {
        $filename = public_path() . '/csv/' . $distId . '_' . mt_rand() . '_active_users.csv';

        $file = fopen($filename, 'w');

        foreach ($results as $result) {
            fputcsv($file, get_object_vars($result));
        }

        fclose($file);

        return $filename;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        list($startDate, $endDate, $user, $distId) = $this->getArgumentsOrFail();
        $results = $this->findActiveDescendants($startDate, $endDate, $user);
        $filename = $this->writeToTempCsvFile($results, $distId);

        $this->info('Please see file located at ' . $filename);
    }
}



