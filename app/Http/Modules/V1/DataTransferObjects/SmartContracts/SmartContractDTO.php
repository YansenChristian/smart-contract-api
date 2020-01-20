<?php


namespace App\Http\Modules\V1\DataTransferObjects\SmartContracts;


use App\Http\Modules\V1\DTO;

class SmartContractDTO extends DTO
{
    public $id;
    public $smart_contract_serial;
    public $buyer_user_id;
    public $vendor_id;
    public $item_id;
    public $smart_contract_status;
    public $smart_contract_status_id;
    public $payment_method_id;
    public $total_price;
    public $on_going_order;
    public $total_order;
    public $buyer_notes;
    public $created_at;

    public function __construct()
    {
        $this->hiddenFields = [
            'smart_contract_status'
        ];

        parent::__construct($this);
    }

}