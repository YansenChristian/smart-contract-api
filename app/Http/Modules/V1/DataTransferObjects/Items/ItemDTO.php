<?php


namespace App\Http\Modules\V1\DataTransferObjects\Items;


use App\Http\Modules\V1\DTO;

class ItemDTO extends DTO
{
    public $item_id;

    public function __construct()
    {
        parent::__construct($this);
    }
}