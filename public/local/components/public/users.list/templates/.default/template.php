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

<?php if (!empty($arResult['USERS'])): ?>
    <div class="users-list_items">
        <?php foreach ($arResult['USERS']['ITEMS'] as $user): ?>
            <div class="users-list_item border border-primary rounded p-3 mb-2">
                <p>ID: <?= $user['ID'] ?></p>
                <p>Имя: <?= (!empty($user['NAME'])) ? $user['NAME'] : '' ?><?= (!empty($user['SECOND_NAME'])) ? ' ' . $user['SECOND_NAME'] : '' ?><?= (!empty($user['LAST_NAME'])) ? ' ' . $user['LAST_NAME'] : '' ?></p>

                <?php if (!empty($user['PERSONAL_PHONE'])): ?>
                    <p>Телефон: <?= $user['PERSONAL_PHONE'] ?></p>
                <?php endif; ?>

                <?php if (!empty($user['PERSONAL_PHONE'])): ?>
                    <p>E-mail: <?= $user['EMAIL'] ?></p>
                <?php endif; ?>

                <p>Активен: <?= ($user['ACTIVE']) ? 'да' : 'нет' ?></p>
                <p>Дата регистрации: <?= $user['DATE_REGISTER'] ?></p>
            </div>
        <? endforeach; ?>
    </div>
<?php endif; ?>

<?php

if ($arResult['USERS']['NAV_PARAMS']['END_PAGE'] > 1) {
    $APPLICATION->IncludeComponent(
        'public:pagination',
        'version_ajax',
        [
            'CACHE_TIME' => 0,
            'NAV_PARAMS' => $arResult['USERS']['NAV_PARAMS'],
            'LEFT_LIMIT' => 4,
            'RIGHT_LIMIT' => 4,
        ],
        false
    );
}
?>
