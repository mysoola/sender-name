<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


$APPLICATION->IncludeComponent(
    'public:company.header',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 86400,
        'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
        'PREVIEW_PICTURE' => $arResult['COMPANY_DATA']['PREVIEW_PICTURE'],
        'SHORT_COMPANY_NAME' => $arResult['COMPANY_DATA']['SHORT_COMPANY_NAME']['~VALUE'],
        'SUBSTITUTION_PHONE' => $arResult['COMPANY_DATA']['SUBSTITUTION_PHONE']['VALUE'],
    ],
    false
);


$APPLICATION->IncludeComponent("bitrix:menu", "public-company-top-menu", [
    'ROOT_MENU_TYPE' => 'company',
    'MAX_LEVEL' => 1,
    'USE_EXT' => 'N',
    'DELAY' => 'N',
    'ALLOW_MULTI_SELECT' => 'N',
    'MENU_CACHE_TYPE' => 'A',
    'MENU_CACHE_TIME' => 8600,
    'MENU_CACHE_USE_GROUPS' => 'Y',
    'MENU_CACHE_GET_VARS' => '',
    'COMPANY_CODE' => $arResult['COMPANY_CODE'],
]);
