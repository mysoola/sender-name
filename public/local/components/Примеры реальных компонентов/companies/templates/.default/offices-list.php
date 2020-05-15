<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

BreadcrumbBuilder::setBreadcrumbs('Офисы');

$this->setFrameMode(true); ?>

<?php $APPLICATION->IncludeFile(
    $this->GetFolder() . "/company_header.php",
    [
        'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
        'PREVIEW_PICTURE' => $arResult['COMPANY_DATA']['PREVIEW_PICTURE'],
        'SUBSTITUTION_PHONE' => $arResult['COMPANY_DATA']['SUBSTITUTION_PHONE']['VALUE'],
        'COMPANY_CODE' => $arResult['COMPANY_CODE'],
        'COMPANY_ID' => $arResult['COMPANY_DATA']['ID'],
        'TITLE' => 'Офисы компании ' . $arResult['COMPANY_DATA']['NAME'],
    ]
); ?>

<section>
    <div class="container">
        <?php $APPLICATION->IncludeComponent(
            'public:company.offices.list',
            '',
            [
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => 86400,
                'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
            ],
            false
        ); ?>
    </div>
</section>