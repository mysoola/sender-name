<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

BreadcrumbBuilder::setBreadcrumbs('Отзывы');

$APPLICATION->IncludeFile(
    $this->GetFolder() . "/company_header.php",
    [
        'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
        'PREVIEW_PICTURE' => $arResult['COMPANY_DATA']['PREVIEW_PICTURE'],
        'SUBSTITUTION_PHONE' => $arResult['COMPANY_DATA']['SUBSTITUTION_PHONE']['VALUE'],
        'COMPANY_CODE' => $arResult['COMPANY_CODE'],
        'COMPANY_ID' => $arResult['COMPANY_DATA']['ID'],
        'TITLE' => 'Отзывы о компании ' . $arResult['COMPANY_DATA']['NAME'],
    ]
);

$APPLICATION->IncludeComponent(
    'public:company.reviews.list',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 900,
        'COMPANY_CODE' => $arResult['COMPANY_CODE'],
        'COMPANY_ID' => (int) $arResult['COMPANY_DATA']['ID'],
        'RATING_COMPONENT' => 'company.rating.reviews',
        'COUNT_REVIEWS' => 20,
        'ADVERTISING_BANNERS_SECTION' => PUBLIC_ADVERTISING_BANNERS_COMPANY_SECTION,
    ],
    false
);
