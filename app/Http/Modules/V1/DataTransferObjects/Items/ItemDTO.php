<?php


namespace App\Http\Modules\V1\DataTransferObjects\Items;


class ItemDTO
{
    private $item_id;

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
     * @param mixed $id
     */
    public function setItemId($id): void
    {
        $this->item_id = $id;
    }
}