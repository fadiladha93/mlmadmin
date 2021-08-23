<?php

namespace App\Providers;

use App;
use App\Services\BinaryViewerService;
use App\Services\HoldingTankService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Services\BinaryPlanService;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (env('FORCE_HTTPS')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mailgun.client', function () {
            return \Http\Adapter\Guzzle6\Client::createWithConfig([]);
        });

        // Add application custom services
        App::singleton('ibu.service.binary_plan_tree', BinaryPlanService::class);
        App::singleton('ibu.service.holding_tank', HoldingTankService::class);
        App::singleton('ibu.service.binary_viewer', BinaryViewerService::class);
        App::singleton('ibu.service.unilevel_commission', App\Services\UnilevelCommission::class);
        App::singleton('ibu.service.leadership_commission', App\Services\LeadershipCommission::class);
        App::singleton('ibu.service.binary_commission', App\Services\BinaryCommissionService::class);
        App::singleton('ibu.service.achieved_ranks', App\Services\AchievedRankService::class);
        App::singleton('ibu.service.tsb_commission', App\Services\TsbCommissionService::class);
        App::singleton('ibu.service.user_transfer', App\Services\UserTransferService::class);
    }
}
