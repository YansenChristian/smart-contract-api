<?php


namespace App\Http\Modules\V1\DataTransferObjects\Users;


use App\Http\Modules\V1\DTO;

class SellerDTO extends DTO
{
    public $seller_user_id;

    public function __construct()
    {
        parent::__construct($this);
    }
}