<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$APPLICATION->IncludeComponent(
    'public:company.reviews.add',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 0,
        'TYPE_SERVICE_CODE' => $arResult['TYPE_SERVICE_CODE'],
    ],
    false
);

$APPLICATION->IncludeComponent(
    'public:sms.login',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 0,
    ],
    false
);
