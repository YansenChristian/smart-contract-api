<?php


namespace App\Http\Modules\V1\DataTransferObjects\Carts;


use App\Http\Modules\V1\DTO;

class CartDTO extends DTO
{
    public $cart_id;

    public function __construct()
    {
        parent::__construct($this);
    }
}