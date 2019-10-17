<?php


namespace App\Http\Modules\V1\DataTransferObjects\Users;


class SellerDTO
{
    private $seller_user_id;

    public function toArray($withEmptyAttributes = true): array
    {
        $item = get_object_vars($this);
        return ($withEmptyAttributes)
            ? $item
            : array_filter($item, function ($value){
                return !is_null($value);
            });
    }

    /**
     * @return mixed
     */
    public function getSellerUserId()
    {
        return $this->seller_user_id;
    }

    /**
     * @param mixed $seller_user_id
     */
    public function setSellerUserId($seller_user_id): void
    {
        $this->seller_user_id = $seller_user_id;
    }
}