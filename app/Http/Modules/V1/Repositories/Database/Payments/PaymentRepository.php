<?php


namespace App\Http\Modules\V1\Repositories\Database\Payments;


use Illuminate\Support\Facades\DB;

class PaymentRepository
{
    public function getById($id)
    {
        return DB::table('payments')
            ->where('id', '=', $id)
            ->first();
    }
}