<?php

class OnboardingInformationCest
{
    private $informationData;

    public function __construct()
    {
        $this->informationData = [
            "name_shop" => "codeceptionTest". date('d-m-y H:i:s'),
            "owner_name" => "Testi",
            "microsite_url" => "codeceptiontest". date('dmyHis'),
            "company_since" => 2001,
            "address" => "Jl. Anggrek Jakarta",
            "province_id" => 15,
            "city_id" => 185,
            "subdistrict_id" => 2805,
            "postal_code" => 75772,
            "description_shop" => "Jual Barang Apapun",
            "npwp_file" => null,
            "ktp_file" => null,
            "ktp_number" => 1234567898765439,
            "logo" => null,
            "logo_filename" => "default_logo.jpg",
            "company_video_url" => "https://www.youtube.com/watch?v=ft1a9AC_aog&list=RDdvHZGpwyMP0&index=5",
            "email" => "richio1017@gmail.com",
            "position" => "CEO",
            "handphone" =>  628123123123,
            "name" => "Rich",
            "seller_profilling" => "personal",
            "item_categories" => ["3"],
            "banners" => null,
            "photos" => null,
            "business_type" => ["distributor","reseller"],
            "npwp_number" => 123456789098765,
            "logo_link" => "https:"
        ];
    }

    public function tryUnauthorizedInformation(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: failed created data');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendPOST('/v1/onboarding-process/information');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedInformation(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: success created data');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/onboarding-process/information', $this->informationData);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
