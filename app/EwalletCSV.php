<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class EwalletCSV extends Model {

    protected $table = "ewallet_csv";
    public $timestamps = false;

    public static function addNew($recs) {
        $memo = "Pay Period 1";
        $memo_to_csv = \utill::getCurrentDateTime();
        //
        $r = new EwalletCSV();
        $r->generated_on = \utill::getCurrentDateTime();
        $r->generated_by = Auth::user()->id;
        $r->processed = 0;
        $r->no_of_entries = $recs->count();
        $r->memo = $memo;
        $r->save();
        $csvId = $r->id;
        // 
        self::writeToCSV($csvId, $recs, "USD", $memo_to_csv);
        //
        EwalletTransaction::markAsTransfered($csvId, $recs);
        //
        return $csvId;
    }

    private static function writeToCSV($csvId, $recs, $currency, $memo) {
        $columns = array('Phone number', 'Amount', 'Currency', 'Memo');
        $file = fopen(storage_path('/payap_csv/' . $csvId . '.csv'), 'w');
        fputcsv($file, $columns);

        foreach ($recs as $rec) {
            fputcsv($file, array($rec->payap_mobile, number_format($rec->amount, 2), $currency, $memo));
        }
        fclose($file);
    }

}
