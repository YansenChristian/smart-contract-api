<?php


class OnboardingStoreShippingInformationCest
{
    private $storeShippingInformationData;

    public function __construct()
    {
        $this->storeShippingInformationData = [
            "name" => "abas",
            "telephone" => 8123123234,
            "mobile_number" => 628198876413,
            "shipping_services" => [
                [
                    "id" => 40,
                    "name" => "GO-SEND",
                    "service_code" => "Instant",
                    "courier_code" => "go-send",
                    "shipping_description" =>"Instant",
                    "used_vendor" => 1
                ]
            ],
            "address" => "Jl. Anggrek Jakarta",
            "province_id" => 15,
            "city_id" => 185,
            "subdistrict_id" => 2805,
            "postal_code" => 75772,
            "latitude" => -10.05,
            "longitude" => 2.24,
            "is_same_microsite" => "0"
        ];
    }

    public function tryUnauthorizedStoreShippingInformation(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: failed created data');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPOST('/v1/onboarding-process/shipping/create');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryRequestInvalidStoreShippingInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Not Found Data');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/onboarding-process/shipping/create');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    public function tryAuthorizedStoreShippingInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: success created data');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/onboarding-process/shipping/create', $this->storeShippingInformationData);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
