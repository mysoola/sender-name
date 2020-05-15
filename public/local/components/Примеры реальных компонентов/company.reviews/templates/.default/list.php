<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

if (isset($arResult['LOCATION_DATA'])) {
    BreadcrumbBuilder::setBreadcrumbs($arResult['LOCATION_DATA']['NAME'], '/' . $arResult['LOCATION_DATA']['CODE'] . '/');
}

BreadcrumbBuilder::setBreadcrumbs('Отзывы');

?>

<div itemscope="itemscope" itemtype="http://schema.org/Product" class="bg-body-bg full-page">

    <?php
    $APPLICATION->IncludeComponent(
        'public:main.city.banner',
        'reviews',
        [
            'CACHE_TIME' => 86400,
            'REVIEWS_LOCATION_NAME' => $arResult['LOCATION_DATA']['PROPERTY_PREPOSITION_NAME_VALUE'],
        ],
        false
    );

    $APPLICATION->IncludeComponent(
        'public:company.reviews.list',
        '',
        [
            'CACHE_TYPE' => 'A',
            'LOCATION_ID' => $arResult['LOCATION_DATA']['ID'] ?? null,
            'LOCATION_CODE' => $arResult['LOCATION_DATA']['CODE'] ?? null,
            'CACHE_TIME' => 900,
            'RATING_COMPONENT' => 'companies.rating',
            'COUNT_REVIEWS' => 20,
        ],
        false
    ); ?>
</div>
