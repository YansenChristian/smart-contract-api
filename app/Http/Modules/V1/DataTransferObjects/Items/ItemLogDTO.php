<?php


namespace App\Http\Modules\V1\DataTransferObjects\Items;


class ItemLogDTO
{
    private $item_id;
    private $seller_user_id;
    private $action;

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
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param mixed $item_id
     */
    public function setItemId($item_id): void
    {
        $this->item_id = $item_id;
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

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }
}