<?php


namespace App\helpers\External;


use App\Customer;
use App\PaymentMethod;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class Lookup
{
    const LOOKUP_TYPE_PHONE  = 'phone';
    const LOOKUP_TYPE_EMAIL  = 'email';

    private $type;
    private $value;
    private $response;

    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return Lookup
     */
    public function build()
    {
        if ($this->type === self::LOOKUP_TYPE_PHONE) {
           $this->lookupByPhoneNumber();
        }

        if ($this->type === self::LOOKUP_TYPE_EMAIL) {
            $this->lookupByEmail();
        }

        return $this;
    }

    protected function lookupByPhoneNumber()
    {
        $users = User::query()->where('phonenumber', $this->value)->get();
        foreach ($users as $key => $user) {
            $users[$key]['payment_methods'] = \App\PaymentMethod::getRecAllPaymentMethod($user->id, 1);
        }

        $customers = Customer::query()->where('mobile', $this->value)->get();
        foreach ($customers as $key => $customer) {
            $customers[$key]['payment_methods'] = \App\PaymentMethod::getRecAllPaymentMethod($customer->userid, 1);
        }

        $this->response['user']     = $users;
        $this->response['customer'] = $customers;
    }

    protected function lookupByEmail()
    {
        $users = new Collection();
        foreach (User::query()->where('email', $this->value)->get() as $key => $user) {
            $users[$key] = [
                'firstname' => $user->firstname,
                'lastname'  => $user->lastname,
                'email'     => $user->email,
                'phonenumber' => $user->phonenumber,
                'payment_methods' => PaymentMethod::getAllRec(
                    $user->id,
                    PaymentMethod::PAYMENT_METHOD_TYPE_EWALLET)
            ];
        }

        $customers = new Collection;
        foreach (Customer::query()->where('email', $this->value)->get() as $key => $customer) {
            $name = explode(' ', $customer->name);
            $customers[$key] = [
                'firstname' => !empty($name[0]) ? $name[0] : '',
                'lastname'  => !empty($name[1]) ? $name[1] : '',
                'email'     => $customer->email,
                'phonenumber' => $customer->mobile,
                'payment_methods' => PaymentMethod::getAllRec(
                    $customer->userid,
                    PaymentMethod::PAYMENT_METHOD_TYPE_EWALLET
                )
            ];
        }

        $this->response['users'] = $users;
        $this->response['customers'] = $customers;
    }

    public function get()
    {
        return $this->response;
    }
}
