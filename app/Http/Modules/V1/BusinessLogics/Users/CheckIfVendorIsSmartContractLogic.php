<?php


namespace App\Http\Modules\V1\BusinessLogics\Users;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Users\VendorRepository;

class CheckIfVendorIsSmartContractLogic extends BusinessLogic
{
    protected $vendorRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->vendorRepository = new VendorRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::VendorId' => 'required'
        ]);

        $vendorId = $this->getScope('INPUT::VendorId');
        $conditions = [
            'vendor_id' =>   $vendorId
        ];

        return $this->vendorRepository
            ->vendorExists($conditions);
    }
}