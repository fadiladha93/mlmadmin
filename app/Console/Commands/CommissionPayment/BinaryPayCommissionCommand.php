<?php

namespace App\Console\Commands\CommissionPayment;

use App\Facades\BinaryCommissionManager;
use App\Services\BinaryCommissionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

/**
 * Class BinaryPayCommissionCommand
 * @package App\Console\Commands\CommissionPayment
 */
class BinaryPayCommissionCommand extends Command
{
    /** @var BinaryCommissionService */
    private $binaryCommissionService;

    /**
     * BinaryPayCommissionCommand constructor.
     * @param BinaryCommissionService $binaryCommissionService
     */
    public function __construct(BinaryCommissionService $binaryCommissionService)
    {
        $this->binaryCommissionService = $binaryCommissionService;

        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:pay:binary {--date= : Last day of the posted commission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for paying out Binary commission';

    /**
     * @throws Exception
     */
    public function handle()
    {
        set_time_limit(0);

        $this->info('Start doing payments for users');

        $dateOption = $this->option('date');

        $date = $dateOption ? Carbon::parse($dateOption)->endOfDay() : Carbon::now()->subWeek()->endOfWeek();

        $commission = $this->binaryCommissionService->getCommissionByDate($date);

        if (!$commission) {
            throw new Exception('This commission is not calculated.');
        }

        BinaryCommissionManager::payCommission($date);

        $this->info('Finish');
    }
}
