<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

BreadcrumbBuilder::setBreadcrumbs('Гео');

$APPLICATION->IncludeFile(
    $this->GetFolder() . "/company_header.php",
    [
        'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
        'PREVIEW_PICTURE' => $arResult['COMPANY_DATA']['PREVIEW_PICTURE'],
        'SUBSTITUTION_PHONE' => $arResult['COMPANY_DATA']['SUBSTITUTION_PHONE']['VALUE'],
        'COMPANY_CODE' => $arResult['COMPANY_CODE'],
        'COMPANY_ID' => $arResult['COMPANY_DATA']['ID'],
        'TITLE' => 'География работы компании ' . $arResult['COMPANY_DATA']['NAME'],
    ]
); ?>

<div>
    <?php $APPLICATION->IncludeComponent(
        'public:company.work.geography',
        '',
        [
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => 86400,
            'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
        ],
        false
    ); ?>

    <div class="container">
        <?php $APPLICATION->IncludeComponent(
            'public:breadcrumb',
            '',
            [
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => 0,
            ],
            false
        ); ?>
    </div>
</div>
