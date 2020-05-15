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

<?php if ($arResult['OPTIONS']): ?>
    <div class="block-scroll block-scroll_show-hover block-scroll_card-const pos-rel">
        <div class="block-scroll__button block-scroll__button_prev justify-content-xs-center align-items-xs-center d-xs-flex vhidden pos-abs zi-2 js-bs-btn-arrow-prev">
            <svg class="block-scroll__arrow block-scroll__arrow_prev arrow-style">
                <use xlink:href="/assets/public/images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </div>
        <div class="block-scroll__wrap d-xs-flex js-bs-card-const">
            <div class="block-scroll__content d-xs-flex js-bs-container">
                <div class="wrap d-xs-flex w-100">

                    <?php foreach ($arResult['OPTIONS'] as $option): ?>
                        <a href="javascript:;"
                            class="card-construction  bg-white tdn card-hover js-card-construction"
                            itemscope itemtype="http://schema.org/Product" >

                            <meta itemprop="name" content="<?= $option['TYPE_NAME']?>">
                            <meta itemprop="description" content="<?= $option['TYPE_NAME']?> - купить">
                            <!-- icon_window.svg banner-1.jpg-->
                            <meta itemprop="image" content="<?=SITE_SERVER_NAME . PUBLIC_ASSET_ROOT_PATH . '/images/constructions/icon_' . $option['PRODUCT_OPTION_CODE'] . '.svg'?>">
                            <meta itemprop="brand" content="<?= $arParams['COMPANY_NAME'] ?>">

                            <span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer"
                                  class="d-xs-flex flex-xs-column align-items-xs-center">
                                <span class="card-construction__wrap-img d-xs-flex">
                                    <svg class="card-construction__img
                                                card-construction__img_<?=$option['PRODUCT_OPTION_CODE']?> <?=$option['PRODUCT_MATERIAL_CODE'] ?>
                                                m-xs-auto">
                                        <use xlink:href="<?= $option['TYPE_IMAGE']?>"></use>
                                    </svg>
                                </span>
                                <span class="card-construction__title tx-xs-a-c" itemprop="name"><?= $option['TYPE_NAME']?></span>
                                <meta itemprop="description" content="<?= $option['TYPE_NAME']?>">
                                <span class="card-construction__quantity cl-link">
                                    <span class="js-card-construction-quantity" itemprop="offerCount"><?= $option['STRUCTURES_COUNT'] ?> </span>
                                    <span class="js-card-construction-text">конструкций</span>
                                </span>

                                <meta itemprop="priceCurrency" content="RUB">
                                <span class="card-price card-price_card-construction d-xs-inline-block ws-nw bg-secondary-color">
                                    <?php if ($option['MIN_PRICE']): ?>
                                        <span itemprop="lowPrice" content="<?=$option['MIN_PRICE']?>"><?=number_format($option['MIN_PRICE'], 0, '', ' ')?></span>
                                        <span class="vhidden">руб.</span> &#8381;
                                    <?php endif; ?>

                                    <?php if ($option['MAX_PRICE'] && $option['MAX_PRICE'] != $option['MIN_PRICE']): ?>
                                        &mdash;
                                        <span itemprop="highPrice" content="<?= $option['MAX_PRICE']?>"><?=number_format($option['MAX_PRICE'], 0, '', ' ')?></span>
                                        <span class="vhidden">руб.</span> &#8381;
                                    <?php endif; ?>
                                </span>
                            </span>
                        </a>
                    <?php endforeach ?>

                </div>
            </div>
        </div>
        <div class="block-scroll__button block-scroll__button_next justify-content-xs-center align-items-xs-center d-xs-flex vhidden pos-abs zi-2 js-bs-btn-arrow-next">
            <svg class="block-scroll__arrow block-scroll__arrow_next arrow-style">
                <use xlink:href="/assets/public/images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </div>
    </div>
<?php endif ?>