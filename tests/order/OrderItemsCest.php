<?php

class OrderItemsCest
{
    public function tryUnauthorizedOrderItems(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Order Items');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/orders/xxxxx/items');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryRequestInvalidOrderSerial(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('user put invalid order serial: Get Order Items');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/orders/xxxxx/items');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    ## get authorized order items
}
