<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;
use App\Core\Builders\UrlBuilders\CompanyUrlBuilder;
use App\Core\Helpers\CompaniesFilterHelper;

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

$productConfig = CompaniesFilterHelper::getProductConfigByCode($arParams['PRODUCT_CODE']);
$companyUrl = new CompanyUrlBuilder($arParams['COMPANY_CODE']);
BreadcrumbBuilder::setBreadcrumbs($productConfig['TITLE'], $companyUrl->getCompanySectionPath($arParams['PRODUCT_CODE']));

$productTitle = mb_strtolower($arResult['STRUCTURE']['TYPE_NAME']) . ' (' . $arResult['PRODUCT']['CONFIGURATION']['MATERIAL_NAME'] . ') ' . $arResult['PRODUCT']['CONFIGURATION']['WIDTH'] . ' х ' . $arResult['PRODUCT']['CONFIGURATION']['HEIGHT'] . ' мм.';
BreadcrumbBuilder::setBreadcrumbs('Карточка товара - ' . $productTitle);
?>

<?php ob_start(); ?>

<?php if ($arResult['PRODUCT']['CONFIGURATION']['MATERIAL_NAME']): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link">Материал:</p>
        <p><?= $arResult['PRODUCT']['CONFIGURATION']['MATERIAL_NAME'] ?></p>
    </li>
<?php endif; ?>

<?php if ($arResult['PRODUCT']['CONFIGURATION']['MATERIAL_CODE'] === MATERIAL_TYPE_WOOD): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link">Дерево:</p>
        <p>
            <?= $arResult['PRODUCT']['CONFIGURATION']['WOOD_SPECIES_NAME'] ?>,
            <?= $arResult['PRODUCT']['CONFIGURATION']['WOOD_SPECIES_LAYER_NAME'] ?>,
            <?= $arResult['PRODUCT']['CONFIGURATION']['WOOD_SPECIES_WIDTH'] ?>
            x<?= $arResult['PRODUCT']['CONFIGURATION']['WOOD_SPECIES_HEIGHT'] ?> мм,
            <?= $arResult['PRODUCT']['CONFIGURATION']['WOOD_OVERLAY'] ?>
        </p>
    </li>
<?php endif; ?>

<?php if ($arResult['PRODUCT']['CONFIGURATION']['WIDTH']): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link">Ширина:</p>
        <p><?= $arResult['PRODUCT']['CONFIGURATION']['WIDTH'] ?> мм</p>
    </li>
<?php endif; ?>

<?php if ($arResult['PRODUCT']['CONFIGURATION']['HEIGHT']): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link">Высота:</p>
        <p><?= $arResult['PRODUCT']['CONFIGURATION']['HEIGHT'] ?> мм</p>
    </li>
<?php endif; ?>

<?php foreach ($arResult['STRUCTURE']['PARTS'] as $item): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link"><?= $item['NAME'] ?>:</p>
        <p><?= $item['VALUE'] ?></p>
    </li>
<?php endforeach; ?>

<?php foreach ($arResult['STRUCTURE']['SIZES'] as $item): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link"><?= $item['NAME'] ?>:</p>
        <p><?= $item['VALUE'] ?> мм</p>
    </li>
<?php endforeach; ?>

<?php if ($arResult['PRODUCT']['CONFIGURATION']['PROFILE_BRAND_NAME']): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link">Профиль:</p>
        <p><?= $arResult['PRODUCT']['CONFIGURATION']['PROFILE_BRAND_NAME'] ?>
            , <?= $arResult['PRODUCT']['CONFIGURATION']['PROFILE_LINE_NAME'] ?></p>
    </li>
<?php endif; ?>

<?php if ($arResult['PRODUCT']['CONFIGURATION']['GLASS_TYPE_NAME']): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link">Стеклопакет:</p>
        <p><?= $arResult['PRODUCT']['CONFIGURATION']['GLASS_TYPE_NAME'] ?>
            - <?= $arResult['PRODUCT']['CONFIGURATION']['GLASS_WIDTH'] ?> мм
            (<?= $arResult['PRODUCT']['CONFIGURATION']['GLASS_FORMULA'] ?>)</p>
    </li>
<?php endif; ?>

