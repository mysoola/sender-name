<?php

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandlerCompatible(
    'iblock',
    'OnAfterIBlockElementUpdate',
    ['\App\Core\Events\MessageSender', 'send']
);