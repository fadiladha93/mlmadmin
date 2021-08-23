<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreAPI extends Model {

    public $timestamps = false;
    
    const STORE_CALL_URL = "http://secure.flexvo.com/admin/api?data=";

    public static function getUrl($data) {
        $data = json_encode($data);
        $privKey = file_get_contents(storage_path('/keys/private_key.txt'));
        openssl_private_encrypt($data, $encrypted, openssl_pkey_get_private($privKey, "dev.flexvo"));
        $encrypted = base64_encode($encrypted);
        return self::STORE_CALL_URL . $encrypted;
    }

    public static function getTestData($data) {
        $data = json_encode($data);
        $privKey = file_get_contents(storage_path('/keys/private_key.txt'));
        openssl_private_encrypt($data, $encrypted, openssl_pkey_get_private($privKey, "dev.flexvo"));
        $encrypted = base64_encode($encrypted);
        echo $encrypted;
    }

}
