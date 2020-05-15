<?php

namespace Keyba\Components;

use App\Core\Entities\Company\OfficeTable;
use App\Core\Helpers\ReviewHelper;
use App\Core\Helpers\TimeHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyOfficesDetailComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['OFFICE'] = $this->getCompanyOffice($this->arParams['OFFICE_ID']);
            
            if (empty($this->arResult['OFFICE'])) {
                LocalRedirect('/404.php');
            }

            $this->arResult['ALL_OFFICES_URL'] = PUBLIC_COMPANIES_DIRECTORY . $this->arResult['OFFICE']['COMPANY_CODE'] . '/' . PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY . '/';

            $reviewHelper = new ReviewHelper();
            $this->arResult['ADD_REVIEW_URL'] = $reviewHelper->getReviewAddRoute();
            
            $this->includeComponentTemplate();
        }
    }

    protected function getCompanyOffice(int $officeId): array
    {
        $companyOffice = OfficeTable::getList([
            'filter' => [
                'ID' => $officeId,
                'ACTIVE' => true,
            ],
            'select' => [
                'ID',
                'NAME',
                'CITY',
                'STREET',
                'BUILDING',
                'COORD_X',
                'COORD_Y',
                'OFFICE',
                'start_day_*',
                'end_day_*',
                'PHONE' => 'COMPANY_DUPLICATE.SUB_PHONE',
                'COMPANY_CODE' => 'COMPANY_DUPLICATE.CODE',
            ],
        ])->fetch();

        if ($companyOffice) {
            $companyOffice['PHONE'] = format_phone($companyOffice['PHONE']);
            $companyOffice['WORK_TIME'] = TimeHelper::getPhoneCallSchedule($companyOffice);

            $office = ($companyOffice['OFFICE'])? ', офис ' . $companyOffice['OFFICE'] : '';
            $companyOffice['ADDRESS'] = $companyOffice['CITY'] . ', ' . $companyOffice['STREET'] . ', дом ' . $companyOffice['BUILDING'] . $office;

            $arForMap['center'] = [
                0 => (float)$companyOffice['COORD_X'],
                1 => (float)$companyOffice['COORD_Y'],
            ];
            $arForMap['pins'][0] = [
                'id' => 0,
                'coordinates' => [
                    0 => (float)$companyOffice['COORD_X'],
                    1 => (float)$companyOffice['COORD_Y'],
                ],
                'desc' => [
                    'title' => $companyOffice['NAME'],
                    'address' => $companyOffice['ADDRESS'],
                    'phone' => $companyOffice['PHONE'],
                    'schedule' => $companyOffice['WORK_TIME'],
                ],
            ];

            $companyOffice['MAP_STR'] = json_encode($arForMap);
        }

        return ($companyOffice)? $companyOffice : [];
    }
}
