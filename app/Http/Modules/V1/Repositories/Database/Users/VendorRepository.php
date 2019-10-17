<?php


namespace App\Http\Modules\V1\Repositories\Database\Users;


use Illuminate\Support\Facades\DB;

class VendorRepository
{
    public function vendorExists($conditions)
    {
        $sellers = DB::table('smart_contract_vendors');
        foreach ($conditions as $key => $value){
            $sellers->where($key, '=', $value);
        }
        return $sellers->exists();
    }
}