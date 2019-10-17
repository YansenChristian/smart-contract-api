<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21/06/19
 * Time: 17:26
 */

class SalesPromotionV2Cest
{
    private $data;

    public function __construct()
    {
        $this->data = [
            'start_sale' => '2019-04-11',
            'end_sale' => '2019-04-21',
            'wholesale_data' =>
                [
                    [
                        'price' => 10000,
                        'discount' => 9876,
                        'quantity' => 1
                    ],
                    [
                        'price' => 9000,
                        'discount' => 8765,
                        'quantity' => 2,
                    ],
                    [
                        'price' => 8000,
                        'discount' => 7649,
                        'quantity' => 3,
                    ],
                    [
                        'price' => 7000,
                        'discount' => 6542,
                        'quantity' => 4,
                    ]
                ]
        ];
    }

    public function tryUnauthorizedCreateSalesPromotion(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Create Sales Promotion');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');

        $I->sendPUT('/v2/items/promotion/prices/xxxxx');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedCreateSalesPromotionWithInvalidItemId(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Create Sales Promotion With Invalid Item Id');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPUT('/v2/items/promotion/prices/xxxx');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedCreateSalesPromotion(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Create Sales Promotion');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $I->sendGET('/v1/products');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productId = $product->id;

        $I->sendPUT('/v2/items/promotion/prices/'. $productId, $this->data);
        $I->seeResponseCodeIs(204);
    }

    public function tryUnauthorizedUpdateSalesPromotion(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Update Sales Promotion');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPUT('/v2/items/promotion/prices/xxxxx');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedUpdateSalesPromotionWithInvalidItemId(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Update Sales Promotion With Invalid Item Id');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPUT('/v2/items/promotion/prices/xxxx');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedUpdateSalesPromotion(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Update Sales Promotion');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $I->sendGET('/v1/products');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productId = $product->id;

        $I->sendPUT('/v2/items/promotion/prices/'. $productId, $this->data);
        $I->seeResponseCodeIs(204);
    }

    public function tryUnauthorizedDeleteSalesPromotion(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Delete Sales Promotion');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendDELETE('/v2/items/promotion/prices/xxxxx');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedDeleteSalesPromotionWithInvalidItemId(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Delete Sales Promotion With Invalid Item Id');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendDELETE('/v2/items/promotion/prices/xxxx');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedDeleteSalesPromotion(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Delete Sales Promotion');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/products');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productId = $product->id;

        $I->sendDELETE('/v2/items/promotion/prices/'. $productId);
        $I->seeResponseCodeIs(204);
    }


}
