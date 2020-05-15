<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

BreadcrumbBuilder::setBreadcrumbs('Оплата и доставка');

?>

<section itemscope="itemscope" itemtype="http://schema.org/LocalBusiness">
    <div class="container mt-md-3 mb-md-5 mt-xs-2 mb-xs-3">
        <meta itemprop="image" content="<?= SITE_SERVER_NAME . $arResult['PREVIEW_PICTURE'] ?>" ><!-- добавить itemprop image -->
        <meta itemprop="name" content="<?php echo $arParams['TITLE'] ? $arParams['TITLE'] : 'Название отсутствует' ?>"><!-- добавить itemprop Название -->
        <meta itemprop="telephone" content="<?php echo $arResult['SUBSTITUTION_PHONE'] ? $arResult['SUBSTITUTION_PHONE'] : 'Телефон отсутствует'; ?>"><!-- добавить itemprop telephone -->
        <div class="card-wrapper card-wrapper_deliver bdrs p-md-3 p-xs-2 bg-white d-xs-flex flex-xs-wrap px-lg-4 mb-md-3 mb-xs-2">
            <div class="title title_min title_icon d-xs-flex justify-content-xs-between align-items-xs-center w-100 pt-md-3 pt-xs-2">
                <svg class="title__icon-payment mr-xs-2">
                    <use xlink:href="/assets/public/images/icon.svg#icon_payment"></use>
                </svg>
                <h2>Способы и условия оплаты</h2>
            </div>
            <div class="section w-100">
                <div class="card-wrapper__delivery row">

                    <?php if ($arResult['PAYMENT_TYPES']['VALUE']): ?>
                        <div class="col-md-6 pr-md-1 mb-xs-2">
                            <div class="card-delivery bg-body-bg bdrs h-100 px-md-3 px-xs-2">
                                <h3 class="card-delivery__title cl-link"><?= $arResult['PAYMENT_TYPES']['NAME']?></h3>
                                <ul class="card-delivery-list">
                                    <?php foreach ($arResult['PAYMENT_TYPES']['VALUE'] as $type): ?>
                                        <li class="card-delivery-list__item">
                                            <p><?= mb_ucfirst($type) ?></p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-6 pl-md-1 mb-xs-2">
                        <div class="card-delivery bg-body-bg bdrs h-100 px-md-3 px-xs-2">
                            <h3 class="card-delivery__title cl-link">Размер предоплаты:</h3>
                            <?php if ($arResult['PREPAYMENT']['PREPAYMENT_TYPE']['VALUE_XML_ID'] === 'not_prepayment' || !isset($arResult['PREPAYMENT']['PREPAYMENT_TYPE']['VALUE_XML_ID'])): ?>
                                <p>Работаем без предоплаты</p>
                            <?php else: ?>
                                <ul class="card-delivery-list">
                                    <li class="card-delivery-list__item">
                                        <p><span class="ff-b"><?= mb_ucfirst($arResult['PREPAYMENT']['PREPAYMENT_TYPE']['VALUE'])?>:</span>
                                            <?= $arResult['PREPAYMENT']['PREPAYMENT_VALUE']['VALUE']?>
                                            <?php if ($arResult['PREPAYMENT']['PREPAYMENT_TYPE']['VALUE_XML_ID'] === 'prepayment_percent'): ?>%
                                            <?php elseif ($arResult['PREPAYMENT']['PREPAYMENT_TYPE']['VALUE_XML_ID'] === 'prepayment_fix_price'): ?>
                                                руб
                                            <?php endif; ?>
                                        </p>
                                        <p><?= ($arResult['PREPAYMENT']['PREPAYMENT_COMMENT']['VALUE']['TEXT'])?></p>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($arResult['INSTALLMENT']['IS_GRANT_INSTALLMENT'] || $arResult['PARTNER_BANKS']): ?>
                        <div class="col-md-12">
                            <div class="card-delivery bg-body-bg bdrs px-md-3 px-xs-2">
                                <div class="row">
                                    <?php if ($arResult['INSTALLMENT']['IS_GRANT_INSTALLMENT']): ?>
                                        <div class="col-md-6 card-d-item">
                                            <h3 class="card-delivery__title cl-link">Рассрочка от компании:</h3>
                                                <p class="card-delivery__title-list">Предоставляется, варианты:</p>
                                                <ul class="card-delivery-list card-delivery-list_min-fz" itemprop="makesOffer">
                                                    <li class="card-delivery-list__item">
                                                        <p><?= $arResult['INSTALLMENT']['TERMS']['~VALUE']['TEXT']?></p>
                                                    </li>
                                                </ul>
                                                <?/*= $arResult['INSTALLMENT']['TERMS']['~VALUE']['TEXT']*/?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-md-6 card-d-item">
                                        <?php if ($arResult['PARTNER_BANKS']): ?>
                                            <h3 class="card-delivery__title cl-link">Рассрочка от банков-партнеров:</h3>

                                            <p class="card-delivery__title-list">Предоставляется, банки:</p>
                                            <ul class="card-delivery-list card-delivery-list_min-fz" itemprop="makesOffer">
                                                <?php foreach ($arResult['PARTNER_BANKS'] as $bank): ?>
                                                    <li class="card-delivery-list__item">
                                                        <p><?= $bank['NAME'] ?></p>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($arResult['INSTALLMENT']['IS_GRANT_INSTALLMENT']): ?>
                                        <?php if (!empty($arResult['REQUIREMENTS']['~VALUE'])): ?>
                                            <div class="col-md-6 card-d-item">
                                                <p class="card-delivery__title-list"><?= $arResult['REQUIREMENTS']['NAME'] ?>:</p>
                                                <ul class="card-delivery-list card-delivery-list_min-fz" itemprop="makesOffer">
                                                    <li class="card-delivery-list__item">
                                                        <p><?= $arResult['REQUIREMENTS']['~VALUE']['TEXT'] ?></p>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($arResult['SELECTED_REGIONS'] || $arResult['DELIVERY_TIME']): ?>
            <div class="card-wrapper card-wrapper_deliver bdrs p-md-3 p-xs-2 bg-white px-lg-4">
                <div class="title title_min title_icon d-xs-flex justify-content-xs-between align-items-xs-center pt-md-3 pt-xs-2"><svg class="title__icon-delivery mr-xs-2"><use xlink:href="/assets/public/images/icon.svg#icon_delivery"></use></svg>
                    <h2>Способы и условия доставки</h2>
                </div>
                <div class="section w-100">
                    <div class="card-wrapper__delivery row">
                        <?php if ($arResult['SELECTED_REGIONS']): ?>
                            <div class="col-md-6 pr-md-1 mb-xs-2 mb-md-0">
                                <div class="card-delivery bg-body-bg bdrs h-100 px-md-3 px-xs-2">
                                    <h3 class="card-delivery__title cl-link">Регионы и стоимость доставки:</h3>
                                    <ul class="card-delivery-list">
                                        <?php foreach ($arResult['SELECTED_REGIONS'] as $region): ?>
                                            <li class="card-delivery-list__item">
                                                <p class="ff-b"><?= $region['DELIVERY_PRICE']?> &#8381; <span class="cl-link ff-r"><?= DELIVERY_TYPES[$region['TYPE']]['ru'];  ?> <?=($region['COUNT'] && DELIVERY_TYPES[$region['TYPE']]['count']) ? $region['COUNT'] . ' шт' : ''?></span></p>
                                                <p>Регион: <?= $region['REGION_NAME']?> <?= $region['REGION_TYPE_SHORT']?></p>
                                                <p>Города и районы: <?= $region['DISTRICTS']?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($arResult['DELIVERY_TIME']): ?>
                            <div class="col-md-6 pl-md-1">
                                <div class="card-delivery card-delivery_other-list bg-body-bg bdrs h-100 px-md-3 px-xs-2">
                                    <h3 class="card-delivery__title cl-link">Время доставки заказов:</h3>
                                    <ul class="card-delivery-list">
                                        <?php foreach ($arResult['DELIVERY_TIME'] as $day => $values): ?>
                                            <li class="card-delivery-list__item">
                                                <p itemprop="openingHours"><span><?= $day?></span>с <?= $values['from']?> до <?= $values['to']?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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
