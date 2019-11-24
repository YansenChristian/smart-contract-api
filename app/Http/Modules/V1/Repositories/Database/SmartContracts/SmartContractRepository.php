<?php


namespace App\Http\Modules\V1\Repositories\Database\SmartContracts;


use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use Illuminate\Support\Facades\DB;

class SmartContractRepository
{
    public function getCounter($vendorId)
    {
        $selectStatement = [
            'smart_contract_status.name',
            DB::raw('COUNT(*) AS subtotal')
        ];
        return DB::table('smart_contract_status')
            ->leftJoin('smart_contracts',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id')
            ->groupBy('smart_contract_status_id')
            ->where('vendor_id', '=', $vendorId)
            ->select($selectStatement)
            ->get();
    }

    public function getSellerSmartContracts($filters, $perPage)
    {
        $columns = [
            'smart_contracts.smart_contract_serial',
            'smart_contracts.buyer_user_id',
            'smart_contracts.total_price',
            'smart_contracts.on_going_order',
            'smart_contracts.total_order',
            'smart_contracts.created_at',
            'smart_contract_status.name as status'
        ];

        $smartContracts = DB::table('smart_contracts')
            ->join('smart_contract_status',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id')
            ->select($columns);

        foreach ($filters as $filter) {
            if($filter['operator'] == 'Between') {
                $smartContracts->whereBetween(
                    $filter['column'],
                    $filter['value']
                );
            } else {
                $smartContracts->where(
                    $filter['column'],
                    $filter['operator'],
                    $filter['value']
                );
            }
        }

        return $smartContracts->paginate($perPage);
    }

    public function getSellerSmartContract($smartContractSerial)
    {
        $columns = [
            'smart_contracts.id',
            'smart_contracts.smart_contract_serial',
            'smart_contracts.buyer_user_id',
            'smart_contracts.buyer_notes',
            'smart_contracts.on_going_order',
            'smart_contracts.total_order',
            'smart_contracts.total_price',
            'smart_contracts.created_at',
            'smart_contract_status.name AS status',
        ];

        return DB::table('smart_contracts')
            ->join('smart_contract_status', 'smart_contracts.smart_contract_status_id', '=', 'smart_contract_status.id')
            ->where('smart_contracts.smart_contract_serial', '=', $smartContractSerial)
            ->select($columns)
            ->first();
    }

    public function getBySerialNumber(array $columns, $smartContractSerial)
    {
        return DB::table('smart_contracts')
            ->where('smart_contract_serial', '=', $smartContractSerial)
            ->first();
    }

    public function getDetail($smartContractId)
    {
        $columns = [
            'smart_contract_details.order_serial'
        ];

        return DB::table('smart_contract_details')
            ->join('smart_contracts', 'smart_contracts.id', '=', 'smart_contract_details.smart_contract_id')
            ->where('smart_contracts.id', '=', $smartContractId)
            ->select($columns)
            ->get();
    }

    public function exists(array $conditions)
    {
        $smartContract = DB::table('smart_contracts');
        foreach ($conditions as $column => $value) {
            $smartContract->where($column, '=', $value);
        }
        return $smartContract->exists();
    }

    public function create($smartContracts)
    {
        return DB::table('smart_contracts')
            ->insertGetId($smartContracts);
    }

    public function createDetail($smartContractDetails)
    {
        return DB::table('smart_contract_details')
            ->insert($smartContractDetails);
    }

    public function orderExists($orderSerial)
    {
        return DB::table('smart_contract_details')
            ->where('order_serial', '=', $orderSerial)
            ->exists();
    }

    public function updateStatusBySerialNumber($smartContractSerial, array $status)
    {
        return DB::table('smart_contracts')
            ->where('smart_contract_serial', '=', $smartContractSerial)
            ->update([
                'smart_contract_status_id' => $status['id']
            ]);
    }

    public function getSmartContractFirstOrderSerial($smartContractSerial)
    {
        return DB::table('smart_contracts')
            ->join('smart_contract_details', 'smart_contracts.id', '=', 'smart_contract_details.smart_contract_id')
            ->where('smart_contracts.smart_contract_serial', '=', $smartContractSerial)
            ->select(['order_serial'])
            ->first()
            ->order_serial;
    }

    public function getItemsTotalOrder()
    {
        $columns = [
            'item_id',
            DB::raw('COUNT(item_id) AS total_order')
        ];

        return DB::table('smart_contracts')
            ->select($columns)
            ->groupBy('item_id')
            ->orderByDesc(DB::raw('COUNT(item_id)'))
            ->take(12)
            ->get()
            ->toArray();
    }
}