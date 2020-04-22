<?php


namespace App\Core\Models;

use Bitrix\Main\UserTable;

/**
 * Class User
 * @package App\Core\Models
 */
class User
{
    public static function getUserData(int $userId): array
    {
        $arUser = UserTable::getList([
            'filter' =>
                [
                    'ID' => $userId,
                ],
            'select' => [
                'ID',
                'NAME',
                'SECOND_NAME',
                'LAST_NAME',
                'EMAIL',
                'PASSWORD',
            ]
        ])->fetch();

        return $arUser;
    }
}
