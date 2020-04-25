<?php

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandlerCompatible(
    'iblock',
    'OnAfterIBlockElementUpdate',
    ['\App\Core\Events\MessageSender', 'send']
);


$eventManager->addEventHandlerCompatible(
    'main',
    'OnAfterUserAdd',
    ['\App\Core\Events\UserList', 'clearCache']
);
$eventManager->addEventHandlerCompatible(
    'main',
    'OnAfterUserUpdate',
    ['\App\Core\Events\UserList', 'clearCache']
);
$eventManager->addEventHandlerCompatible(
    'main',
    'OnUserDelete',
    ['\App\Core\Events\UserList', 'clearCache']
);