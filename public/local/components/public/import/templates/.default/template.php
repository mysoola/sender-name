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

<form enctype="multipart/form-data" method="post">
    <div class="form-group">
        <input class="form-control-file" type="file" name="xlsx">
    </div>
    <input type="submit" class="btn btn-primary" value="Отправить">
</form>

<?php if ($arResult['ERRORS']): ?>
    <p class="">Файл не сохранён, в нём найдены ошибки:</p>
    <?php foreach ($arResult['ERRORS'] as $error): ?>
        <p class="text-danger"><?= $error ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($arResult['ROWS'])): ?>
    <?php foreach ($arResult['ROWS'] as $row): ?>
        <div class="item border border-primary p-3 mb-2">
            <p>id: <?= ($row['id']) ? ($row['id']) : 'Не указан' ?></p>
            <p>name: <?= ($row['name']) ? ($row['name']) : 'Не указан' ?></p>
            <p>price: <?= ($row['price']) ? ($row['price']) : 'Не указан' ?></p>
            <p>ean: <?= ($row['ean']) ? ($row['ean']) : 'Не указан' ?></p>
            <p>estimatedCost: <?= ($row['estimatedCost']) ? ($row['estimatedCost']) : 'Не указан' ?></p>
        </div>
    <?php endforeach ?>
<?php endif; ?>
