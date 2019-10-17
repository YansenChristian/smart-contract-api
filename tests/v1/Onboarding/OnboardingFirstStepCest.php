<?php

class OnboardingFirstStepCest
{
    public function tryUnauthorizedFirstStepOnboarding(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get First Step Onboarding');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/onboarding-process/information');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedFirstStepOnboarding(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get First Step Onboarding');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/onboarding-process/information');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}

