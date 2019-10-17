<?php

class OnboardingPaymentInformationDetailCest
{
    public function tryUnauthorizedPaymentInformationDetail(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Payment Information Detail');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/onboarding-process/detail/payment');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedPaymentInformationDetail(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Payment Information Detail');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/onboarding-process/detail/payment');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}