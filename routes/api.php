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
        $router->get('counter', 'SmartContractController@getCounter');
        $router->get('/', 'SmartContractController@getSmartContracts');
    });
});

$router->group([
    'namespace' => 'v2',
    'prefix' => 'v2'
], function () use ($router) {

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'Items',
            'prefix' => 'items'
        ], function () use ($router) {
            $router->get('/','ItemController@getItems');
            $router->put('/{id}/edit', 'ItemController@putUpdate');
            $router->post('/','ItemController@postCreate');
            $router->delete('/{id}', 'ItemController@delete');
            $router->patch('/{id}/show','ItemController@showItem');
            $router->patch('/{id}/hide','ItemController@hideItem');
            $router->put('/promotion/prices/{itemId}', 'ItemController@putInsertOrUpdateSalesPromotion');
            $router->delete('/promotion/prices/{itemId}', 'ItemController@deleteSalesPromotion');
        });
    });

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'ItemInitials',
            'prefix' => 'catalogs'
        ], function () use ($router) {
            $router->get('/search','ItemInitialController@getName');
            $router->get('/{id}','ItemInitialController@getData');
        });
    });

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'Categories',
            'prefix' => 'categories'
        ], function () use ($router) {
            $router->get('/search', 'CategoryController@getLastChild');
        });
    });

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'Brands',
            'prefix' => 'brands'
        ], function () use ($router) {
            $router->get('/search', 'BrandController@getName');
        });
    });

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'UsersFcm',
            'prefix' => 'users-fcm'
        ], function () use ($router) {
            $router->post('/', 'UsersFcmController@insertUsersFcm');
            $router->delete('/', 'UsersFcmController@deleteUsersFcm');
        });
    });

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'Orders',
            'prefix' => 'orders'
        ], function () use ($router) {
            $router->get('/{orderSerial}', 'OrderController@getOrderDetail');
        });
    });

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'Summary',
        ], function () use ($router) {
            $router->get('/seller-summary', 'SummaryController@getSummary');
        });
    });

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'RatingReviews',
            'prefix' => 'rating-reviews'
        ], function () use ($router) {
            $router->get('/', 'RatingReviewController@getProductReviews');
        });
    });

    $router->group([
        'middleware' => ['auth']
    ], function () use ($router) {

        $router->group([
            'namespace' => 'SalesReport',
            'prefix' => 'sales-report'
        ], function () use ($router) {
            $router->get('/order', 'SalesReportController@getOrderReport');
            $router->get('/chat', 'SalesReportController@getChatReport');
            $router->get('/product-visitors', 'SalesReportController@getProductVisitors');
            $router->get('/export-report', 'SalesReportController@getExportReport');
            $router->get('/product', 'SalesReportController@getProductReport');
            $router->get('/top-product', 'SalesReportController@getTopProductList');
            $router->get('/export-product-report', 'SalesReportController@getExportProductReport');
            $router->get('/buyer', 'SalesReportController@getBuyerReport');
            $router->get('/top-buyer', 'SalesReportController@getTopBuyerList');
            $router->get('/top-location', 'SalesReportController@getTopLocationList');
            $router->get('/export-buyer-report', 'SalesReportController@getExportBuyerReport');
        });
    });
});
