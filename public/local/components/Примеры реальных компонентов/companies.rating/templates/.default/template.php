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

<div class="card-wrapper bdrs p-md-3 p-xs-2 bg-white">
    <div class="title title_min title_rating-reviews d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
        <h2>Рейтинг компаний</h2>
    </div>
    <ul class="raiting-companies num-total-list ff-r">
        <?php foreach ($arResult['COMPANIES'] as $key => $company): ?>
            <li class="raiting-companies__d-lg-block d-xs-flex justify-content-xs-betweens flex-xs-wrap">
            <p class="raiting-companies__name pr-xs-1"><?= $company['NAME'] ?></p>
            <div class="raiting-companies__wrap-img-raiting d-xs-flex align-items-xs-center">
                <svg class="raiting-companies__icon">
                    <use xlink:href="/assets/public/images/icon.svg#icon_icon_star"></use>
                </svg>
                <p class="raiting-companies__rait ff-b pos-rel"><?= ($company['REVIEWS_RATE'] > 0)? $company['REVIEWS_RATE'] : 0 ?></p>
            </div>
        </li>
        <?php endforeach ?>
    </ul>
</div>

