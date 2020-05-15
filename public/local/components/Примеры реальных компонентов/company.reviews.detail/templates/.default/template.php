<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;
use App\Core\Builders\UrlBuilders\CompanyUrlBuilder;

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


$companyUrl = new CompanyUrlBuilder($arParams['COMPANY_CODE']);
BreadcrumbBuilder::setBreadcrumbs('Компании', PUBLIC_COMPANIES_DIRECTORY);
BreadcrumbBuilder::setBreadcrumbs($arResult['COMPANY_NAME'], $companyUrl->getRootCompanyUrl());
BreadcrumbBuilder::setBreadcrumbs('Отзывы', $companyUrl->getCompanySectionPath(PUBLIC_PART_URL_FOR_COMPANY_REVIEWS));
BreadcrumbBuilder::setBreadcrumbs('Отзыв');

?>

<main class="bg-body-bg full-page">
    <div itemscope itemtype="http://schema.org/Organization" class="container mt-md-5 mt-xs-3">
        <meta itemprop="name" content="<?= $arResult['REVIEW']['COMPANY_NAME']?>"/>
        <meta itemprop="description" content="<?= (!empty($arResult['REVIEW']['THEME'])) ? $arResult['REVIEW']['THEME'] : '' ?>"/>
        <div class="review-container d-xs-flex flex-xs-wrap bg-white py-md-4 py-xs-3 bdrs">
            <div itemprop="review" itemscope itemtype="http://schema.org/Review"
                 class="review-container__col review-container__col_left ff-r d-xs-flex flex-xs-column">
                <meta itemprop="itemReviewed" content="<?= $arResult['REVIEW']['COMPANY_NAME']?>">
                <div class="review-brand d-xs-flex align-items-xs-center flex-xs-wrap">
                    <div class="who-review-reverse">
                        <a href="<?= $arResult['REVIEW']['COMPANY_URL'] ?>"
                           class="who-review-reverse__link mb-xs-2 mb-sm-0 mr-md-3 mr-sm-2 d-xs-flex align-items-xs-center">
                            <span class="vhidden"><?= $arResult['REVIEW']['COMPANY_NAME']?></span>
                            <img src="<?= $arResult['REVIEW']['COMPANY_PREVIEW_IMG'] ?>" class="who-review-reverse__img bdrs"
                                 alt="<?= $arResult['REVIEW']['THEME'] ?>"/></a>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"
                             class="who-review-reverse__reiting">
                            <div class="review-stars d-xs-flex">
                                <meta itemprop="worstRating" content="1"/>
                                <p itemprop="ratingValue" class="review-stars__txt"><?= $arResult['REVIEW']['RATING'] ?></p>
                                <meta itemprop="bestRating" content="5"/>

                                <?php foreach ($arResult['REVIEW']['ARR_RATING'] as $rating): ?>
                                    <svg class="review-stars__icon pos-rel <?= ($rating['ACTIVE'] === true) ? 'is-active' : '' ?>">
                                        <use xlink:href="/assets/public/images/icon.svg#icon_icon_star"></use>
                                    </svg>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <p class="who-review-reverse__name cl-link">
                            <span itemprop="author">
                                <?= (!empty($arResult['REVIEW']['CREATED_NAME'])) ? $arResult['REVIEW']['CREATED_NAME'] : '' ?><?= (!empty($arResult['REVIEW']['USER_LOCATION'])) ? ', ' . $arResult['REVIEW']['USER_LOCATION'] : '' ?>
                            </span>
                        </p>
                    </div><!-- tags-->
                    <div class="reviews-tags d-md-flex d-xs-none">
                        <div itemprop="reviewAspect"
                             class="reviews-tags__tag card-price ws-nw ml-md-1 bg-secondary-color">
                            <?= (!empty($arResult['REVIEW']['SERVICE_NAME'])) ? $arResult['REVIEW']['SERVICE_NAME'] : '' ?>
                        </div>
                    </div>
                </div>
                <div itemprop="name" class="title title_review d-xs-flex justify-content-xs-between align-items-xs-center">
                    <h1><?= (!empty($arResult['REVIEW']['THEME'])) ? $arResult['REVIEW']['THEME'] : '' ?></h1>
                </div>
                <div class="review-desc d-xs-flex">
                    <div itemprop="description" class="review-desc__text-box content-link-accent cl-link">
                        <p><?= (!empty($arResult['REVIEW']['MESSAGE'])) ? $arResult['REVIEW']['MESSAGE'] : '' ?></p>
                    </div>
                    <div class="review-desc__banner d-lg-none ml-md-3 ml-sm-2">
                        <div class="pos-sticky">
                            <?php
                            $APPLICATION->IncludeFile(
                                "/local/components/public/banners/banner_adsense.php",
                                [
                                    'ADVERTISING_BANNERS_SECTION' => PUBLIC_ADVERTISING_BANNERS_COMPANY_SECTION,
                                ]
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <?php if ($USER->IsAdmin()): ?>
                    <div class="delete-preview tx-xs-a-r">
                        <a href="javascript:;" class="tdn ff-l link-accent js-delete-review"
                           data-comment-id="<?= $arResult['REVIEW']['ID'] ?>"
                           data-company-code="<?= $arResult['REVIEW']['COMPANY_CODE'] ?>">Удалить отзыв
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($arResult['REVIEW']['COMMENTS']['ITEMS'])): ?>
                    <!-- <a name="comments"> -->
                    <div class="title d-xs-flex justify-content-xs-between align-items-xs-center">
                        <h2>Коммeнтарии</h2>
                    </div>
                    <div class="review-container__wrap-comments">
                        <ul class="review-comments">
                            <?php foreach ($arResult['REVIEW']['COMMENTS']['ITEMS'] as $comment): ?>
                                <li class="review-comments__item" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
                                    <div class="review-comment d-xs-flex">
                                        <div class="review-comment__wrap-img">
                                            <?php if ($comment['IS_COMPANY']): ?>
                                            <!-- <a name="comments-company"> -->
                                                <div class="rev-avatar d-xs-flex justify-content-xs-center align-items-xs-center">
                                                    <img class="lazy" alt="" data-src="<?= $arResult['REVIEW']['COMPANY_PREVIEW_IMG'] ?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" >
                                                </div>
                                            <?php else: ?>
                                                <div class="rev-avatar rev-avatar_no-img bg-accent d-xs-flex align-items-xs-center justify-content-xs-center pos-rel">
                                                    <svg class="rev-avatar__icon">
                                                        <use xlink:href="/assets/public/images/icon.svg#icon_avatar"></use>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="review-comment__wrap-txt cl-link">
                                            <?php if ($comment['IS_COMPANY']): ?>
                                                <p class="ff-b cl-body-color" itemprop="name"><?= $arResult['REVIEW']['COMPANY_NAME'] ?></p>
                                            <?php else: ?>
                                                <p class="ff-b cl-body-color" itemprop="name"><?= $comment['CREATED_NAME']?><?= ($comment['SECOND_NAME'])? ' ' . $comment['SECOND_NAME'] : ''?><?= ($comment['LAST_NAME'])? ' ' . $comment['LAST_NAME'] : ''?></p>
                                            <?php endif; ?>
                                            <p itemprop="about"><?= $comment['MESSAGE']?></p>
                                            <div class="review-comment__wrap-data d-xs-flex justify-content-xs-between">
                                                <p itemprop="dateCreated"><?= $comment['DATE']?> в <?= $comment['TIME']?></p>
                                                <div>
                                                    <?php if ($USER->IsAdmin()): ?>
                                                        <a href="javascript:;" class="tdn link-accent js-delete-comment mr-xs-1" 
                                                            data-comment-id="<?= $comment['ID'] ?>">Удалить </a>
                                                    <?php endif; ?>
                                                    <a href="javascript:void(0);" class="tdn link-accent js-reveiw-comment-answer">&nbsp;Ответить</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="review-container__expander"></div>
                <div class="review-container__wrap-answer">
                    <!-- форма комменариев START -->
                    <form class="review-answer-form d-xs-flex js-reveiw-answer-form">
                        <div class="review-answer-form__wrap-img">
                            <div class="rev-avatar rev-avatar_no-img bg-accent d-xs-flex align-items-xs-center justify-content-xs-center pos-rel">
                                <svg class="rev-avatar__icon"><use xlink:href="/assets/public/images/icon.svg#icon_avatar"></use></svg>
                            </div>
                        </div>
                        <label class="review-answer-form__wrap-txt w-100 d-xs-block">
                            <textarea placeholder="Написать комментарий..." 
                                      name="comment"
                                      class="review-answer-form__textarea bg-body-bg cl-link bdrs w-100 js-reviews-public-text-comment"></textarea>
                            <span class="d-xs-flex justify-content-xs-end mt-xs-2">
                                <a href="javascript:;"
                                   class="button d-xs-flex justify-content-xs-center align-items-xs-center tdn button button_rev-answer ff-l cl-white bg-accent-d upcs js-reviews-public-add-comment 
                                   <?= ($USER->IsAuthorized())? 'js-send' : 'js-auth' ?>" data-comment-id="<?= $arResult['REVIEW']['ID'] ?>">Комментировать</a>
                            </span>
                        </label>
                    </form>
                    <!-- форма комменариев END -->
                </div>
            </div>
            <div class="review-container__col review-container__col_right d-lg-block d-xs-none">
                <div class="review-container__columnn-right-content pos-sticky d-lg-block d-xs-none">
                    <!-- Оставьте отзыв START-->
                    <div class="card-wrapper bdrs p-md-3 p-xs-2 bg-body-bg mb-md-3">
                        <?php $APPLICATION->IncludeComponent(
                            'public:company.reviews.add.link.widget',
                            '',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 0,
                                'TITLE' => 'Оставьте отзыв',
                                'COMPANY_ID' => $arResult['REVIEW']['COMPANY_ID'],
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
                                    'ADVERTISING_BANNERS_SECTION' => PUBLIC_ADVERTISING_BANNERS_COMPANY_SECTION,
                                    'DOUBLE_BANNER' => true,
                            ]
                        );
                        ?>
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
</main>
