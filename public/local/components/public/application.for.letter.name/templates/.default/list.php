<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

$this->setFrameMode(true);
?>

<?php
$APPLICATION->IncludeComponent(
    'public:application.for.letter.name.list',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 900,
    ],
    false
);
?>
