<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

<?php
$APPLICATION->IncludeComponent(
    'public:application.for.letter.name',
    '',
    [
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => 0,
        'COMPONENT_DIR' => APPLICATIONS_FOR_LETTER_NAME_DIR,
    ],
    false
);
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>