<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/docs/static-docs', ['as' => 'static-docs-file', 'uses' => 'Controller@getStaticDocs']);
$router->get('/docs', 'Controller@staticDocs');
$router->get('/health-check', 'Controller@healthcheck');

$router->group([
    'namespace' => 'V1',
    'prefix' => 'v1'
], function () use ($router){
    $router->group(['prefix' => 'payments'], function () use ($router) {
        $router->get('confirmation-via-email', 'PaymentController@getConfirmOrderPaymentViaEmail');
    });

    $router->group(['prefix' => 'vendors'], function () use ($router) {
        $router->get('{vendor_id}/exists', 'VendorController@getCheckVendorExists');
    });

    $router->group(['prefix' => 'items'], function () use ($router) {
        $router->post('/', 'ItemController@postToggleItemActivation');
        $router->post('/exist', 'ItemController@postCheckIfItemsAreSmartContract');
    });

    $router->group(['prefix' => 'smart-contracts'], function () use ($router) {
        $router->get('/', 'SmartContractController@getSmartContracts');
        $router->post('/', 'SmartContractController@postCreateSmartContract');
        $router->get('counter', 'SmartContractController@getCounter');
        $router->get('orders/exists', 'SmartContractController@getCheckIfOrderIsSmartContract');
        $router->get('{smart_contract_serial}', 'SmartContractController@getSmartContractDetail');
        $router->post('{smart_contract_serial}/approve', 'SmartContractController@postApproveSmartContract');
        $router->post('{smart_contract_serial}/reject', 'SmartContractController@postRejectSmartContract');
    });

    $router->group(['prefix' => 'legals'], function () use ($router) {
        $router->get('/', 'LegalController@getContent');
    });
});