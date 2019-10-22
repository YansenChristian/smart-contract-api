<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GenerateSmartContractSerial extends BusinessLogic
{
    private $smartContractRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        do {
            $smartContractAbbreviation = 'SC';
            $createdDate = str_replace('-', '/', date('d-m-Y'));
            $randomNumber = $this->generateRandomNumber(1, 100000);

            $smartContractSerial =
                $randomNumber . '/'
                . $smartContractAbbreviation . '/'
                . $createdDate;
        } while ($this->checkIfSerialNumberAlreadyExists($smartContractSerial));

        return $smartContractSerial;
    }

    private function generateRandomNumber($start, $end)
    {
        $randomNumber =  mt_rand($start, $end);
        $gapLength = strlen($randomNumber) - strlen($end);
        for ($i = 0; $i < $gapLength; $i++) {
            $randomNumber = '0' . $randomNumber;
        }

        return $randomNumber;
    }

    private function checkIfSerialNumberAlreadyExists($serialNumber)
    {
        return $this->smartContractRepository
            ->exists(['smart_contract_serial' => $serialNumber]);
    }
}