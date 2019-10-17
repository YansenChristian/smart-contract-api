<?php

class OnboardingShippingInformationCest
{
    public function tryUnauthorizedShippingInformation(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Shipping Information');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/onboarding-process/detail/shipping');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedShippingInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Shipping Information');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/onboarding-process/detail/shipping');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
