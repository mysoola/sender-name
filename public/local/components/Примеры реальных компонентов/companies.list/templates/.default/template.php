<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;

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

BreadcrumbBuilder::setBreadcrumbs('Компании');

?>
<section class="section_catalog-list bg-white bd-top d-xs-flex align-items-xs-center">
    <div class="container tx-xs-a-c py-md-3">
        <h1 data-current-city="<?= $arResult['CITY_NAME'] ?>" class="main-header js-main-header">Оконные компании в <?= $arResult['CITY_NAME'] ?>
            <span class="main-header__wrap-icon d-sm-inline-flex d-xs-none justify-content-xs-center align-items-xs-center pos-rel">
                <svg class="main-header__icon"><use xlink:href="/assets/public/images/icon.svg#icon_icon_tag"></use>
                </svg>
            </span>
        </h1>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="search pos-rel my-md-3 my-xs-2">
            <input type="text" placeholder="Введите название компании" class="search__input w-100 js-search-by-company-name"
                value="<?=$arParams['QUERY']?>"/>
            <svg class="search__icon pos-abs"><use xlink:href="/assets/public/images/icon.svg#icon_icon_search"></use>
            </svg>
        </div>
        <div id="app-vue" class="list-offers list-offers_companies js-list-ofers pos-rel mb-md-5 mb-xs-3">
            <div class="list-offers__header bg-white">
                <div class="list-offers__head-item list-offers__head-item_company tx-md-a-c py-md-1">Компания</div>
                <div class="list-offers__head-item list-offers__head-item_price pl-lg-3 pl-md-2 py-md-1">Центральный офис</div>
                <div class="list-offers__head-item list-offers__head-item_brands pl-lg-3 pl-md-2 py-md-1">Часы работы</div>
                <div class="list-offers__head-item list-offers__head-item_phone"></div>
            </div>
            <?php $itemIndex = 0;
            foreach ($arResult['COMPANIES']['items'] as $company): ?>
                <div data-offers-body-id="<?= $itemIndex ?>" itemscope="itemscope" itemtype="http://schema.org/Organization" class="list-offers__body list-offers__body_companies bg-white">
                    <div class="list-offers-company d-xs-flex flex-sm-column pos-rel align-items-xs-center bd-bottom-tbl px-xs-2 p-md-2 py-xs-1">
                        <div class="list-offers-company__wrap-img d-xs-flex align-items-xs-center justify-content-xs-center">
                            <?php if ($company['PREVIEW_IMG']): ?>
                                <noscript>
                                    <img itemprop="image" src="<?= $company['PREVIEW_IMG']?>" alt="<?= $company['NAME']?>">
                                </noscript>
                                <img class="list-offers-company__img lazy" alt="<?= $company['NAME']?>" data-src="<?= $company['PREVIEW_IMG']?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
                            <?php else: ?>
                                <p><?= $company['NAME']?></p>
                            <?php endif; ?>
                            <p itemprop="name" class="d-xs-none"><?= $company['NAME']?></p>
                            <p itemprop="description" class="d-xs-none">Пластиковые окна</p>
                        </div>
                        <div class="list-offers-company__wrap-raiting d-sm-flex align-items-sm-center">
                            <svg class="list-offers-company__icon-star pos-rel">
                                <use xlink:href="/assets/public/images/icon.svg#icon_icon_star"></use>
                            </svg>
                            <p class="list-offers-company__raiting"><?= $company['RATING']?></p>
                            <?php if ($company['REQ_CHECK']): ?>
                                <div data-tooltype="Документы проверены" class="tooltype tooltype_l">
                                    <svg class="tooltype__icon-doc cl-accent">
                                        <use xlink:href="/assets/public/images/icon.svg#icon_icon_doc"></use>
                                    </svg>
                                </div>
                            <?php else: ?>
                                <div data-tooltype="Документы не проверены" class="tooltype tooltype_l">
                                    <svg class="tooltype__icon-doc cl-star">
                                        <use xlink:href="/assets/public/images/icon.svg#icon_icon_doc"></use>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <?php if ($company['PHONE_CHECK']): ?>
                                <div data-tooltype="Телефон подтвержден" class="tooltype tooltype_r">
                                    <svg class="tooltype__icon-phone cl-accent">
                                        <use xlink:href="/assets/public/images/icon.svg#icon_icon_phone"></use>
                                    </svg>
                                </div>
                            <?php else: ?>
                                <div data-tooltype="Телефон не подтвержден" class="tooltype tooltype_r">
                                    <svg class="tooltype__icon-phone cl-star">
                                        <use xlink:href="/assets/public/images/icon.svg#icon_icon_phone"></use>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a data-offers-subline-id="<?= $itemIndex ?>" href="javascript:;"
                           class="js-company-filter-company-detail-btn  l-link-more tdn cl-i d-xs-none d-sm-block link-accent js-btn-list-offers-show-more">
                            <span>Подробнее</span>
                            <svg class="l-link-more__arrow arrow-style">
                                <use xlink:href="/assets/public/images/icon.svg#icon_icon_arrow"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="list-content list-content_address d-xs-flex h-100 flex-md-column justify-content-md-center pos-rel align-items-sm-center bd-bottom-tbl px-lg-3 px-md-2 py-md-3 pl-sm-0 p-xs-2">
                        <div itemscope="itemscope" itemtype="http://schema.org/Store" class="d-xs-none">
                            <?php if ($company['PREVIEW_IMG']): ?>
                                <img src="<?= $company['PREVIEW_IMG']?>" itemprop="image">
                            <?php endif; ?>

                            <?php if (!empty($company['MAIN_OFFICE']['WORK_TIME'])): ?>
                                <?php foreach ($company['MAIN_OFFICE']['WORK_TIME'] as $day => $workTime): ?>
                                    <span itemprop="openingHours" content="<?= $day ?> <?= $workTime['from'] ?>-<?= $workTime['to'] ?>"></span>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <span itemprop="name" class="d-xs-none"><?= $company['NAME']?></span>

                            <?php if ($company['MAIN_OFFICE']['CITY']): ?>
                                <span itemprop="address" class="d-xs-none"><?=$company['MAIN_OFFICE_STR']?></span>
                            <?php endif; ?>
                        </div>
                        <div itemprop="address" itemscope="itemscope" itemtype="http://schema.org/PostalAddress" class="list-content__content d-xs-flex flex-xs-column justify-content-xs-center">
                            <?php if ($company['MAIN_OFFICE']['CITY']): ?>
                                <p><?=$company['MAIN_OFFICE_STR']?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="list-content list-content_opening-hours d-xs-flex h-100 flex-md-column justify-content-md-center pos-rel align-items-sm-center bd-bottom-tbl px-lg-3 px-md-2 py-md-3 p-xs-2 align-items-sm-center align-items-md-start">
                        <?php if (!empty($company['MAIN_OFFICE']['WORK_TIME'])): ?>
                            <div class="list-content__content d-xs-flex flex-xs-column justify-content-xs-center">
                                <?php foreach ($company['MAIN_OFFICE']['WORK_TIME'] as $day => $workTime): ?>
                                    <p><?= $day ?>: <?= $workTime['from'] ?>-<?= $workTime['to'] ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="list-offers-phones d-xs-flex flex-xs-column justify-content-xs-center pos-rel h-100
                    bd-bottom-tbl pl-xl-3 py-ld-3 py-md-2 pl-md-2 pr-md-3 py-sm-1 pl-sm-0 px-xs-2 py-xs-2">
                        <?php if ($company['SUB_PHONE']): ?>
                            <div
                                data-vue-role="filter-company-card-phone-btn"
                                data-v-bind_id="<?=$company['COMPANY_ID']?>"
                                data-v-bind_phone-mask="'<?=$company['SUB_PHONE']?>'">
                            </div>
                        <?php endif; ?>
                        <a href="<?= $company['LINK'] ?>" class="button d-xs-flex justify-content-xs-center
                        align-items-xs-center tdn button button_btn-offers-application cl-white bg-accent-d upcs ws-nw">Перейти</a>
                        <a data-offers-subline-id="<?= $itemIndex ?>" href="javascript:;"
                           class="js-company-filter-company-detail-btn l-link-more tdn cl-i d-xs-block d-sm-none
                           mx-xs-auto js-btn-list-offers-show-more link-accent">
                            <span>Подробнее</span>
                            <svg class="l-link-more__arrow arrow-style">
                                <use xlink:href="/assets/public/images/icon.svg#icon_icon_arrow"></use>
                            </svg>
                        </a>
                    </div>
                    <div
                        data-vue-role="companies-list-item-detail"
                        data-v-bind_id="<?=(int)$company['COMPANY_ID']?>"
                        data-v-bind_sub-line-id="<?=$itemIndex?>">
                    </div>
                </div>
            <?php $itemIndex++;
            endforeach; ?>

            <div
                data-vue-role="companies-list"
                data-v-bind_product-url="'<?=$arParams['PRODUCT_CODE']?>'"
                data-v-bind_list-url="'/public-ajax/companies/search/'"
                data-v-bind_on-page="<?=$arResult['COMPANIES']['ON_PAGE']?>"
                data-v-bind_page="<?=$arResult['COMPANIES']['PAGE']?>"
                data-v-bind_item-index-start="<?=$arResult['COMPANIES']['ON_PAGE']?>"
            ></div>

            <?php if ($arResult['COMPANIES']['COUNT_LEFT'] > 0): ?>
                <a href="javascript:;" class="list-offers-show-more tdn tx-xs-a-c d-xs-block py-xs-2 bg-white">Показать еще
                    <span class="list-offers-show-more__first-quantity js-list-offers-first-quantity"><?=$arResult['COMPANIES']['ON_PAGE']?></span> из
                    <span class="list-offers-show-more__second-quantity js-list-offers-second-quantity"><?=$arResult['COMPANIES']['COUNT_LEFT']?></span></a>
            <?php endif; ?>
            <div class="<?=(count($arResult['COMPANIES']['items']) > 0) ? 'hidden-block' : ''?> not-found bdrs bg-white cl-body-color d-xs-flex flex-xs-wrap my-md-5 my-xs-3">
                <div class="not-found__wr-img d-sm-flex d-xs-none justify-content-xs-center align-items-xs-center mb-sm-3 mb-md-0">
                    <img src="/assets/public/images/window-not-found.jpg" class="not-found__img" alt="OknaZavr - ничего не найдено" />
                </div>
                <div class="not-found__wr-text d-xs-flex flex-xs-column justify-content-xs-center">
                    <p class="not-found__title">К сожалению ничего не найдено </p>
                    <p class="not-found__text">Попробуйте изменить условия фильтра.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-lg-2 mt-md-5 mt-xs-3">
        <?php
        $APPLICATION->IncludeComponent(
            'public:brands.widget.list',
            '',
            [
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => 86400,
                'TITLE' => 'Бренды материалов',
                'COUNT_ELEMENTS' => 12,
            ],
            false
        );
        ?>

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
