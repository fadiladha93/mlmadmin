<?php

$mode = env("APP_ENV", 'dev');  // live or dev
$api_endpoinds = [
    'mode' => $mode,
    //
    'TOKENEXDetokenize' => 'Detokenize',
    'TOKENEXProcessTransactionAndTokenize' => 'ProcessTransactionAndTokenize',
    'TOKENEXGetKountHashValue' => 'GetKountHashValue',
    //idecide
    'iDecideUserDisable' => 'setDisabled',
    'iDecideUserEnable' => 'setEnabled',
    'iDecideCheckExistinUser' => 'users/subscriptions/grantDefaultPackage',
    'iDecideCreateNewUser' => 'users/create',
    'iDecideUpdatePassword' => 'update/password',
    'IDecideGenerateSSOToken' => 'createToken',
    'IDecideUpdateUserEmailAddress' => 'update/email',
    'IDecideUpdateUser' => 'update',
    //SOR
    'SORActivateUser' => 'clubmembership/activatemember',
    'SORGetMemberInfo' => 'clubmembership/getmembers',
    'SORDeactivatedUser' => 'clubmembership/deactivatemember',
    // TODO - Clean this up
    'SORGetLoginToken' => 'clubmembership/getlogintoken',
    'SORGetLoginToken' => 'clubmembership/getlogintokennovalidation',
    // 'SORGetLoginTokenNoVal' => 'clubmembership/getlogintokennovalidation',
    // TODO END
    'SORCreateUser' => 'clubmembership/createdefault',
    'SORUserTransfer' => 'clubmembership/transferuser',
    'UserAccountTypeID' => 9,
    'SORGetMembers' => 'clubmembership/getmembers',
];

