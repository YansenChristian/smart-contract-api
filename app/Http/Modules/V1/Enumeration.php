<?php


namespace App\Http\Modules\V1;


abstract class Enumeration
{
    protected static $childClass;

    /**
     * Find constant by it's id
     * @return mixed
     */
    public static function getById($id)
    {
        return call_user_func(self::$childClass.'::getConstants')[--$id];
    }

    /**
     * Get list of constants
     * @return array
     */
    abstract public static function getConstants(): array;
}