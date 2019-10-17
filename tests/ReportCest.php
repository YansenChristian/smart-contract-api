<?php

class ReportCest
{
    public function tryUnauthorizedReport(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Dashboard Information');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/dashboard/report-dashboard');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedReport(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Dashboard Information');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/dashboard/report-dashboard', ['start_date' => '2018-11-01', 'end_date' => '2018-11-30']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
