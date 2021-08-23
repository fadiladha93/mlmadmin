<?php

namespace App\Console\Commands;

use App\Facades\BinaryPlanManager;
use App\Models\BinaryPlanNode;
use App\Product;
use App\User;
use App\UserType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class MigrateBinaryTree
 * @package App\Console\Commands
 */
class MigrateBinaryTree extends Command {

    // common settings
    const BATCH_SIZE = 30;
    const ROW_COLUMNS = 6;
    // reserved rows
    const TABLE_HEADER_ROW = 0;
    const ROOT_SPONSOR_ROW = 1;
    // csv-file columns (for each row)
    const FIELD_AGENT_ID = 0;
    const FIELD_AGENT_NAME = 1;
    const FIELD_AGENT_START_DATE = 2;
    const FIELD_AGENT_STATUS = 3;
    const FIELD_BINARY_SPONSOR_ID = 4;
    const FIELD_BINARY_DIRECTION = 5;
    const DEFAULT_PASSWORD = '$access123';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binary-tree:users:migrate {--file=} {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate agents to the binary tree structure.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the command.
     */
    public function handle() {
        // make sure we have time to execute the script.
        set_time_limit(0);

        $filePath = $this->option('file');
        $clear = $this->option('clear');

        if (!file_exists($filePath)) {
            $this->error(sprintf('CSV-file `%s` doesn\'t exist.', $filePath));
            return;
        }

        // The way to clear an existing datatable
//        if ($clear) {
//            if ($this->confirm('Are you sure you want to clear existing binary plan database table?')) {
//                DB::statement('TRUNCATE binary_plan');
//                $this->line('Binary plan table was cleared successfully.');
//            }
//        }

        $this->info('Start the binary tree agents migration process.');

        // Init the progress bar with line counts
        $this->output->progressStart(
                $this->getCsvFileRowsCount($filePath)
        );

        // Command main cycle
        $errors = $this->processFile($filePath);

        $this->output->progressFinish();

        // Show all possible errors at the end of the script execution
        foreach ($errors as $message) {
            $this->error($message);
        }

        $this->info('Finish.');
    }

    /**
     * Process the CSV-file main cycle.
     *
     * @param $file
     * @return array
     */
    private function processFile($file) {
        $errors = [];
        $handle = fopen($file, 'r');

        if ($handle !== false) {
            $row = 0;
            $isProcessing = true;
            while ($handle !== false && $isProcessing) {
                // Use transaction to decrease a count of requests to the db while each row are processing
                DB::transaction(function () use ($handle, &$row, &$isProcessing, &$errors) {
                    $transactionCycles = 0;
                    while ($transactionCycles < self::BATCH_SIZE) {
                        // Get the new line data
                        $rowData = fgetcsv($handle);

                        // End of an execution the csv-file (all lines were processed by the command)
                        if ($rowData === false) {
                            $isProcessing = false;
                            break;
                        }

                        // Create a root node or append a new one to the root node
                        try {
                            if ($row !== self::TABLE_HEADER_ROW) {
                                //if ($row === self::ROOT_SPONSOR_ROW) {
                                //    $this->createRootSponsor($rowData);
                                //} else {
                                $this->placeRowToBinaryTree($rowData);
                                //}
                            }
                        } catch (\Exception $e) {
                            // Collect all errors during the script execution
                            $errors[] = sprintf(
                                    'Cannot process the agent #%s due to error: `%s`', $rowData[self::FIELD_AGENT_ID], $e->getMessage()
                            );
                        }

                        // Just counters and nothing more
                        $transactionCycles++;
                        $row++;
                        $this->output->progressAdvance();
                    }
                });

                // free the memory after each batch
                if (($row % self::BATCH_SIZE) === 0) {
                    gc_collect_cycles();
                }
            }
        }
        fclose($handle);

        return $errors;
    }

    /**
     * Add new row to the binary tree structure.
     *
     * @param $row
     * @return mixed
     * @throws \Exception
     */
    private function placeRowToBinaryTree($row) {
        // check if the distid already exist
        $recId = $this->getNodeByDistId($row[self::FIELD_AGENT_ID]);
        if ($recId > 0) {
            throw new \Exception(sprintf(
                    'Record already found for #%s.', $row[self::FIELD_AGENT_ID]
            ));
        }

        // laravel cannot cast the result from DB to the model directly when the join operator is using here
        $recordId = $this->getNodeByDistId($row[self::FIELD_BINARY_SPONSOR_ID]);
        if (!$recordId) {
            throw new \Exception(sprintf(
                    'Binary node with the sponsor #%s does not exists.', $row[self::FIELD_BINARY_SPONSOR_ID]
            ));
        }

        $node = BinaryPlanNode::where('id', $recordId)->first();

        if (!$node) {
            throw new \Exception(sprintf(
                    'Binary node of the sponsor #%s has not found in the binary structure.', $row[self::FIELD_BINARY_SPONSOR_ID]
            ));
        }

        $user = User::where('distid', $row[self::FIELD_AGENT_ID])->first();

        if (!$user) {
            // just create a mock of the user with terminated status in the database
            // need for consistency for the db data
            $user = $this->mockUser($row);
        }

        $newNode = $this->createNewNode($row, $user);

        $node->appendNode($newNode);

        return $node;
    }

    /**
     * Create a root node.
     *
     * @param $row
     * @return BinaryPlanNode
     * @throws \Exception
     */
    private function createRootSponsor($row) {
        $user = User::where('distid', $row[self::FIELD_AGENT_ID])->first();

        $direction = BinaryPlanNode::MAP_DIRECTION[
                strtolower($row[self::FIELD_BINARY_DIRECTION])
        ];

        if (!$user) {
            throw new \Exception(sprintf(
                    'Agent #%s does not exists.', $row[self::FIELD_BINARY_SPONSOR_ID]
            ));
        }

        $node = $this->createRootNode($row, $direction, $user);

        return $node;
    }

    /**
     * Get file lines count (for the progress bar counter).
     *
     * @param $file
     * @return \SplFileObject
     */
    private function getCsvFileRowsCount($file): int {
        $file = new \SplFileObject($file, 'r');
        $file->seek(PHP_INT_MAX);

        return $file->key();
    }

    /**
     * Prepare a root node of the binary tree.
     *
     * @param $row
     * @param $direction
     * @param $user
     * @return BinaryPlanNode
     * @throws \Exception
     */
    private function createRootNode($row, $direction, $user): BinaryPlanNode {
        if (!in_array($direction, [
                    BinaryPlanNode::DIRECTION_LEFT,
                    BinaryPlanNode::DIRECTION_RIGHT
                ])) {
            throw new \Exception(sprintf('Invalid direction value `%s`', $direction));
        }

        $node = new BinaryPlanNode();
        $node->user_id = $user->id;
        $node->sponsor_id = User::where('distid', $row[self::FIELD_BINARY_SPONSOR_ID])->first();
        $node->enrolled_at = \DateTime::createFromFormat(
                        "!n/j/y G:i", $row[self::FIELD_AGENT_START_DATE], new \DateTimeZone('UTC')
        );

        $node->direction = $direction;
        $node
                ->makeRoot()
                ->save();

        return $node;
    }

    /**
     * Prepare a new node before the place to the binary tree.
     *
     * @param $row
     * @param $user
     * @return BinaryPlanNode
     */
    private function createNewNode($row, $user): BinaryPlanNode {
        $sponsor = User::where('distid', $row[self::FIELD_BINARY_SPONSOR_ID])->first();

        $newNode = new BinaryPlanNode();
        $newNode->user_id = $user->id;
        $newNode->sponsor_id = $sponsor ? $sponsor->id : null;
        $newNode->enrolled_at = \DateTime::createFromFormat(
                        "!Y-m-d", $user->created_date, new \DateTimeZone('UTC')
        );

        if (strtolower($row[self::FIELD_BINARY_DIRECTION]) === BinaryPlanManager::DIRECTION_LEFT) {
            $newNode->setLeftDirection();
        } else {
            $newNode->setRightDirection();
        }

        $newNode->enrolled_at = \DateTime::createFromFormat(
                        "!n/j/y G:i", $row[self::FIELD_AGENT_START_DATE], new \DateTimeZone('UTC')
        );
        return $newNode;
    }

    /**
     * Database helper method.
     *
     * @param $row
     * @return mixed
     */
    private function getNodeByDistId($distId) {
        $recordId = DB::table('binary_plan')
                ->select('binary_plan.id')
                ->join('users', 'binary_plan.user_id', '=', 'users.id')
                ->where('users.distid', $distId)
                ->pluck('id')
                ->first();
        return $recordId;
    }

    /**
     * Mock user with the sample for the database data consistency.
     *
     * @param $row
     * @return User
     */
    private function mockUser($row) {
        $fullname = explode(' ', $row[self::FIELD_AGENT_NAME]);

        $newUser = new User();
        $newUser->firstname = count($fullname) > 0 ? $fullname[0] : '';
        $newUser->lastname = count($fullname) > 1 ? $fullname[1] : '';
        $newUser->distid = $row[self::FIELD_AGENT_ID];
        $newUser->sponsorid = $row[self::FIELD_BINARY_SPONSOR_ID];
        $newUser->email = sprintf('disabled-%s@ibuumerang.com', $row[self::FIELD_AGENT_ID]);
        $newUser->default_password = self::DEFAULT_PASSWORD;
        $newUser->password = password_hash(self::DEFAULT_PASSWORD, PASSWORD_BCRYPT);
        $newUser->usertype = UserType::TYPE_DISTRIBUTOR;
        $newUser->current_product_id = Product::ID_NCREASE_ISBO;
        $newUser->account_status = strtoupper($row[self::FIELD_AGENT_STATUS]);

        $newUser->save();

        return $newUser;
    }

}
