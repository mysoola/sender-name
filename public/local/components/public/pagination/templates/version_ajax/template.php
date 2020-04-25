<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>

<div class="page-navigation-nums d-flex justify-content-center d-flex align-items-center">
    <?php if ($arResult['PER_PAGE'] > $arResult['ELEMENTS_COUNT'] || $arResult['PAGE'] == 1): ?>
        <div class="arrow-box pos-rel tdn">
            <svg class="arrow-box__icon arrow-box__icon_left arrow-style gray">
                <use xlink:href="<?= SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH ?>images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </div>
    <?php elseif ($arResult['PAGE'] == $arResult['END_PAGE']): ?>
        <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . $arResult['PREV_PAGE'], [$arResult['PAGE_PARAM_NAME']]) ?>"
           class="arrow-box pos-rel tdn js-page-navigation__item" data-page="<?= $arResult['PREV_PAGE'] ?>">
            <span class="vhidden">Предыдущая страница</span>
            <svg class="arrow-box__icon arrow-box__icon_left arrow-style">
                <use xlink:href="<?= SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH ?>images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </a>
    <?php else : ?>
        <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . $arResult['PREV_PAGE'], [$arResult['PAGE_PARAM_NAME']]) ?>"
           class="arrow-box pos-rel tdn js-page-navigation__item" data-page="<?= $arResult['PREV_PAGE'] ?>">
            <span class="vhidden">Предыдущая страница</span>
            <svg class="arrow-box__icon arrow-box__icon_left arrow-style">
                <use xlink:href="<?= SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH ?>images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </a>
    <?php endif ?>

    <div class="page-navigation-nums">
        <?php
        $i = 1;
        $leftLimit = $arParams['LEFT_LIMIT'];
        $rightLimit = $arParams['RIGHT_LIMIT'];

        if ($arResult['PAGE'] > $leftLimit + 1) {
            ?>

            <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . 1, [$arResult['PAGE_PARAM_NAME']]) ?>"
               class="page-navigation-nums__num js-page-navigation__item" data-page="1">1</a>
            <div class="page-navigation-nums__dots">...</div>

            <?php
        }

        do {
            if ($i < $arResult['PAGE'] && $arResult['PAGE'] <= $leftLimit) {
                ?>
                <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . $i, [$arResult['PAGE_PARAM_NAME']]) ?>"
                   class="page-navigation-nums__num js-page-navigation__item" data-page="<?= $i ?>"><?= $i ?></a>
                <?php
            } elseif ($arResult['PAGE'] > $leftLimit && $i > ($arResult['PAGE'] - $leftLimit) && $i < $arResult['PAGE']) {
                ?>
                <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . $i, [$arResult['PAGE_PARAM_NAME']]) ?>"
                   class="page-navigation-nums__num js-page-navigation__item" data-page="<?= $i ?>"><?= $i ?></a>
                <?php
            }

            if ($i == $arResult['PAGE']) {
                ?>
                <div class="page-navigation-nums__num js-page-navigation__item is-active"><?= $i ?></div>
                <?php
            }

            if ($i > $arResult['PAGE'] && $i < ($arResult['PAGE'] + $rightLimit) && $i > ($arResult['PAGE'] - 1) && $arResult['PAGE'] != $arResult['END_PAGE']) {
                ?>
                <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . $i, [$arResult['PAGE_PARAM_NAME']]) ?>"
                   class="page-navigation-nums__num js-page-navigation__item" data-page="<?= $i ?>"><?= $i ?></a>
                <?php
            }

            $i++;
        } while ($i <= $arResult['END_PAGE']);

        if ($arResult['PAGE'] < ($arResult['END_PAGE'] - $rightLimit)) {
            ?>
            <div class="page-navigation-nums__dots">...</div>
            <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . $arResult['END_PAGE'], [$arResult['PAGE_PARAM_NAME']]) ?>"
               class="page-navigation-nums__num js-page-navigation__item"
               data-page="<?= $arResult['END_PAGE'] ?>"><?= $arResult['END_PAGE'] ?></a>
            <?php
        }
        ?>

    </div>

    <?php if ($arResult['PER_PAGE'] > $arResult['ELEMENTS_COUNT']): ?>
        <div class="arrow-box pos-rel tdn">
            <svg class="arrow-box__icon arrow-box__icon_right arrow-style">
                <use xlink:href="<?= SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH ?>images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </div>
    <?php elseif ($arResult['PAGE'] == 1): ?>
        <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . $arResult['NEXT_PAGE'], [$arResult['PAGE_PARAM_NAME']]) ?>"
           class="arrow-box pos-rel tdn js-page-navigation__item" data-page="<?= $arResult['NEXT_PAGE'] ?>">
            <span class="vhidden">Следующая страница</span>
            <svg class="arrow-box__icon arrow-box__icon_right arrow-style">
                <use xlink:href="<?= SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH ?>images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </a>
    <?php elseif ($arResult['PAGE'] == $arResult['END_PAGE']): ?>
        <div class="arrow-box pos-rel tdn">
            <svg class="arrow-box__icon arrow-box__icon_right arrow-style gray">
                <use xlink:href="<?= SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH ?>images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </div>
    <?php else : ?>
        <a href="<?= $APPLICATION->GetCurPageParam($arResult['PAGE_PARAM_NAME'] . '=' . $arResult['NEXT_PAGE'], [$arResult['PAGE_PARAM_NAME']]) ?>"
           class="arrow-box pos-rel tdn js-page-navigation__item" data-page="<?= $arResult['NEXT_PAGE'] ?>">
            <span class="vhidden">Следующая страница</span>
            <svg class="arrow-box__icon arrow-box__icon_right arrow-style">
                <use xlink:href="<?= SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH ?>images/icon.svg#icon_icon_arrow"></use>
            </svg>
        </a>
    <?php endif ?>
</div>