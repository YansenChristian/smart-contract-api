<?php
/**
 * Created by PhpStorm.
 * User: ralalian
 * Date: 22/05/19
 * Time: 14.35
 */

class UsersFcmCest
{
    private $usersFcmData;

    public function __construct()
    {
        $this->usersFcmData = [
            'device_id' => sprintf('%s-%s', 'device_id', str_random()),
            'fcm_token' => sprintf('%s-%s', 'fcm_token', str_random())
        ];
    }

    public function tryUnauthorizedUsersFcm(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Delete Users Fcm');

        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendDELETE('/v2/users-fcm', $this->usersFcmData);
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('AUTHENTICATION_FAILURE');
    }

    public function tryAuthorizedInsertUsersFcm(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user success: Insert Users Fcm');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendPOST('/v2/users-fcm', $this->usersFcmData);
        $I->seeResponseCodeIs(204);
    }

    public function tryAuthorizedDeleteNotFoundUsersFcm(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Delete Not Found Users Fcm');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendDELETE('/v2/users-fcm', ['device_id' => 'xxxx', 'fcm_token' => 'xxxx']);
        $I->seeResponseCodeIs(204);
    }

    public function tryAuthorizedDeleteFoundUsersFcm(ApiTester $I, ApiCest $api)
    {
        $I->wantToTest('authorized user failed: Delete Found Users Fcm');

        $I = $api->tryAuthenticate($I);
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated($api->token);
        $I->sendDELETE('/v2/users-fcm', $this->usersFcmData);
        $I->seeResponseCodeIs(204);
    }
}
