<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DB;
use Auth;

class EsignGenieController extends Controller
{
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $authEndPoint;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $clientId;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $clientSecret;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $createEndPoint;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $formW8ben;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $gotoSuccessUrl;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $gotoFailUrl;

    public function __construct()
    {
        $this->middleware('auth.admin', ['except' => [
            'captureJsonFromEsignGenie',
            'getFw8ben'
        ]]);
        $this->middleware('auth.affiliate', ['except' => [
            'captureJsonFromEsignGenie',
            'getFw8ben'
        ]]);

        // Get creds from /config folder
        $this->authEndPoint = config('api_endpoints.EsignGenie_Auth_URL');
        $this->createEndPoint = config('api_endpoints.EsignGenie_NewDoc_URL');
        $this->clientId = config('api_endpoints.EsignGenie_Client_id');
        $this->clientSecret = config('api_endpoints.EsignGenie_Secret');
        $this->gotoSuccessUrl = env('EGENIE_SUCCESS_URL');
        $this->gotoFailUrl = config('api_endpoints.EsignGenie_Redirect_Fail');

        // Templates
        $this->formW8ben = config('api_endpoints.EsignGenie_W8BEN_Template_id');
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getAuthEndPoint()
    {
        return $this->authEndPoint;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getFormW8ben()
    {
        return $this->formW8ben;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getCreateEndPoint()
    {
        return $this->createEndPoint;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getGotoSuccessUrl()
    {
        return $this->gotoSuccessUrl;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getGotoFailUrl()
    {
        return $this->gotoFailUrl;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getToken()
    {
        $data = [];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->getAuthEndPoint(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => sprintf("grant_type=client_credentials&client_id=%s&client_secret=%s&scope=read-write", $this->getClientId(), $this->getClientSecret()),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }

        if (isset($error_msg)) {
            throw new Exception($error_msg);
        } else {
            curl_close($curl);
            $data_string = json_decode($response);
            $access_token = $data_string->access_token;
        }

        return $access_token;
    }

    /**
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function getFw8ben()
    {
        $data = [];

        $checkForNoW8benAlready = $this->userHaveDocument();

        if (!$checkForNoW8benAlready) {
            try {
                $auth_token = $this->getToken();

                $user = Auth::user();

                $folderName = sprintf("W8BEN_%s_%s", $user->distid, date('Y_m_d'));
                $templateId = $this->getFormW8ben();

                $nameOfIndividual = sprintf("%s %s", $user->firstname, $user->lastname);
                $fname = $user->firstname;
                $lname = $user->lastname;
                $email = $user->email;
                $distId = $user->distid;
                $dob = $user->date_of_birth;
                $country = $user->addresses()->where('addrtype', '3')->first()->countrycode;
                $city = $user->addresses()->where('addrtype', '3')->first()->city;
                $address = $user->addresses()->where('addrtype', '3')->first()->address1;

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->getCreateEndPoint(),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => '{
                    "folderName": "' . $folderName . '",
                    "templateIds": ' . $templateId . ',
                    "fields":
                        {
                            "Name of Individual": "' . $nameOfIndividual . '",
                            "County of Citizenship":"' . $country . '",
                            "City or Town":"' . $city . '",
                            "County":"' . $country . '",
                            "Permanent Residence":"' . $address . '",
                            "Mailing Address":"Same As Above",
                            "Mailing County":"' . $country . '",
                            "Mailing City or Town":"' . $city . '",
                            "Date Signed":"",
                            "US Taxpayer ID Number":"",
                            "Foreign Tax ID Number":"",
                            "Reference Number":"",
                            "Date of Birth":"' . $dob . '",
                            "P2 country":"",
                            "Signer Name":""
                        },
                    "parties":[
                        {
                            "firstName":"' . $fname . '",
                            "lastName":"' . $lname . '",
                            "emailId":"' . $email . '",
                            "permission":"FILL_FIELDS_AND_SIGN",
                            "sequence":1
                        }
                    ],
                    "signInSequence":false,
                    "custom_field1":{
                        "name":"distid",
                        "value":"' . $distId . '"
                             },
                    "createEmbeddedSigningSession":true,
                    "createEmbeddedSigningSessionForAllParties":true,
                    "signSuccessUrl":"' . $this->getGotoSuccessUrl() . '",
                    "signDeclineUrl":"' . $this->getGotoFailUrl() . '",
                    "themeColor":"#0066CB"
                    }',
                    CURLOPT_HTTPHEADER => array(
                        sprintf("Authorization: Bearer %s", $auth_token),
                        "content-type: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $data_string = json_decode($response);

                //
                // TODO in case API spits back error... embeddedSigningSessions will be not set, so catch it
                //

                $embedded_session_URL = $data_string->embeddedSigningSessions[0]->embeddedSessionURL;

                $status = 200;

                $data = ['error' => 0, 'url' => $embedded_session_URL];
            } catch (Exception $e) {
                $data = [
                    'error' => 1,
                    'msg' => $e->getMessage()
                ];

                $status = 500;
            }
        } else {
            $data = [
                'error' => 1,
                'msg' => 'If you have already filled out your tax form, please wait as it may take a few minutes'
            ];

            $status = 500;
        }

        return response()->json($data, $status);
    }

    /**
     * @param Request $request
     * @return |null
     */
    public function captureJsonFromEsignGenie(Request $request)
    {
        $json = utf8_encode($request->getContent());

        $respArray = json_decode($json, true);

        try {
            $distid = $respArray["data"]["folder"]["custom_field1"]["value"];
            $doc_id = $respArray["data"]["folder"]["documentsList"][0]["documentId"];
            $folder_id = $respArray["data"]["folder"]["folderId"];

            try {
                $user = \App\User::where('distid', '=', $distid)->first();

                $user->update(['is_tax_confirmed' => 1]);

                DB::table('user_assets')->updateOrInsert(['user_id' => $user->id], [
                    'w8ben_folder_id' => $folder_id,
                    'w8ben_doc_id' => $doc_id
                ]);

                return 'success';
            } catch (QueryException $e) {
                \Log::info($e->getMessage());
            }
        } catch (Exception $e) {
            \Log::info($e->getMessage());
        }

        return false;
    }

    /**
     * @return bool
     */
    private function userHaveDocument()
    {
        return !is_null(DB::table('user_assets')->where('user_id', '=', Auth::id())->first());
    }
}
