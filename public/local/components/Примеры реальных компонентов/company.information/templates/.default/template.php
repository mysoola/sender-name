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

<div class="card-wrapper bg-white bdrs p-md-3 p-xs-2 my-md-3 my-xs-2">
    <div
        class="title title_min d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
        <h2>Общая информация о компании</h2>
    </div>
    <div class="table">
        <table>
            <tbody>
            <?php if (!empty($arResult['LOCATION'])): ?>
                <tr>
                    <td>
                        <p>Головной офис</p>
                    </td>
                    <td>
                        <p itemprop="address"><?= $arResult['LOCATION']['INDEX'] ?>,
                            г. <?= $arResult['LOCATION']['CITY'] ?>,
                            ул. <?= $arResult['LOCATION']['STREET'] ?>,
                            д. <?= $arResult['LOCATION']['BUILDING'] ?>
                        </p>
                    </td>
                </tr>
            <?php endif ?>

            <?php if ($arResult['SUBSTITUTION_PHONE']): ?>
                <tr>
                    <td>
                        <p>Телефон</p>
                    </td>
                    <td>
                        <p itemprop="telephone"><?= $arResult['SUBSTITUTION_PHONE'] ?></p>
                    </td>
                </tr>
            <?php endif ?>

            <?php if ($arResult['SITE_LINK']): ?>
                <tr>
                    <td>
                        <p>Официальный сайт</p>
                    </td>
                    <td>
                        <p>
                            <a itemprop="url" target="_blank" href="<?= REDIRECT_URL_PATH ?><?= $property['VALUE'] ?><?= $arResult['SITE_LINK'] ?>">Перейти</a>
                        </p>
                    </td>
                </tr>
            <?php endif ?>

            <?php if ($arResult['DELIVERY_TYPES']): ?>
                <tr>
                    <td>
                        <p>Доставка</p>
                    </td>
                    <td>
                        <?php foreach ($arResult['DELIVERY_TYPES'] as $deliveryType): ?>
                            <p><?= $deliveryType['NAME'] ?></p>
                        <?php endforeach ?>
                    </td>
                </tr>
            <?php endif ?>

            <!--<tr>
                <td>
                    <p>Реквизиты</p>
                </td>
                <td>
                    <p><a href="<?/*= PUBLIC_PART_URL_FOR_REQUISITES_COMPANY*/?>/">Перейти</a></p>
                </td>
            </tr>-->

            </tbody>
        </table>
    </div>
</div>