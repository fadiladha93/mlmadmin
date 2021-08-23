<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Kordy\Ticketit\Models\Configuration;
use Kordy\Ticketit\Models\Priority;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Status;
use Kordy\Ticketit\Helpers\LaravelVersion;

class TicketPresetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:install-presets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up the database as required for running Ticketit. Run this only once, before making any modifications to project files.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->installPresets();
    }

    private function installPresets()
    {
        $this->line("Installing presets...");

        $newStatus = Status::create([
            "name"  => "New",
            "color" => "#e9551e",
        ]);

        $closedStatus = Status::create([
            "name"  => "Closed",
            "color" => "#186107",
        ]);

        $reopenedStatus = Status::create([
            "name"  => "Re-opened",
            "color" => "#71001f",
        ]);

        Configuration::where('slug', 'default_status_id')->first()->update(['value' => $newStatus->id]);
        Configuration::where('slug', 'default_close_status_id')->first()->update(['value' => $closedStatus->id]);
        Configuration::where('slug', 'default_reopen_status_id')->first()->update(['value' => $reopenedStatus->id]);
        Configuration::where('slug', 'bootstrap_version')->first()->update(['value' => '4']);

        \Cache::flush();

        Priority::create(["name" => "1" , "color" => "#830909"]);
        Priority::create(["name" => "2" , "color" => "#090909"]);
        Priority::create(["name" => "3" , "color" => "#125f71"]);
        Priority::create(["name" => "4" , "color" => "#000000"]);
        Priority::create(["name" => "5" , "color" => "#000000"]);
        Priority::create(["name" => "6" , "color" => "#000000"]);
        Priority::create(["name" => "7" , "color" => "#000000"]);
        Priority::create(["name" => "8" , "color" => "#000000"]);
        Priority::create(["name" => "9" , "color" => "#000000"]);
        Priority::create(["name" => "10", "color" => "#000000"]);

        $supportCategory = Category::create([
            "name"  => "Support",
            "color" => "#000000",
        ]);

        # Super Admin can be change and add tickets
        $superAdmin = \App\User::whereUsertype(1)
            ->whereAdminRole(1)
            ->update(['ticketit_admin' => 1, 'ticketit_agent' => 1]);

        # Admin Executives can be add tickets
        $admin = \App\User::whereUsertype(1)
            ->whereAdminRole(2)
            ->update(['ticketit_agent' => 1]);

        # Fix it for category_user
        # $admin = \App\User::whereTicketitAgent(1)->get();
        # $supportCategory->agents()->attach($admin->id);

        $this->line("OK, It's done.");
    }
}
