<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class InfoIsIncompleteComposer
{
    /**
     * ChecksUserDataComposer constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * This ViewComposer only returns data if the user is logged.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $loggedUser = auth()->user();

        if (!is_null($loggedUser)) {
            $view->with('infoIsIncomplete', true);
        }
    }
}
