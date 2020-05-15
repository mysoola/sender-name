<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

$this->setFrameMode(true);
?>

<?php
$APPLICATION->IncludeComponent(
    'public:company.header',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 86400,
        'TITLE' => $arResult['COMPANY_DATA']['NAME'],
        'PREVIEW_PICTURE' => $arResult['COMPANY_DATA']['PREVIEW_PICTURE'],
        'SHORT_COMPANY_NAME' => $arResult['COMPANY_DATA']['SHORT_COMPANY_NAME']['~VALUE'],
        'SUBSTITUTION_PHONE' => $arResult['COMPANY_DATA']['SUBSTITUTION_PHONE']['VALUE'],
    ],
    false
);
?>

<?php $APPLICATION->IncludeComponent("bitrix:menu", "public-company-top-menu", [
    'ROOT_MENU_TYPE' => 'company',
    'MAX_LEVEL' => 1,
    'USE_EXT' => 'N',
    'DELAY' => 'N',
    'ALLOW_MULTI_SELECT' => 'N',
    'MENU_CACHE_TYPE' => 'A',
    'MENU_CACHE_TIME' => 8600,
    'MENU_CACHE_USE_GROUPS' => 'Y',
    'MENU_CACHE_GET_VARS' => '',
    'COMPANY_CODE' => $arResult['COMPANY_CODE'],
    'COMPANY_ID' => $arResult['COMPANY_DATA']['ID'],
    'PAGE' => PUBLIC_PART_URL_FOR_ABOUT,
]);?>

