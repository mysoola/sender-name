<?php

namespace Keyba\Components;

use App\Core\Builders\Breadcrumbs\BreadcrumbBuilder;
use App\Core\Builders\UrlBuilders\CompanyUrlBuilder;
use App\Core\Configs\ProductConfig;
use App\Core\Helpers\CompaniesFilterHelper;
use App\Core\Helpers\LocationHelper;
use App\Core\Models\Company;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompaniesComponent extends \CBitrixComponent
{
    protected $arUserCompany;

    public function executeComponent()
    {
        $arDefaultUrlTemplates404 = [];
        $arSelfUrlTemplates = [
            'list' => 'index.php',
            'about' => '#COMPANY_CODE#/',
            'offices-list' => '#COMPANY_CODE#/' . PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY . '/',
            'office-detail' => '#COMPANY_CODE#/' . PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY . '/#OFFICE_ID#/',
            'requisites' => '#COMPANY_CODE#/' . PUBLIC_PART_URL_FOR_REQUISITES_COMPANY . '/',
            'reviews' => '#COMPANY_CODE#/' . PUBLIC_PART_URL_FOR_COMPANY_REVIEWS . '/',
            'work-geography' => '#COMPANY_CODE#/' . PUBLIC_PART_URL_FOR_COMPANY_WORK_GEOGRAPHY . '/',
            'payment-delivery' => '#COMPANY_CODE#/' . PUBLIC_PART_URL_FOR_COMPANY_PAYMENT_DELIVERY . '/',
            'product-prices' => '#COMPANY_CODE#/#PRODUCT_CODE#/#STRUCTURE_ID#/',
            'product-card' => '#COMPANY_CODE#/#PRODUCT_CODE#/#STRUCTURE_ID#/#STR_PRODUCT_PARAMS#/',
        ];

        $arProductCodes = CompaniesFilterHelper::getFilterProductCodes();

        foreach ($arProductCodes as $arProductCode) {
            $arSelfUrlTemplates[$arProductCode] = '#COMPANY_CODE#/' . $arProductCode . '/';
        }

        $arVariables = [];
        $arUrlTemplates = \CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arSelfUrlTemplates);

        // Также необходимо добавить путь в urlrewrite.php
        $componentPage = \CComponentEngine::parseComponentPath(
            $this->arParams['COMPONENT_DIR'],
            $arUrlTemplates,
            $arVariables
        );



        if (isset($arVariables['COMPANY_CODE']) && $componentPage != 'list' && !in_array($arVariables['COMPANY_CODE'], $arProductCodes)) {
            $this->arResult['COMPANY_CODE'] = $arVariables['COMPANY_CODE'];

            $companyModel = new Company();
            $companyId = $companyModel->getCompanyIdByCode($arVariables['COMPANY_CODE']);

            if ($companyId) {
                $this->arResult['COMPANY_DATA'] = $companyModel->getCompanyByIdFull($companyId);

                $this->arResult['COMPANY_DATA']['PHONE_CHECKED'] = ($this->arResult['COMPANY_DATA']['PHONE_CHECKED']['VALUE'] === 'Y');
                $this->arResult['COMPANY_DATA']['EMAIL_CHECKED'] = ($this->arResult['COMPANY_DATA']['EMAIL_CHECKED']['VALUE'] === 'Y');
                $this->arResult['COMPANY_DATA']['REQUISITES_CHECKED'] = ($this->arResult['COMPANY_DATA']['REQUISITES_CHECKED']['VALUE'] === 'Y');
            } else {
                LocalRedirect('/404.php');
            }

            $companyUrl = new CompanyUrlBuilder($this->arResult['COMPANY_CODE']);
            BreadcrumbBuilder::setBreadcrumbs('Компании', PUBLIC_COMPANIES_DIRECTORY);
            BreadcrumbBuilder::setBreadcrumbs($this->arResult['COMPANY_DATA']['NAME'], $companyUrl->getRootCompanyUrl());
        }

        if (isset($arVariables['OFFICE_ID'])) {
            $this->arResult['OFFICE_ID'] = $arVariables['OFFICE_ID'];
        }

        if (isset($arVariables['PRODUCT_CODE'])) {
            $this->arResult['PRODUCT_CODE'] = $arVariables['PRODUCT_CODE'];
        }

        if (isset($arVariables['STR_PRODUCT_PARAMS'])) {
            $this->arResult['STR_PRODUCT_PARAMS'] = $arVariables['STR_PRODUCT_PARAMS'];
        }

        if (isset($arVariables['STRUCTURE_ID'])) {
            $this->arResult['STRUCTURE_ID'] = $arVariables['STRUCTURE_ID'];
        }

        if (!$componentPage) {
            LocalRedirect('/404.php');
        }

        if (isset($arVariables['COMPANY_CODE'])) {
            $arProductCodesPossible = CompaniesFilterHelper::getByMaterialProductInValuesBoth([
                '=COMPANY_ID' => $companyId,
            ], ProductConfig::PRODUCT_TYPE_SIMPLE);

            // Перенаправялем на шаблон динамического вывода фильтра
            if (array_key_exists($componentPage, $arProductCodesPossible)) {
                $filterConfig = CompaniesFilterHelper::checkFilterUrl($componentPage);
                CompaniesFilterHelper::setFilterConfig($filterConfig);

                $this->includeComponentTemplate('construction-filter');
            } elseif (in_array($arVariables['COMPANY_CODE'], $arProductCodes)) {
                LocationHelper::clearLocation();

                //или динамического вывода списка компаний по продукту
                $filterConfig = CompaniesFilterHelper::checkFilterUrl($arVariables['COMPANY_CODE']);
                CompaniesFilterHelper::setFilterConfig($filterConfig);

                $this->arResult['PRODUCT_CODE'] = $arVariables['COMPANY_CODE'];

                $this->includeComponentTemplate('list');
            } elseif (array_key_exists($componentPage, $arSelfUrlTemplates)) {
                $this->includeComponentTemplate($componentPage);
            } else {
                LocalRedirect('/404.php');
            }
        } else {
            $this->includeComponentTemplate($componentPage);
        }
    }
}
