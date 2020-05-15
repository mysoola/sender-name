<?php

namespace Keyba\Components;

use App\Core\Builders\UrlBuilders\CompanyUrlBuilder;
use App\Core\Helpers\StringHelper;
use App\Core\Models\Company;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyOfficesComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $companyModel = new Company();

            $companyOffices = $companyModel->getCompanyOfficesById($this->arParams['COMPANY_ID']);

            $this->arResult = [
                'COMPANY_OFFICES_COUNT' => count($companyOffices),
                'COMPANY_OFFICES_FOR_MAP' => $this->prepareOfficesPointsJson($companyOffices, $this->arParams['COMPANY_CODE']),
                'COUNT_OFFICES' => num2word(
                    count($companyOffices),
                    [
                        'офис',
                        'офиса',
                        'офисов',
                    ]
                ),
            ];

            $this->includeComponentTemplate();
        }
    }

    protected function prepareOfficesPointsJson(array $companyOffices, string $companyCode): array
    {
        $result = [];

        $companyUrl = new CompanyUrlBuilder($companyCode);
        $officeSectionUrl = $companyUrl->getCompanySectionPath(PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY);

        foreach ($companyOffices as $key => $office) {
            $result['pins'][$key] = [
                    'coordinates' => [
                        0 => (float)$office['COORD_X'],
                        1 => (float)$office['COORD_Y'],
                    ],
                    'url' => $officeSectionUrl . $office['ID'] . '/',
                ];
        }

        if (!$result) {
            return [];
        }

        return $result;
    }
}
