<?php


namespace App\Http\Modules\V1;


abstract class DTO
{
    private $child;

    public function __construct($child)
    {
        $this->child = $child;
    }

    public function toArray($withEmptyAttributes = false): array
    {
        $dto = clone $this->child;
        unset($dto->child);

        $dto = get_object_vars($dto);
        return ($withEmptyAttributes)
            ? $dto
            : array_filter($dto, function ($value){
                return !is_null($value);
            });
    }
}