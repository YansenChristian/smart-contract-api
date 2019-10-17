<?php

class BrandV2Cest
{
    public function tryUnauthorizedGetBrandName(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Brand Name');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/brands/search');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }
    public function tryAuthorizedGetBrandNameNotFoundItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Get Brand Name Not Found Item');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/brands/search');
        $I->seeResponseEquals('[]');
    }
    public function tryAuthorizedGetBrandName(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Brand Name');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $I->sendGET('/v1/products');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productBrand = $product->brand;

        $I->sendGET('/v2/brands/search?keyword='.$productBrand);
        $I->seeResponseCodeIs(200);
    }
}
