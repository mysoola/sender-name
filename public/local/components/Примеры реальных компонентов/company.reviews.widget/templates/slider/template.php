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

<?php if (!empty($arResult['REVIEWS'])): ?>
    <div class="slider-wrapper ovh js-slider-wrapper mb-md-3 mt-xs-2" data-loader="loadSlider">
        <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <meta itemprop="ratingValue" content="<?= round(collect($arResult['REVIEWS'])->avg('RATING'), 1) ?>"/>
            <meta itemprop="reviewCount" content="<?= count($arResult['REVIEWS']) ?>"/>
        </div>
        <div class="slider-wrapper__content">
            <div class="slider pos-rel owl-carousel js-review-owl-carousel">
                <?php foreach ($arResult['REVIEWS'] as $review): ?>
                    <div class="slider-item bdrs bg-white d-xs-flex flex-xs-column p-md-3 p-xs-2"
                        itemprop="review" itemscope itemtype="http://schema.org/Review"
                    >
                        <div class="who-review mt-sm-1">
                            <div class="who-review__reiting" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                <div class="review-stars d-xs-flex">
                                    <meta itemprop="worstRating" content="1"/>
                                    <meta itemprop="bestRating" content="5"/>
                                    <p itemprop="ratingValue" class="review-stars__txt"><?= $review['RATING']?></p>
                                    <?php foreach ($review['ARR_RATING'] as $rating): ?>
                                        <svg class="review-stars__icon pos-rel <?= ($rating['ACTIVE'] === true) ? 'is-active' : '' ?>">
                                            <use xlink:href="/assets/public/images/icon.svg#icon_icon_star"></use>
                                        </svg>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <p itemprop="author" class="who-review__name">
                                <?= (!empty($review['CREATED_NAME'])) ? $review['CREATED_NAME'] : '' ?><?= (!empty($review['USER_LOCATION'])) ? ', ' . $review['USER_LOCATION'] : '' ?>
                            </p>
                            <img data-src="<?= $review['COMPANY_PREVIEW_IMG'] ?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="who-review__img mb-xs-2 mb-sm-0 who-review__rating-img lazy" alt="" />
                        </div>
                        <meta itemprop="itemReviewed" content="<?= $arParams['COMPANY_NAME']?>">
                        <div itemprop="name" class="title title_min title_slider d-xs-flex justify-content-xs-between align-items-xs-center">
                            <a href="<?= $review['REVIEW_URL']?>"><?= $review['THEME']?></a>
                        </div>
                        <div class="slider-item__indent"></div>
                        <div itemprop="reviewBody" class="slider-item__block-text content content_about ovh text-ellipsis">
                            <p><?= (!empty($review['MESSAGE'])) ? $review['MESSAGE'] : '' ?></p>
                        </div>
                        <a href="<?= $review['REVIEW_URL']?>" class="slider-item__read link-accent tdn">Читать</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>