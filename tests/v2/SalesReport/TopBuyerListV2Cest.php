<?php

class TopBuyerListV2Cest
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

    public function tryUnauthorizedGetTopBuyerList(ApiTester $I)
    {
        $I->wantToTest('unauthorised user failed: Unauthorised user');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/sales-report/top-buyer/');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryGetTopBuyerListWithInvalidData(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Bad Request Data');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/sales-report/top-buyer/', $this->data);
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedGetTopBuyerList(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorised user success: Show Data Top Buyer List');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $this->data['start_date'] = '2019-03-19';
        $this->data['end_date'] = '2019-08-19';

        $I->sendGET('/v2/sales-report/top-buyer/', $this->data);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
