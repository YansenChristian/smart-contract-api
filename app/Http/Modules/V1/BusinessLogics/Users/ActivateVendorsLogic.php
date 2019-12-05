<?php


namespace App\Http\Modules\V1\BusinessLogics\Users;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Users\VendorRepository;

class ActivateVendorsLogic extends BusinessLogic
{
    private $vendorRepository;

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
            'INPUT::VendorIds'
        ]);

        $existingVendors = $this->vendorRepository->getByIds($this->getScope('INPUT::VendorIds'));
        $existingVendorIds = $existingVendors->pluck('vendor_id')->toArray();

        $this->vendorRepository->activateVendors($existingVendorIds);

        $newVendorIds = array_diff($this->getScope('INPUT::VendorIds'), $existingVendorIds);
        $vendorData = [];
        array_walk($newVendorIds, function ($id) use (&$vendorData){
             $vendorData[] = ['vendor_id' => $id];
        });

        return $this->vendorRepository->insert($vendorData);
    }
}