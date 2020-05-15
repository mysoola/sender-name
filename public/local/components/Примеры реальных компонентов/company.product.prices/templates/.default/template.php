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
BreadcrumbBuilder::setBreadcrumbs('Конструкция ID ' . $arResult['STRUCTURE']['ID']);

?>

<div itemscope="itemscope" itemtype="http://schema.org/Product" class="bg-body-bg full-page">

    <?php $APPLICATION->IncludeFile(
        "/local/components/public/companies/templates/.default/company_header.php",
        [
            'COMPANY_NAME' => $arParams['NAME'],
            'PREVIEW_PICTURE' => $arParams['PREVIEW_PICTURE'],
            'COMPANY_CODE' => $arParams['COMPANY_CODE'],
            'COMPANY_ID' => $arParams['COMPANY_ID'],
            'SUBSTITUTION_PHONE' => $arParams['SUBSTITUTION_PHONE'],
            'TITLE' => 'Цены на ' . mb_strtolower($arResult['STRUCTURE']['TYPE_NAME']) . ' от компании ' . $arParams['COMPANY_NAME'],
            'STRUCTURE_ID' => $arResult['STRUCTURE']['ID'],
        ]
    ); ?>

    <section class="section_detail-product bd-top mt-md-3 mb-md-0 mb-xs-0">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="detailed-product-content detailed-product-content_img bg-white pt-md-3 px-md-3 pt-sm-3 pt-xs-2">
                        <p class="detailed-product-content__shilder pos-abs card-tag bg-accent cl-white d-xs-inline-flex">Под заказ</p>
                        <div class="block-scroll block-scroll_prod-img-big block-scroll_d-line">
                            <div class="block-scroll__wrap d-xs-flex js-block-scroll pos-rel ps">
                                <div class="block-scroll__content d-xs-flex">
                                    <div class="cards-window-scroll cards-window-scroll_product-card-price row">
                                        <div data-img-name="<?= $arResult['STRUCTURE']['CODE'] ?><?= $arResult['STRUCTURE']['HAS_FRAME'] ? '_frame' : '' ?>"
                                             data-casement-arr="<?= $arResult['STRUCTURE']['TYPE_OF_OPENINGS'] ?>"
                                             class="wrap-img-svg c-detail d-xs-flex justify-content-xs-center align-items-xs-start ml-xs-auto mr-xs-auto js-l-construction-item">
                                        </div>
                                    </div>
                                </div>
                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section_detail-product mb-md-5 mb-xs-3">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bg-white px-md-3 bd-top pt-lg-6 pt-md-5 pt-sm-4 pt-xs-3 pb-xs-3 pb-md-5">
                        <div class="l-table-price l-table-price_prod-card">

                            <?php if ($arResult['CONFIGURATION']['PRICES']): ?>
                                <h2 class="l-table-price__title ff-b">Полный прайс-лист конфигурации “Конструкция”</h2>
                                <p class="l-table-price__title-desc ff-l cl-link"><?= mb_ucfirst($arResult['CONFIGURATION']['DESCRIPTION']) ?></p>
                                <div class="block-scroll block-scroll_prod-table block-scroll_d-line">
                                    <div class="block-scroll__wrap d-xs-flex js-block-scroll pos-rel">
                                        <div class="block-scroll__content d-xs-flex">
                                            <div class="cards-window-scroll cards-window-scroll_prod-table row">
                                                <table class="ff-r">
                                                    <thead>
                                                    <tr>
                                                        <td></td>
                                                        <?php foreach ($arResult['CONFIGURATION']['PRICES']['widths'] as $width): ?>
                                                            <td><?= $width ?> мм</td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <?php $prices = $arResult['CONFIGURATION']['PRICES']; ?>

                                                        <?php foreach ($prices['heights'] as $height): ?>
                                                            <tr>
                                                                <td><?= $height ?></td>
                                                                    <?php foreach ($prices['widths'] as $width): ?>
                                                                        <?php if ($prices['table'][$height][$width]): ?>
                                                                            <td>
                                                                                <a href="<?= $prices['table'][$height][$width]['LINK'] ?>" target="_blank" class="link-accent tdn"><?= $prices['table'][$height][$width]['PRICE'] ?>
                                                                                    <span class="vhidden">руб.</span><span class="cl-body-color">&#8381;</span>
                                                                                </a>
                                                                            </td>
                                                                        <?php else: ?>
                                                                            <td>&mdash;</td>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($arResult['CONFIGURATION_PREPARED']['PRICES'] && $arResult['CONFIGURATION_PREPARED']['DESCRIPTION']): ?>
                                <h2 class="l-table-price__title ff-b <?= ($arResult['CONFIGURATION']['PRICES']) ? 'mt-xs-3 mt-sm-4 mt-md-5' : '' ?>">
                                    Полный прайс-лист конфигурации “Под ключ”
                                </h2>
                                <?php if ($arResult['CONFIGURATION_PREPARED']['DESCRIPTION']): ?>
                                    <p class="l-table-price__title-desc ff-l cl-link"><?= mb_ucfirst($arResult['CONFIGURATION_PREPARED']['DESCRIPTION']) ?></p>
                                <?php endif; ?>

                                <?php if ($arResult['CONFIGURATION_PREPARED']['PRICES']): ?>
                                    <div class="block-scroll block-scroll_prod-table block-scroll_d-line">
                                        <div class="block-scroll__wrap d-xs-flex js-block-scroll pos-rel">
                                            <div class="block-scroll__content d-xs-flex">
                                                <div class="cards-window-scroll cards-window-scroll_prod-table row">
                                                    <table class="ff-r">
                                                        <thead>
                                                        <tr>
                                                            <td></td>
                                                            <?php foreach ($arResult['CONFIGURATION_PREPARED']['PRICES']['widths'] as $width): ?>
                                                                <td><?= $width ?> мм</td>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <?php $prices = $arResult['CONFIGURATION_PREPARED']['PRICES']; ?>

                                                            <?php foreach ($prices['heights'] as $height): ?>
                                                        <tr>
                                                            <td><?= $height ?></td>
                                                            <?php foreach ($prices['widths'] as $width): ?>
                                                                <?php if ($prices['table'][$height][$width]): ?>
                                                                    <td>
                                                                        <a href="<?= $prices['table'][$height][$width]['LINK'] ?>" target="_blank" class="link-accent tdn"><?= $prices['table'][$height][$width]['PRICE'] ?>
                                                                            <span class="vhidden">руб.</span><span class="cl-body-color">&#8381;</span>
                                                                        </a>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <td>&mdash;</td>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
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


