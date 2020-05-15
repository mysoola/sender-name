<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
    'public:company.offices.detail',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 86400,
        'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
        'OFFICE_ID' => $arResult['OFFICE_ID'],
        'PAGE' => PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY,
        'PREVIEW_PICTURE' => $arResult['COMPANY_DATA']['PREVIEW_PICTURE'],
        'COMPANY_CODE' => $arResult['COMPANY_CODE'],
        'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
    ],
    false
);
