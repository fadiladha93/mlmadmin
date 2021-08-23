<?php

namespace App\Services;

use App\EwalletTransaction;
use App\Order;
use App\SaveOn;
use App\User;
use App\VibeCommission;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\TSBCommission;
use Illuminate\Support\Facades\Log;

/**
 * Class LeadershipCommission
 * @package App\Services
 */
class VibeCommissionService
{
    private $sheetMappings = [
        'name' => 'Member Name',
        'sorUserId' => 'Boomerang SOR User ID',
        'distId' => 'Referring User Contract Number',
        'club' => 'VacationClubName',
        'tsbAmount' => 'DIRECT TSB',
        'total' => 'TOTAL AMOUNT',
        'cv' => 'CV',
        'reservation' => 'Reservation Id'
    ];

    const TABLE_NAME = 'vibe_commissions';
    const COMMISSION_TYPE = 'VBE';
    const CALCULATED_STATUS = 'calculated';
    const POSTED_STATUS = 'posted';
    const PAID_STATUS = 'paid';

    public function isPostedCommission($startDate, $endDate)
    {
        return VibeCommission::whereDate('paid_date', '>=', $startDate)
                ->whereDate('paid_date', '<=', $endDate)
                ->where('status', VibeCommissionService::POSTED_STATUS)
                ->count() > 0;
    }

    public function isPaidCommission($startDate, $endDate)
    {
        return VibeCommission::whereDate('paid_date', '>=', $startDate)
                ->whereDate('paid_date', '<=', $endDate)
                ->where('status', VibeCommissionService::PAID_STATUS)
                ->count() > 0;
    }

    public function checkForMissingHeaders($headerRow)
    {
        $missingHeaders = [];

        $headers = array_values($this->sheetMappings);

        foreach ($headers as $header) {
            if (!in_array($header, $headerRow)) {
                $missingHeaders[] = $header;
            }
        }

        return $missingHeaders;
    }

    private function parseCSV($filename)
    {
        Log::debug('Parsing TSB csv...');

        $csv = array_map('str_getcsv', file($filename));

        $headers = $csv[0];

        array_walk($csv, function(&$a) use ($csv, $headers) {
            $a = array_combine($headers, $a);
        });

        array_shift($csv); // remove header row

        Log::debug('Finished parsing TSB csv... Number of rows: ' . count($csv));

        return $csv;
    }

    public function calculateCommission($filename, Carbon $endDate)
    {
        $results = $this->parseCSV($filename);

        $defaultPaidAt = $endDate->copy()
            ->setDate($endDate->year, $endDate->month, 20)
            ->setTime(12, 0, 0, 0);

        $defaults = [
            'paidAt' => $defaultPaidAt->format('Y-m-d H:i:s')
        ];

        $numHeaders = count(array_keys($this->sheetMappings));

        Log::debug('Calculating Vibe...');

        $counter = 1;
        $numResults = count($results);

        foreach ($results as $result) {
            Log::info($counter++ . '/' . $numResults);

            $numEmptyFields = 0;

            foreach ($this->sheetMappings as $header=>$mapping) {
                if (empty($result[$mapping])) {
                    $numEmptyFields++;
                }
            }

            if ($numEmptyFields == $numHeaders) {
                Log::warning('Empty record skipped!');
                continue;
            }

            $parsedResult = $this->parseResult($result, $defaults);
            $distId = $parsedResult['distId'];
            $user = User::getByDistId($distId);

            $paidDate = date('Y-m-d h:i:s', strtotime($parsedResult['paidAt']));

            $transactionId = 'SOR#' . $parsedResult['reservation'];

            if (Order::orderWithTransactionIdExists($transactionId)) {
                Log::warning('Vibe already done? Order for user id already done',
                    ['parsedResult' => $parsedResult, 'userId' => $user->id, 'paidDate' => $paidDate]);

                continue;
            }

            $this->processCommission($parsedResult, $transactionId, $endDate);
        }
    }

    private function parseResult($result, $defaults)
    {
        $parsedResult = $defaults;

        foreach ($this->sheetMappings as $header=>$mapping) {
            $parsedResult[$header] = trim($result[$mapping]);

            if (stripos($parsedResult[$header], '$') !== false) {
                $parsedResult[$header] = str_replace('$', '', trim($parsedResult[$header]));
            }
        }

        // Just in case
        $parsedResult['cv'] = ceil(trim($parsedResult['cv']));

        return $parsedResult;
    }

    private function processCommission($parsedResult, $transactionId, Carbon $endDate)
    {
        Log::debug('Processing commission...', ['parsedResult' => $parsedResult]);

        $user = User::getByDistId($parsedResult['distId']);

        DB::table('vibe_commissions')->insert([
            'order_id' => $orderId,
            'user_id' => $user->id,
            'dist_id' => $user->id,
            'amount' => (float)$parsedResult['tsbAmount'],
            'paid_date' => date('Y-m-d h:i:s', strtotime($parsedResult['paidAt'])),
            'created_at' => date('Y-m-d h:i:s', strtotime($endDate)),
            'status' => VibeCommissionService::CALCULATED_STATUS,
            'name' => trim($parsedResult['name'])
        ]);

        Log::debug('Finished Processing commission...', ['parsedResult' => $parsedResult]);
    }

    public function payoutCommission($startDate, $endDate)
    {
        $query = DB::table('vibe_commissions')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->where('status', VibeCommissionService::POSTED_STATUS);

        if ($query->count() === 0) {
            return ['message' => 'There is no posted commission to payout on Vibe'];
        }

        $commissions = $query->get();

        $success = 0;
        $failures = 0;

        foreach ($commissions as $commission) {

            $userExists = User::where('id', $commission->user_id)->exists();

            if (!$userExists) {
                $failures++;
                continue;
            }
        

            $transactionId = EwalletTransaction::addCommission(
                    $commission->user_id,
                    $commission->direct_payout,
                    EwalletTransaction::TYPE_VIBE_COMMISSION,
                    "Vibe Bonus"
            );

            if ($transactionId) {
                DB::table('vibe_commissions')->where('id', $commission->id)->update(['status' => 'paid']);
                $success++;
            } else {
                DB::table('vibe_commissions')->where('id', $commission->id)->update(['status' => 'fail']);
                $failures++;
            }
        }

        return array($success, $failures);
    }
}
