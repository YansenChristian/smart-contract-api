<?php


class AuthVerifyCest
{
    public function tryUnauthorizedSignup(ApiTester $I)
    {
        $I->wantToTest('unauthorized user failed: Get Verify');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->amBearerAuthenticated('xxxxx');
        $I->sendGET('/v1/auth/verify/xxxxx');
        $I->seeResponseCodeIs(404);
    }
}
