<?php


namespace App\Http\Modules\V1\DataTransferObjects\Users;


use App\Http\Modules\V1\DTO;

class VendorDTO extends DTO
{
    public $id;

    public function __construct()
    {
        parent::__construct($this);
    }
}