<?php if ($arResult['PRODUCT']['CONFIGURATION']['FITTINGS_BRAND_NAME']): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link">Фурнитура:</p>
        <p><?= $arResult['PRODUCT']['CONFIGURATION']['FITTINGS_BRAND_NAME'] ?>
            , <?= $arResult['PRODUCT']['CONFIGURATION']['FITTINGS_LINE_NAME'] ?></p>
    </li>
<?php endif; ?>

<?php if ($arResult['PRODUCT']['CONFIGURATION']['WARRANTY']): ?>
    <li class="list-two__item d-xs-flex">
        <p class="list-two__name link">Гарантия:</p>
        <p><?= $arResult['PRODUCT']['CONFIGURATION']['WARRANTY'] ?> месяцев</p>
    </li>
<?php endif; ?>

<?php

$strConfiguration = ob_get_contents();
ob_end_clean();
?>


<div itemscope="itemscope" itemtype="http://schema.org/Product" class="bg-body-bg full-page">
    <section class="section_detail-product bd-top mt-md-5 mb-md-0 mb-xs-3">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="detailed-product-content detailed-product-content_img bg-white pt-md-3 px-md-3 pt-xs-3">
                        <div class="title title_detailed-product d-xs-flex justify-content-xs-between align-items-xs-center">
                            <h1><?= $arResult['STRUCTURE']['TYPE_NAME'] ?>
                                (<?= $arResult['PRODUCT']['CONFIGURATION']['MATERIAL_NAME'] ?>
                                ) <?= $arResult['PRODUCT']['CONFIGURATION']['WIDTH'] ?>
                                х <?= $arResult['PRODUCT']['CONFIGURATION']['HEIGHT'] ?> мм</h1>
                            <p class="title__schilder card-tag bg-accent cl-white">Под заказ</p>
                        </div>
                        <div class="block-scroll block-scroll_prod-img-big block-scroll_d-line">
                            <div class="block-scroll__wrap d-xs-flex js-block-scroll pos-rel">
                                <div class="block-scroll__content d-xs-flex">
                                    <div class="cards-window-scroll cards-window-scroll_product-content row">
                                        <div data-img-name="<?= $arResult['STRUCTURE']['CODE'] ?><?= $arResult['STRUCTURE']['HAS_FRAME'] ? '_frame' : '' ?>"
                                             data-casement-arr="<?= $arResult['STRUCTURE']['TYPE_OF_OPENINGS'] ?>"
                                             class="wrap-img-svg c-detail d-xs-flex justify-content-xs-center align-items-xs-start ml-xs-auto mr-xs-auto js-l-construction-item">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container mb-md-5 mb-xs-3">
            <div class="row">
                <div class="col-xs-12">
                    <div class="detailed-product-content detailed-product-content_text bg-white bd-top bd-top_sm-none px-md-3 pb-md-3 pt-md-0 px-xs-2 pt-xs-2 js-detailed-product-content-txt">
                        <div class="row">
                            <div class="col-md-6 d-xs-flex align-items-xs-center bd-right bd-right_sm-none py-md-3">
                                <div class="switch-die switch-die_prod-card d-xs-flex flex-xs-wrap w-100 js-switch-die-detailed-card">
                                    <?php if ($arResult['PRODUCT']['CONFIGURATION']['IS_CONFIGURATION']): ?>
                                        <label class="switch-die-item switch-die-item_prod-card tx-xs-a-c pos-rel js-switch-die-item">
                                            <input type="radio" data-name="0" name="undefined"
                                                   class="switch-die-item__input vhidden"
                                                   checked="<?= ($arResult['PRODUCT']['CONFIGURATION']['IS_CONFIGURATION']) ? 'checked' : '' ?>"
                                            />
                                            <span class="switch-die-item__bg pos-abs"></span>
                                            <span class="switch-die-item__text pos-rel">Только конструкция</span>
                                        </label>
                                    <?php endif; ?>

                                    <?php if ($arResult['PRODUCT']['CONFIGURATION']['IS_PREPARED']): ?>
                                        <label class="switch-die-item switch-die-item_prod-card tx-xs-a-c pos-rel js-switch-die-item">
                                            <input type="radio"
                                                   data-name="1"
                                                   name="undefined"
                                                   class="switch-die-item__input vhidden"
                                                <?= (!$arResult['PRODUCT']['CONFIGURATION']['IS_CONFIGURATION']) ? 'checked' : '' ?>
                                            />
                                            <span class="switch-die-item__bg pos-abs"></span>
                                            <span class="switch-die-item__text pos-rel">Под ключ</span>
                                        </label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 py-md-3 mb-xs-2 mb-sm-0">
                                <div itemprop="offers" itemscope="itemscope" itemtype="http://schema.org/Offer"
                                     class="prod-price d-xs-flex flex-xs-wrap bg-body-bg bdrs mt-xs-2 mt-md-0">
                                    <div class="prod-price__left d-sm-block d-xs-flex flex-xs-row-reverse justify-content-xs-between align-items-xs-center mb-xs-2 mb-sm-0">
                                        <p class="pr-cur-price">
                                            <span itemprop="price"
                                                  class="js-prod-price js-locale-price"
                                                  data-prod-price-array='[
                                                  <?php if ($arResult['PRODUCT']['CONFIGURATION']['IS_CONFIGURATION']): ?>
                                                    <?= $arResult['PRODUCT']['CONFIGURATION']['CONFIGURATION_PRICE'] ?>
                                                    <?= (isset($arResult['PRODUCT']['CONFIGURATION']['PREPARED_PRICE']))? ',' . $arResult['PRODUCT']['CONFIGURATION']['PREPARED_PRICE'] : '' ?>
                                                  <?php else : ?>
                                                    "", <?= $arResult['PRODUCT']['CONFIGURATION']['PREPARED_PRICE']?>
                                                  <?php endif; ?>]'>
                                            </span>
                                            <span class="pr-cur-price__unit pos-rel"> &#8381;
                                                <?php /* ?>
                                                <span class="pr-cur-price__info circle pos-abs d-sm-flex d-xs-none justify-content-xs-center align-items-xs-center bg-sky">i</span>
                                                <? */?>
                                            </span>
                                            <span class="d-xs-none">
                                                <meta itemprop="priceCurrency" content="RUB"/>руб.</span>
                                        </p>
                                        <p class="pr-cur-name d-xs-flex flex-xs-column d-sm-block">Продавец:
                                            <a href="<?= $arResult['COMPANY']['URL'] ?>"
                                               class="link link-accent tdn"><?= $arResult['COMPANY']['NAME'] ?></a>
                                        </p>
                                    </div>
                                    <div class="prod-price__right py-sm-1 d-xs-flex align-items-xs-center">
                                        <a href="javascript:;"
                                           class="button d-xs-flex justify-content-xs-center align-items-xs-center tdn button button_prod-price button_font-semibold cl-white bg-accent-d upcs">Обратный
                                            звонок</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 bd-right bd-right_sm-none">
                                <div class="accordion-item accordion-item_c-prod content-link-accent js-accordion">
                                    <div class="accordion-item__header js-accordion-header is-open d-xs-flex justify-content-xs-between">
                                        <div class="title title_prod d-xs-flex justify-content-xs-between align-items-xs-center">
                                            <h2>Что включено</h2>
                                        </div>
                                        <svg class="accordion-item__arrow arrow-style pos-rel ml-xs-2 d-xs-block d-md-none">
                                            <use xlink:href="/assets/public/images/icon.svg#icon_icon_arrow"></use>
                                        </svg>
                                    </div>
                                    <div class="accordion-item__content js-accordion-content js-accordion-content-pr-txt">
                                        <?php if ($arResult['PRODUCT']['CONFIGURATION']['IS_CONFIGURATION']): ?>
                                            <ul data-list-two-id='0'
                                                class="list-two d-xs-none js-list-two-detail-prod-card">
                                                <?= $strConfiguration ?>
                                            </ul>
                                        <?php endif; ?>

                                        <?php if ($arResult['PRODUCT']['CONFIGURATION']['IS_PREPARED']): ?>
                                            <ul data-list-two-id='1'
                                                class="list-two d-xs-none js-list-two-detail-prod-card">

                                                <?php if (!isset($arResult['PRODUCT']['CONFIGURATION']['IS_CONFIGURATION'])): ?>
                                                    <?= $strConfiguration ?>
                                                <?php endif; ?>

                                                <li class="list-two__item d-xs-flex">
                                                    <p><?= $arResult['PRODUCT']['CONFIGURATION']['PREPARED'] ?>.</p>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($arResult['COMPANY']['PAYMENTS']) || !empty($arResult['COMPANY']['REGIONS'])): ?>
                                <div class="col-md-6">
                                    <div class="accordion-item accordion-item_c-prod accordion-item_c-prod-img content-link-accent js-accordion">
                                        <div class="accordion-item__header js-accordion-header is-open d-xs-flex justify-content-xs-between">
                                            <div class="title title_prod d-xs-flex justify-content-xs-between align-items-xs-center">
                                                <h2>Оплата и доставка</h2>
                                            </div>
                                            <svg class="accordion-item__arrow arrow-style pos-rel ml-xs-2 d-xs-block d-md-none">
                                                <use xlink:href="/assets/public/images/icon.svg#icon_icon_arrow"></use>
                                            </svg>
                                        </div>
                                        <div class="accordion-item__content js-accordion-content">
                                            <ul class="list-img">
                                                <?php if (!empty($arResult['COMPANY']['PAYMENTS'])): ?>
                                                    <li class="list-img__item pos-rel">
                                                        <svg class="list-img__ico pos-abs">
                                                            <use xlink:href="/assets/public/images/icon.svg#icon_pay"></use>
                                                        </svg>
                                                        <p class="list-img__name">Безопасная оплата:</p>
                                                        <p class="list-img__val">
                                                            <?php $elementsMaxCount = 2 ?>
                                                            <?php $paymentCount = count($arResult['COMPANY']['PAYMENTS']) ?>
                                                            <?php $iterationCount = ($paymentCount > $elementsMaxCount) ? $elementsMaxCount : $paymentCount ?>
                                                            <?php for ($i = 0; $i < $iterationCount; $i++): ?>
                                                                <?= ($i == 0) ? mb_ucfirst($arResult['COMPANY']['PAYMENTS'][$i]) : $arResult['COMPANY']['PAYMENTS'][$i] ?><?= ($i < $iterationCount - 1) ? ', ' : '' ?>
                                                            <?php endfor; ?>
                                                            <?php $remainsElements = $paymentCount - $elementsMaxCount; ?>
                                                            <a href="<?= $arResult['COMPANY']['PAY_AND_DELIVERY_URL'] ?>">
                                                                <?= ($remainsElements > 0) ? ' и еще ' . $remainsElements . ' ' . num2word($remainsElements, ['способ', 'способа', 'способов']) : '' ?>
                                                            </a>
                                                        </p>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if (!empty($arResult['COMPANY']['REGIONS'])): ?>
                                                    <li class="list-img__item pos-rel">
                                                        <svg class="list-img__ico pos-abs">
                                                            <use xlink:href="/assets/public/images/icon.svg#icon_delivery"></use>
                                                        </svg>
                                                        <p class="list-img__name">Регионы доставки:</p>
                                                        <p class="list-img__val region">
                                                            <?php foreach ($arResult['COMPANY']['REGIONS'] as $region): ?>
                                                        <p><?= $region['REGION_NAME'] ?> <?= $region['REGION_TYPE_SHORT'] ?>
                                                            :</p>
                                                        <?php $elementsMaxCount = 6 ?>
                                                        <?php $paymentCount = count($region['DISTRICTS']) ?>
                                                        <?php $iterationCount = ($paymentCount > $elementsMaxCount) ? $elementsMaxCount : $paymentCount ?>
                                                        <?php for ($i = 0; $i < $iterationCount; $i++): ?>
                                                            <?= ($i == 0) ? mb_ucfirst($region['DISTRICTS'][$i]) : $region['DISTRICTS'][$i] ?><?= ($i < $iterationCount - 1) ? ', ' : '' ?>
                                                        <?php endfor; ?>
                                                        <?php $remainsElements = $paymentCount - $elementsMaxCount; ?>
                                                        <a href="<?= $arResult['COMPANY']['PAY_AND_DELIVERY_URL'] ?>">
                                                            <?= ($remainsElements > 0) ? ' и еще ' . $remainsElements . ' шт.' : '' ?>
                                                        </a>
                                                        <?php endforeach; ?>
                                                        </p>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if (!empty($arResult['COMPANY']['DELIVERY_TIME'])): ?>
                                                    <li class="list-img__item pos-rel">
                                                        <p class="list-img__name">Время доставки:</p>
                                                        <?php foreach ($arResult['COMPANY']['DELIVERY_TIME'] as $day => $time): ?>
                                                            <p class="list-img__val"><?= $day ?>, с <?= $time['from'] ?>
                                                                до <?= $time['to'] ?></p>
                                                        <?php endforeach; ?>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
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
</div>
