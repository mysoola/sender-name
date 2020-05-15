<?php

namespace Keyba\Components;

use App\Core\Builders\UrlBuilders\CompanyUrlBuilder;
use App\Core\Configs\ProductConfig;
use App\Core\Entities\Company\OfficeTable;
use App\Core\Entities\Duplicates\CompanyDuplicateTable;
use App\Core\Helpers\CompaniesFilterHelper;
use App\Core\Helpers\LocationHelper;
use App\Core\Helpers\TimeHelper;
use App\Core\Models\Company;
use App\Core\Models\Paginator;
use App\Core\Traits\AjaxRequestable;
use App\Core\Validator as V;
use App\Prices\Entities\PriceMaterialTable;
use App\Prices\Entities\PriceOptionStatusTable;
use App\Prices\Entities\PriceOptionTable;
use App\Prices\Entities\PriceProductTable;
use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class PublicCompaniesListComponent extends \CBitrixComponent
{
    protected $optionApprovedStatusId;
    protected $request;

    use AjaxRequestable;

    const DEFAULT_SHOW_ELEMENTS_COUNT = 20;
    const QUERY_CACHE_TIME = 60*60*2;

    const QUERY_CACHE_PREFIX = 'comp_list_result';
    const QUERY_CACHE_DIR = '/site/comp_list_result';

    public function executeComponent()
    {
        $this->optionApprovedStatusId = PriceOptionStatusTable::getIdByCode(OPTION_STATUS_APPROVED);

        $this->request = Application::getInstance()->getContext()->getRequest();
        $this->handleAjax($this->request);

        $this->arResult['CITY_NAME'] = LocationHelper::getLocationPrepositionName();

        $this->arResult['COMPANIES'] = $this->getCompanies($this->arParams['QUERY'], $this->arParams['PRODUCT_CODE']);

        $this->includeComponentTemplate();
    }

    protected function getCompanies($query = null, $productCode = null): array
    {
        $filter = [];

        if (isset($query) && strlen(trim($query)) > 0) {
            $filter['%NAME'] = $query;
        }

        $filter['=COMPANY_ID'] = (new Company())->getCompaniesIdsByLocation(LocationHelper::getLocationId());

        if (isset($productCode) && $productCode) {
            $filter['=COMPANY_ID'] = $this->getCompaniesWithProducts($productCode, $filter['=COMPANY_ID']);
        }

        $nav = new Paginator('compf');
        $nav->setDefaultPerPage($this->getShowElementsCount());
        $nav->initFromUri();

        $cache = Cache::createInstance();

        $cacheId = self::QUERY_CACHE_PREFIX . md5(serialize(array_merge(
            $filter,
            [$this->request['compf_page']]
        )));

        if ($cache->initCache(
            self::QUERY_CACHE_TIME,
            $cacheId,
            self::QUERY_CACHE_DIR
        )) {
            $arCompanies = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $companiesObj = CompanyDuplicateTable::getList([
                'filter' => $filter,
                'select' => [
                    'ID',
                    'PREVIEW_IMG',
                    'RATING',
                    'NAME',
                    'PHONE_CHECK',
                    'REQ_CHECK',
                    'CODE',
                    'SUB_PHONE',
                    'COMPANY_ID',
                ],
                'order' => [
                    'RATING' => 'DESC',
                ],
                'count_total' => true,
                'limit' => $nav->getLimit(),
                'offset' => $nav->getOffset(),
            ]);

            $arCompanies = [];
            $arCompanies['items'] = [];

            while ($company = $companiesObj->fetch()) {
                $company['LINK'] = PUBLIC_COMPANIES_DIRECTORY . $company['CODE'] . '/';

                if ($company['SUB_PHONE']) {
                    $company['SUB_PHONE'] = format_phone($company['SUB_PHONE']);
                    $company['SUB_PHONE'] = preg_replace('/\d\d-\d\d$/', '**-**', $company['SUB_PHONE']);
                }

                $company['ID'] = (int)$company['ID'];
                $company['COMPANY_ID'] = (int)$company['COMPANY_ID'];
                $company['REQ_CHECK'] = (bool)$company['REQ_CHECK'];
                $company['PHONE_CHECK'] = (bool)$company['PHONE_CHECK'];

                $arCompanies['items'][] = $company;
            }

            if (!empty($arCompanies)) {
                $arOffices = $this->getMainOfficesByCompanyIds(collect($arCompanies['items'])->pluck('COMPANY_ID')->toArray());

                foreach ($arCompanies['items'] as &$company) {
                    $company['MAIN_OFFICE'] = $arOffices[$company['COMPANY_ID']] ?? [];

                    $company['MAIN_OFFICE_STR'] = '';
                    $company['MAIN_OFFICE_STR'] .= ($company['MAIN_OFFICE']['CITY']) ? 'г. ' . $company['MAIN_OFFICE']['CITY'] : '';
                    $company['MAIN_OFFICE_STR'] .= ($company['MAIN_OFFICE']['STREET']) ? ', ' . $company['MAIN_OFFICE']['STREET'] .
                        '-' . $company['MAIN_OFFICE']['BUILDING'] : '';
                    $company['MAIN_OFFICE_STR'] .= ($company['MAIN_OFFICE']['OFFICE']) ? ', ' .
                        OFFICE_TYPES[$company['MAIN_OFFICE']['OFFICE_TYPE']]['ru'] . ' ' . $company['MAIN_OFFICE']['OFFICE'] : '';
                }

                $countFindElements = $companiesObj->getCount();
                $nav->setElementsCount($countFindElements);
                $nav->setPagination();

                $arCompanies['COUNT_FIND_ELEMENTS'] = $countFindElements;
            }

            $arCompanies['COMPANY_COUNT'] = $companiesObj->getCount();

            $arCompanies['COUNT_LEFT'] = (int)$arCompanies['COMPANY_COUNT'] - ((int)$nav->getPerPage() * (int)$nav->getPage());

            if ($arCompanies['COUNT_LEFT'] >= $nav->getPerPage()) {
                $arCompanies['ON_PAGE'] = $nav->getPerPage();
            } else {
                $arCompanies['ON_PAGE'] = $arCompanies['COUNT_LEFT'];
            }

            $arCompanies['PAGE'] = $nav->getPage();

            $cache->endDataCache($arCompanies);
        }

        return $arCompanies;
    }

    protected function getCompaniesWithProducts($productCode, $companiesIds = null): array
    {
        $companiesWithProductsIds = [];

        $productParameters = CompaniesFilterHelper::getProductConfigByCode($productCode);

        $filter = [
            '=MATERIAL.ID' => PriceMaterialTable::getIdsByCodesAsArray($productParameters['MATERIAL_CODE']),
            '=PRODUCT.ID' => PriceProductTable::getIdsByCodesAsArray($productParameters['PRODUCT_CODE']),
            '=STATUS_ID' => $this->optionApprovedStatusId,
            '>STRUCTURES.P_COUNT' => 0,
            'STRUCTURES.STATUS_ID' => $this->optionApprovedStatusId,
        ];

        if (isset($companiesIds)) {
            $filter['=COMPANY_ID'] = $companiesIds;
        }

        $companiesStr = PriceOptionTable::getList([
            'filter' => $filter,
            'select' => [
                new ExpressionField(
                    'COMPANIES_IDS',
                    'GROUP_CONCAT(DISTINCT %s SEPARATOR ",")',
                    ['COMPANY_ID']
                ),
            ],
        ])->fetch();

        if ($companiesStr) {
            $companiesWithProductsIds = explode(',', $companiesStr['COMPANIES_IDS']);
        }

        return $companiesWithProductsIds;
    }

    protected function getMainOfficesByCompanyIds(array $companyIds): array
    {
        $obOffices = OfficeTable::getList([
            'filter' => [
                '=ACTIVE' => true,
                '=COMPANY_ID' => $companyIds,
                '=IS_MAIN' => 'Y',
            ],
            'select' => [
                'CITY',
                'STREET',
                'BUILDING',
                'OFFICE',
                'OFFICE_TYPE',
                'COMPANY_ID',
                'start_day_*',
                'end_day_*',
            ],
        ]);

        $offices = [];
        while ($office = $obOffices->fetch()) {
            $office['WORK_TIME'] = TimeHelper::getArrSchedule($office);

            $offices[$office['COMPANY_ID']] = $office;
        }

        return $offices;
    }

    protected function getShowElementsCount()
    {
        return (isset($this->arParams['SHOW_ELEMENTS_COUNT'])) ? $this->arParams['SHOW_ELEMENTS_COUNT'] : self::DEFAULT_SHOW_ELEMENTS_COUNT;
    }


    public function getCompaniesAjax(HttpRequest $request)
    {
        $resValid = V::validateArray(
            $request->getQueryList()->toArray(),
            [
                'page' => 'integer',
                'q' => 'string',
                'productUrl' => 'string',
            ]
        );

        if (isset($resValid['errors'])) {
            throw new \Exception(V::getErrorStr($resValid['errors']));
        } else {
            $result = $this->getCompanies($request['q'], $request['productUrl']);
            
            $result['COMPANIES'] = json_encode($result);

            return $result;
        }
    }

    public function getCompanyDetailInfo(HttpRequest $request)
    {
        $resValid = V::validateArray(
            $request->getQueryList()->toArray(),
            [
                'companyId' => 'required|integer',
            ]
        );

        if (isset($resValid['errors'])) {
            throw new \Exception(V::getErrorStr($resValid['errors']));
        } else {
            $companyId = $request['companyId'];

            $this->checkCompany($companyId);

            $arCountersForCompany = $this->getCountersForCompany($companyId);

            $companyUrlBuilder = new CompanyUrlBuilder($arCountersForCompany['CODE']);

            $urls = [
                'information' => [
                    'cities' => $companyUrlBuilder->getCompanySectionPath(PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY),
                    'offices' => $companyUrlBuilder->getCompanySectionPath(PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY),
                    'delivery' => $companyUrlBuilder->getCompanySectionPath(PUBLIC_PART_URL_FOR_COMPANY_PAYMENT_DELIVERY),
                    'payment' => $companyUrlBuilder->getCompanySectionPath(PUBLIC_PART_URL_FOR_COMPANY_PAYMENT_DELIVERY),
                    'dealers' => $companyUrlBuilder->getCompanySectionPath(PUBLIC_PART_URL_FOR_DEALERS_ADD),
                    'requisites' => $companyUrlBuilder->getCompanySectionPath(PUBLIC_PART_URL_FOR_REQUISITES),
                ],
                'products' => $this->getUrlsToProducts($arCountersForCompany['COMPANY_ID'], $arCountersForCompany['CODE']),
            ];

            $result = [
                'item' => $arCountersForCompany,
                'urls' => $urls,
            ];

            $result['item'] = $arCountersForCompany;

            return $result;
        }
    }

    protected function checkCompany(int $companyId)
    {
        $issetCompany = CompanyDuplicateTable::getList([
            'filter' => [
                '=COMPANY_ID' => $companyId,
            ],
            'select' => [
                'COMPANY_ID',
            ]
        ])->fetch();

        if (!$issetCompany['COMPANY_ID']) {
            throw new \Exception('Компания не найдена');
        }
    }

    protected function getCountersForCompany(int $companyId): array
    {
        $obCountersForCompany = CompanyDuplicateTable::getList([
            'filter' => [
                '=COMPANY_ID' => $companyId,
            ],
            'select' => [
                'COMPANY_ID',
                'CODE',
                new ExpressionField(
                    'REVIEW_COUNT',
                    'COUNT(DISTINCT %s)',
                    ['REVIEW.ID']
                ),
                'OFFICE_MAIN_CITY_NAME' => 'OFFICE_MAIN.CITY',
                new ExpressionField(
                    'OFFICE_COUNT',
                    'COUNT(DISTINCT %s)',
                    ['OFFICE.CITY']
                ),
            ],
            'runtime' => [
                (new Reference(
                    'OFFICE_MAIN',
                    OfficeTable::class,
                    Query\Join::on('this.COMPANY_ID', 'ref.COMPANY_ID')
                        ->where('ref.IS_MAIN', 'Y')
                )),
            ],
            'cache' => ['ttl' => self::QUERY_CACHE_TIME, 'cache_joins' => true]
        ]);

        if ($arCountersForCompany = $obCountersForCompany->fetch()) {
            $arCountersForCompany['LINK'] = PUBLIC_COMPANIES_DIRECTORY . $arCountersForCompany['CODE'] . '/';
            $arCountersForCompany['REVIEWS_LINK'] = PUBLIC_COMPANIES_DIRECTORY . $arCountersForCompany['CODE'] . '/'
                . PUBLIC_PART_URL_FOR_COMPANY_REVIEWS . '/';
            $arCountersForCompany['OFFICES_LINK'] = PUBLIC_COMPANIES_DIRECTORY . $arCountersForCompany['CODE'] . '/'
                . PUBLIC_PART_URL_FOR_OFFICES_LIST_COMPANY . '/';
            $arCountersForCompany['GEO_LINK'] = PUBLIC_COMPANIES_DIRECTORY . $arCountersForCompany['CODE'] . '/'
                . PUBLIC_PART_URL_FOR_COMPANY_WORK_GEOGRAPHY . '/';

            $arCountersForCompany['STR_OFFICES'] = ($arCountersForCompany['OFFICE_COUNT'] - 1) > 0 ?
                num2word(
                    $arCountersForCompany['OFFICE_COUNT'] - 1,
                    [
                        'другой населенный пункт',
                        'других населенных пункта',
                        'других населенных пунктов',
                    ]
                )
                : '';
        }

        return ($arCountersForCompany)? $arCountersForCompany : [] ;
    }

    protected function getUrlsToProducts(int $companyId, string $companyCode): array
    {
        $arUrls = [];

        $companyUrlBuilder = new CompanyUrlBuilder($companyCode);

        $arProductCodesPossible = CompaniesFilterHelper::getByMaterialProductInValuesBoth([
            '=COMPANY_ID' => $companyId,
        ], ProductConfig::PRODUCT_TYPE_SIMPLE);

        foreach ($arProductCodesPossible as $product) {
            $arUrls[$product['CODE']] = [
                'title' => $product['COMPANY_CARD_TITLE'],
                'link' => $companyUrlBuilder->getCompanySectionPath($product['CODE']),
            ];
        }

        return $arUrls;
    }
}
