<?php


class OnboardingSaveOnboardingCest
{
    private $saveOnboardingData;

    public function __construct()
    {
        $this->saveOnboardingData = [
            'term_verified' => "1",
        ];
    }

    public function tryUnauthorizedSaveOnboarding(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: failed created data');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPOST('/v1/onboarding-process/term-and-condition');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedSaveOnboarding(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: success created data');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/onboarding-process/term-and-condition', $this->saveOnboardingData);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
