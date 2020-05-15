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

<div class="title title_min title_wr-rev d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
    <h2><?= $arParams['TITLE'] ?></h2>
</div>
<div class="write-review d-xs-flex flex-xs-column">
    <p class="write-review__txt">Расскажите о своем опыте работы с компанией</p>
    <a href="<?= $arResult['REVIEW_ADD_URL']?>"
       class="button d-xs-flex justify-content-xs-center align-items-xs-center tdn button button_wr-review button_font-semibold tdn bg-accent-d cl-white">Написать отзыв</a>
</div>


