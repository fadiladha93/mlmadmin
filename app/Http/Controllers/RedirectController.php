<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

/**
 * Class RedirectController
 * @package App\Http\Controllers
 */
class RedirectController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function helpDesk()
    {
        return Redirect::away(env('HELPDESK_URL'));
    }
}
