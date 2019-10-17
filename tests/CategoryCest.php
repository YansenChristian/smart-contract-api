<?php

class CategoryCest
{
    public function tryUnauthorizedCategory(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Categories List');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/categories');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedCategory(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Categories List');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/categories');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
