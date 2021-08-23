<?php

namespace App\Console\Commands;

use App\BoomerangInv;
use App\OrderItem;
use App\PaymentMethod;
use App\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class MigrateCarryoverToHistoryTable
 * @package App\Console\Commands
 */
class BoomerangCSV extends Command
{
    private const BATCH_SIZE = 500;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boomerang:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give boomerangs to every user CSV based on distid field';


    public function __construct()
    {
        parent::__construct();

        $this->addArgument('filename', InputArgument::REQUIRED, 'Filename (CSV)');
        $this->addArgument('amount', InputArgument::REQUIRED, 'Amount of boomerangs to give (Integer)');
    }

    public function getArgumentsOrFail()
    {
        $filename = $this->argument('filename');

        if (!file_exists($filename)) {
            $this->error('File does not exist: ' . $filename);
            exit(1);
        }

        if (!is_readable($filename)) {
            $this->error('File is not readable: ' . $filename);
            exit(1);
        }


        if (filesize($filename) == 0) {
            $this->error('File is empty: ' . $filename);
            exit(1);
        }

        $amount = intval($this->argument('amount'));

        if ($amount == 0) {
            $this->error('Amount must be an integer greater than 0');
            exit(1);
        }

        return array($filename, $amount);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        list($filename, $amount) = $this->getArgumentsOrFail();
        $userIds = $this->processFileOrFail($filename);
        $this->giveBoomerangs($userIds, $amount);
    }

    private function processFileOrFail($filename)
    {
        $this->info('Processing csv...');

        $userIds = [];

        try {
            $csv = array_map('str_getcsv', file($filename));

            $userIdIndex = array_search('user_id', $csv[0]);

            if ($userIdIndex === false) {
                $this->error('CSV must have user_id (lowercase) on top line');
                exit(1);
            }

            // remove header line
            array_shift($csv);

            foreach ($csv as $line) {
                $userIds[] = $line[$userIdIndex];
            }
        } catch (\Exception $e) {
            $this->error('An error has occurred in processing the file.');
            $this->error($e->getTraceAsString());
            exit(1);
        }

        $this->info('Found ' . count($userIds) . ' ambassadors..');
        $this->info('Finished processing csv...');

        return $userIds;
    }

    private function giveBoomerangs($userIds, $amount)
    {
        $this->info('Giving users boomerangs & associated orders..');

        $boomerangSessionId = Carbon::now()->toDateTimeString() . '_' . mt_rand();

        $numberUsers = count($userIds);
        $counter = 1;

        $userIdChunks = array_chunk($userIds, static::BATCH_SIZE);

        foreach ($userIdChunks as $userIdChunk) {
            $counter = $this->giveBoomerangChunk($userIdChunk, $boomerangSessionId, $amount, $numberUsers, $counter);
        }

        $this->info('End of boomerang process');
    }

    private function giveBoomerangChunk($userIdChunk, $boomerangSessionId, $amount, $numberUsers, $counter)
    {
        try {
            DB::beginTransaction();

            foreach ($userIdChunk as $userId) {
                $counterText = '[' . $counter . '/' . $numberUsers . ']';

                $this->info($counterText . '===== User ID: ' . $userId . ' =====');

                $this->info('Adding ' . $amount . ' boomerang(s)');

                BoomerangInv::addToInventory($userId, $amount);

//                $this->addOrderForBoomerang($boomerangSessionId, $userId);

                $counter++;
            }

            $this->info('Chunk done.. committing result.');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return $counter;
    }

    private function addOrderForBoomerang($boomerangSessionId, $userId)
    {
        $paymentMethodId = PaymentMethod::getPaymentMethodIdOfPayMethodTypeAdmin($userId);

        $transactionId = 'BoomerangCSV#' . $boomerangSessionId . '#' . $userId;

        $this->info('Creating order..');
        $orderId = \App\Order::addNew(
            $userId,
            0,
            0,
            $amount,
            0,
            0,
            $transactionId,
            $paymentMethodId,
            null,
            null
        );

        $this->info('Order created: ' . $orderId);


        OrderItem::addNew($orderId,
            Product::ID_IBUUMERANG_25,
            1,
            0,
            $amount,
            0,
            0,
            false,
            null,
            0,
            0);
    }
}
