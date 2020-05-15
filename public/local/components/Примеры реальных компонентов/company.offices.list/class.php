<?php

namespace Keyba\Components;

use App\Core\Entities\Company\OfficeTable;
use App\Core\Helpers\TimeHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyOfficesListComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['OFFICES'] = $this->getCompanyOffices($this->arParams['COMPANY_ID'], $this->arParams['OFFICE_ID']);

            $this->includeComponentTemplate();
        }
    }

    protected function getCompanyOffices(int $companyId, $officeId = null): array
    {
        $filter = [
            'COMPANY_ID' => $companyId,
            'ACTIVE' => true,
        ];

        if (isset($officeId)) {
            $filter['!ID'] = $officeId;
        }

        $companyOfficesObj = OfficeTable::getList([
            'filter' => $filter,
            'select' => [
                'ID',
                'NAME',
                'CITY',
                'STREET',
                'BUILDING',
                'OFFICE',
                'start_day_*',
                'end_day_*',
                'PHONE' => 'COMPANY_DUPLICATE.SUB_PHONE',
                'COMPANY_CODE' => 'COMPANY_DUPLICATE.CODE',
            ],
        ]);

        $arCompanyOffices = [];
        while ($companyOffice = $companyOfficesObj->fetch()) {
            $companyOffice['OFFICE_URL'] = PUBLIC_COMPANIES_DIRECTORY . $companyOffice['COMPANY_CODE'] . '/' . PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY . '/' . $companyOffice['ID'] . '/';
            $companyOffice['PHONE'] = format_phone($companyOffice['PHONE']);
            $companyOffice['WORK_TIME'] = TimeHelper::getPhoneCallSchedule($companyOffice);
            
            $arCompanyOffices[] = $companyOffice;
        }

        return ($arCompanyOffices)? $arCompanyOffices : [];
    }
}
