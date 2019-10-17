<?php

class UpdateShopInformationCest
{
    public function tryUnauthorizedUpdateShopInformation(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Update Shop Information');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPATCH('/v1/account/shop');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedUpdateShopInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Update Shop Information');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPATCH('/v1/account/shop', [
            'name_shop' => 'PT. Kaderwasih',
            'province_id' => 34,
            'city_id' => 407,
            'subdistrict_id' => 5464,
            'category_id' => [2,9,8,7],
            'postal_code' => 11640,
            'shipping_address' => 'Jl tanjung duren selatan'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedUpdatePartialShopInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Update Partial Shop Information');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPATCH('/v1/account/shop', [
            'name_shop' => 'PT. Kaderwasih'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
