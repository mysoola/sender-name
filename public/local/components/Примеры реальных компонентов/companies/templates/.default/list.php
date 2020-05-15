<?php

use Bitrix\Main\Application;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$request = Application::getInstance()->getContext()->getRequest();
?>

<div class="bg-body-bg full-page">
    <?php $APPLICATION->IncludeComponent(
        'public:companies.list',
        '',
        [
            'CACHE_TYPE' => 'A',
            'SHOW_ELEMENTS_COUNT' => 20,
            'QUERY' => $request->getQuery('q'),
            'SORT_TYPE' => 'ID',
            'SORT_DIRECTION' => 'ASC',
            'PRODUCT_CODE' => $arResult['PRODUCT_CODE'],
        ],
        false
    ); ?>
</div>
