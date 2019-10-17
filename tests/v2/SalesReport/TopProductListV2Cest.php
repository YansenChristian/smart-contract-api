<?php

class TopProductListV2Cest
{
    private $data;

    public function __construct()
    {
        $this->data = [
            'start_date' => 'Codeception',
            'end_date' => 'Codeception',
            'page' => '1',
            'limit' => '5',
            'order' => 'desc',
        ];
    }

    public function tryUnauthorizedGetTopProductList(ApiTester $I)
    {
        $I->wantToTest('unauthorised user failed: Unauthorised user');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/sales-report/top-product/');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryGetTopProductListWithInvalidData(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Bad Request Data');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/sales-report/top-product/', $this->data);
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedGetTopProductList(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorised user success: Show Data Top Product List');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $this->data['start_date'] = '2019-01-19';
        $this->data['end_date'] = '2019-08-19';

        $I->sendGET('/v2/sales-report/top-product/', $this->data);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
