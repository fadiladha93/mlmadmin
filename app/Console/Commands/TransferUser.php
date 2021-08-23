<?php

namespace App\Console\Commands;

use App\Facades\UserTransferManager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class TransferUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate user by user id';


    public function __construct()
    {
        parent::__construct();
        $this->addArgument('userId', InputArgument::REQUIRED, 'The user\'s id');
    }

    public function handle()
    {
        $userId = $this->argument('userId');
        $newUserId = UserTransferManager::transferUser($userId);

        if (!$newUserId) {
            $this->error('Error: user id does not exist: ' . $userId);
            exit(1);
        }

        $this->info('Old user id: ' . $userId);
        $this->info('New user id: ' . $newUserId);
    }
}
