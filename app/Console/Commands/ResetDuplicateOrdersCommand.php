<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class ResetDuplicateOrdersCommand
 * @package App\Console\Commands
 */
class ResetDuplicateOrdersCommand extends Command
{
    const TABLE_HEADER_ROW = 0;

    // csv-file rows order
    const USER_ID_ROW = 1;
    const AGENT_TSA_ROW = 2;
    const ORDER_ID_ROW = 3;
    const ORDER_ITEM_ID_ROW = 4;

    const FILE_PATH = './public/csv/adjustments/reset_duplicate_orders.csv';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duplicate:orders:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);

        $this->info('Start doing reset orders for users from csv-file');

        $errors = $this->processReset();

        foreach ($errors as $message) {
            $this->error($message);
        }

        $this->info('Finish');

    }

    /**
     * @return array
     */
    protected function processReset()
    {
        $errors = [];

        $handle = fopen(self::FILE_PATH, 'r');
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

                    DB::transaction(function () use ($rowData){
                        $this->updateOrder($rowData[self::ORDER_ID_ROW]);
                        $this->updateOrderItem($rowData[self::ORDER_ITEM_ID_ROW]);
                    });

                    unset($rowData);
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }

                $row++;
            }
        }

        fclose($handle);

        return $errors;
    }

    /**
     * @param $id
     */
    protected function updateOrder($id)
    {
        DB::table('orders')
            ->where('id', $id)
            ->update([
                'ordersubtotal' => 0,
                'ordertotal' => 0,
                'orderbv' => 0,
                'orderqv' => 0,
                'ordercv' => 0
            ]);
    }

    /**
     * @param $id
     */
    protected function updateOrderItem($id)
    {
        DB::table('orderItem')
            ->where('id', $id)
            ->update([
                'itemprice' => 0,
                'bv' => 0,
                'qv' => 0,
                'cv' => 0
            ]);
    }
}
