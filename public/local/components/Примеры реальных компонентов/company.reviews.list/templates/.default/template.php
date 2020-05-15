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

<section>
    <div class="container">
        <div class="row mt-md-3 mt-xs-2">
            <div class="col-xl-9 col-lg-8">

                <?php $APPLICATION->IncludeComponent(
                    'public:company.reviews.add.link.widget',
                    'mobile',
                    [
                        'CACHE_TYPE' => 'A',
                        'CACHE_TIME' => 0,
                        'TITLE' => 'Написать отзыв',
                        'COMPANY_ID' => (isset($arParams['COMPANY_ID']))? $arParams['COMPANY_ID'] : null,
                        'CSS_CLASS' => 'mb-xs-2 d-md-none d-xs-flex',
                    ],
                    false
                ); ?>

                <div class="reviews-list pos-rel ff-l">
                    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                        <meta itemprop="ratingValue" content="<?= round(collect($arResult['REVIEWS']['ITEMS'])->avg('RATING'), 1) ?>"/>
                        <meta itemprop="reviewCount" content="<?= count($arResult['REVIEWS']['ITEMS']) ?>"/>
                    </div>

                    <form class="reviews-list__wr-selects pos-rel mb-xs-2" method="get">
                        <label class="select-filter select-filter_w select-filter_zi-3 mb-xs-2 mb-sm-0 pos-rel js-select-filter-simple">
                            <select name="service" class="select-filter__list">
                                <option value="0">О чем отзыв</option>
                                <?php foreach ($arResult['SERVICES'] as $service): ?>
                                    <option value="<?= $service['ID'] ?>" <?= ($service['ID'] == $arParams['SERVICE_ID']) ? 'selected' : '' ?>><?= $service['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="select-filter select-filter_w select-filter_zi-2 mb-xs-2 mb-sm-0 pos-rel js-select-filter-simple">
                            <select name="rating" class="select-filter__list">
                                <option value="0">Все оценки</option>
                                <?php foreach ($arParams['RATING_FOR_FILTER'] as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= ($value['SELECTED']) ? 'selected' : '' ?> ><?= $key ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <input type="submit" value="Применить" class="button button_bdd button_h-46 d-xs-flex justify-content-xs-center align-items-xs-center tdn ff-r cl-white bg-accent-d">
                    </form>


                    <!--<div class="search-select pos-rel mb-xs-2">
                        <select name="search-review"
                                class="search-select__select w-100 js-search-select">
                            <option value="">Введите название компании</option>
                            <option value="Москва3">Москва2</option>
                            <option value="Екатеренбург3">Екатеренбург 2</option>
                            <option value="Казань3">Казань 2</option>
                        </select>
                        <svg class="search-select__icon pos-abs">
                            <use xlink:href="/assets/public/images/icon.svg#icon_icon_search"></use>
                        </svg>
                    </div>-->

                    <?php if (isset($arResult['REVIEWS']['ITEMS'])): ?>
                        <?php foreach ($arResult['REVIEWS']['ITEMS'] as $review): ?>
                            <div itemprop="review" itemscope itemtype="http://schema.org/Review"
                                 class="review-item bg-white bd-bottom p-md-3 p-xs-2 js-review-item mb-xs-1 mb-md-0">
                                <div class="review-item__img-tag d-xs-flex align-items-xs-center flex-xs-wrap mb-xs-2">
                                    <!-- review-->
                                    <div class="who-review-reverse">
                                        <a href="<?= $review['COMPANY_URL'] ?>" class="who-review-reverse__link mb-xs-2 mb-sm-0 mr-md-3 mr-sm-2 d-xs-flex align-items-xs-center">
                                            <span class="vhidden"><?= (!empty($review['CREATED_NAME'])) ? $review['CREATED_NAME'] : '' ?></span>
                                            <noscript>
                                                <img src="<?= $review['COMPANY_PREVIEW_IMG'] ?>" alt="<?= $review['THEME'] ?>" />
                                            </noscript>
                                            <img data-src="<?= $review['COMPANY_PREVIEW_IMG'] ?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="who-review-reverse__img lazy" alt="<?= $review['THEME'] ?>" />
                                        </a>
                                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="who-review-reverse__reiting">
                                            <div class="review-stars d-xs-flex">
                                                <meta itemprop="worstRating" content="1"/>
                                                <p itemprop="ratingValue" class="review-stars__txt"><?= $review['RATING'] ?></p>
                                                <meta itemprop="bestRating" content="5"/>

                                                <?php foreach ($review['ARR_RATING'] as $rating): ?>
                                                    <svg class="review-stars__icon pos-rel <?= ($rating['ACTIVE'] === true) ? 'is-active' : '' ?>">
                                                        <use xlink:href="/assets/public/images/icon.svg#icon_icon_star"></use>
                                                    </svg>
                                                <?php endforeach; ?>

                                            </div>
                                        </div>
                                        <p class="who-review-reverse__name cl-link">
                                            <span itemprop="author">
                                                <?= (!empty($review['CREATED_NAME'])) ? $review['CREATED_NAME'] : '' ?><?= (!empty($review['USER_LOCATION'])) ? ', ' . $review['USER_LOCATION'] : '' ?>
                                            </span>
                                        </p>
                                    </div>
                                      <meta itemprop="itemReviewed" content="<?= $review['COMPANY_NAME'] ?>">
                                    <!-- tags  productSupported  serviceType -->
                                    <div class="reviews-tags d-md-flex d-xs-none">
                                        <!-- itemprop="itemReviewed" -->
                                        <div itemprop="reviewAspect" class="reviews-tags__tag card-price ws-nw ml-md-1 bg-secondary-color">
                                            <?= (!empty($review['SERVICE_NAME'])) ? $review['SERVICE_NAME'] : '' ?>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= $review['REVIEW_URL'] ?>" itemprop="name"
                                   class="review-item__main-link tdn link d-xs-inline-block mb-xs-2 ff-b">
                                    <?= (!empty($review['THEME'])) ? $review['THEME'] : '' ?>
                                </a><!-- text-->
                                <div class="review-item__wrap-msg content-link-accent cl-link ovh">
                                    <p itemprop="reviewBody" class="review-item__read-more text-ellipsis ovh">
                                        <?= (!empty($review['MESSAGE'])) ? $review['MESSAGE'] : '' ?>
                                    </p>

                                    <a href="javascript" class="d-sm-none d-xs-block mt-xs-2">Читать</a>

                                </div>
                                <?php if ($review['COMMENT_CNT'] > 0 || $review['HAS_COMPANY_COMMENT'] > 0): ?>
                                    <div class="review-item-comment d-sm-flex d-xs-none justify-content-xs-end cl-link mt-md-3 mt-sm-2">
                                        <?php if ($review['COMMENT_CNT'] > 0): ?>
                                            <a href="<?= $review['REVIEW_URL'] ?>#comments"
                                               class="review-item-comment__com d-xs-flex align-items-xs-center link tdn">
                                                <svg class="review-item-comment__ico">
                                                    <use xlink:href="/assets/public/images/icon.svg#icon_comment"></use>
                                                </svg>
                                                <span class="review-item-comment__txt-n js-review-item-com-q"><?= $review['COMMENT_CNT'] ?> </span>
                                                <span class="review-item-comment__txt js-review-item-com-t">комментариев</span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($review['HAS_COMPANY_COMMENT'] > 0): ?>
                                            <a href="<?= $review['REVIEW_URL'] ?>#comments-company"
                                               class="review-item-comment__com d-xs-flex align-items-xs-center ml-sm-2 link tdn">
                                                <svg class="review-item-comment__ico">
                                                    <use xlink:href="/assets/public/images/icon.svg#icon_comment-company"></use>
                                                </svg>
                                                Ответ компании
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
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
                    <?php endif ?>

                    <?php
                    if ($arResult['REVIEWS']['NAV_PARAMS']['END_PAGE'] > 1) {
                        $APPLICATION->IncludeComponent(
                            'public:pagination',
                            'version_one',
                            [
                                'CACHE_TIME' => 0,
                                'NAV_PARAMS' => $arResult['REVIEWS']['NAV_PARAMS'],
                                'LEFT_LIMIT' => 4,
                                'RIGHT_LIMIT' => 4,
                                'CSS_CLASS' => 'd-xs-none d-sm-flex',
                            ],
                            false
                        );
                    }
                    ?>

                    <!-- Пагинация mobile START-->
                    <?php
                    if ($arResult['REVIEWS']['NAV_PARAMS']['END_PAGE'] > 1) {
                        $APPLICATION->IncludeComponent(
                            'public:pagination',
                            'version_one',
                            [
                                'CACHE_TIME' => 0,
                                'NAV_PARAMS' => $arResult['REVIEWS']['NAV_PARAMS'],
                                'LEFT_LIMIT' => 2,
                                'RIGHT_LIMIT' => 2,
                                'CSS_CLASS' => 'd-xs-flex d-sm-none',
                            ],
                            false
                        );
                    }
                    ?>
                    <!-- Пагинация mobile END-->
                </div>



                <!-- Оставьте отзыв mobile START-->
                <?php $APPLICATION->IncludeComponent(
                    'public:company.reviews.add.link.widget',
                    'mobile',
                    [
                        'CACHE_TYPE' => 'A',
                        'CACHE_TIME' => 0,
                        'TITLE' => 'Написать отзыв',
                        'COMPANY_ID' => (isset($arParams['COMPANY_ID']))? $arParams['COMPANY_ID'] : null,
                        'CSS_CLASS' => 'mb-xs-2 d-md-none d-sm-flex d-xs-none',
                    ],
                    false
                ); ?>
                <!-- Оставьте отзыв mobile END-->

                <div class="wrap-banner-mobile d-xs-flex d-lg-none d-sm-flex mt-md-3 mt-xs-2">
                    <div class="wrap-banner-mobile__left d-xs-flex flex-xs-column mr-md-3 mr-sm-2">
                        <?php
                        $APPLICATION->IncludeComponent(
                            'public:' . $arParams['RATING_COMPONENT'],
                            '',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 21600,
                                'COMPANY_CODE' => $arParams['COMPANY_CODE'],
                                'COMPANY_ID' => (int) $arParams['COMPANY_ID'],
                            ],
                            false
                        );
                        ?>

                        <!-- Оставьте отзыв START-->
                        <div class="card-wrapper bdrs p-md-3 p-xs-2 bg-white h-100 d-md-flex d-xs-none flex-xs-column mt-md-3">
                            <?php $APPLICATION->IncludeComponent(
                                'public:company.reviews.add.link.widget',
                                '',
                                [
                                    'CACHE_TYPE' => 'A',
                                    'CACHE_TIME' => 0,
                                    'TITLE' => 'Оставьте отзыв',
                                    'COMPANY_ID' => (isset($arParams['COMPANY_ID']))? $arParams['COMPANY_ID'] : null,
                                ],
                                false
                            ); ?>
                        </div>
                        <!-- Оставьте отзыв END-->

                        <?php $APPLICATION->IncludeComponent(
                            'public:company.reviews.add.link.widget',
                            'mobile',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 0,
                                'TITLE' => 'Написать отзыв',
                                'COMPANY_ID' => (isset($arParams['COMPANY_ID']))? $arParams['COMPANY_ID'] : null,
                                'CSS_CLASS' => 'my-xs-2 d-sm-none d-xs-flex',
                            ],
                            false
                        ); ?>
                    </div>
                    <div class="wrap-banner-mobile__right">

                        <div class="art-banner-two">
                            <?php
                            $APPLICATION->IncludeFile(
                                "/local/components/public/banners/banner_adsense.php",
                                [
                                    'ADVERTISING_BANNERS_SECTION' => (isset($arParams['ADVERTISING_BANNERS_SECTION']))? $arParams['ADVERTISING_BANNERS_SECTION'] : PUBLIC_ADVERTISING_BANNERS_REVIEWS_SECTION,
                                ]
                            );
                            ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4">
                <div class="pos-sticky d-lg-block d-xs-none">

                    <!-- Оставьте отзыв START-->
                    <div class="card-wrapper bdrs p-md-3 p-xs-2 bg-white mb-md-3">
                        <?php $APPLICATION->IncludeComponent(
                            'public:company.reviews.add.link.widget',
                            '',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 0,
                                'TITLE' => 'Оставьте отзыв',
                                'COMPANY_ID' => (isset($arParams['COMPANY_ID']))? $arParams['COMPANY_ID'] : null,
                            ],
                            false
                        ); ?>
                    </div>
                    <!-- Оставьте отзыв END-->
                    <div class="art-banner-two">
                        <?php
                        $APPLICATION->IncludeFile(
                            "/local/components/public/banners/banner_adsense.php",
                            [
                                'ADVERTISING_BANNERS_SECTION' => (isset($arParams['ADVERTISING_BANNERS_SECTION']))? $arParams['ADVERTISING_BANNERS_SECTION'] : PUBLIC_ADVERTISING_BANNERS_REVIEWS_SECTION,
                                'DOUBLE_BANNER' => true,
                            ]
                        );
                        ?>
                    </div>

                    <?php
                    $APPLICATION->IncludeComponent(
                        'public:' . $arParams['RATING_COMPONENT'],
                        '',
                        [
                            'CACHE_TYPE' => 'A',
                            'CACHE_TIME' => 21600,
                            'COMPANY_CODE' => $arParams['COMPANY_CODE'],
                            'COMPANY_ID' => (int) $arParams['COMPANY_ID'],
                        ],
                        false
                    );
                    ?>
                   </div>
            </div>
            <div class="col-xs-12">
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
        </div>
    </div>
</section>
