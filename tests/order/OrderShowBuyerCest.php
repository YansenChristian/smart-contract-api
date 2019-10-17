<?php

class OrderShowBuyerCest
{
    public function tryUnauthorizedOrderShowBuyer(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Buyer Info from Order');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/orders/xxxxx/buyer');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryRequestInvalidOrderSerial(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('user put invalid order serial: Get Buyer Info from Order');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/orders/xxxxx/buyer');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    ## get authorized order logs
}
