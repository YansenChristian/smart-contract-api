<?php

class ProductVisitorsV2Cest
{
    public function tryUnauthorizedGetProductVisitors(ApiTester $I)
    {
        $I->wantToTest('unauthorised user failed: Unauthorised user');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/sales-report/product-visitors/');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedGetProductVisitors(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorised user success: Show Data Product Visitors');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $I->sendGET('/v2/sales-report/product-visitors/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
