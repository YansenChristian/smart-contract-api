<?php

class OrderRejectReasonsCest
{
    public function tryUnauthorizedOrderRejectReasons(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Order Reject Reasons');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/orders/reject-reasons');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedOrderRejectReasons(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Order Reject Reasons');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/orders/reject-reasons');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
