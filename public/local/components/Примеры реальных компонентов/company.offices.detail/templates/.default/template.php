<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;
use App\Core\Builders\UrlBuilders\CompanyUrlBuilder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$companyUrl = new CompanyUrlBuilder($arParams['COMPANY_CODE']);
BreadcrumbBuilder::setBreadcrumbs('Офисы', $companyUrl->getCompanySectionPath(PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY));
BreadcrumbBuilder::setBreadcrumbs($arResult['OFFICE']['ADDRESS']);


$APPLICATION->IncludeComponent(
    'public:company.header',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 86400,
        'TITLE' => 'Офис компании ' . $arParams['COMPANY_NAME'] . ': ' . $arResult['OFFICE']['ADDRESS'],
        'PREVIEW_PICTURE' => $arParams['PREVIEW_PICTURE'],
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
]);
?>

<section class="ovh">
    <div class="container">
        <div class="map-offices pos-rel">
            <div id="js-map-offices"
                 data-loader="loadMap"
                 class="map-offices__container lazy-map-offices js-map-offices"
                 data-params="<?=  htmlentities($arResult['OFFICE']['MAP_STR'], ENT_QUOTES, 'UTF-8');?>"
                 data-review-add-url="<?= $arResult['ADD_REVIEW_URL'] ?>">
            </div>
            <div class="map-offices__desc pos-abs">
                <div class="map-content bg-white bdrs js-map-offices-content px-lg-4 py-lg-3 px-md-3 py-md-2 py-xs-2"></div>
            </div>
        </div>
        <?php
            $APPLICATION->IncludeComponent(
                'public:company.offices.list',
                'similar_offices_list',
                [
                    'CACHE_TYPE' => 'A',
                    'CACHE_TIME' => 86400,
                    'COMPANY_ID' => $arParams['COMPANY_ID'],
                    'OFFICE_ID' => $arParams['OFFICE_ID'],
                    'ALL_OFFICES_URL' => $arResult['ALL_OFFICES_URL'],
                ],
                false
            );
        ?>
        <a href="<?= $arResult['ALL_OFFICES_URL'] ?>" class="all-offers cl-accent tdn d-xs-flex d-sm-none justify-content-xs-center align-items-xs-center">Все офисы</a>

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
</section>