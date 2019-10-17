<?php

class ItemInitialV2Cest
{
    public function tryUnauthorizedGetItemInitialName(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Item Initial Name');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/catalogs/search');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedGetItemInitialNameNotFoundItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Get Item Initial Name Not Found Item');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/catalogs/search');
        $I->seeResponseEquals('[]');
    }

    public function tryAuthorizedGetItemInitialName(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Item Initial Name');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $I->sendGET('/v1/products');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productName = $product->name;
        $keyword = explode(' ',trim($productName));

        $I->sendGET('/v2/catalogs/search?keyword='.$keyword[0]);
        $I->seeResponseCodeIs(200);
    }

    public function tryUnauthorizedGetItemInitialData(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Item Initial Data');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/catalogs/xxxx');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedGetItemInitialDataNotFoundItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Get Item Initial Not Found Item');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/catalogs/xxx');
        $I->seeResponseContains('VALIDATION_FAILURE');
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedGetItemInitialData(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Item Initial Data');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $I->sendGET('/v1/products');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productName = $product->name;
        $keyword = explode(' ',trim($productName));

        $I->sendGET('/v2/catalogs/search?keyword='.$keyword[0]);
        $response = $I->grabResponse();
        $item = json_decode($response);
        $itemInitialId = $item[0]->id;

        $I->sendGET('/v2/catalogs/'.$itemInitialId);
        $I->seeResponseCodeIs(200);
    }
}
