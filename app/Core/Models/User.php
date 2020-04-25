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

    public function getAllUsers(): array
    {
        $usersObj = UserTable::getList([
            'select' => [
                'ID',
                'NAME',
                'SECOND_NAME',
                'LAST_NAME',
                'PERSONAL_PHONE',
                'EMAIL',
                'ACTIVE',
                'DATE_REGISTER',
            ],
            'order' => [
                'NAME' => 'ASC',
            ],
        ]);

        $arUsers = [];
        while ($user = $usersObj->fetch()) {
            $user['DATE_REGISTER'] = $user['DATE_REGISTER']->toString();
            $user['ACTIVE'] = ($user['ACTIVE'] == 'Y');

            $arUsers[$user['ID']] = $user;
        }

        return $arUsers;
    }
}
