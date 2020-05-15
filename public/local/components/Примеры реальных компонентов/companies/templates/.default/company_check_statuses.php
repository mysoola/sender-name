<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
} ?>

<div class="title title_min d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
    <h2>Статусы проверки</h2>
</div>

<?php if ($arParams['PHONE_CHECKED']): ?>
    <div class="card-contacts__list-item pos-rel">
        <svg class="card-contacts__icon pos-abs is-confirm">
            <use xlink:href="/assets/public/images/icon.svg#icon_icon_phone"></use>
        </svg>
        <p class="card-contacts__text">Номер телефона подтвержден</p>
    </div>
<?php else: ?>
    <div class="card-contacts__list-item pos-rel">
        <svg class="card-contacts__icon pos-abs">
            <use xlink:href="/assets/public/images/icon.svg#icon_icon_phone"></use>
        </svg>
        <p class="card-contacts__text">Номер телефона не подтвержден</p>
    </div>
<?php endif ?>

<?php if ($arParams['EMAIL_CHECKED']): ?>
    <div class="card-contacts__list-item pos-rel">
        <svg class="card-contacts__icon pos-abs is-confirm">
            <use xlink:href="/assets/public/images/icon.svg#icon_icon_email"></use>
        </svg>
        <p class="card-contacts__text">Email подтвержден</p>
    </div>
<?php else: ?>
    <div class="card-contacts__list-item pos-rel">
        <svg class="card-contacts__icon pos-abs">
            <use xlink:href="/assets/public/images/icon.svg#icon_icon_email"></use>
        </svg>
        <p class="card-contacts__text">Email не подтвержден</p>
    </div>
<?php endif ?>

<?php if ($arParams['REQUISITES_CHECKED']): ?>
    <div class="card-contacts__list-item pos-rel pos-rel">
        <svg class="card-contacts__icon pos-abs is-confirm">
            <use xlink:href="/assets/public/images/icon.svg#icon_icon_doc"></use>
        </svg>
        <p class="card-contacts__text">Реквизиты проверены</p>
    </div>
<?php else: ?>
    <div class="card-contacts__list-item pos-rel">
        <svg class="card-contacts__icon pos-abs">
            <use xlink:href="/assets/public/images/icon.svg#icon_icon_doc"></use>
        </svg>
        <p class="card-contacts__text">Реквизиты не проверены</p>
    </div>
<?php endif ?>
