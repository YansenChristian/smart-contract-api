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

    public function getVendors($perPage, $keyword)
    {
        return DB::table('smart_contract_vendors')
            ->where('smart_contract_vendors.name', 'LIKE', '%'.$keyword.'%')
            ->paginate($perPage);
    }

    public function insert(array $vendors)
    {
        return DB::table('smart_contract_vendors')
            ->insert($vendors);
    }

    public function deactivateVendors(array $vendorIds)
    {
        return DB::table('smart_contract_vendors')
            ->whereIn('smart_contract_vendors.vendor_id', $vendorIds)
            ->update(['smart_contract_vendors.deleted_at' => date('Y-m-d H:i:s')]);
    }

    public function activateVendors(array $vendorIds)
    {
        return DB::table('smart_contract_vendors')
            ->whereIn('smart_contract_vendors.vendor_id', $vendorIds)
            ->update(['smart_contract_vendors.deleted_at' => null]);
    }

    public function getByIds(array $vendorIds)
    {
        return DB::table('smart_contract_vendors')
            ->whereIn('smart_contract_vendors.vendor_id', $vendorIds)
            ->get();
    }
}