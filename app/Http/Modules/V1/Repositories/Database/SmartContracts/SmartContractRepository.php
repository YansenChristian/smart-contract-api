<?php


namespace App\Http\Modules\V1\Repositories\Database\SmartContracts;


use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use Illuminate\Support\Facades\DB;

class SmartContractRepository
{
    public function getSellerCounter($vendorId)
    {
        $selectStatement = [
            'smart_contract_status.name',
            DB::raw('COUNT(rl_smart_contracts.id) AS subtotal')
        ];
        return DB::table('smart_contract_status')
            ->leftJoin('smart_contracts',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id')
            ->groupBy('smart_contract_status.name')
            ->where('vendor_id', '=', $vendorId)
            ->select($selectStatement)
            ->get();
    }

    public function getCounter()
    {
        $selectStatement = [
            'smart_contract_status.name',
            DB::raw('COUNT(rl_smart_contracts.id) AS subtotal')
        ];
        return DB::table('smart_contract_status')
            ->leftJoin('smart_contracts',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id')
            ->groupBy('smart_contract_status.name')
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
            if ($filter['operator'] == 'Between') {
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
            ->join('smart_contract_status',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id')
            ->where('smart_contract_serial', '=', $smartContractSerial)
            ->select($columns)
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

    public function getBuyerSmartContracts($filters, $perPage)
    {
        $columns = [
            'smart_contracts.id',
            'smart_contracts.smart_contract_serial',
            'smart_contracts.on_going_order',
            'smart_contracts.total_order',
            'smart_contract_status.name AS status',
        ];

        $smartContracts = DB::table('smart_contracts')
            ->join('smart_contract_status',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id'
            )
            ->select($columns);

        foreach ($filters as $filter) {
            if ($filter['operator'] == 'Between') {
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

    public function getSmartContractsDetailBySerialNumbers(array $smartContractSerials)
    {
        $columns = [
            'smart_contract_details.*'
        ];

        return DB::table('smart_contracts')
            ->join('smart_contract_details',
                'smart_contracts.id',
                '=',
                'smart_contract_details.smart_contract_id'
            )
            ->whereIn('smart_contracts.smart_contract_serial', $smartContractSerials)
            ->select($columns)
            ->get()
            ->groupBy('smart_contract_id')
            ->toArray();
    }

    public function getSmartContracts($filters, $perPage)
    {
        $columns = [
            'smart_contracts.smart_contract_serial',
            'smart_contracts.created_at AS start_date',
            'smart_contracts.buyer_user_id',
            'smart_contracts.vendor_id',
            'smart_contracts.total_price',
            'smart_contract_status.name AS status',
            'smart_contract_details.order_serial',
        ];

        $smartContracts = DB::table('smart_contracts')
            ->join('smart_contract_details',
                'smart_contracts.id',
                '=',
                'smart_contract_details.smart_contract_id'
            )
            ->join('smart_contract_status',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id'
            )
            ->groupBy([
                'smart_contracts.smart_contract_serial',
                'smart_contracts.created_at',
                'smart_contracts.buyer_user_id',
                'smart_contracts.vendor_id',
                'smart_contracts.total_price',
                'smart_contract_status.name'
            ])
            ->orderBy('smart_contracts.created_at', 'DESC')
            ->select($columns);

        foreach ($filters as $filter) {
            if ($filter['operator'] == 'Between') {
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

    public function getSmartContract($smartContractSerial)
    {
        $columns = [
            'smart_contracts.id',
            'smart_contracts.smart_contract_serial',
            'smart_contracts.buyer_user_id',
            'smart_contracts.vendor_id',
            'smart_contracts.payment_method_id',
            'smart_contracts.item_id',
            'smart_contracts.on_going_order',
            'smart_contracts.total_order',
            'smart_contracts.total_price',
            'smart_contracts.created_at',
            'smart_contract_status.name AS status'
        ];

        return DB::table('smart_contracts')
            ->join('smart_contract_status',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id'
            )
            ->where('smart_contracts.smart_contract_serial', '=', $smartContractSerial)
            ->select($columns)
            ->first();
    }

    public function getByOrderSerial($orderSerial)
    {
        $columns = [
            'smart_contracts.smart_contract_serial',
            'smart_contracts.created_at AS start_date',
            'smart_contracts.buyer_user_id',
            'smart_contracts.vendor_id',
            'smart_contracts.on_going_order',
            'smart_contracts.total_order',
            'smart_contracts.total_price',
        ];

        return DB::table('smart_contracts')
            ->join('smart_contract_details',
                'smart_contracts.id',
                '=',
                'smart_contract_details.smart_contract_id'
            )
            ->select($columns)
            ->where('smart_contract_details.order_serial', '=', $orderSerial)
            ->first();
    }

    public function update($smartContractSerial, array $smartContractData)
    {
        return DB::table('smart_contracts')
            ->where('smart_contracts.smart_contract_serial', '=', $smartContractSerial)
            ->update($smartContractData);
    }
}