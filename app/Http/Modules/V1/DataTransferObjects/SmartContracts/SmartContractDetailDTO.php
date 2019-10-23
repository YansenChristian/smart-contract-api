<?php


namespace App\Http\Modules\V1\DataTransferObjects\SmartContracts;


use App\Http\Modules\V1\DTO;

class SmartContractDetailDTO extends DTO
{
    public $smart_contract_id;
    public $order_serial;

    public function __construct()
    {
        parent::__construct($this);
    }
}