<?php
class ApiCest 
{
    public $token;

    public function tryApi(ApiTester $I)
    {
        $I->sendGET('/');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContains("ROUTE_FAILURE");
    }

    public function tryAuthenticate(ApiTester $I)
    {
        if($this->token == null) {
            $I->haveHttpHeader('Content-Type', 'application/json');
            $I->sendPOST(env('API_OAUTH_HOST').'/v2/token', [
                'grant_type' => 'password',
                'username' => env('CODECEPTION_USERNAME'),
                'password' => env('CODECEPTION_PASSWORD'),
                'client_id' => env('CODECEPTION_CLIENT_ID'),
                'client_secret' => env('CODECEPTION_CLIENT_SECRET')
            ]);
            $I->seeResponseCodeIs(200);
            $I->seeResponseIsJson();
            $this->token = $I->grabDataFromResponseByJsonPath('$.access_token')[0];
        }

        return $I;
    }
}