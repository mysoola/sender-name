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

<header class="section bd-top bd-bottom bg-white">
    <div class="container">
        <div class="card-company d-xs-flex align-items-xs-center py-lg-5 py-md-4 py-xs-3">
            <div class="card-company__left">
                <div class="card-company-brand">
                    <div class="card-company-brand__wrap-img">
                        <?php if ($arResult['PREVIEW_PICTURE']): ?>

                            <div class="card-company-brand__img-cont">

                                <?php if (isset($arResult['COMPANY_ABOUT_URL'])): ?>
                                    <a href="<?= $arResult['COMPANY_ABOUT_URL'] ?>">
                                        <?/*<meta itemprop="image" content="<?= SITE_SERVER_NAME . $arResult['PREVIEW_PICTURE'] ?>">
                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                        */?>
                                        <meta itemprop="image" content="<?= SITE_SERVER_NAME . $arResult['PREVIEW_PICTURE'] ?>">
                                        <img src="<?= $arResult['PREVIEW_PICTURE'] ?>"
                                             class="card-company-brand__img bdrs" alt="<?= $arParams['TITLE'] ? $arParams['TITLE'] : 'Организация' ?>" />
                                    </a>
                                <?php else: ?>
                                    <img itemprop="image" src="<?= $arResult['PREVIEW_PICTURE'] ?>"
                                         class="card-company-brand__img bdrs" alt="<?= $arParams['TITLE'] ? $arParams['TITLE'] : 'Организация' ?>" />
                                <?php endif; ?>

                            </div>
                        <?php endif ?>
                    </div>

                    <?php if ($arParams['TITLE']): ?>
                        <h1 itemprop="name" class="card-company-brand__title ml-md-3 ml-xs-2 mr-md-2"><?= $arParams['TITLE'] ?></h1>
                        <?php if ($arResult['LOCATION']['INDEX']): ?>
                            <meta itemprop="address" content="<?= $arResult['LOCATION']['INDEX'] ?>,
                                г. <?= $arResult['LOCATION']['CITY'] ?>,
                                ул. <?= $arResult['LOCATION']['STREET'] ?>,
                                д. <?= $arResult['LOCATION']['BUILDING'] ?>">
                        <?php endif; ?>
                        <meta itemprop="telephone" content="<?php echo $arResult['SUBSTITUTION_PHONE'] ? $arResult['SUBSTITUTION_PHONE'] : 'Телефон отсутствует'; ?>">
                    <?php endif ?>

                    <?php if ($arResult['SHORT_COMPANY_NAME']): ?>
                        <div class="card-company-brand__desc ml-md-3 ml-sm-2 mt-xs-1 mr-md-2">
                            <?= $arResult['SHORT_COMPANY_NAME'] ?>
                        </div>
                    <?php endif ?>

                </div>
            </div>

            <?php if ($arResult['SUBSTITUTION_PHONE']): ?>
                <div class="card-company__right">
                    <div class="card-company-btns d-xs-flex justify-content-xs-between flex-xs-wrap mt-xs-2 mt-md-0">
                        <a href="javascript:;"
                           class="button d-xs-flex justify-content-xs-center align-items-xs-center tdn button button_btn-company-brand cl-body-color order-xs-2 order-sm-1">
                            <?= format_phone($arResult['SUBSTITUTION_PHONE']) ?>
                        </a>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</header>
