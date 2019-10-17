<?php

class OrderReportV2Cest
{
    private $data;

    public function __construct()
    {
        $this->data = [
            'start_date' => 'Codeception',
            'end_date' => 'Codeception',
        ];
    }

    public function tryUnauthorizedGetOrderReport(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Unauthorised user');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v2/sales-report/order/');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryRequestInvalidCest(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Bad Request Data');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v2/sales-report/order/', $this->data);
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedGetOrderReport(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorised user success: Show Data Order Report');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);

        $this->data['start_date'] = '2019-05-19';
        $this->data['end_date'] = '2019-08-19';

        $I->sendGET('/v2/sales-report/order/', $this->data);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
