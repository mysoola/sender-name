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

<?php if ($arResult['SERVICES']): ?>
    <div class="card-wrapper bdrs p-md-3 p-xs-2 bg-white mt-md-3 mt-xs-2">
        <div class="title title_min d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
            <h2>Услуги</h2>
        </div>
        <ul class="card-list card-list_col-3 card-list_disc" itemprop="makesOffer">
            <?php foreach ($arResult['SERVICES'] as $material => $services): ?>
                <?php foreach ($services as $service): ?>
                    <li><?= $service['SERVICE_NAME']?></li>
                <?php endforeach; ?>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>