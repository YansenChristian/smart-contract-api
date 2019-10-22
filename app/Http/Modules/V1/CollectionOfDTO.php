<?php


namespace App\Http\Modules\V1;


use Facebook\WebDriver\Exception\IndexOutOfBoundsException;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class CollectionOfDTO
{
    private $listOfDTO;

    public function __construct()
    {
        $this->listOfDTO = [];
    }

    public function get($index = null)
    {
        return is_null($index)
            ? $this->listOfDTO
            : $this->listOfDTO[$index];
    }

    public function add(DTO $dto)
    {
        if(count($this->listOfDTO) == 0) {
            return $this->listOfDTO[] = $dto;
        }

        $randomIndex = mt_rand(0, count($this->listOfDTO) - 1);
        if(get_class($this->listOfDTO[$randomIndex]) == get_class($dto)) {
            return $this->listOfDTO[] = $dto;
        }

        throw new InvalidArgumentException('Cannot add DTO with different data type other than '
            . get_class($this->listOfDTO[$randomIndex]) . ' data type');
    }

    public function remove($index)
    {
        if(!isset($this->listOfDTO[$index])) {
            throw new IndexOutOfBoundsException('Cannot find index ' . $index . ' in DTO Collection');
        }

        unset($this->listOfDTO[$index]);
        return $this->listOfDTO = array_values($this->listOfDTO);
    }

    public function toArray()
    {
        $data = [];
        foreach ($this->listOfDTO as $dto) {
            $data[] = $dto->toArray();
        }

        return $data;
    }
}