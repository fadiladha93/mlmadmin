<?php

namespace App\Console\Commands;

use App\PaymentMethod;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class RetokenizePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:retokenize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detokenize and re-tokenize all payment methods in filename';


    public function __construct()
    {
        parent::__construct();

        $this->addArgument('filename', InputArgument::REQUIRED, 'Filename (CSV)');
    }

    public function getFilenameOrFail()
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

        return $filename;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = $this->getFilenameOrFail();
        $paymentMethodIds = $this->processFileOrFail($filename);
        $tokenEx = new \tokenexAPI();

        foreach ($paymentMethodIds as $paymentMethodId) {
            $paymentMethod = PaymentMethod::find($paymentMethodId);

            if (!$paymentMethod) {
                $this->error('Payment method id not found: ' . $paymentMethodId);
                continue;
            }

            $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $paymentMethod->token);
            $tokenRes = $tokenRes['response'];

            if (!$tokenRes->Success) {
                $this->error('Detokenization failed for payment method id ' . $paymentMethodId);
                continue;
            }

            $result = PaymentMethod::generateTokenEx($tokenRes->Value);

            if ($result['error']) {
                $this->error('Tokenization failed for payment method id ' . $paymentMethodId);
                continue;
            }

            $paymentMethod->token = $result['token'];
            $paymentMethod->save();
        }
    }

    private function processFileOrFail($filename)
    {
        $this->info('Processing csv...');

        $paymentMethodIds = [];

        try {
            $csv = array_map('str_getcsv', file($filename));

            $paymentMethodId = array_search('payment_method_id', $csv[0]);

            if ($paymentMethodId === false) {
                $this->error('CSV must have payment_method_id (lowercase) on top line');
                exit(1);
            }

            // remove header line
            array_shift($csv);

            foreach ($csv as $line) {
                $paymentMethodIds[] = $line[$paymentMethodId];
            }
        } catch (\Exception $e) {
            $this->error('An error has occurred in processing the file.');
            $this->error($e->getTraceAsString());
            exit(1);
        }

        $this->info('Found ' . count($paymentMethodIds) . ' ambassadors..');
        $this->info('Finished processing csv...');

        return $paymentMethodIds;
    }
}
