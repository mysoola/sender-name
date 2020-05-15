<?php

use App\Core\Entities\Company\CompanyChoiceLocationsTable;
use App\Core\Models\Company;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyWorkGeographyComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['REGIONS'] = $this->getRegions($this->arParams['COMPANY_ID']);
            $this->includeComponentTemplate();
        }
    }

    protected function getRegions(int $companyId): array
    {
        $regionsObj = CompanyChoiceLocationsTable::getList([
            'filter' => [
                '=COMPANY_ID' => $companyId,
            ],
            'select' => [
                'ID', 'REGION_ID', 'LOCATION_ID',
                'REGION_NAME' => 'REGION.NAME',
                'REGION_TYPE_SHORT' => 'REGION.PROPERTY_TYPE_SHORT_VALUE',
                'DISTRICT_NAME' => 'LOCATION.NAME',
                'DISTRICT_TYPE_SHORT' => 'LOCATION.PROPERTY_TYPE_SHORT_VALUE',
            ]
        ]);

        $regions = [];
        while ($region = $regionsObj->fetch()) {
            $regions[$region['REGION_ID']]['REGION_NAME'] = $region['REGION_NAME'] . ' ' . $region['REGION_TYPE_SHORT'];
            $regions[$region['REGION_ID']]['IS_MAIN'] = ($region['IS_MAIN'] === 'Y');
            $regions[$region['REGION_ID']]['LOCATIONS'][$region['LOCATION_ID']] = $region['DISTRICT_TYPE_SHORT'] . '. ' . $region['DISTRICT_NAME'];
        }
        
        return $regions;
    }
}
