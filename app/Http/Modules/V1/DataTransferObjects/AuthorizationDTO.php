<?php


namespace App\Http\Modules\V1\DataTransferObjects;


class AuthorizationDTO
{
    private $bearerToken;

    /**
     * @return mixed
     */
    public function getBearerToken()
    {
        return $this->bearerToken;
    }

    /**
     * @param mixed $bearerToken
     */
    public function setBearerToken($bearerToken): void
    {
        $this->bearerToken = $bearerToken;
    }
}