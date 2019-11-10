<?php


namespace App\Http\Modules\V1\DataTransferObjects\Auth;


use App\Http\Modules\V1\DTO;

class AuthorizationDTO extends DTO
{
    public $bearer;
    public $access_token;

    public function __construct()
    {
        parent::__construct($this);
    }
}