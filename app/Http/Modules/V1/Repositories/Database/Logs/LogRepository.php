<?php


namespace App\Http\Modules\V1\Repositories\Database\Logs;


use Illuminate\Support\Facades\DB;

class LogRepository
{
    public function addItemLog(array $logData)
    {
        return DB::table('smart_contract_item_logs')
            ->insert($logData);
    }

    public function addSmartContractLog(array $logData)
    {
        return DB::table('smart_contract_logs')
            ->insert($logData);
    }

    public function getSmartContractLog($smartContractSerial)
    {
        return DB::table('smart_contract_logs')
            ->where('smart_contract_serial', '=', $smartContractSerial)
            ->get();
    }
}