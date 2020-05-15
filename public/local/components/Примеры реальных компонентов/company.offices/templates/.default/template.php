<?php

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
?>

<?php if ($arResult['COMPANY_OFFICES_COUNT']): ?>
    <div class="card-wrapper bg-white bdrs p-md-3 p-xs-2 mb-md-3">
        <div class="title title_min d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
            <h2>Офисы</h2>
            <a href="<?= PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY ?>/"
               class="all-offers all-offers_min cl-accent-d tdn d-xs-none d-sm-block">
                <?= $arResult['COMPANY_OFFICES_COUNT'] ?>
                <?= $arResult['COUNT_OFFICES'] ?></a>
        </div>

        <?php $companyOfficesJson = json_encode($arResult['COMPANY_OFFICES_FOR_MAP']); ?>
        <div class="map js-map-company" data-params="<?=  htmlentities($companyOfficesJson, ENT_QUOTES, 'UTF-8');?>">
            <div class="relation relation_map pos-rel">
                <div class="relation__offset"></div>
                <div class="relation__content pos-abs w-100 h-100">
                    <div id="js-map-container" data-loader="loadMap"
                         class="map-container js-map-container h-100 lazy-map"></div>
                </div>
            </div>
        </div>
    </div>
    <a href="<?= PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY ?>/"
       class="all-offers all-offers_mobile cl-accent tdn d-xs-flex d-sm-none mt-xs-2 mt-sm-0 justify-content-xs-center align-items-xs-center">
        <?= $arResult['COMPANY_OFFICES_COUNT'] ?>
        <?= $arResult['COUNT_OFFICES'] ?>
    </a>
<?php endif ?>