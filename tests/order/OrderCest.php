<?php

class OrderCest
{
    public function tryUnauthorizedOrder(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Orders List');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/orders');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedOrder(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Orders List');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/orders');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
