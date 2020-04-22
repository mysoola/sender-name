<?php

namespace App\Core\Controllers;

use App\Core\Models\IblockModel;
use App\Core\Models\User;

class ApplicationForLetterNameController
{
    const SENDER_NAME_EVENT = 'SENDER_NAME_STATUS';

    public function save(array $params): bool
    {
        $iBlockModel = new IblockModel();

        $falseStatusPropertyId = $iBlockModel->getPropertyEnumIdByXmlId(
            $iBlockModel->getIblockIdByCode(APPLICATIONS_FOR_LETTER_NAME_IBLOCK_CODE),
            'STATUS',
            'FALSE'
        );

        global $USER;

        $result = $iBlockModel->addOrUpdate(
            [
                'NAME' => $params['NAME'],
            ],
            [
                'NAME' => $params['NAME'],
            ],
            [
                'STATUS' => $falseStatusPropertyId,
                'USER_ID' => $USER->GetID(),
            ],
            APPLICATIONS_FOR_LETTER_NAME_IBLOCK_CODE
        );

        return ($result) ? true : false;
    }

    public function sendingNameStatusFromEvent(array $arFields)
    {
        $iBlockModel = new IblockModel();

        $element = $iBlockModel->findByParams(APPLICATIONS_FOR_LETTER_NAME_IBLOCK_CODE, [
            'filter' => [
                'ID' => $arFields['ID'],
            ],
            'fields' => [
                'NAME',
            ],
            'props' => [
                'STATUS',
                'USER_ID',
            ],
        ]);

        $element = $userId = array_shift($element);
        $userId = $element['PROPERTY_USER_ID_VALUE'];

        $userData = User::getUserData($userId);

        $arEventFields = [
            'USER_EMAIL' => $userData['EMAIL'],
            'USER_NAME' => $userData['NAME'],
            'SENDER_NAME' => $element['NAME'],
            'STATUS' => $element['PROPERTY_STATUS_VALUE'],
        ];

        \CEvent::Send(self::SENDER_NAME_EVENT, SITE_ID, $arEventFields);
    }
}