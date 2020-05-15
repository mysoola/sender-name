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
<?php if ($arResult['RATING_REVIEWS']): ?>
    <div class="card-wrapper bdrs p-md-3 p-xs-2 bg-white">
        <div class="title title_min title_rating-reviews d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
            <h2>Рейтинг отзывов по оценкам</h2>
        </div>
        <ul class="rating-reviews bg-white bdrs">
            <?php foreach ($arResult['RATING_REVIEWS'] as $rating => $review): ?>
                <li class="rating-reviews__item d-xs-flex justify-content-xs-between align-items-xs-center js-rating-reviews-list">
                    <div class="review-stars d-xs-flex">
                        <p class="review-stars__txt"><?= $rating ?></p>
                        <?php for ($i = 1; $i < 6; $i++): ?>
                            <svg class="review-stars__icon pos-rel <?= ($i <= $rating)? 'is-active': ''?>">
                                <use xlink:href="/assets/public/images/icon.svg#icon_icon_star"></use>
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <?php if ($review['COUNT'] > 0): ?>
                        <a href="<?= $review['URL'] ?>" class="r-reviews-links link-accent tdn tx-a-xs-end">
                            <span class="r-reviews-links__quatity js-r-review-quantity"><?= $review['COUNT'] ?> </span>
                            <span class="r-reviews-links__txt js-r-review-txt"><?= num2word($review['COUNT'], ['отзыв', 'отзыва', 'отзывов']) ?></span>
                        </a>
                    <?php else: ?>
                        <div class="r-reviews-links tdn tx-a-xs-end">
                            <span class="r-reviews-links__quatity js-r-review-quantity"><?= $review['COUNT'] ?> </span>
                            <span class="r-reviews-links__txt js-r-review-txt"><?= num2word($review['COUNT'], ['отзыв', 'отзыва', 'отзывов']) ?></span>
                        </div>
                    <?php endif;?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>