<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs(SITE_TEMPLATE_PATH . PUBLIC_ASSETS_PATH . 'js/user-list.js');