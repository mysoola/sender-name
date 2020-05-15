<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'public:company.header',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 86400,
        'COMPANY_NAME' => $arParams['NAME'],
        'TITLE' => $arParams['TITLE'],
        'PREVIEW_PICTURE' => $arParams['PREVIEW_PICTURE'],
        'SHORT_COMPANY_NAME' => $arParams['SHORT_COMPANY_NAME'],
        'SUBSTITUTION_PHONE' => $arParams['SUBSTITUTION_PHONE'],
        'COMPANY_CODE' => $arParams['COMPANY_CODE'],
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
    'COMPANY_CODE' => $arParams['COMPANY_CODE'],
    'COMPANY_ID' => $arParams['COMPANY_ID'],
    'PAGE' => $arParams['PAGE'],
    'STRUCTURE_ID' => (isset($arParams['STRUCTURE_ID']))? $arParams['STRUCTURE_ID'] : null,
]);
