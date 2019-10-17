<?php

class OnboardingPaymentInformationCest
{
    public function tryUnauthorizedPaymentInformation(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Payment Information');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/onboarding-process/payment');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedPaymentInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Payment Information');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/onboarding-process/payment');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}


