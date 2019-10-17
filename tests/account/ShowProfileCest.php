<?php

class ShowProfileCest
{
    public function tryUnauthorizedShowProfile(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Show Seller Profile ');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/account/profile');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedShowSellerProfile(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Show Seller Profile ');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/account/profile');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
