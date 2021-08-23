<?php

namespace App\Console\Commands;

use App\PaymentMethod;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class DetokenizeCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:detokenize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detokenize and insert into csv';


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
        list($csv, $headers, $tokenIndex, $tokenToLineNumber) = $this->processFileOrFail($filename);
        $tokenEx = new \tokenexAPI();
        $tokenIndex = $headers[$tokenIndex];

        foreach ($tokenToLineNumber as $token=>$lineNumber) {
            $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $token);
            $tokenRes = $tokenRes['response'];

            if (!$tokenRes->Success) {
                $this->error('Detokenization failed for token ' . $token);
                continue;
            }

            $csv[$lineNumber][$tokenIndex] = $tokenRes->Value;
        }

        $output = implode(',', $headers) . PHP_EOL;

        foreach ($csv as $lineNumber=>$fields) {
            $output .= implode(',', array_values($fields)) . PHP_EOL;
        }

        file_put_contents($filename, $output);
    }

    private function processFileOrFail($filename)
    {
        $this->info('Processing csv...');

        $tokenToLineNumber = [];

        try {
            $csv = array_map('str_getcsv', file($filename));

            $headers = $csv[0];
            $tokenIndex = array_search('token', $headers);

            if (!$tokenIndex) {
                $this->error('CSV must have token (lowercase) on top line');
                exit(1);
            }

            // remove header line
            array_shift($csv);

            foreach ($csv as $lineNumber=>$lineData) {
                $token = $lineData[$tokenIndex];
                $tokenToLineNumber[$token] = $lineNumber;
            }
        } catch (\Exception $e) {
            $this->error('An error has occurred in processing the file.');
            $this->error($e->getTraceAsString());
            exit(1);
        }

        $this->info('Finished processing csv...');

        return array($csv, $headers, $tokenIndex, $tokenToLineNumber);
    }
}
