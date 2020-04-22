<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="container">
    <div class="wrapper mt-5">
        <?php if (!empty($arResult['NAMES'])): ?>
            <?php foreach ($arResult['NAMES'] as $name): ?>
                <div class="item border border-primary p-3 mb-2">
                    <p>ID: <?= $name['ID'] ?></p>
                    <p>Имя отправителя: <?= $name['NAME'] ?></p>
                    <p>Статус: <?= $name['PROPERTY_STATUS_VALUE'] ?></p>
                    <p>Дата добавления статуса: <?= $name['DATE_CREATE'] ?></p>
                    <p>Дата изменения статуса: <?= $name['TIMESTAMP_X'] ?></p>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="item border border-primary p-3 mb-2">
                <div class="lust_empty">
                    <p>Список пуст</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
