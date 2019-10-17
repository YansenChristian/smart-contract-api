<?php

class OnboardingVendorSurveyCest
{
    private $vendorSurveyData;

    public function __construct()
    {
        $this->vendorSurveyData = [
            "seller_profilling" => "personal",
            "survey_name" => "other",
            "reference" => "facebook"
        ];
    }

    public function tryUnauthorizedvendorSurvey(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: failed created data');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPOST('/v1/onboarding');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedvendorSurvey(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: success created data');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/onboarding', $this->vendorSurveyData);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}

