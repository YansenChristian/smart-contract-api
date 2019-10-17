<?php

class BrandCest
{
    public function tryUnauthorizedBrand(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Brands List');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/brands');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedBrand(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Brands List');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/brands');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
