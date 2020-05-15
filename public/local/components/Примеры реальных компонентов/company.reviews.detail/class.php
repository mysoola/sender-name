<?php

namespace Keyba\Components;

use App\Core\Entities\Duplicates\CompanyDuplicateTable;
use App\Core\Entities\Review\ReviewCommentsTable;
use App\Core\Entities\Review\ReviewTable;
use App\Core\Helpers\ReviewHelper;
use App\Core\Models\Company;
use App\Core\Models\EntityTableModel;
use App\Core\Models\IblockModel;
use App\Core\Traits\AjaxRequestable;
use App\Core\Validator as V;
use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class PublicCompanyReviewsDetailComponent extends \CBitrixComponent
{
    use AjaxRequestable;

    public function executeComponent()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $this->handleAjax($request);

        $this->arResult['REVIEW'] = $this->getReview($this->arParams['COMPANY_CODE'], $this->arParams['REVIEW_ID']);

        $arCompany = $this->getCompany($this->arParams['COMPANY_CODE']);
        $this->arResult['COMPANY_NAME'] = $arCompany['NAME'];

        if (!$this->arResult['REVIEW']) {
            LocalRedirect('/404.php');
        }

        $this->includeComponentTemplate();
    }

    protected function getReview(string $companyCode, $reviewId)
    {
        $arReview = [];
        $cacheTtl = $this->arParams['CACHE_TIME'];
        $cacheId = ReviewTable::getCacheId($companyCode, $reviewId);
        $cacheDir = ReviewTable::getCacheDir();
        $cache = Cache::createInstance();

        if ($cache->initCache(
            $cacheTtl,
            $cacheId,
            $cacheDir
        )) {
            $arReview = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $cityIblockId = (new IblockModel())
                ->getIblockIdByCode(LOCATIONS_IBLOCK_CODE);

            $arReview = ReviewTable::getList([
                'filter' => [
                    'ID' => $reviewId,
                    'COMPANY.CODE' => $companyCode,
                ],
                'select' => [
                    'ID', 'THEME', 'MESSAGE', 'COMPANY_ID', 'RATING',
                    'CREATED_NAME' => 'CREATED.NAME',
                    'USER_LOCATION' => 'CITY.NAME',
                    'SERVICE_NAME' => 'SERVICE.NAME',
                    'COMPANY_PREVIEW_IMG' => 'COMPANY.PREVIEW_IMG',
                    'COMPANY_NAME' => 'COMPANY.NAME',
                    'COMPANY_CODE' => 'COMPANY.CODE',
                ],
                'runtime' => [
                    new Reference(
                        'CITY',
                        \Bitrix\Iblock\ElementTable::class,
                        Query\Join::on('this.CITY_ID', 'ref.ID')
                            ->where('ref.IBLOCK_ID', $cityIblockId)
                    )
                ],
            ])->fetch();

            if (!$arReview) {
                return $arReview;
            }

            $arReview['COMMENTS'] = $this->getReviewComments($arReview['ID'], $arReview['COMPANY_ID']);

            $cache->endDataCache($arReview);
        }

        $reviewHelper = new ReviewHelper();
        $arReview['ARR_RATING'] = $reviewHelper->makeRatingArray((int)$arReview['RATING']);

        $arReview['COMPANY_URL'] = PUBLIC_COMPANIES_DIRECTORY . $companyCode . '/';

        return $arReview;
    }

    protected function getReviewComments(int $reviewId): array
    {
        $result = [];

        $obComments = ReviewCommentsTable::getList([
            'filter' => [
                '=REVIEW_ID' => $reviewId,
            ],
            'select' => [
                'ID',
                'IS_COMPANY',
                'MESSAGE',
                'CREATED_BY',
                'DATE_CREATE',
                'CREATED_NAME' => 'CREATED.NAME',
                'SECOND_NAME' => 'CREATED.SECOND_NAME',
                'LAST_NAME' => 'CREATED.LAST_NAME',
            ],
        ]);

        while ($comment = $obComments->fetch()) {
            $comment['DATE'] = $comment['DATE_CREATE']->format('d.m.Y');
            $comment['TIME'] = $comment['DATE_CREATE']->format('H:i');

            $result['ITEMS'][] = $comment;
        }

        return $result;
    }


    protected function getCompany(string $code): array
    {
        $result = CompanyDuplicateTable::getList([
            'limit' => 1,
            'filter' => [
                'CODE' => $code,
            ],
            'select' => [
                'NAME'
            ],
        ])->fetch();

        return (!empty($result))? $result : [];
    }

    public function addComment(HttpRequest $request)
    {
        global $USER;

        if ($USER->IsAuthorized()) {
            $resValid = V::validateArray(
                $request->getPostList()->toArray(),
                [
                    'id' => 'required|integer',
                    'message' => 'required|max:1500',
                ]
            );

            if (isset($resValid['errors'])) {
                throw new \Exception(V::getErrorStr($resValid['errors']));
            } else {
                $review = ReviewTable::getList([
                    'filter' => [
                        '=ID' => $request['id'],
                    ],
                    'select' => [
                        'ID', 'HAS_COMPANY_COMMENT',
                    ],
                ])->fetchObject();

                if (!$review) {
                    throw new \Exception('Отзыв не найден');
                }

                $entity = new EntityTableModel();
                $entity->add(ReviewCommentsTable::class, [
                    'IS_COMPANY' => false,
                    'MESSAGE' => $request['message'],
                    'REVIEW_ID' => $request['id'],
                    'IS_NEW' => true,
                ]);
            }
        } else {
            throw new \Exception('Для добавления комментария необходима авторизация на сайте.');
        }
    }

    public function deleteReview(HttpRequest $request)
    {
        global $USER;

        if ($USER->IsAdmin()) {
            $resValid = V::validateArray(
                $request->getPostList()->toArray(),
                [
                    'id' => 'required|integer',
                    'companyCode' => 'required|string',
                ]
            );

            if (isset($resValid['errors'])) {
                throw new \Exception(V::getErrorStr($resValid['errors']));
            } else {
                (new EntityTableModel())->delete(ReviewTable::class, $request['id']);

                $result['url'] = PUBLIC_COMPANIES_DIRECTORY . $request['companyCode'] . '/' . PUBLIC_PART_URL_FOR_COMPANY_REVIEW . '/';

                return $result;
            }
        } else {
            throw new \Exception('Для удаление отзыва необходима авторизация на сайте с правами администратора.');
        }
    }

    public function deleteComment(HttpRequest $request)
    {
        global $USER;

        if ($USER->IsAdmin()) {
            $resValid = V::validateArray(
                $request->getPostList()->toArray(),
                [
                    'id' => 'required|integer',
                ]
            );

            if (isset($resValid['errors'])) {
                throw new \Exception(V::getErrorStr($resValid['errors']));
            } else {
                (new EntityTableModel())->delete(ReviewCommentsTable::class, $request['id']);
            }
        } else {
            throw new \Exception('Для удаления комментария необходима авторизация на сайте с правами администратора.');
        }
    }
}
