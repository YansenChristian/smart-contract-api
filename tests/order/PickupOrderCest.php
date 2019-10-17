<?php

class PickupOrderCest
{
    public function tryUnauthorizedPickupOrder(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Pick up Order');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPATCH('/v1/orders/xxxxx/pick-up');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryRejectInvalidOrderSerial(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('user put invalid order serial: Pick up Order');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPATCH('/v1/orders/xxxxx/pick-up');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }


    ## Try reject valid order serial
}
