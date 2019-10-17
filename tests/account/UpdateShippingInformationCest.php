<?php

class UpdateShippingInformationCest
{
    public function tryUnauthorizedUpdateShippingInformation(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Update Shipping Information');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPUT('/v1/account/shipping');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }
    public function tryAuthorizedUpdateShippingInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Update Shipping Information');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPUT('/v1/account/shipping', [
            'pic_name' => 'Peter Hartawan Suherman',
            'pic_phone_number' => '08999990199',
            'warehouse_phone_number' => '08123123126',
            'warehouse_address' => 'Jl. Jati Bening',
            'district_id' => 812,
            'postal_code' => 17412,
            'courier_id' => [10, 16, 18, 20, 23]
        ]);
        $I->seeResponseCodeIs(204);
    }
}
