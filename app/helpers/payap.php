<?php

class PayAP {

    private $service_url = 'https://payap.me/api.php?';
    private $cid;

    public function __construct() {
        $this->cid = \Config::get('api_endpoints.CIDToken');
    }

    public function curl($action, $requestPayload) {
        $query["action"] = $action;
        if ($action == 'checkPhoneNumber') {
            $query["phoneNumber"] = $requestPayload;
            $options = [
                'query' => http_build_query($query),
                'verify' => false
            ];
        } elseif ($action == 'makePayment') {
            $requestPayload['cid'] = $this->cid;
            $options = [
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'json' => $requestPayload,
                'query' => http_build_query($query),
                'verify' => false
            ];
        }
        $client = new \GuzzleHttp\Client();
        $response = $client->post($this->service_url, $options);
        return (string) $response->getBody();
    }

}
