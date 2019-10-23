<?php


namespace App\Http\Modules\V1\DataTransferObjects\SmartContracts;


use App\Http\Modules\V1\DTO;

class SmartContractLogDTO extends DTO
{
    public $smart_contract_id;
    public $smart_contract_serial;
    public $smart_contract_status;
    public $information;

    public function __construct()
    {
        parent::__construct($this);
    }
}