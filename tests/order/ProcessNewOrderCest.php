<?php

class ProcessNewOrderCest
{
    public function tryUnauthorizedProcessNewOrder(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Process New Order');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPOST('/v1/orders/xxxxx/process');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryProcessInvalidOrderSerial(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('user put invalid order serial: Process New Order');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/orders/xxxxx/process');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

//    public function tryProcessValidOrderNumber(ApiTester $I, ApiCest $api)
//    {
//        $I->wantToTest('user put valid order serial: Process New Order');
//
//        $I = $api->tryAuthenticate($I);
//        $I->haveHttpHeader('Accept', 'application/json');
//        $I->amBearerAuthenticated($api->token);
//
//        $I->sendGET('/orders?type=unprocessed');
//        $orders = $I->grabDataFromResponseByJsonPath('$.data.[0]');
//        $order_serial = $orders[0]['order_serial_alias'];
//
//        $I->sendGET(sprintf('/orders/%s/items', $order_serial));
//        $items = $I->grabDataFromResponseByJsonPath('$.[0]');
//
//        $I->sendPOST(sprintf('/orders/%s/process', $order_serial), ["item_detail" => $items]);
//        $I->seeResponseCodeIs(204);
//    }
}
