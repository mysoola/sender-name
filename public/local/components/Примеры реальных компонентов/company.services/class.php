<?php

namespace Keyba\Components;

use App\Core\Entities\Company\CompanyChoiceServicesTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyServicesComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['SERVICES'] = $this->getServices($this->arParams['COMPANY_ID']);

            $this->includeComponentTemplate();
        }
    }

    protected function getServices(int $companyId): array
    {
        $obServices = CompanyChoiceServicesTable::getList([
            'filter' => [
                'COMPANY_ID' => $companyId,
                'ACTIVE' => true,
            ],
            'select' => [
                'MATERIAL_ID',
                'SERVICE_MATERIAL_CODE' => 'MATERIAL.CODE',
                'SERVICE_NAME' => 'SERVICE.NAME',
            ],
            'group' => [
                'MATERIAL.ID'
            ],
        ]);

        $arServices = [];
        while ($service = $obServices->fetch()) {
            $arServices[$service['SERVICE_MATERIAL_CODE']][] = $service;
        }

        return $arServices ?? [];
    }
}
