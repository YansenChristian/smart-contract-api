<?php

class CategoryV2Cest
{
    public function tryUnauthorizedGetCategoryLastChild(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Category Last Child');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/categories/search');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedGetCategoryLastChildNotFoundItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Get Category Last Child Not Found Item');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/categories/search');
        $I->seeResponseEquals('[]');
    }

    public function tryAuthorizedGetCategoryLastChild(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Category Last Child');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $I->sendGET('/v1/products');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productCategory = $product->category;

        $I->sendGET('/v2/categories/search?keyword='.$productCategory);
        $I->seeResponseCodeIs(200);
    }
}
