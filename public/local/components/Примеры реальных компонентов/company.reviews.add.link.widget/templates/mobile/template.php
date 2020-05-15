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

<a href="<?= $arResult['REVIEW_ADD_URL'] ?>"
   class="all-offers all-offers_blue-bd cl-accent tdn <?= ($arParams['CSS_CLASS'])? $arParams['CSS_CLASS'] : '' ?> justify-content-xs-center align-items-xs-center">
    <?= $arParams['TITLE'] ?>
</a>
