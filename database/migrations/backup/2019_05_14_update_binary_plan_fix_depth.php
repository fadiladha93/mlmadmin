<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BinaryPlanFixDepth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
UPDATE binary_plan bp
SET depth = (SELECT (COUNT(parent.id) - 1)
	FROM binary_plan AS node,
		binary_plan AS parent
	 WHERE node._lft BETWEEN parent._lft AND parent._rgt
	AND node.id = bp.id
	 GROUP BY node.id
	 ORDER BY node._lft
	);
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
UPDATE binary_plan bp
SET depth = (SELECT (COUNT(parent.id))
	FROM binary_plan AS node,
		binary_plan AS parent
	 WHERE node._lft BETWEEN parent._lft AND parent._rgt
	AND node.id = bp.id
	 GROUP BY node.id
	 ORDER BY node._lft
	);
        ');
    }
}
