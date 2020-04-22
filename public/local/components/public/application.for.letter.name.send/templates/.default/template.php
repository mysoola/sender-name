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
    <div class="form_wrapper mt-5 w-50">
        <?php if ($arResult['STATUS'] === true): ?>
            <div class="success">
                <p>Имя отправлено на модерацию.</p>
                <p><a href="<?= APPLICATIONS_FOR_LETTER_NAME_DIR ?>">Перейти в список</a></p>
            </div>
        <?php else: ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Имя отправителя (буквенное имя)</label>
                    <input id="name" type="text" name="name" class="form-control"
                           value="<?= (!empty($arResult['NAME'])) ? $arResult['NAME'] : '' ?>" placeholder="Введите имя">
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>

            <?php if (!empty($arResult['ERRORS'])): ?>
                <?php foreach ($arResult['ERRORS'] as $error): ?>
                    <p class="error"><?= $error ?></p>
                <?php endforeach ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
