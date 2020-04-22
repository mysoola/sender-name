<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class ApplicationForLetterNameComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $arDefaultUrlTemplates404 = [];
        $arSelfUrlTemplates = [
            'list' => 'index.php',
            'send' => 'send/',
        ];

        $arVariables = [];
        $arUrlTemplates = \CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arSelfUrlTemplates);

        // Также необходимо добавить путь в urlrewrite.php
        $componentPage = \CComponentEngine::parseComponentPath(
            $this->arParams['COMPONENT_DIR'],
            $arUrlTemplates,
            $arVariables
        );

        if (!$componentPage) {
            LocalRedirect('/404.php');
        }

        $this->includeComponentTemplate($componentPage);
    }
}
