<?php
/**
 * Created by PhpStorm.
 * User: ralalian
 * Date: 31/05/19
 * Time: 15.35
 */

class OrderV2Cest
{
    public function tryUnauthorizedGetOrderDetail(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Order Detail');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/orders/xxxx');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedGetNotFoundOrderDetail(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Get Not Found Order Detail');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/orders/xxxx');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedGetOrderDetail(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Order Detail ');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/orders');
        $orders =  $I->grabResponse();
        $orderSerial = json_decode($orders)->data[0]->order_serial_alias;
        $I->sendGET('/v2/orders/'.$orderSerial);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
