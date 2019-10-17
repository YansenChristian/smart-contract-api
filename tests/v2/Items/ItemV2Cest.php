<?php

class ItemV2Cest
{
    private $itemData;

    public function __construct()
    {
        $this->itemData = [
            'is_stock_available' => '1',
            'name' => 'Item from codeception '.date('d-m-y H:i:s'),
            'description' => 'description',
            'video_url' => 'https://www.youtube.com/',
            'sellernote' => 'seller note',
            'is_view_website' => 'Y',
            'sku_number' => 'SKU-123',
            'unit_type' => 'Unit',
            'weight_shipping_uom' => 'Kilogram',
            'weight_shipping' => 21.5,
            'dim_uom' => 'Meter',
            'length' => 2.2,
            'width' => 5.0,
            'height' => 7.2,
            'price_type' => 'wholesale',
            'wholesale_data' => [
                [
                    'price' => 30000,
                    'quantity' => 1
                ],
                [
                    'price' => 15000,
                    'quantity' => 15
                ],
                [
                    'price' => 10000,
                    'quantity' => 48
                ]
            ],
            'images' => [
                [
                    'type' => 'url',
                    'data' => 'https://img1dev.ralali.id/passthrough/assets/img/Libraries/77340_Edit_API_S_0003_wu4TSvyCWvY99bvw_1556630483.png'
                ],
                [
                    'type' => 'video',
                    'data' => 'https://www.youtube.com/embed/hw3L1cNu_7o'
                ]
            ],
            'default_image_index' => 1
        ];
    }

    # Update Item

    public function tryUnauthorizedUpdateItem(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Update Item');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPUT('/v2/items/xxxx/edit', $this->itemData);
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedUpdateNotFoundItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Update Not Found Item');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPUT('/v2/items/xxxx/edit', $this->itemData);
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedUpdateItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Update Item');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $I->sendGET('/v1/products');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productId = $product->id;

        $itemData = $this->itemData;
        $itemData['name'] = 'Edit '. $this->itemData['name'];

        $I->sendPUT('/v2/items/'.$productId.'/edit', $itemData);
        $I->seeResponseCodeIs(204);
    }

    # Add Item

    public function tryUnauthorizedAddItem(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Add Item');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPOST('/v2/items', $this->itemData);
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedAddInvalidItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Add Invalid Item');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v2/items', $this->itemData);
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedAddItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Add Item');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $itemData = $this->itemData;
        $itemData['name'] = 'Add '. $this->itemData['name'];
        $itemData['vendor_id'] = '17844';
        $itemData['source'] = 'mobile';
        $itemData['is_new_brand'] = false;

        $randomAlphabet = array_random(range('a','z'));

        $I->sendGET('/v2/categories/search?keyword='.$randomAlphabet);
        $response = $I->grabResponse();
        $categories = json_decode($response);
        $itemData['category_id'] = $categories[0]->id;

        $I->sendPOST('/v2/items', $itemData);
        $I->seeResponseCodeIs(204);
    }

    #Hide Item

    public function tryUnauthorizedHideItem(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Hide Item');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPATCH('/v2/items/xxxx/hide');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedHideNotFoundItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Hide Not Found Item');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPATCH('/v2/items/xxxx/hide');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedHideItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Hide Item');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/products?status=show');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productId = $product->id;
        $I->sendPATCH('/v2/items/'.$productId.'/hide');
        $I->seeResponseCodeIs(204);
    }

    #Show Item

    public function tryUnauthorizedShowItem(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Show Item');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPATCH('/v2/items/xxxx/show');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedShowNotFoundItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Show Not Found Item');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPATCH('/v2/items/xxxx/show');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedShowItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Show Item');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/products?status=hide');
        $products = $I->grabResponse();
        $product = json_decode($products)->result->items[0];
        $productId = $product->id;
        $I->sendPATCH('/v2/items/'.$productId.'/show');
        $I->seeResponseCodeIs(204);
    }

    # Delete Item

    public function tryUnauthorizedDeleteItem(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Delete Item');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendDELETE('/v2/items/xxxx');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedDeleteNotFoundItem(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Delete Not Found Item');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendDELETE('/v2/items/xxxx');
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedDeleteItem(ApiTester $I, ApiCest $api)
    {
//        $I->wantToTest('authorized user failed: Delete Item');
//        $I = $api->tryAuthenticate($I);
//        $I->haveHttpHeader('Accept', 'application/json');
//        $I->amBearerAuthenticated($api->token);
//        $I->sendGET('/v1/products');
//        $products = $I->grabResponse();
//        $product = json_decode($products)->result->items[0];
//        $productId = encode($product->id);
//        $I->sendDELETE('/v2/items/'.$productId);
//        $I->seeResponseCodeIs(204);
    }

    public function tryUnauthorizedGetListOfItems(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get List of Items');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/items');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedGetListOfItems(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get List of Items');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/items');
        $I->seeResponseCodeIs(200);
    }
}
