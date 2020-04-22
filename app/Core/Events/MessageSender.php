<?php


namespace App\Core\Events;

use App\Core\Controllers\ApplicationForLetterNameController;
use App\Core\Models\IblockModel;

class MessageSender
{
    public function send($arFields)
    {
        $iBlockModel = new IblockModel();

        if ($arFields['IBLOCK_ID'] == $iBlockModel->getIblockIdByCode(APPLICATIONS_FOR_LETTER_NAME_IBLOCK_CODE)) {
            $applicationForLetterNameController = new ApplicationForLetterNameController();
            $applicationForLetterNameController->sendingNameStatusFromEvent($arFields);
        }
    }
}