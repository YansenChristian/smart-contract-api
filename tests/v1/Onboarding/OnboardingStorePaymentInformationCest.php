<?php

class OnboardingStorePaymentInformationCest
{
    private $paymentInformationData;

    public function __construct()
    {
        $this->paymentInformationData = [
            "account_number" => 2200701065,
            "account_name" => "Rich",
            "account_branch" => "Bulungan (bulongan)",
            "bank_account" => null,
            "bank_account_file" => null
        ];
    }

    public function tryUnauthorizedPaymentInformation(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: failed created data');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPOST('/v1/onboarding-process/payment');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedPaymentInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: success created data');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/onboarding-process/payment', $this->paymentInformationData);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}

