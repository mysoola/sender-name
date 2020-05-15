<?php

namespace App\Core\Models;

namespace Keyba\Components;

use App\Core\Models\MaterialsCompanyModel;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyMaterialsComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $materialCompanyModel = new MaterialsCompanyModel();
            $this->arResult['PROFILES'] = $materialCompanyModel->getWindowsProfilesByCompanyId($this->arParams['COMPANY_ID']);
            $this->arResult['GLASSES'] = $materialCompanyModel->getWindowsGlassesByCompanyId($this->arParams['COMPANY_ID']);
            $this->arResult['SILLS'] = $materialCompanyModel->getSillsByCompanyId($this->arParams['COMPANY_ID']);

            $this->includeComponentTemplate();
        }
    }
}
