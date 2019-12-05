<?php


namespace App\Http\Modules\V1\BusinessLogics\Users;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Users\VendorRepository;

class DeactivateVendorsLogic extends BusinessLogic
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

        return $this->vendorRepository->deactivateVendors(
            $this->getScope('INPUT::VendorIds')
        );
    }
}