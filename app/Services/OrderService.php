<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderService
 * @package App\Services
 */
class OrderService
{
    /**
     * @param User $user
     * @param null $startDate
     * @param null $endDate
     * @return mixed
     */
    public function getOrdersAmountForUser(User $user, $startDate = null, $endDate = null)
    {
        $query = DB::table('orders')
            ->select([DB::raw("COALESCE(SUM(orderqv), 0) as sum_orders")])
            ->where('userid', '=', $user->id);

        if ($startDate && $endDate) {
            $query->whereBetween('created_dt', [$startDate, $endDate]);
        }

        return $query->value('sum_orders');
    }
}
