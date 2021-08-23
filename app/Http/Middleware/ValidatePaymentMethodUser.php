<?php

namespace App\Http\Middleware;

use App\PaymentMethod;
use Closure;

class ValidatePaymentMethodUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$paymentMethodId = $request->id) {
            return false;
        }

        if (!$paymentMethod = PaymentMethod::find($paymentMethodId)) {
            return false;
        }

        return $next($request);
    }
}
