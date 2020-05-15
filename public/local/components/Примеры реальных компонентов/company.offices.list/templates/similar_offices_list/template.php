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

<?php if ($arResult['OFFICES']): ?>
    <div class="title title_min title_offices d-xs-flex justify-content-xs-between align-items-xs-center mt-md-3 mt-xs-2 mb-md-0 mb-xs-2 bg-white bd-bottom px-md-3 py-md-4 px-xs-2 py-xs-3">
        <h2>Другие офисы компании </h2>
        <a href="<?= $arParams['ALL_OFFICES_URL'] ?>" class="all-offers cl-accent-d tdn d-xs-none d-sm-block">Все офисы</a>
    </div>
    <ul class="list-offices list-offices_map ff-r mb-md-5 mb-xs-3">
        <?php foreach ($arResult['OFFICES'] as $office): ?>
            <li itemscope itemtype="http://schema.org/Organization" class="list-offices__item bg-white">
                <div class="o-title pos-rel bd-bottom-tbl d-xs-flex align-items-xs-center px-xl-4 py-xl-5 px-lg-3 py-lg-4 px-xs-2 py-xs-2">
                    <div class="o-title__wrapp">
                        <a itemprop="name" href="<?= $office['OFFICE_URL']?>" class="o-title__title link ff-b tdn d-xs-block"><?= $office['NAME']?></a>
                        <a href="<?= $office['OFFICE_URL']?>" class="o-title__link link-accent tdn ff-l">Подробнее</a></div>
                </div>
                <div class="o-info o-info_address pos-rel bd-bottom-tbl px-lg-3 py-lg-4 p-xs-2" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                    <p class="o-info__title cl-link">Адрес</p>
                    <p class="o-info__cont"><?= $office['CITY']?>, <?= $office['STREET']?>, дом <?= $office['BUILDING']?><?= ($office['OFFICE'])? ', офис ' . $office['OFFICE'] : ''?></p><!-- для микроразметки PostalAddress-->
                    <meta itemprop="streetAddress" content="<?= $office['STREET']?>, <?= $office['BUILDING']?>" />
                    <meta itemprop="addressLocality" content="<?= $office['CITY']?>" />
                </div>
                <div class="o-info o-info_phone pos-rel bd-bottom-tbl px-lg-3 py-lg-4 p-xs-2">
                    <p class="o-info__title cl-link">Телефон</p>
                    <p itemprop="telephone" class="o-info__cont ff-b"><?= $office['PHONE']?></p>
                </div>
                <div class="o-info o-info_schedule pos-rel bd-bottom-tbl px-lg-3 py-lg-4 p-xs-2" itemscope itemtype="http://schema.org/Store">
                    <!-- для микроразметки Store-->
                    <meta itemprop="image" content="/assets/public/images/rehau-new.png" />
                    <meta itemprop="telephone" content="<?= $office['PHONE']?>" />
                    <meta itemprop="name" content="<?= $office['NAME']?>" />
                    <meta itemprop="address" content="<?= $office['CITY']?>, <?= $office['STREET']?>, дом <?= $office['BUILDING']?><?= ($office['OFFICE'])? ', офис ' . $office['OFFICE'] : ''?>" />
                    <p class="o-info__title cl-link">График работы</p>
                    <p itemprop="openingHours" class="o-info__cont"><?= $office['WORK_TIME']?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>