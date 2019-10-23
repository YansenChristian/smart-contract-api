<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GenerateSmartContractSerialLogic extends BusinessLogic
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
            $randomNumber = $this->generateRandomNumber(100000);

            $smartContractSerial =
                $randomNumber . '/'
                . $smartContractAbbreviation . '/'
                . $createdDate;
        } while ($this->checkIfSerialNumberAlreadyExists($smartContractSerial));

        $this->putScope('DB::SmartContractSerial', $smartContractSerial);
        return $smartContractSerial;
    }

    private function generateRandomNumber($maxNumber)
    {
        $randomNumber = '';
        for ($i = 1; $i < strlen($maxNumber); $i++) {
            $random = mt_rand(0, 9);
            $randomNumber .= (string) $random;
        }

        return $randomNumber;
    }

    private function checkIfSerialNumberAlreadyExists($serialNumber)
    {
        return $this->smartContractRepository
            ->exists(['smart_contract_serial' => $serialNumber]);
    }
}