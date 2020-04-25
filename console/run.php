#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use App\Core\Commands\Export\UsersExportCommand;

$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__) . '/../public');
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

set_time_limit(0);
ini_set("memory_limit", "1000M");


$app = new Application();

// Регистрация команд
$app->add(new UsersExportCommand());


$app->run();

require_once $DOCUMENT_ROOT . "/public/bitrix/modules/main/include/epilog_after.php";
