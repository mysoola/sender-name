<?php

namespace Keyba\Components;

use \Bitrix\Main\Localization\Loc;
use App\Core\Models\Company;
use App\Core\Traits\AjaxRequestable;
use App\Core\Validator as V;
use Bitrix\Main\Application;
use Bitrix\Main\HttpRequest;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompaniesSelectorComponent extends \CBitrixComponent
{
    use AjaxRequestable;

    protected $arUserCompany;

    public function executeComponent()
    {
        Loc::loadMessages(__FILE__);

        $request = Application::getInstance()->getContext()->getRequest();

        $this->handleAjax($request);

        $this->includeComponentTemplate();
    }

    protected function getCompanies(HttpRequest $request)
    {
        $resValid = V::validateArray(
            $request->getQueryList()->toArray(),
            [
                'name' => 'required|string',
            ]
        );

        if (isset($resValid['errors'])) {
            throw new \Exception(V::getErrorStr($resValid['errors']));
        } else {
            $result = [];
            $companyModel = new Company();
            $arCompanies = $companyModel->getCompaniesByNameFromDuplicateTable($request['name'], 10);

            if ($arCompanies) {
                foreach ($arCompanies as $company) {
                    $result['data'][] = [
                        'id' => $company['COMPANY_ID'],
                        'name' => $company['NAME'],
                        'preview_img' => $company['PREVIEW_IMG'],
                    ];
                }
            } else {
                throw new \Exception('Компания не найдена');
            }
            
            return $result;
        }
    }
}
