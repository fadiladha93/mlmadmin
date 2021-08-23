<?php

namespace App\Providers;

use Exception;
use ReflectionClass;
use Kordy\Ticketit\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Kordy\Ticketit\Models\Comment;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Console\Htmlify;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Kordy\Ticketit\Helpers\LaravelVersion;
use Collective\Html\FormFacade as CollectiveForm;
use Kordy\Ticketit\Controllers\InstallController;
use Kordy\Ticketit\ViewComposers\TicketItComposer;
use Kordy\Ticketit\Controllers\NotificationsController;

class TicketitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $reflector = new ReflectionClass("\Kordy\Ticketit\TicketitServiceProvider");
        $path_package = dirname($reflector->getFileName());

        /*if (!Schema::hasTable('migrations')) {
            // Database isn't installed yet.
            return;
        }
        $installer = new InstallController();

        // if a migration or new setting is missing scape to the installation
        if (empty($installer->inactiveMigrations()) && !$installer->inactiveSettings()) {
            // Send the Agent User model to the view under $u
            // Send settings to views under $setting

            //cache $u
            $u = null;

            TicketItComposer::settings($u);

            // Adding HTML5 color picker to form elements
            CollectiveForm::macro('custom', function ($type, $name, $value = '#000000', $options = []) {
                $field = $this->input($type, $name, $value, $options);

                return $field;
            });

            TicketItComposer::general();
            TicketItComposer::codeMirror();
            TicketItComposer::sharedAssets();
            TicketItComposer::summerNotes();

            // Send notification when new comment is added
            Comment::creating(function ($comment) {
                if (Setting::grab('comment_notification')) {
                    try {
                        $notification = new NotificationsController();
                        $notification->newComment($comment);
                    } catch (\Swift_TransportException $e) {
                    }
                }
            });

            // Send notification when ticket status is modified
            Ticket::updating(function ($modified_ticket) {
                if (Setting::grab('status_notification')) {
                    $original_ticket = Ticket::find($modified_ticket->id);
                    if ($original_ticket->status_id != $modified_ticket->status_id || $original_ticket->completed_at != $modified_ticket->completed_at) {
                        $notification = new NotificationsController();
                        try {
                            $notification->ticketStatusUpdated($modified_ticket, $original_ticket);
                        } catch (\Swift_TransportException $e) {
                        }
                    }
                }
                if (Setting::grab('assigned_notification')) {
                    $original_ticket = Ticket::find($modified_ticket->id);
                    if ($original_ticket->agent->id != $modified_ticket->agent->id) {
                        $notification = new NotificationsController();
                        try {
                            $notification->ticketAgentUpdated($modified_ticket, $original_ticket);
                        } catch (\Swift_TransportException $e) {
                        }
                    }
                }

                return true;
            });

            // Send notification when ticket status is modified
            Ticket::created(function ($ticket) {
                if (Setting::grab('assigned_notification')) {
                    $notification = new NotificationsController();
                    try {
                        $notification->newTicketNotifyAgent($ticket);
                    } catch (\Swift_TransportException $e) {
                    }
                }

                return true;
            });

            $this->loadTranslationsFrom($path_package . '/Translations', 'ticketit');

            $viewsDirectory = $path_package . '/Views/bootstrap3';
            if (Setting::grab('bootstrap_version') == '4') {
                $viewsDirectory = $path_package . '/Views/bootstrap4';
            }

            $this->loadViewsFrom($viewsDirectory, 'ticketit');

            $this->publishes([$viewsDirectory => base_path('resources/views/vendor/ticketit')], 'views');
            $this->publishes([$path_package . '/Translations' => base_path('resources/lang/vendor/ticketit')], 'lang');
            $this->publishes([$path_package . '/Public' => public_path('vendor/ticketit')], 'public');
            $this->publishes([$path_package . '/Migrations' => base_path('database/migrations')], 'db');

            // Check public assets are present, publish them if not
            //            $installer->publicAssets();

            $main_route = Setting::grab('main_route');
            $main_route_path = Setting::grab('main_route_path');
            $admin_route = Setting::grab('admin_route');
            $admin_route_path = Setting::grab('admin_route_path');

            if (file_exists(Setting::grab('routes'))) {
                include Setting::grab('routes');
            } else {
                include $path_package . '/routes.php';
            }
        } elseif (
            Request::path() == 'tickets-install'
            || Request::path() == 'tickets-upgrade'
            || Request::path() == 'tickets'
            || Request::path() == 'tickets-admin'
            || (isset($_SERVER['ARTISAN_TICKETIT_INSTALLING']) && $_SERVER['ARTISAN_TICKETIT_INSTALLING'])
        ) {*/
            $this->loadTranslationsFrom($path_package . '/Translations', 'ticketit');
            $this->loadViewsFrom($path_package . '/Views/bootstrap3', 'ticketit');
            $this->publishes([$path_package . '/Migrations' => base_path('database/migrations')], 'db');

            $authMiddleware = LaravelVersion::authMiddleware();

            Route::get('/tickets-install', [
                'middleware' => $authMiddleware,
                'as'         => 'tickets.install.index',
                'uses'       => 'Kordy\Ticketit\Controllers\InstallController@index',
            ]);
            Route::post('/tickets-install', [
                'middleware' => $authMiddleware,
                'as'         => 'tickets.install.setup',
                'uses'       => 'Kordy\Ticketit\Controllers\InstallController@setup',
            ]);
            Route::get('/tickets-upgrade', [
                'middleware' => $authMiddleware,
                'as'         => 'tickets.install.upgrade',
                'uses'       => 'Kordy\Ticketit\Controllers\InstallController@upgrade',
            ]);
            Route::get('/tickets', function () {
                return redirect()->route('tickets.install.index');
            });
            Route::get('/tickets-admin', function () {
                return redirect()->route('tickets.install.index');
            });
        //}
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Register the service provider for the dependency.
         */
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);

        if (LaravelVersion::min('5.4')) {
            $this->app->register(\Yajra\DataTables\DataTablesServiceProvider::class);
        } else {
            $this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);
        }

        $this->app->register(\Jenssegers\Date\DateServiceProvider::class);
        $this->app->register(\Mews\Purifier\PurifierServiceProvider::class);
        /*
         * Create aliases for the dependency.
         */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('CollectiveForm', 'Collective\Html\FormFacade');

        /*
         * Register htmlify command. Need to run this when upgrading from <=0.2.2
         */

        $this->app->singleton('command.kordy.ticketit.htmlify', function ($app) {
            return new Htmlify();
        });
        $this->commands('command.kordy.ticketit.htmlify');
    }
}
