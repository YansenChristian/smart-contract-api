<?php


namespace App\Http\Modules\V1\Rules;


use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentValidator extends Rule
{
    public function validatePaymentExistsById($attribute, $value, $parameters)
    {
        return DB::table('payments')
            ->where('id', '=', $value)
            ->exists();
    }
}