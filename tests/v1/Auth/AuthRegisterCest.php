<?php


class AuthRegisterCest
{

    private $registerData;

    public function __construct()
    {
        $this->registerData = [
            'email'             => 'codeceptionRegisterTest'. str_random() .'@yopmail.com',
            'name'              => 'codeception register test'.date('d-m-y H:i:s'),
            'password'          => '12345678',
            'newboarding'       => 'true',
            'source'            => 'Landing Page',
            'telepon_prefix'    => '+62'
        ];
    }

    public function tryAuthorizedAuthCest(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: success created data register');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/register', $this->registerData);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryRequestInvalidAuthCest(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Bad Request Data');
        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v1/register');
        $I->seeResponseCodeIs(500);
    }
}
