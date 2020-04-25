<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;

$assets = Asset::getInstance();

/**
 * CSS
 */

$assets->addCss('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');
$assets->addCss(SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH . 'css/styles.css');

/**
 * JS
 */
$assets->addJs('http://code.jquery.com/jquery-latest.min.js');
$assets->addJs('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js');
?>

<html lang="ru">
<head>
    <meta charset="utf-8">
    <?php $APPLICATION->ShowTitle() ?>
    <? $APPLICATION->ShowMeta("description") ?>

    <? $APPLICATION->ShowHead() ?>
</head>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <? $APPLICATION->ShowMeta("description") ?>
    <meta name="format-detection" content="telephone=no">
    <?php $APPLICATION->ShowTitle() ?>
    <!--Favicon-->
</head>

<body>
<div class=""><?php $APPLICATION->ShowPanel(); ?></div>