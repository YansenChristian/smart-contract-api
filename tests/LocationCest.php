<?php

class LocationCest
{

    ## Provinces ##
    public function tryUnauthorizedProvince(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Provinces List');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/provinces');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedProvince(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Provinces List');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/provinces');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    ## Cities ##
    public function tryUnauthorizedCities(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Cities List');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/provinces/31/cities');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedInvalidProvinceToGetCities(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('Authorized user failed: Get Cities List Using Invalid Province ID');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/provinces/xxxxx/cities');
        $I->seeResponseCodeIs(422);
    }

    public function tryAuthorizedCities(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Cities List');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/provinces/31/cities');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    ## Sub District ##
    public function tryUnauthorizedSubdistrict(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Cities List');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/provinces/31/cities/226/subdistricts');
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedInvalidSubDistrict(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('Authorized user failed: Get Sub District List Using Invalid City ID');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/provinces/xxxxx/cities/xxxxx/subdistricts');
        $I->seeResponseCodeIs(422);
    }

    public function tryAuthorizedSubDistrict(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Get Sub District List');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendGET('/v1/provinces/31/cities/226/subdistricts');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
