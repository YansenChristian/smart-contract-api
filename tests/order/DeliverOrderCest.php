<?php

class DeliverOrderCest
{
    public function tryUnauthorizedDeliverOrder(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Deliver Order');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPATCH('/v1/orders/xxxxx/delivered');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryDeliverInvalidOrderSerial(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('user deliver invalid order serial: Deliver Order');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPATCH('/v1/orders/xxxxx/delivered');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }


    ## Try deliver valid order serial
}
