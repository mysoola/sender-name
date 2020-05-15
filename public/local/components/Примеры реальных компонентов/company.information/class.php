<?php

namespace Keyba\Components;

use App\Core\Helpers\StringHelper;
use App\Core\Models\Company;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyInformationComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $companyModel = new Company();

            $this->arResult = [
                'LOCATION' => $companyModel->getCompanyGeneralOfficeById($this->arParams['COMPANY_ID']),
                'SUBSTITUTION_PHONE' => $this->arParams['SUBSTITUTION_PHONE'],
                'SITE_LINK' => $this->arParams['SITE_LINK'],
                'DELIVERY_TYPES' => $companyModel->getCompanyDeliveryTypes($this->arParams['COMPANY_ID']),
            ];

            $this->includeComponentTemplate();
        }
    }
}
