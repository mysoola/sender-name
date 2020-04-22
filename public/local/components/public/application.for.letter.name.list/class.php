<?php

namespace Mt\Components;

use App\Core\Models\IblockModel;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class ApplicationForLetterNameListComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {

            global $USER;
            if ($USER->IsAuthorized()) {
                $this->arResult['NAMES'] = $this->getNames();
            } else {
                LocalRedirect('/404.php');
            }

            $this->includeComponentTemplate();
        }
    }

    protected function getNames(): array
    {
        $iBlockModel = new IblockModel();

        global $USER;

        $result = $iBlockModel->findByParams(APPLICATIONS_FOR_LETTER_NAME_IBLOCK_CODE, [
            'filter' => [
                'PROPERTY_USER_ID' => $USER->GetID(),
            ],
            'fields' => [
                'NAME',
                'TIMESTAMP_X',
                'DATE_CREATE',
            ],
            'props' => [
                'STATUS',
            ],
        ]);

        return (!empty($result)) ? $result : [];
    }
}