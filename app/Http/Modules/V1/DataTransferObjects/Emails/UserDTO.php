<?php


namespace App\Http\Modules\V1\DataTransferObjects\Emails;


use App\Http\Modules\V1\DTO;

class UserDTO extends DTO
{
    public $name;
    public $email;

    public function __construct()
    {
        parent::__construct($this);
    }
}