<?php

use Bitrix\Main\Application;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$request = Application::getInstance()->getContext()->getRequest();

global $APPLICATION;

$curPage = $APPLICATION->GetCurPage();

if (strpos($curPage, 'public-ajax') !== false) {

    require_once __DIR__ . '/../../config/route-public.php';

    $arDefaultUrlTemplates404 = [];
    $arSelfUrlTemplates = [];
    
    foreach ($arRestConfig as $urlName => $config) {
        $arSelfUrlTemplates[$urlName] = $config['url'];
    }


    $arVariables = [];
    $arUrlTemplates = \CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arSelfUrlTemplates);
    
    $componentPage = \CComponentEngine::ParseComponentPath(
        '/public-ajax/',
        $arUrlTemplates,
        $arVariables
    );

    $componentRestConfig = $arRestConfig[$componentPage];
    $defaultComponentNamespace = 'public';

    // Проверска активности сессии, если в конфиге стоит флажок, только для публичной части
    if (isset($arRestConfig[$componentPage]['checkSession']) && $arRestConfig[$componentPage]['checkSession'] === true) {
        if (!check_bitrix_sessid()) {
            return_json([
                'error' => true,
                'messages' => ['Ваша сессия истекла.'],
            ]);
        }
    }
} else {
    die;
}

if ($request->isAjaxRequest()) {
    $APPLICATION->RestartBuffer();

    if (strlen($componentPage) > 0) {
        if ($request->getRequestMethod() !== $componentRestConfig['method']) {
            return_json([
                'error' => true,
                'messages' => ['Неверный метод запроса'],
            ]);
        }

        $defaultParams = [
            'CACHE_TIME' => 0,
            'ACTION' => $componentRestConfig['action'],
        ];

        $resComponentNamespace = $componentRestConfig['component_namespace'] ?? $defaultComponentNamespace;

        $APPLICATION->IncludeComponent(
            $resComponentNamespace . ':' . $componentRestConfig['component_name'],
            '',
            (isset($componentRestConfig['component_params'])) ?
                array_merge($defaultParams, $componentRestConfig['component_params']) :
                $defaultParams,
            false
        );
    }
}
