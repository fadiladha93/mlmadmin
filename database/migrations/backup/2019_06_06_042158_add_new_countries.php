<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('country')->insert(
            [
                [
                    'countrycode' => 'CPV',
                    'country' => 'Cabo Verde',
                    'is_tier3'=> 0
                ],
                [
                    'countrycode' => 'CI',
                    'country' => "Cote d'Ivoire",
                    'is_tier3'=> 0
                ],
                [
                    'countrycode' => 'SWZ',
                    'country' => "Eswatini",
                    'is_tier3'=> 0
                ],
                [
                    'countrycode' => 'LY',
                    'country' => 'Libya',
                    'is_tier3'=> 0
                ],
                [
                    'countrycode' => 'SS',
                    'country' => 'South Sudan',
                    'is_tier3'=> 0
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $countries = ["Cabo Verde" , "Cote d'Ivoire", "Libya", "South Sudan", "Eswatini" ];
        DB::table('country')
            ->whereIn('country', $countries)
            ->delete();
    }
}
