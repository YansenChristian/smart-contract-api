<?php

class onboardingReminderExistingSellerCest
{
    public function tryUnauthorizedReminderExistingSeller(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Reminder Existing Seller');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/onboarding-process/reminder-existing-seller');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedReminderExistingSeller(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Reminder Existing Seller');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/onboarding-process/reminder-existing-seller');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
