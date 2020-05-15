<?php

namespace Keyba\Components;

use App\Core\Entities\Duplicates\CompanyDuplicateTable;
use App\Core\Entities\Review\ReviewServicesTable;
use App\Core\Entities\Review\ReviewTable;
use App\Core\Helpers\LocationHelper;
use App\Core\Models\Company;
use App\Core\Models\EntityTableModel;
use App\Core\Models\LocationModel;
use App\Core\Models\ReviewsModel;
use App\Core\Traits\AjaxRequestable;
use App\Core\Validator as V;
use Bitrix\Main\Application;
use Bitrix\Main\HttpRequest;
use Illuminate\Validation\Rule;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class PublicCompanyReviewsAddComponent extends \CBitrixComponent
{
    use AjaxRequestable;

    public function executeComponent()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $this->handleAjax($request);

        $reviewsModel = new ReviewsModel();
        $this->arResult['SERVICES'] = $reviewsModel->getServices();
        $this->arResult['CITY_DATA'] = LocationHelper::getLocationData();

        $reqCompanyId = $request->get('company');
        if (isset($reqCompanyId)) {
            $this->arResult['COMPANY'] = $this->getCompany($reqCompanyId);
        }

        if (isset($this->arParams['TYPE_SERVICE_CODE'])) {
            $this->arResult['SERVICES'][$this->arParams['TYPE_SERVICE_CODE']]['SELECTED'] = true;
        }

        $this->includeComponentTemplate();
    }

    protected function getCompany(int $companyId): array
    {
        $result = [];

        if (isset($companyId)) {
            $arCompany = (new Company())->getCompanyByIdFromDuplicateTable($companyId);

            if (!empty($arCompany)) {
                $result = [
                    'ID' => $arCompany['COMPANY_ID'],
                    'NAME' => $arCompany['NAME'],
                ];
            }
        }

        return (!empty($result)) ? $result : [];
    }

    protected function save(HttpRequest $request)
    {
        global $USER;

        if ($USER->IsAuthorized()) {
            $resValid = V::validateArray(
                $request->getPostList()->toArray(),
                [
                    'client' => ['integer', Rule::in([0, 1])],
                    'rating' => 'required|array',
                    'city' => 'required|integer',
                    'company' => 'required|integer',
                    'services' => 'required|integer',
                    'title' => 'required|string',
                    'desc' => 'required|string',
                ]
            );

            if (isset($resValid['errors'])) {
                throw new \Exception(V::getErrorStr($resValid['errors']));
            } else {
                $locationModel = new LocationModel();
                if (empty($locationModel->getCityById($request['city']))) {
                    throw new \Exception('Город не найден.');
                }

                $companyModel = new Company();
                $arCompany = $companyModel->getCompanyByIdFromDuplicateTable($request['company']);
                if (empty($arCompany)) {
                    throw new \Exception('Компания не найдена.');
                }

                $rating = (int)round(array_sum($request['rating']) / count($request['rating']));
                if ($rating > 5) {
                    throw new \Exception('Рейтинг не может быть больше 5');
                }

                if (count($request['rating']) < 3) {
                    throw new \Exception('Необходимо оценить компанию минимум по 3-м пунктам');
                }

                if (!empty((int)$request['services'])) {
                    if (!$this->checkService((int)$request['services'])) {
                        throw new \Exception('Услуга должна быть выбрана из списка');
                    }
                } else {
                    throw new \Exception('Услуга должна быть выбрана');
                }

                $arFields = [
                    'ACTIVE' => 1,
                    'SERVICE_ID' => $request['services'],
                    'COMPANY_ID' => $request['company'],
                    'CITY_ID' => $request['city'],
                    'RATING' => $rating,
                    'MODERATED' => 1,
                    'THEME' => $request['title'],
                    'MESSAGE' => $request['desc'],
                    'IS_CLIENT' => ($request['client']) ? true : false,
                ];

                $entityModel = new EntityTableModel();
                $reviewsModel = new ReviewsModel();

                $res = $entityModel->add(ReviewTable::class, $arFields);

                $reviewId = $res->getId();
                if ($reviewId) {
                    $result['url'] = PUBLIC_COMPANIES_DIRECTORY . $arCompany['CODE'] . '/' . PUBLIC_PART_URL_FOR_COMPANY_REVIEW . '/' . $reviewId . '/';
                }

                $entityModel->updateIfExist(
                    CompanyDuplicateTable::class,
                    [
                        'filter' => [
                            '=COMPANY_ID' => $request['company'],
                        ]
                    ],
                    [
                        'REVIEWS_RATE' => $reviewsModel->ratingCalc($request['company'])
                    ]
                );

                return $result;
            }
        } else {
            throw new \Exception('Для добавления комментария необходима авторизация на сайте.');
        }
    }

    protected function checkService(int $serviceId): bool
    {
        $service = ReviewServicesTable::getList([
            'filter' => [
                'ID' => $serviceId,
            ],
            'select' => [
                'ID',
            ]
        ])->fetch();

        return (!empty($service)) ? true : false;
    }
}