if ($mode == "prod") {
    $live_credentials = [
        'MerchantGUID' => '4a812785-50bd-481f-86ae-3b086430a946',
        'MerchantPassword' => 'bYYwDaDuvL',
        'eWalletAPIURL' => 'https://www.i-payout.net/eWalletWS/ws_JsonAdapter.aspx',
        //
        //
        'NetworkMerchantsUsername' => 'Rsacco',
        'NetworkMerchantsPassword' => '1EldmfcROI!',
        //
        // iGo4Less0
        'iGo4Less0Username' => 'iGo4Less0',
        'iGo4Less0Password' => 'bezp96o5y04oqj73',
        'iGo4Less0ClubId' => '12744',
        //
        // iGo4Less1
        'iGo4Less1Username' => 'iGo4less1',
        'iGo4Less1Password' => 'fwy18ytwmrs8ct1h',
        'iGo4Less1ClubId' => '12716',
        //
        // iGo4Less2
        'iGo4Less2Username' => 'iGo4less2',
        'iGo4Less2Password' => 'mi3q4uliwfrx6hg0',
        'iGo4Less2ClubId' => '12718',
        //
        // iGo4Less3
        'iGo4Less3Username' => 'iGo4less3',
        'iGo4Less3Password' => 'x9ydzxxhuvlh3bik',
        'iGo4Less3ClubId' => '12719',
        //
        // iGo4LessBoom
        'iGo4LessBoomUsername' => 'iGo4lessBoom',
        'iGo4LessBoomPassword' => 'vyfqlewszsgd4f2v',
        'iGo4LessBoomClubId' => '12715',
        //
        //
        'SaveOnServiceURL' => 'https://api.saveonresorts.com/',
        //
        // idecide
        'IDecideServiceURL' => 'https://api.idecideinteractive.com/idecide/',
        'IDecideUserName' => 'ibuumerang',
        'IDecidePassword' => 'e30bf7c923bd46ab80a83',
        //
        // payap
        'CIDToken' => '100230401',
        //
        // token ex
        'TOKENEXAPIServiceURL' => 'https://api.tokenex.com/',
        'TOKENEXAPIKey' => 'AHnRo2tZAoGG5HJFjuYPEnkZa51hogANGW4VH3f3',
        'TOKENEXTokenEXId' => '4357543053584306',
        //
        // kount
        'KOUNTServiceURL' => 'https://risk.kount.net/',
        'KOUNTIFrameURL' => 'https://ssl.kaptcha.com/',
        'KOUNTAPIKey' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiI2MTYxMDciLCJhdWQiOiJLb3VudC4xIiwiaWF0IjoxNTUwOTQzNDQxLCJzY3AiOnsia2EiOm51bGwsImtjIjpudWxsLCJhcGkiOmZhbHNlLCJyaXMiOnRydWV9fQ.jVbi4AQibs3PE6U18PoS4zhezrcVYMXhVOwEz0O1XmA',
        'KOUNTMerchantID' => '616107',
        //
        // nmi
        'NMIUsername' => 'Rsacco',
        'NMIPassword' => '1EldmfcROI!',
        //
        // t1_payment
        't1Username' => 'Rsacco',
        't1Password' => '1EldmfcROI!',
        //
        // payArc payment
//        'payArcUsername' => 'payIbuumerang',
//        'payArcPassword' => '1EldmfcROI!',
        'payArcSecurityKey' => 'Em43S2Ff94BB5HbK7dD5p47xpHgccmpj',
        //
        // bitpay
        'BitPayTokenStr' => '79SZEyqYUQf895yhdQBtvCnB9f4GqKKNgCZvTCGPfNQJ',
        'BitPayAPIURL' => 'https://bitpay.com/invoices/',
        'BitPayCallBackURL' => 'https://myibuumerang.com',
        'EncryptedFilesystemStorageKey' => 'dksiflzi3kl3ialdkf',
        //
        // skrill
        'SkrillURL' => 'https://pay.skrill.com',
        'SkrillPayToEmail' => 'shawn@traverusglobal.com',
        'SkrillCallBackURL' => 'http://myibuumerang.com/skrill/callback',
        'SkrillCancelURL' => 'https://myibuumerang.com/skrill/cancel',
        //
        // EsignGenie - note use zoho web based client
        'EsignGenie_Auth_URL' => 'https://www.esigngenie.com/esign/api/oauth2/access_token',
        'EsignGenie_NewDoc_URL' => 'https://www.esigngenie.com/esign/api/templates/createFolder',
        'EsignGenie_Client_id' => '66d5e92638fd4c20a32cbf0c19d77480',
        'EsignGenie_Secret' => '680149ecad62411f808c1171311d884d',
        'EsignGenie_W8BEN_Template_id' => '[64113]',
        // Note: we might want to move these to params passed in init in future version
        'EsignGenie_Redirect_Good' => 'https://myibuumerang.com/',
        'EsignGenie_Redirect_Fail' => 'https://myibuumerang.com/update-user-tax-info-international/',

        'eWalletMerchantURL' => 'https://ibuumerang.globalewallet.com/MemberLogin.aspx',
        'metroUsername' => 'ibuumerang2020',
        'metroPassword' => 'iBuum123!!!',

        'payNetworxSecurityKey' => '26tKcDa8DhakTx3vxBbbEcVp9598PR2X',
        'payNetworxHostname' => 'secure.cardflexonline.com'
    ];
    $api_endpoinds = array_merge($api_endpoinds, $live_credentials);
} else {
    $dev_credentials = [
        //
        'MerchantGUID' => '4a812785-50bd-481f-86ae-3b086430a946',
        'MerchantPassword' => '7uKy7ABm25',
        'eWalletAPIURL' => 'https://testewallet.com/eWalletWS/ws_JsonAdapter.aspx',
        'eWalletMerchantURL' => 'https://ibuumerang.testewallet.com',
        'eWalletCheckoutThankYouPageURL' => 'http://myibuumerang.com/thank-you',
        //
        //
        'NetworkMerchantsUsername' => 'demo',
        'NetworkMerchantsPassword' => 'password',
        //
        // iGo4Less0
        'iGo4Less0Username' => 'iGo4Less0',
        'iGo4Less0Password' => 'bezp96o5y04oqj73',
        'iGo4Less0ClubId' => '12713',
        //
        // iGo4Less1
        'iGo4Less1Username' => 'iGo4less1',
        'iGo4Less1Password' => 'fwy18ytwmrs8ct1h',
        'iGo4Less1ClubId' => '12709',
        //
        // iGo4Less2
        'iGo4Less2Username' => 'iGo4less2',
        'iGo4Less2Password' => 'mi3q4uliwfrx6hg0',
        'iGo4Less2ClubId' => '12710',
        //
        // iGo4Less3
        'iGo4Less3Username' => 'iGo4less3',
        'iGo4Less3Password' => 'x9ydzxxhuvlh3bik',
        'iGo4Less3ClubId' => '12711',
        //
        // iGo4LessBoom
        'iGo4LessBoomUsername' => 'iGo4lessBoom',
        'iGo4LessBoomPassword' => 'vyfqlewszsgd4f2v',
        'iGo4LessBoomClubId' => '12712',
        //
        //
        'SaveOnServiceURL' => 'https://api.saveonuat.com/',
        //
        // idecide
        'IDecideServiceURL' => '',
        'IDecideUserName' => '',
        'IDecidePassword' => '',
        //
        // payap
        'CIDToken' => '',
        //
        // token ex
        'TOKENEXAPIServiceURL' => 'https://api.tokenex.com/',
        'TOKENEXAPIKey' => 'AHnRo2tZAoGG5HJFjuYPEnkZa51hogANGW4VH3f3',
        'TOKENEXTokenEXId' => '4357543053584306',
        //
        // kount
        'KOUNTServiceURL' => 'https://risk.beta.kount.net/',
        'KOUNTIFrameURL' => 'https://tst.kaptcha.com/',
        'KOUNTAPIKey' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiI5OTk2NjYiLCJhdWQiOiJLb3VudC4xIiwiaWF0IjoxNDk0NTM0Nzk5LCJzY3AiOnsia2EiOm51bGwsImtjIjpudWxsLCJhcGkiOmZhbHNlLCJyaXMiOnRydWV9fQ.eMmumYFpIF-d1up_mfxA5_VXBI41NSrNVe9CyhBUGck',
        'KOUNTMerchantID' => '999666',
        //
        // nmi
        'NMIUsername' => 'demo',
        'NMIPassword' => 'password',
        //
        // t1_payment
        't1Username' => 'Rsacco',
        't1Password' => '1EldmfcROI!',

        //
        // payArc payment
//        'payArcUsername' => 'payIbuumerang',
//        'payArcPassword' => '1EldmfcROI!',
//        'payArcUsername' => 'demo',
//        'payArcPassword' => 'password',
        'payArcSecurityKey' => '2JkwJZ6h28DWWJYRsjxTKz96x773KSX3',
//        'payArcSecCode' => 'WEB',
        //
        // bitpay
        'BitPayTokenStr' => 'C2HJM7LZw3bXdVoqQkGkyf',
        'BitPayAPIURL' => 'https://test.bitpay.com/invoices/',
        'BitPayCallBackURL' => 'https://dev.cloud.countdown4freedom.com',
        'EncryptedFilesystemStorageKey' => 'dksiflzi3kl3ialdkf',
        //
        // skrill
        'SkrillURL' => 'https://pay.skrill.com',
        'SkrillPayToEmail' => 'shawn@traverusglobal.com',
        'SkrillCallBackURL' => 'https://dev.cloud.countdown4freedom.com/skrill/callback',
        'SkrillCancelURL' => 'https://dev.cloud.countdown4freedom.com/skrill/cancel',
        //
        // EsignGenie - note use zoho web based client
        'EsignGenie_Auth_URL' => 'https://www.esigngenie.com/esign/api/oauth2/access_token',
        'EsignGenie_NewDoc_URL' => 'https://www.esigngenie.com/esign/api/templates/createFolder',
        'EsignGenie_Client_id' => '429386c821914e1194ca8c2e544ee681',
        'EsignGenie_Secret' => '95083dc67f9a423d90d892d6f6b87e3c',
        'EsignGenie_W8BEN_Template_id' => '[63943]',
        // Note: we might want to move these to params passed in init in future version
        'EsignGenie_Redirect_Good' => env('EGENIE_SUCCESS_URL'),
        'EsignGenie_Redirect_Fail' => env('EGENIE_ERROR_URL'),

        'payNetworxSecurityKey' => 'G8p294Tvt47eM963H6p3WWaWDK4xF54Q',
        'payNetworxHostname' => 'secure.networkmerchants.com'
    ];
    $api_endpoinds = array_merge($api_endpoinds, $dev_credentials);
}
return $api_endpoinds;
