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
$this->setFrameMode(true); ?>

<?php if ($arResult['OFFICES']): ?>
    <ul class="list-offices ff-r bdrs mt-md-3 mb-md-5 mt-xs-2 mb-xs-3">
        <?php foreach ($arResult['OFFICES'] as $office): ?>
            <li itemprop="department" itemscope itemtype="http://schema.org/Organization" class="list-offices__item bg-white">
                <div class="o-title pos-rel bd-bottom-tbl d-xs-flex align-items-xs-center px-xl-4 py-xl-5 px-lg-3 py-lg-4 px-xs-2 py-xs-2">
                    <div class="o-title__wrapp">
                        <a href="<?= $office['OFFICE_URL']?>" class="o-title__title link ff-b tdn d-xs-block"><span itemprop="name"><?= $office['NAME']?></span></a>
                        <a href="<?= $office['OFFICE_URL']?>" class="o-title__link link-accent tdn ff-l">Подробнее</a></div>
                </div>
                <div class="o-info o-info_address pos-rel bd-bottom-tbl px-lg-3 py-lg-4 p-xs-2" >
                    <?php if ($office['CITY']): ?>
                    <p class="o-info__title cl-link">Адрес</p>
                    <p class="o-info__cont" itemprop="address"><?= ($office['CITY'])? $office['CITY'] : '' ?><?= ($office['STREET'])? ', ' . $office['STREET']: '' ?><?= ($office['BUILDING'])? ', дом ' . $office['BUILDING'] : '' ?><?= ($office['OFFICE'])? ', офис ' . $office['OFFICE'] : ''?></p><!-- для микроразметки PostalAddress-->
                    <?/*<meta itemprop="streetAddress" content="<?= $office['STREET']?>, <?= $office['BUILDING']?>" />
                    <meta itemprop="addressLocality" content="<?= $office['CITY']?>" />*/?>
                    <?php endif; ?>
                </div>

                <div class="o-info o-info_phone pos-rel bd-bottom-tbl px-lg-3 py-lg-4 p-xs-2">
                    <p class="o-info__title cl-link">Телефон</p>
                    <p class="o-info__cont ff-b" itemprop="telephone"><?= $office['PHONE']?></p>
                </div>
                <div class="o-info o-info_schedule pos-rel bd-bottom-tbl px-lg-3 py-lg-4 p-xs-2">
                    <!-- для микроразметки Store-->
                    <?/*
                    <meta itemprop="image" content="/assets/public/images/rehau-new.png" />
                    <meta itemprop="telephone" content="<?= $office['PHONE']?>" />
                    <meta itemprop="name" content="<?= $office['NAME']?>" />
                    <meta itemprop="address" content="<?= $office['CITY']?>, <?= $office['STREET']?>, дом <?= $office['BUILDING']?><?= ($office['OFFICE'])? ', офис ' . $office['OFFICE'] : ''?>" />*/?>
                    <p class="o-info__title cl-link">График работы</p>
                    <p class="o-info__cont" ><?= $office['WORK_TIME']?></p> <!-- itemprop="openingHours" -->
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <?php $APPLICATION->IncludeComponent(
        'public:not.found',
        '',
        [
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => 0,
        ],
        false
    ); ?>
<?php endif; ?>

<?php $APPLICATION->IncludeComponent(
    'public:breadcrumb',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 0,
    ],
    false
); ?>
