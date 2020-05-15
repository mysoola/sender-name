<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) {
    die();
}

$assets = \Bitrix\Main\Page\Asset::getInstance();

$assets->addCss('/assets/public/styles/list-constructions.min.css');
