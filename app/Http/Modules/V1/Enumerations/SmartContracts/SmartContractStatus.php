<?php


namespace App\Http\Modules\V1\Enumerations\SmartContracts;


use App\Http\Modules\V1\Enumeration;

class SmartContractStatus extends Enumeration
{
    const WAITING = [
        'id' => 1,
        'name' => 'WAITING',
        'description' => 'SmartContracts/status.WAITING'
    ];

    const APPROVED = [
        'id' => 2,
        'name' => 'APPROVED',
        'description' => 'SmartContracts/status.APPROVED'
    ];

    const REJECTED = [
        'id' => 3,
        'name' => 'REJECTED',
        'description' => 'SmartContracts/status.REJECTED'
    ];

    const CANCELED = [
        'id' => 4,
        'name' => 'CANCELED',
        'description' => 'SmartContracts/status.CANCELED'
    ];

    const IN_PROGRESS = [
        'id' => 5,
        'name' => 'IN_PROGRESS',
        'description' => 'SmartContracts/status.IN_PROGRESS'
    ];

    const ENDED = [
        'id' => 6,
        'name' => 'ENDED',
        'description' => 'SmartContracts/status.ENDED'
    ];

    /**
     * Get list of constants
     * @return array
     */
    public static function getConstants(): array
    {
        Enumeration::$childClass = self::class;
        return [
            self::WAITING,
            self::APPROVED,
            self::REJECTED,
            self::IN_PROGRESS,
            self::CANCELED,
            self::ENDED
        ];
    }

    public static function getByName($statusName): array
    {
        $indexOfEnum = array_search(
            $statusName,
            array_column(self::getConstants(), 'name')
        );
        return self::getById(--$indexOfEnum);
    }
}