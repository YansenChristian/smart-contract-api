<?php

class CourierCest
{
    public function tryUnauthorizedCourier(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Couriers List');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/couriers');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedCourier(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Courier List');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/couriers');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
