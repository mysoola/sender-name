<?php

namespace Keyba\Components;

use App\Core\Builders\UrlBuilders\CompanyUrlBuilder;
use App\Core\Helpers\StringHelper;
use App\Core\Models\Company;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyHeaderComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult = [
                'COMPANY_NAME' => $this->arParams['COMPANY_NAME'],
                'PREVIEW_PICTURE' => $this->arParams['PREVIEW_PICTURE'],
                'SHORT_COMPANY_NAME' => $this->arParams['SHORT_COMPANY_NAME'],
                'SUBSTITUTION_PHONE' => $this->arParams['SUBSTITUTION_PHONE'],
            ];

            if ($this->arParams['PREVIEW_PICTURE']) {
                $this->arResult['PREVIEW_PICTURE'] = \CFile::ResizeImageGet(
                    $this->arParams['PREVIEW_PICTURE'],
                    ["width" => 220, "height" => 70],
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                )['src'];
            }

            if (isset($this->arParams['COMPANY_CODE'])) {
                $companyUrlBuilder = new CompanyUrlBuilder($this->arParams['COMPANY_CODE']);

                $this->arResult['COMPANY_ABOUT_URL'] = $companyUrlBuilder->getRootCompanyUrl();
            }
            
            $this->includeComponentTemplate();
        }
    }
}
