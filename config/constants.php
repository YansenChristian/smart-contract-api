<?php
/**
 * Created by PhpStorm.
 * User: David Lienardy
 * Date: 4/14/2016
 * Time: 1:47 PM
 */


// DEVELOPMENT
return [
    'IMAGE_PATH' => [
        'ITEM' => 'assets/img/Libraries/'
    ],
    'DOCUMENT_PATH' => [
        'SELLER_REPORT' => '/assets/documents/seller/report'
    ],

    'APP_ENV' => 'local',
    'encode_key' => 'ralali',
    'rows_per_page' => 20,
    'EMAIL_CS' => 'nurul@ralali.com',
    'EMAIL_LOGISTIC' => '',
    'EMAIL_SAP' => '',
    'EMAIL_RPX' => '',
    'EMAIL_FL' => '',
    'EMAIL_DEMO_FL' => 'harry.prastio@ralali.com',
    'EMAIL_DEMO_RPX' => 'ashari.fauzi@ralali.com',
    'EMAIL_DEMO_SAP' => 'dede.sukimah@ralali.com',
    'vendor_status' => [
        'A' => 'Approve',
        'P' => 'Profile Undefined',
        'N' => 'Not yet',
        'R' => 'Reject',
        'CP' => 'Contact by Phone',
        'CE' => 'Contact by Email',
        'OP' => 'On Progress',
        'F' => 'Follow Up',
        'D' => 'Deleted',
        'RG' => 'Registered',
        'NC' => 'Not Completed'
    ],
    'ENCRYPTION_KEY' => "SomeRandomString",
    'GRANT_URL' => 'https://kongdev.ralali.xyz/oauth/v1/grant',
    'ACCESS_URL' => 'https://kongdev.ralali.xyz/oauth/v1/login',
    'RETOKEN_URL' => 'https://kongdev.ralali.xyz/oauth/v1/retoken',
    'LOGOUT_URL' => 'https://kongdev.ralali.xyz/oauth/v1/logout',
    'TOKEN_STATUS_URL' => 'https://kongdev.ralali.xyz/oauth/v1/token_status',
    'GRANT_STATUS_URL' => 'https://kongdev.ralali.xyz/oauth/v1/grant_status',
    'API_URL' => 'https://apidev.ralali.xyz/api/v1/',
    'API_GLOBAL_URL' => 'https://apidev.ralali.xyz/',
    'API_USERNAME' => NULL,
    'API_PASSWORD' => NULL,
    'CLIENT_SELLER_KEY' => '20160314094255',
    'CLIENT_SELLER_PASSWORD' => '201619872345923',
    'CLIENT_KEY' => '20160314093245',
    'CLIENT_PASSWORD' => '201615889075239',
    'CLIENT_ADMIN_KEY' => '20160314095225',
    'CLIENT_ADMIN_PASSWORD' => '201667432788890',
    'WEB_URL' => 'https://dev.ralali.xyz/',
    'RPX_URL' => 'http://api.rpxholding.com/wsdl/rpxwsdl.php?wsdl',
    'RPX_USERNAME' => 'zapikoe',
    'RPX_PASSWORD' => 'b2bmarketplace',
    'RPX_DEMO_USERNAME' => 'demo',
    'RPX_DEMO_PASSWORD' => 'demo',
    'RPX_DEMO_SHIPPER_ACCOUNT' => '234098705',
    'FL_URL' => 'http://ws.firstlogistics.co.id/',
    'FL_DEMO_APPID' => 'STDEMO01',
    'FL_DEMO_ACCOUNT' => '100000ST',
    'FL_APPID' => 'RLX9P3PP',
    'FL_ACCOUNT' => '1500001320',
    'SAP_URL' => 'http://182.23.64.151/serverapi.sap/api',
    'SAP_USERNAME' => 'sapclientapi',
    'SAP_PASSWORD' => 'SAPCLIENTAPI_2014',
    'JNE_URL' => 'http://api.jne.co.id:8889/tracing/ralali/',
    'JNE_USERNAME' => 'RALALI',
    'JNE_API_KEY' => 'ef3d8364c974088daed1282a0d530961',
    // -- this code belows MUST BE equals with OAuth2 project (.env file)
    'OAUTH_RESPONSE_CODE_SUCCESS_AUTHENTICATION' => 10,
    'OAUTH_RESPONSE_CODE_SUCCESS_TOKEN' => 16,
    'OAUTH_RESPONSE_CODE_SUCCESS_RETOKEN' => 12,
    'OAUTH_RESPONSE_CODE_SUCCESS_LOGOUT' => 13,
    'OAUTH_RESPONSE_CODE_SUCCESS_LOGIN' => 14,
    'OAUTH_RESPONSE_CODE_ERROR_HEADER' => 100,
    'OAUTH_RESPONSE_CODE_ERROR_AUTHENTICATION' => 99,
    'OAUTH_RESPONSE_CODE_ERROR_TOKEN' => 98,
    'OAUTH_RESPONSE_CODE_ERROR_SERVER' => 97,
    'OAUTH_RESPONSE_CODE_EXPIRED_TOKEN' => 96,
    'OAUTH_RESPONSE_CODE_INVALID_LOGIN' => 95,
    'OAUTH_RESPONSE_CODE_INVALID_LOGOUT' => 94,
    'OAUTH_RESPONSE_CODE_SUCCESS_GRANT_STATUS' => 15,
    // end of OAuth 2 response code
];
