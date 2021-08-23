<?php

namespace App\Http\Controllers;

use App\Address;
use App\Facades\UserTransferManager;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Authy\AuthyApi;

class UserTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    private function makeValidator()
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'distid' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'address' => 'required|max:50',
            'city' => 'required|max:25',
            'stateprov' => 'required|max:50',
            'postalcode' => 'required|max:10',
            'phonenumber' => 'required',
            'country_code' => 'required'
        ];

        $messages = [
            'firstname.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
            'username.required' => 'Username is required',
            'username.unique' => 'This username is already used',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'This email is already used',
            'address.max' => 'Address cannot exceed 50 characters',
            'address.required' => 'Address is required',
            'country_code.required' => 'Country is required',
            'city.max' => 'City cannot exceed 25 characters',
            'city.required' => 'City is required',
            'stateprov.max' => 'State cannot exceed 50 characters',
            'stateprov.required' => 'State is required',
            'postalcode.max' => 'Postal code cannot exceed 10 characters',
            'postalcode.required' => 'Postal code is required',
            'distid.required' => 'An distribuitor must be chosen',
            'phonenumber.required' => 'A phone number is required'
        ];

        return Validator::make(request()->post(), $rules, $messages);
    }

    public function transferUser()
    {
        if (!\App\AdminPermission::sidebar_user_transfer()) {
            return redirect('/');
        }

        $user = User::getByDistId(request()->post('distid'));

        if (!$user) {
            return ['error' => 1, 'msg' => '<div>- User does not exist with dist id: ' . request()->distId];
        }

        $validator = $this->makeValidator();

        $errorMessages = [];

        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $errorMessages[] = "<div> - " . $m . "</div>";
            }
        }

        if (!empty($errorMessages)) {
            return ['error' => 1, 'msg' => implode("", $errorMessages)];
        }

        // Show the validation messages, but don't actually run the process unless we have is_confirmed 1!
        if (request()->post('is_confirmed') == 0) {
            return ['error' => 0];
        }

        try {
            DB::beginTransaction();

            $newUserId = UserTransferManager::transferUser($user->id);

            if (!$newUserId) {
                return ['error' => 1, 'msg' => 'Unknown issue transferring user.'];
            }

            $newUser = User::find($newUserId);
            $this->replaceUserFieldsAndAddress($newUser);

            DB::commit();

            return ['error' => 0, 'msg' => 'User transferred successfully!<br>New Dist ID: ' . $newUser->distid];
        } catch (\Exception $e) {
            DB::rollback();
            return ['error' => 1, 'msg' => $e->getMessage()];
        }
    }

    /**
     * @param User $newUser
     */
    private function replaceUserFieldsAndAddress($newUser)
    {
        $newUser->fill(request()->only(['firstname', 'lastname', 'username', 'email', 'country_code']));

        $newUser->fill([
            'password' => null,
            'default_password' => 'Welcome123'
        ]);

        $newUser->save();

        $addressData = [
            'primary' => 1,
            'address1' => request()->post('address'),
            'city' => request()->post('city'),
            'stateprov' => request()->post('stateprov'),
            'postalcode' => request()->post('postalcode'),
            'countrycode' => request()->post('country_code')
        ];

        foreach([Address::TYPE_BILLING, Address::TYPE_SHIPPING, Address::TYPE_REGISTRATION] as $addrType) {
            $addressData['addrtype'] = $addrType;
            $newUser->addresses()->create($addressData);
        }
    }

    public function showTransferUserForm()
    {
        if (!\App\AdminPermission::sidebar_user_transfer()) {
            return redirect('/');
        }

        $d['countries'] = \App\Country::getAll();
        $d['email'] = base64_encode(Auth::user()->email);

        return view('admin.user.frmTransferUser')->with($d);
    }

    public function twoFactorAuthRequest()
    {
        $user = User::find(21976);

        $authyApi = new AuthyApi(env('AUTHY_API_KEY'));

        $response = $authyApi->phoneVerificationStart($user->phonenumber, '+1');

        $success = $response->ok();

        return ['success' => $success];
    }

    public function twoFactorAuthVerify()
    {
        $user = User::find(21976);

        $verificationCode = request()->post('verification_code');

        $authyApi = new AuthyApi(env('AUTHY_API_KEY'));

        $response = $authyApi->phoneVerificationCheck($user->phonenumber, '+1', $verificationCode);

        $success = $response->ok();

        return ['success' => $success];

    }
}
