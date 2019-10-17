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
}