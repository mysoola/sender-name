<?php

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;

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

BreadcrumbBuilder::setBreadcrumbs('Добавить отзыв');
?>

<div class="reviews-leave-review bg-body-bg full-page">
    <div class="container my-lg-6 my-md-4">
        <div class="row">
            <div class="col-lg-offset-2 col-md-offset-1"></div>
            <div class="col-lg-8 col-md-10">
                <form data-validate="Y" class="leave-review bdrs bg-white px-md-4 ff-l js-leave-review">
                    <div class="leave-review__section-headers d-sm-none d-xs-block tx-xs-a-c bg-white ff-b cl-body-cl">
                        <div class="leave-review__mob-title js-review-mob-title">Добавить отзыв</div>
                    </div>
                    <div class="lr-section-nav d-sm-none d-xs-flex justify-content-xs-between align-items-xs-center ff-sb bg-white">
                        <a href="javascript:void(0);" data-link-leave-review-id="0"
                           class="lr-section-nav__tab-item pos-rel tdn js-link-leave-review is-active">Параметры</a>
                        <svg class="lr-section-nav__arrow lr-section-nav__arrow_review arrow-style pos-rel">
                            <use xlink:href="/assets/public/images/icon.svg#icon_icon_arrow"></use>
                        </svg>
                        <a href="javascript:void(0);" data-link-leave-review-id="1"
                           class="lr-section-nav__tab-item pos-rel tdn js-link-leave-review">Оценка</a>
                        <svg class="lr-section-nav__arrow lr-section-nav__arrow_review arrow-style pos-rel">
                            <use xlink:href="/assets/public/images/icon.svg#icon_icon_arrow"></use>
                        </svg>
                        <a href="javascript:void(0);" data-link-leave-review-id="2"
                           class="lr-section-nav__tab-item pos-rel tdn js-link-leave-review">Отзыв</a>
                    </div>
                    <div data-leave-review-id="0" data-title-rev="Добавить отзыв"
                         class="leave-review__section mb-md-5 mb-xs-3 is-show js-section-leave-review">
                        <div class="title title_l-rev d-xs-flex justify-content-xs-between align-items-xs-center">
                            <h2>Оставить отзыв о компании</h2>
                        </div>
                        <div class="lr-firs-section mt-md-3 mt-sm-2 mt-xs-3 mb-xs-3 mb-sm-0">
                            <label class="select-filter select-filter_modal-filter select-filter_leave select-filter_zi-3 mb-xs-2 mb-sm-0 js-select-filter-simple">
                                <span class="select-filter__title d-xs-block">Ваш город:</span>
                                <select name="city" class="select-filter__list" data-url="/public-ajax/city/search/in-all-cities/">
                                    <option value="<?= (!empty($arResult['CITY_DATA']))? $arResult['CITY_DATA']['ID'] : '' ?>"><?= (!empty($arResult['CITY_DATA']))? $arResult['CITY_DATA']['NAME'] : '' ?></option>
                                </select>
                            </label>
                            <div class="switch-die d-xs-flex flex-xs-wrap mb-xs-2 mb-sm-0">
                                <p class="w-100">Вы клиент компании?:</p>
                                <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                    <input type="radio"
                                           name="client"
                                           value="1"
                                           checked="checked"
                                           class="switch-die-item__input js-is-client vhidden"/>
                                    <span class="switch-die-item__bg pos-abs"></span>
                                    <span class="switch-die-item__text pos-rel">Да</span>
                                </label>
                                <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                    <input type="radio"
                                           name="client"
                                           value="0"
                                           class="switch-die-item__input js-is-client vhidden"/>
                                    <span class="switch-die-item__bg pos-abs"></span>
                                    <span class="switch-die-item__text pos-rel">Нет</span>
                                </label>
                            </div>
                            <label class="js-loading-elements-container select-filter select-filter_modal-filter select-filter_zi-2 select-filter_leave mb-xs-2 mb-sm-0 js-select-filter-simple">
                                <span class="select-filter__title d-xs-block">Компания: </span>
                                <select name="company" class="select-filter__list" data-url="/public-ajax/get-companies/">
                                    <?php if (!empty($arResult['COMPANY'])): ?>
                                        <option value="<?= $arResult['COMPANY']['ID'] ?>"><?= $arResult['COMPANY']['NAME']?></option>
                                    <?php else: ?>
                                        <option value="">Не выбрано</option>
                                    <?php endif; ?>
                                </select>
                            </label>
                            <label class="select-filter select-filter_modal-filter select-filter_zi-1 select-filter_leave js-select-filter-simple">
                                <span class="select-filter__title d-xs-block">О чем отзыв: </span>
                                <select name="services" class="select-filter__list">
                                    <option value="">Не выбрано</option>
                                    <?php foreach ($arResult['SERVICES'] as $service): ?>
                                        <option value="<?= $service['ID'] ?>" <?= ($service['SELECTED'])? 'selected' : ''?>>
                                            <?= $service['NAME'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                        <a href="javascript:;"
                           class="button d-xs-flex justify-content-xs-center align-items-xs-center tdn button button_h-46 bg-accent-d cl-white ff-sb d-sm-none js-btn-leave-review">Продолжить</a>
                    </div>
                    <div data-leave-review-id="1" data-title-rev="Оценка компании"
                         class="leave-review__section mb-md-3 mb-sm-1 mb-xs-3 js-section-leave-review">
                        <div class="title title_l-rev d-xs-flex justify-content-xs-between align-items-xs-center">
                            <h2>Оцените работу компании, ответив минимум на 3 вопроса</h2>
                        </div>
                        <div class="leave-review__help-title tx-xs-a-c d-xs-block d-sm-none bd-bottom bd-bottom_review">
                            Оцените работу компании, ответив минимум на 3 вопроса
                        </div>
                        <div class="lr-second-section mt-md-1">
                            <div class="lr-second-section__item d-xs-flex py-sm-2 align-items-sm-center bd-bottom bd-bottom bd-bottom_review flex-xs-wrap-reverse flex-sm-nowrap">
                                <div class="switch-die switch-die_leave d-xs-flex flex-xs-wrap mr-md-3 mr-sm-2 w-100">
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-order]"
                                               value="5"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Да</span>
                                    </label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-order]"
                                               value="3"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Не заню</span>
                                    </label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-order]"
                                               value="1"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Нет</span>
                                    </label>
                                </div>
                                <p class="lr-second-section__txt">Заказать оконные конструкции достаточно просто и удобно?</p>
                            </div>
                            <div class="lr-second-section__item d-xs-flex py-sm-2 align-items-sm-center bd-bottom bd-bottom bd-bottom_review flex-xs-wrap-reverse flex-sm-nowrap">
                                <div class="switch-die switch-die_leave d-xs-flex flex-xs-wrap mr-md-3 mr-sm-2 w-100">
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-services]"
                                               value="5"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Да</span></label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-services]"
                                               value="3"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Не заню</span></label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-services]"
                                               value="1"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Нет</span>
                                    </label>
                                </div>
                                <p class="lr-second-section__txt">Были предоставлены полные условия по монтажу?</p>
                            </div>
                            <div class="lr-second-section__item d-xs-flex py-sm-2 align-items-sm-center bd-bottom bd-bottom bd-bottom_review flex-xs-wrap-reverse flex-sm-nowrap">
                                <div class="switch-die switch-die_leave d-xs-flex flex-xs-wrap mr-md-3 mr-sm-2 w-100">
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-price]"
                                               value="5"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Да</span>
                                    </label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-price]"
                                               value="3"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Не заню</span></label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-price]"
                                               value="1"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Нет</span>
                                    </label>
                                </div>
                                <p class="lr-second-section__txt">Озвученные цены были реальными?</p>
                            </div>
                            <div class="lr-second-section__item d-xs-flex py-sm-2 align-items-sm-center bd-bottom bd-bottom bd-bottom_review flex-xs-wrap-reverse flex-sm-nowrap">
                                <div class="switch-die switch-die_leave d-xs-flex flex-xs-wrap mr-md-3 mr-sm-2 w-100">
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-deadline]"
                                               value="5"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Да</span>
                                    </label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-deadline]"
                                               value="3"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Не заню</span>
                                    </label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-deadline]"
                                               value="1"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Нет</span>
                                    </label>
                                </div>
                                <p class="lr-second-section__txt">Все сроки были соблюдены?</p>
                            </div>
                            <div class="lr-second-section__item d-xs-flex py-sm-2 align-items-sm-center bd-bottom bd-bottom bd-bottom_review flex-xs-wrap-reverse flex-sm-nowrap">
                                <div class="switch-die switch-die_leave d-xs-flex flex-xs-wrap mr-md-3 mr-sm-2 w-100">
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-behavior]"
                                               value="5"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Да</span>
                                    </label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel"><input
                                                type="radio"
                                                name="rating[q-behavior]"
                                                value="3"
                                                class="switch-die-item__input vhidden"/><span
                                                class="switch-die-item__bg pos-abs"></span><span
                                                class="switch-die-item__text pos-rel">Не заню</span></label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                                name="rating[q-behavior]"
                                               value="1"
                                                class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Нет</span>
                                    </label>
                                </div>
                                <p class="lr-second-section__txt">Сотрудники компании были вежливы?</p>
                            </div>
                            <div class="lr-second-section__item d-xs-flex py-sm-2 align-items-sm-center bd-bottom bd-bottom_review bd-bottom bd-bottom_last flex-xs-wrap-reverse flex-sm-nowrap">
                                <div class="switch-die switch-die_leave d-xs-flex flex-xs-wrap mr-md-3 mr-sm-2 w-100">
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-respect]"
                                               value="5"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Да</span>
                                    </label><label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-respect]"
                                               value="3"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Не заню</span>
                                    </label>
                                    <label class="switch-die-item switch-die-item_leave tx-xs-a-c pos-rel">
                                        <input type="radio"
                                               name="rating[q-respect]"
                                               value="1"
                                               class="switch-die-item__input vhidden"/>
                                        <span class="switch-die-item__bg pos-abs"></span>
                                        <span class="switch-die-item__text pos-rel">Нет</span>
                                    </label>
                                </div>
                                <p class="lr-second-section__txt">Довольны ли вы работой компании в целом?</p>
                            </div>
                            <a href="javascript:;"
                               class="button d-xs-flex justify-content-xs-center align-items-xs-center tdn button button_h-46 bg-accent-d cl-white ff-sb d-sm-none js-btn-leave-review">Продолжить</a>
                        </div>
                    </div>
                    <div data-leave-review-id="2" data-title-rev="Текст отзыва"
                         class="leave-review__section js-section-leave-review">
                        <div class="title title_l-rev d-xs-flex justify-content-xs-between align-items-xs-center">
                            <h2>Расскажите о своем опыте</h2>
                        </div>
                        <div class="lr-second-third mt-xs-2">
                            <label class="field-form field-form_rev-txt d-xs-block cl-link">Заголовок отзыва:
                                <input type="text"
                                       name="title"
                                       required="required"
                                       autocomplete="off"
                                       class="field-form__input field-form__input_review d-xs-block w-100 bg-body-bg px-xs-2 cl-body-color bdrs"/>
                                <span class="field-form__require d-xs-flex justify-content-xs-between"><span>! Обязательно</span>
                                    <span>Минимум 10 символов</span>
                                </span>
                            </label>
                            <label class="field-form d-xs-block cl-link">Подробное описание:
                                <textarea name="desc"
                                          required="required"
                                          autocomplete="off"
                                          class="field-form__textarea field-form__textarea_review d-xs-block w-100 bg-body-bg p-xs-2 cl-body-color bdrs"></textarea>
                                <span class="field-form__require d-xs-flex justify-content-xs-between">
                                    <span>!Обязательно</span>
                                    <span>Минимум 100 символов</span>
                                </span>
                            </label>
                        </div>
                        <div class="lr-send mt-md-4 mb-sm-0 mt-sm-2 mt-xs-3 my-xs-2">
                            <div class="pr-md-2 pr-lg-1">
                                <label class="form-agree form-agree_white-sm form-agree_review pos-rel d-xs-block js-form-agree-lr">
                                    <input type="checkbox"
                                           checked="checked"
                                           name="agree"
                                           class="form-agree__checkbox vhidden"/>
                                    <span class="form-agree__checkbox-imitator pos-abs bg-white d-xs-flex"></span>
                                    <span class="form-agree__text">Согласен с
                                        <a href="javascript:void(0);" class="link-accent tdn">правилами публикации отзывов </a>и <a
                                                href="http://localhost:3000/dist/reviews-leave-review.html"
                                                class="link-accent tdn">правилами оценки отзыва</a>
                                    </span>
                                </label>
                            </div>
                            <div class="mt-sm-3 mt-xs-3">
                                <a href="javascript:;"
                                   class="button button_bdd d-xs-flex justify-content-xs-center align-items-xs-center tdn button button_review tdn bg-accent-d cl-white w-100 js-button-review-send upcs <?= ($USER->IsAuthorized())? 'js-send' : 'js-auth' ?> ">Опубликовать</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <?php $APPLICATION->IncludeComponent(
            'public:breadcrumb',
            '',
            [
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => 0,
            ],
            false
        ); ?>
    </div>
</div>
