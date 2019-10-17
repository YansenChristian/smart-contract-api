<?php

namespace App;

use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'initial',
        'alias',
        'type',
        'vendor_id',
        'access_group_id',
        'confirmation_code',
        'email',
        'password',
        'subscribe_news',
        'vendor_status',
        'phone',
        'confirmed',
        'handphone',
        'address',
        'province_id',
        'city_id',
        'subdistrict_id',
        'postal_code',
        'cp_name',
        'cp_phone',
        'cp_email',
        'source',
        'vendor_description',
        'parent_category_vendor',
        'image_profile',
        'default_shipping_address_id',
        'default_billing_address_id',
        'npwp',
        'npwp_file',
        'company_name',
        'company_field',
        'company_phone',
        'company_type',
        'step_onboarding'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'confirmation_code',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function create(array $attributes = [])
    {
        $model = new static($attributes);

        $model->save();

        return $model;
    }
}
