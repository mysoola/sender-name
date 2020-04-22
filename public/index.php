<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<?php
$APPLICATION->IncludeComponent(
    'public:application.for.letter.name.send',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 0,
    ],
    false
);
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>