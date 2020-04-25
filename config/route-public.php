<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arRestConfig = [
    'users.list' => [
        'url' => 'users/list/',
        'component_name' => 'users.list',
        'method' => 'GET',
        'action' => 'getUsersAjax',
        'checkSession' => false,
    ],
];
