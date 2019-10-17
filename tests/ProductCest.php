<?php

class ProductCest
{
    private $itemId;

    public function tryUnauthorizedProductList(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Product List');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/products');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedProductList(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Product List');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/products');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $items = json_decode($I->grabResponse())->result->items;
        $this->itemId = $items[array_rand($items)]->id;
    }

    public function tryAuthorizedNotFoundProductDetail(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user fail: Get Not Found Product Detail');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/products/xxxx');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedProductDetail(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Product Detail');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/products/'.$this->itemId);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
