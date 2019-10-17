<?php

class OnboardingShowInformationCest
{
    public function tryUnauthorizedShowInformation(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Information detail');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/onboarding-process/detail/information');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedShowInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Information detail');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/onboarding-process/detail/information');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
