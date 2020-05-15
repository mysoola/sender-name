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
