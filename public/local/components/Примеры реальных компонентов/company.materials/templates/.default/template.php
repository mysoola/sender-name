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

<div class="card-wrapper bg-white bdrs p-md-3 p-xs-2 my-md-3 my-xs-2">
    <div class="title title_min d-xs-flex justify-content-xs-between align-items-xs-center py-md-3">
        <h2>Материалы</h2>
    </div>
    <div class="table">
        <div class="table__title">Продукция</div>
        <table>
            <tbody>
                <?php if ($arResult['PROFILES']): ?>
                    <tr>
                        <td>
                            <p>Профили</p>
                        </td>
                        <td>
                            <ul>
                                <!-- <div class="public-company-material-wrap"> -->
                                <?php foreach ($arResult['PROFILES'] as $material => $brands): ?>
                                    <?/*
                                    <div class="public-company-material-title">Материал <?= MATERIAL_TYPES[$material] ?></div>
                                    */?>
                                    <li>
                                        <span class="d-xs-block">Материал <?= MATERIAL_TYPES[$material] ?></span>
                                        <p>
                                            <?php foreach ($brands as $brand => $lines): ?>
                                                <span>
                                                    <?= $brand ?> (линейки
                                                    <?php foreach ($lines as $line): ?><?= $line ?><?php if (next($lines)): ?>,&nbsp;<?php endif?><?php endforeach ?>)<?php if (next($brands)): ?>,&nbsp;<?php endif?>
                                                </span>
                                            <?php endforeach ?>
                                        </p>
                                    </li>
                                <?php endforeach ?>
                            <!-- </div> -->
                            </ul>
                        </td>
                    </tr>
                <?php endif?>

                <?php if ($arResult['GLASSES']): ?>
                    <tr>
                        <td>
                            <p>Стекло и стеклопакеты</p>
                        </td>
                        <td>
                            <ul>
                                <?php foreach ($arResult['GLASSES'] as $type => $glasses): ?>
                                    <li>
                                        <p><?= $type?></p>
                                        <ul>
                                            <?php foreach ($glasses as $width => $formulae): ?>
                                            <li>
                                                <p><?= $width?> мм
                                                    (формула
                                                    <?php foreach ($formulae as $formula): ?>
                                                        <span>
                                                            <?= $formula?>
                                                            <?php if (next($formulae)): ?>,&nbsp;<?php endif?>
                                                        </span>
                                                    <?php endforeach ?>
                                                    )
                                                </p>
                                            </li>
                                            <?php endforeach ?>
                                        </ul>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </td>
                    </tr>
                <?php endif?>

                <?php if ($arResult['SILLS']): ?>
                    <tr>
                        <td>
                            <p>Подоконники</p>
                        </td>
                        <td>
                            <p>
                                <?php foreach ($arResult['SILLS'] as $sill): ?><?= $sill?><?php if (next($arResult['SILLS'])): ?>,&nbsp;
                                <?php endif?>
                                <?php endforeach?>
                            </p>
                        </td>
                    </tr>
                <?php endif?>
            </tbody>
        </table>
    </div>
</div>
