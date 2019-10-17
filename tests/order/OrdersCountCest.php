<?php

class OrdersCountCest
{
    public function tryUnauthorizedOrdersCount(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Orders Count');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/orders/count');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedOrdersCount(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Orders Count');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/orders/count');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
