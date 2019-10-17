<?php

class OrderDetailCest
{
    public function tryUnauthorizedOrderDetail(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Order Detail');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/orders/xxxxx/detail');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryRequestInvalidOrderSerial(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('user put invalid order serial: Get Order Detail');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/orders/xxxxx/detail');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    ## get authorized order detail
}
