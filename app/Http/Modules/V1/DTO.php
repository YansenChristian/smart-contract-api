<?php


namespace App\Http\Modules\V1;


abstract class DTO
{
    private $child;
    protected $hiddenFields = [];

    public function __construct($child)
    {
        $this->child = $child;
    }

    public function toArray($withEmptyAttributes = false): array
    {
        $dto = clone $this->child;
        unset($dto->child);

        $dto = get_object_vars($dto);
        $dtoWithAllowableFields = [];
        foreach ($dto as $key => $value) {
            if(!in_array($key, $this->hiddenFields)) {
                $dtoWithAllowableFields[$key] = $value;
            }
        }

        unset($dtoWithAllowableFields['hiddenFields']);

        return ($withEmptyAttributes)
            ? $dtoWithAllowableFields
            : array_filter($dtoWithAllowableFields, function ($value){
                return !is_null($value);
            });
    }
}