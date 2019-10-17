<?php

class OnboardingShippingServicesCest
{
    public function tryUnauthorizedShippingServices(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Shipping Services');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/onboarding-process/shipping/services');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedShippingServices(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Shipping Services');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/onboarding-process/shipping/services');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
