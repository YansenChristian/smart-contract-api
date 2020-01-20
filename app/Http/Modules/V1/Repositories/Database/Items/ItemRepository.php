<?php


namespace App\Http\Modules\V1\Repositories\Database\Items;


use Illuminate\Support\Facades\DB;

class ItemRepository
{
    public function getById($itemId)
    {
        return DB::table('smart_contract_items')
            ->where('item_id', '=', $itemId)
            ->first();
    }

    public function exists(array $conditions)
    {
        $item = DB::table('smart_contract_items');
        foreach ($conditions as $column => $value) {
            $item->where($column, '=', $value);
        }
        return $item->exists();
    }

    public function add(array $items)
    {
        return DB::table('smart_contract_items')
            ->insertGetId($items);
    }

    public function update(array $itemData, array $conditions)
    {
        $item = DB::table('smart_contract_items');
        foreach ($conditions as $column => $value) {
            $item->where($column, '=', $value);
        }
        return $item->update($itemData);
    }

    public function softDelete($itemId)
    {
        return DB::table('smart_contract_items')
            ->where('item_id', '=', $itemId)
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }

    public function getExistingItemIds($itemIds)
    {
        return DB::table('smart_contract_items')
            ->whereIn('item_id', $itemIds)
            ->select([
                'item_id',
                'deleted_at'
            ])
            ->get();
    }

    public function getExistingItems($itemIds)
    {
        return DB::table('smart_contract_items')
            ->whereIn('item_id', $itemIds)
            ->select([
                'item_id',
                'deleted_at'
            ])
            ->paginate(10);
    }

    public function getVendorTotalItem($vendorId)
    {
        return DB::table('smart_contract_items')
            ->where('vendor_id', '=', $vendorId)
            ->select([
                DB::raw('COUNT(item_id) AS total_product')
            ])
            ->get();
    }
}