<section class="section pos-rel zi-1">
    <div class="container">
        <div class="row mt-md-3 mt-xs-2">
            <div class="col-xl-9">

                <?php
                $APPLICATION->IncludeComponent(
                    'public:company.prices',
                    '',
                    [
                        'CACHE_TYPE' => 'A',
                        'CACHE_TIME' => 86400,
                        'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
                        'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
                    ],
                    false
                );
                ?>

                <div class="row mt-md-3 mt-xs-2">
                    <div class="col-xl-12 col-lg-8">
                        <?php if ($arResult['COMPANY_DATA']['DETAIL_TEXT']): ?>
                            <div class="card-wrapper bg-white bdrs p-md-3 p-xs-2 mb-md-3">
                                <div class="title title_min d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
                                    <h2>О компании</h2>
                                </div>
                                <div class="about-company bdrs bg-white js-about-company">
                                    <div id="about-more" class="about-company__content content content_about js-about-company-content">
                                        <?= $arResult['COMPANY_DATA']['DETAIL_TEXT']?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <div class="row d-lg-none d-xs-flex"> <!-- mt-md-3 mt-xs-2 -->
                            <div class="col-md-6 col-xs-12 mb-md-3 mb-xs-2">
                                <div class="card-contacts bg-white bdrs px-md-3 pb-md-3 px-xs-2 pb-xs-2 h-100">
                                    <?php
                                    $APPLICATION->IncludeFile(
                                        $this->GetFolder() ."/company_check_statuses.php",
                                        [
                                            'PHONE_CHECKED' => $arResult['COMPANY_DATA']['PHONE_CHECKED'],
                                            'EMAIL_CHECKED' => $arResult['COMPANY_DATA']['EMAIL_CHECKED'],
                                            'REQUISITES_CHECKED' => $arResult['COMPANY_DATA']['REQUISITES_CHECKED'],
                                        ]
                                    );
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-6 col-xs-12 mb-md-3 mb-xs-2">
                                <?php
                                $APPLICATION->IncludeComponent(
                                    'public:company.rating.reviews',
                                    '',
                                    [
                                        'CACHE_TYPE' => 'A',
                                        'CACHE_TIME' => 21600,
                                        'COMPANY_CODE' => $arResult['COMPANY_CODE'],
                                        'COMPANY_ID' => (int) $arResult['COMPANY_DATA']['ID'],
                                    ],
                                    false
                                );
                                ?>
                            </div>
                        </div>

                        <?php
                        $APPLICATION->IncludeComponent(
                            'public:company.offices',
                            '',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 86400,
                                'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
                                'COMPANY_CODE' => $arResult['COMPANY_CODE'],
                            ],
                            false
                        );
                        ?>

                        <?php
                        $APPLICATION->IncludeComponent(
                            'public:company.reviews.widget',
                            'slider',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 3600,
                                'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
                                'REVIEWS_COUNT' => 10,
                                'COMPANY_NAME' => $arResult['COMPANY_DATA']['NAME'],
                            ],
                            false
                        );
                        ?>

                        <?php
                        $APPLICATION->IncludeComponent(
                            'public:company.information',
                            '',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 86400,
                                'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
                                'SITE_LINK' => $arResult['COMPANY_DATA']['SITE_LINK']['VALUE'],
                                'SUBSTITUTION_PHONE' => $arResult['COMPANY_DATA']['SUBSTITUTION_PHONE']['VALUE'],
                            ],
                            false
                        );
                        ?>

                        <?php
                        $APPLICATION->IncludeComponent(
                            'public:company.materials',
                            '',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 86400,
                                'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
                            ],
                            false
                        );
                        ?>

                        <?php
                        $APPLICATION->IncludeComponent(
                            'public:company.services',
                            '',
                            [
                                'CACHE_TYPE' => 'A',
                                'CACHE_TIME' => 86400,
                                'COMPANY_ID' => (int)$arResult['COMPANY_DATA']['ID'],
                            ],
                            false
                        );
                        ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="pos-sticky d-xl-none d-lg-block d-xs-none">
                            <div class="card-contacts bg-white bdrs px-md-3 pb-md-3 px-xs-2 pb-xs-2 mb-md-3">
                                <?php
                                $APPLICATION->IncludeFile(
                                    $this->GetFolder() ."/company_check_statuses.php",
                                    [
                                        'PHONE_CHECKED' => $arResult['COMPANY_DATA']['PHONE_CHECKED'],
                                        'EMAIL_CHECKED' => $arResult['COMPANY_DATA']['EMAIL_CHECKED'],
                                        'REQUISITES_CHECKED' => $arResult['COMPANY_DATA']['REQUISITES_CHECKED'],
                                    ]
                                );
                                ?>
                            </div>

                            <div class="art-banner-two">
                                <?php
                                    $APPLICATION->IncludeFile(
                                        "/local/components/public/banners/banner_adsense.php",
                                        [
                                            'ADVERTISING_BANNERS_SECTION' => PUBLIC_ADVERTISING_BANNERS_COMPANY_SECTION,
                                        ]
                                    );
                                ?>
                            </div>

                            <?php
                            $APPLICATION->IncludeComponent(
                                'public:company.rating.reviews',
                                '',
                                [
                                    'CACHE_TYPE' => 'A',
                                    'CACHE_TIME' => 21600,
                                    'COMPANY_CODE' => $arResult['COMPANY_CODE'],
                                    'COMPANY_ID' => (int) $arResult['COMPANY_DATA']['ID'],
                                ],
                                false
                            );
                            ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-3">
                <div class="pos-sticky d-xl-block d-xs-none">
                    <div class="card-contacts bg-white bdrs px-md-3 pb-md-3 px-xs-2 pb-xs-2 mb-md-3">
                        <?php
                        $APPLICATION->IncludeFile(
                            $this->GetFolder() ."/company_check_statuses.php",
                        [
                            'PHONE_CHECKED' => $arResult['COMPANY_DATA']['PHONE_CHECKED'],
                            'EMAIL_CHECKED' => $arResult['COMPANY_DATA']['EMAIL_CHECKED'],
                            'REQUISITES_CHECKED' => $arResult['COMPANY_DATA']['REQUISITES_CHECKED'],
                        ]
                        );
                        ?>
                    </div>

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

                    <?php
                    $APPLICATION->IncludeComponent(
                        'public:company.rating.reviews',
                        '',
                        [
                            'CACHE_TYPE' => 'A',
                            'CACHE_TIME' => 21600,
                            'COMPANY_CODE' => $arResult['COMPANY_CODE'],
                            'COMPANY_ID' => (int) $arResult['COMPANY_DATA']['ID'],
                        ],
                        false
                    );
                    ?>
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
</section>
