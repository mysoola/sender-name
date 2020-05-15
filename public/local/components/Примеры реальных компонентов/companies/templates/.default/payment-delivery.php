<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeFile(
    $this->GetFolder() . "/company_header.php",
    [
        'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
        'PREVIEW_PICTURE' => $arResult['COMPANY_DATA']['PREVIEW_PICTURE'],
        'SUBSTITUTION_PHONE' => $arResult['COMPANY_DATA']['SUBSTITUTION_PHONE']['VALUE'],
        'COMPANY_CODE' => $arResult['COMPANY_CODE'],
        'COMPANY_ID' => $arResult['COMPANY_DATA']['ID'],
        'TITLE' => 'Условия оплаты и доставки заказов, размещенных в компании ' . $arResult['COMPANY_DATA']['NAME'],
    ]
);

$APPLICATION->IncludeComponent(
    'public:company.payment.delivery',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 86400,
        'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
        'PAYMENT_TYPES' => $arResult['COMPANY_DATA']['PAYMENT_TYPES'],
        'INSTALLMENT' => [
            'GRANT_INSTALLMENT_NAME' => $arResult['COMPANY_DATA']['GRANT_INSTALLMENT']['NAME'],
            'IS_GRANT_INSTALLMENT' => ($arResult['COMPANY_DATA']['GRANT_INSTALLMENT']['VALUE'] === 'Y'),
            'TERMS' => $arResult['COMPANY_DATA']['TERMS_INSTALLMENT'],
        ],
        'REQUIREMENTS' => $arResult['COMPANY_DATA']['REQUIREMENTS_INSTALLMENT'],
        'PARTNER_BANKS' => $arResult['COMPANY_DATA']['PARTNER_BANKS'],
        'PREPAYMENT' => [
            'PREPAYMENT_TYPE' => $arResult['COMPANY_DATA']['PREPAYMENT_TYPE'],
            'PREPAYMENT_VALUE' => $arResult['COMPANY_DATA']['PREPAYMENT_VALUE'],
            'PREPAYMENT_COMMENT' => $arResult['COMPANY_DATA']['PREPAYMENT_COMMENT'],
        ],
    ],
    false
); ?>


