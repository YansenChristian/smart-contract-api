<?php

class AuthStepOnboardingCest
{
    public function tryUnauthorizedStepOnboarding(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Step Onboarding');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/step-onboarding');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }
    public function tryAuthorizedStepOnboarding(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Step Onboarding');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/step-onboarding');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}