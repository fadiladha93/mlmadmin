<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class FixDateForFSBCommission
 * @package App\Console\Commands
 */
class FixDateForFSBCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:fsb:date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix date for fsb commission';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start updating dates');

        try {
            DB::transaction(function () {
                $this->processFirstPartCommission();
                $this->processSecondPartCommission();
            });
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->info('Finish');
    }

    public function processFirstPartCommission()
    {
        $commissionDates = DB::table('commission')
            ->select(['processed_date'])
            ->where('processed_date', '<', '2019-06-21 00:00:00')
            ->groupBy('processed_date')
            ->orderBy('processed_date', 'ASC')
            ->get();

        foreach ($commissionDates as $date) {
            $currentDate = $date->processed_date;

            $lastOrderDate = DB::table('commission as c')
                ->join('orders as o', 'c.order_id', '=', 'o.id')
                ->select(['o.created_dt'])
                ->where('c.processed_date', $currentDate)
                ->orderBy('o.created_dt', 'DESC')
                ->first();

            $newDate = Carbon::parse($lastOrderDate->created_dt)->endOfDay();

            if (($newDate->dayOfWeek == 0 || $newDate->dayOfWeek == 6) && $newDate != '2019-04-13 23:59:59') {
                $newDate = $newDate->endOfWeek();
            }

            DB::statement('UPDATE commission SET processed_date = :newDate WHERE processed_date = :currentDate', [
                'currentDate' => $currentDate,
                'newDate' => $newDate
            ]);

            DB::statement('UPDATE week_detail SET week_ending = :newDate WHERE week_ending = :currentDate', [
                'currentDate' => $currentDate,
                'newDate' => $newDate
            ]);

            DB::statement('UPDATE week_summary SET week_ending = :newDate WHERE week_ending = :currentDate', [
                'currentDate' => $currentDate,
                'newDate' => $newDate
            ]);
        }
    }

    public function processSecondPartCommission()
    {
        $commissionDates = DB::table('commission')
            ->select(['processed_date'])
            ->where('processed_date', '>=', '2019-06-21 00:00:00')
            ->groupBy('processed_date')
            ->orderBy('processed_date', 'ASC')
            ->get();

        foreach ($commissionDates as $date) {
            $currentDate = $date->processed_date;

            $lastOrderDate = DB::table('commission as c')
                ->join('orders as o', 'c.order_id', '=', 'o.id')
                ->select(['o.created_dt'])
                ->where('c.processed_date', $currentDate)
                ->orderBy('o.created_dt', 'DESC')
                ->first();

            $newDate = Carbon::parse($lastOrderDate->created_dt)->endOfDay();

            if ($newDate->dayOfWeek == 0 || $newDate->dayOfWeek == 6) {
                $newDate = $newDate->endOfWeek();
            }

            DB::statement('UPDATE commission SET processed_date = :newDate WHERE processed_date = :currentDate', [
                'currentDate' => $currentDate,
                'newDate' => $newDate
            ]);

            DB::statement('UPDATE week_detail SET week_ending = :newDate WHERE week_ending = :currentDate', [
                'currentDate' => $newDate->copy()->startOfDay(),
                'newDate' => $newDate
            ]);

            DB::statement('UPDATE week_summary SET week_ending = :newDate WHERE week_ending = :currentDate', [
                'currentDate' => $newDate->copy()->startOfDay(),
                'newDate' => $newDate
            ]);

            DB::statement("UPDATE ewallet_transactions SET created_at = :newDate WHERE type = 'DEPOSIT' AND commission_type = 'FSB' AND created_at = :currentDate", [
                'currentDate' => $newDate->copy()->startOfDay(),
                'newDate' => $currentDate
            ]);
        }
    }
}
