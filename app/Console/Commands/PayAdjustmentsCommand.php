<?php

namespace App\Console\Commands;

use App\EwalletTransaction;
use App\Models\BinaryPlanNode;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class PayAdjustmentsCommand
 * @package App\Console\Commands
 */
class PayAdjustmentsCommand extends Command
{
    const TABLE_HEADER_ROW = 0;

    // csv-file rows order
    const AGENT_TSA_ROW = 1;
    const DIFF_AMOUNT_ROW = 3;

    const PROTECTION_TOKEN = 'tNKtVQT7qYRf83ck2Hqrw6zD4x2mgk9p';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:pay:adjustment {--token=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Temporary script for adjustment payments within fourth commission';

    protected $users = [];

    /**
     * Execute the command.
     */
    public function handle()
    {
        // make sure we have time to execute the script.
        set_time_limit(0);

        $protectionToken = $this->option('token');

        if ($protectionToken !== self::PROTECTION_TOKEN) {
            $this->error('Protection token has not provided.');
            return;
        }

        $this->info('Start doing adjustments for users from csv-file');

        //$this->doPayment();

        $this->info('Finish');

    }

    private function doPayment()
    {
        // unilevel adjustments
        $errors1 = $this->adjustCsv(
            './public/csv/adjustments/unilevel_commission_diff_2019_06_30.csv',
            'UL_06_30',
            'Adjustment for the Unilevel commission for June'
        );

        // errors stack
        foreach ($errors1 as $message) {
            $this->error($message);
        }

        // leadership adjustments
        $errors2 = $this->adjustCsv(
            './public/csv/adjustments/leadership_commission_diff_2019_08_02.csv',
            'LC_06_30',
            'Adjustment for the Leadership commission for June'
        );

        // errors stack
        foreach ($errors2 as $message) {
            $this->error($message);
        }
    }

    private function adjustCsv($filePath, $type, $remarks)
    {
        $errors = [];

        $totalAdjust = 0.0;
        $processedUserCount = 0;

        $handle = fopen($filePath, 'r');
        if ($handle !== false) {
            $row = 0;
            while ($handle !== false) {
                $agent = null;
                try {
                    $rowData = fgetcsv($handle);

                    // data validation first
                    if ($row === self::TABLE_HEADER_ROW) {
                        $row++;
                        continue;
                    }

                    if ($rowData === false) {
                        break;
                    }

                    $agent = $this->getUserByAgentTsa($rowData[self::AGENT_TSA_ROW]);

                    if (!$agent) {
                        throw new \Exception(
                            sprintf('Cannot find the agent TSA: %s', $rowData[self::AGENT_TSA_ROW])
                        );
                    }

                    $newAmountValue = floatval($rowData[self::DIFF_AMOUNT_ROW]);

                    if (!is_numeric($newAmountValue)) {
                        throw new \Exception(
                            sprintf('Invalid new amount %s', $newAmountValue)
                        );
                    }

                    // Process only positive adjustments
                    $adjustmentValue = $newAmountValue;

                    if ($adjustmentValue !== 0.0) {
                        // adjust
                        $this->payAdjustmentForAgent($agent, $adjustmentValue, $type, $remarks);
                        $totalAdjust += $adjustmentValue;
                    }

                    $processedUserCount++;
                    unset($rowData);
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }

                $row++;
            }
        }
        fclose($handle);

        $this->info(sprintf(
            'users: %s | type: [%s] | total_adjust: $%s',
            $processedUserCount,
            $type,
            $totalAdjust
        ));

        return $errors;
    }

    public function getUserByAgentTsa($tsaNumber)
    {
        return User::where('distid', trim($tsaNumber))->first();
    }

    private function payAdjustmentForAgent($agent, $adjustmentAmountValue, $type, $remarks)
    {
        if ($adjustmentAmountValue === 0.0) {
            return;
        }

        DB::transaction(function () use ($agent, $adjustmentAmountValue, $type, $remarks) {
            $openingBalance = $agent->estimated_balance;
            $closingBalance = $openingBalance + $adjustmentAmountValue;

            $adjustmentType = $adjustmentAmountValue > 0
                ? EwalletTransaction::ADJUSTMENT_ADD
                : EwalletTransaction::ADJUSTMENT_DEDUCT
            ;

            $ew = new EwalletTransaction();
            $ew->user_id = $agent->id;
            $ew->opening_balance = $openingBalance;
            $ew->closing_balance = $closingBalance;
            $ew->amount = abs($adjustmentAmountValue);
            $ew->type = $adjustmentType;
            // unique commission type to detect it in the binary commission tabs
            $ew->commission_type = $type;
            $ew->remarks = $remarks;
            $ew->created_at = \utill::getCurrentDateTime();
            $ew->purchase_id = 0;
            $ew->save();

            $agent->estimated_balance = $closingBalance;
            $agent->save();
        });
    }
}
