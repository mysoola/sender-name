<?php

use App\Core\Helpers\LocationHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class ReviewsComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $arDefaultUrlTemplates404 = [];
        $arSelfUrlTemplates = [
            'list' => 'index.php',
            'add' => 'dobavit/',
            'add.type-service' => '#TYPE_SERVICE_CODE#/dobavit/',
        ];

        $arVariables = [];
        $arUrlTemplates = \CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arSelfUrlTemplates);

        $componentDir = PUBLIC_COMPANIES_DIRECTORY . PUBLIC_PART_URL_FOR_COMPANY_REVIEWS . '/';
        $locationData = LocationHelper::urlGetLocationData();

        if (!empty($locationData)) {
            $componentDir = '/' . $locationData['CODE'] . $componentDir;

            $this->arResult['LOCATION_DATA'] = $locationData;
        }

        // Также необходимо добавить путь в urlrewrite.php
        $locationCode = LocationHelper::getLocationCode();
        $componentPage = \CComponentEngine::ParseComponentPath(
            $componentDir,
            $arUrlTemplates,
            $arVariables
        );

        if (isset($arVariables['REVIEW_ID'])) {
            $this->arResult['REVIEW_ID'] = $arVariables['REVIEW_ID'];
        }
        if (isset($arVariables['TYPE_SERVICE_CODE'])) {
            $this->arResult['TYPE_SERVICE_CODE'] = $arVariables['TYPE_SERVICE_CODE'];
        }

        if (!$componentPage) {
            LocalRedirect('/404.php');
        }

        $this->includeComponentTemplate($componentPage);
    }
}
