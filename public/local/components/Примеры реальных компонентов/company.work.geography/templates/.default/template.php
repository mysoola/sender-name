<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>


<section>
    <div class="container mt-md-3 mb-md-5 mt-xs-2 mb-xs-3">
        <?php if (!empty($arResult['REGIONS'])): ?>
            <div class="card-wrapper card-wrapper_geogr bdrs p-md-3 p-xs-2 bg-white px-lg-4">
                <div class="title title_min title_icon d-xs-flex justify-content-xs-between align-items-xs-center pt-md-3 pt-xs-2"><svg class="title__icon mr-xs-2"><use xlink:href="/assets/public/images/icon.svg#icon_geotag"></use></svg>
                    <h2>Домашний субъект РФ и регионы работы</h2>
                </div>
                <div class="card-list-subjects d-xs-flex flex-xs-wrap flex-xs-wrap ff-r">
                    <?php foreach ($arResult['REGIONS'] as $region): ?>
                        <div class="card-list-subjects__item mt-md-3 mt-xs-2">
                            <p class="card-list-subjects__title" itemprop="areaServed"><?= $region['REGION_NAME']?></p>
                            <?php if ($region): ?>
                                <div class="card-list-subjects__tag card-tag bg-secondary-color bdrs ff-r d-xs-inline-block">Домашний субъект</div>
                            <?php endif; ?>
                            <ul class="card-list card-list_geog card-list_disc">
                                <!-- Если нужна ссылка черного цвета то у ссылки ставил класс link если синего то link-accent-->
                                <?php foreach ($region['LOCATIONS'] as $location): ?>
                                    <li itemprop="areaServed"><?= $location ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
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
        <?php endif; ?>
    </div>
</section>