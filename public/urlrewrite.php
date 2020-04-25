<?php
$arUrlRewrite = array(
    0 =>
        array(
            'CONDITION' => '#^/rest/#',
            'RULE' => '',
            'ID' => NULL,
            'PATH' => '/bitrix/services/rest/index.php',
            'SORT' => 100,
        ),
    1 =>
        array(
            'CONDITION' => '#^/application-for-letter-name/#',
            'RULE' => '',
            'ID' => 'application.for.letter.name',
            'PATH' => '/application-for-letter-name/index.php',
            'SORT' => 100,
        ),
    2 =>
        array (
            'CONDITION' => '#^/public-ajax/#',
            'RULE' => '',
            'ID' => '',
            'PATH' => '/ajax/route.php',
            'SORT' => 100,
        ),
);
