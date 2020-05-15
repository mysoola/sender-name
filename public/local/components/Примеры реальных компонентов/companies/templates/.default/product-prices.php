<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'public:company.product.prices',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 3600,
        'COMPANY_CODE' => $arResult['COMPANY_CODE'],
        'PRODUCT_CODE' => $arResult['PRODUCT_CODE'],
        'STRUCTURE_ID' => $arResult['STRUCTURE_ID'],
        'COMPANY_ID' => $arResult['COMPANY_DATA']['ID'],
        'SUBSTITUTION_PHONE' => $arResult['COMPANY_DATA']['SUBSTITUTION_PHONE']['VALUE'],
        'PREVIEW_PICTURE' => $arResult['COMPANY_DATA']['PREVIEW_PICTURE'],
        'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
        'PAYMENT_TYPES' => $arResult['COMPANY_DATA']['PAYMENT_TYPES']['VALUE'],
    ],
    false
);
