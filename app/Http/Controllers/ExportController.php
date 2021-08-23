<?php

namespace App\Http\Controllers;


class ExportController extends Controller
{

    public function __construct()
    {

    }

    public function fieldWatchExport()
    {
        \App\Export::fieldWatchExport();
    }

}
