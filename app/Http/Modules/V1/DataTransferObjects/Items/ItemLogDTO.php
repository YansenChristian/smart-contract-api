<?php


namespace App\Http\Modules\V1\DataTransferObjects\Items;


use App\Http\Modules\V1\DTO;

class ItemLogDTO extends DTO
{
    public $item_id;
    public $seller_user_id;
    public $action;

    public function __construct()
    {
        parent::__construct($this);
    }
}