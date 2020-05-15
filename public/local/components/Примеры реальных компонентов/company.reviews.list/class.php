<?php

namespace Keyba\Components;

use App\Core\Entities\Review\ReviewCommentsTable;
use App\Core\Entities\Review\ReviewTable;
use App\Core\Helpers\ReviewHelper;
use App\Core\Models\Company;
use App\Core\Models\IblockModel;
use App\Core\Models\Paginator;
use App\Core\Models\ReviewsModel;
use App\Core\Validator as V;
use Bitrix\Main\Application;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query;
use Bitrix\Main\Page\Asset;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class PublicCompanyReviewsListComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->count() > 0) {
            $resValid = V::validateArray(
                $request->getQueryList()->toArray(),
                [
                    'service' => 'integer',
                    'rating' => 'integer',
                    'review_page' => 'integer',
                ]
            );

            if (isset($resValid['errors'])) {
                LocalRedirect('/404.php');
            }
        }

        if (isset($request['service'])) {
            $this->arParams['SERVICE_ID'] = $request['service'];
        }

        if (isset($request['rating'])) {
            $this->arParams['RATING'] = $request['rating'];
        }

        if (isset($request['review_page'])) {
            if ($this->arParams['LOCATION_CODE']) {
                Asset::getInstance()->addString('<link rel="canonical" href="'. '/' . $this->arParams['LOCATION_CODE'] . PUBLIC_COMPANIES_DIRECTORY . PUBLIC_PART_URL_FOR_COMPANY_REVIEWS . '/' .'"/>');
            } else {
                Asset::getInstance()->addString('<link rel="canonical" href="'. PUBLIC_COMPANIES_DIRECTORY . PUBLIC_PART_URL_FOR_COMPANY_REVIEWS . '/' .'"/>');
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            if (isset($this->arParams['RATING']) && (int)$this->arParams['RATING'] === $i) {
                $this->arParams['RATING_FOR_FILTER'][$i]['SELECTED'] = true;
            } else {
                $this->arParams['RATING_FOR_FILTER'][$i]['SELECTED'] = false;
            }
        }

        $reviewsModel = new ReviewsModel();
        $this->arResult['SERVICES'] = $reviewsModel->getServices();

        $this->arResult['REVIEWS'] = $this->getReviews(
            $this->arParams['RATING'],
            $this->arParams['SERVICE_ID'],
            $this->arParams['LOCATION_ID'],
            $this->arParams['COMPANY_CODE']
        );

        $reviewHelper = new ReviewHelper();
        $this->arResult['REVIEW_URL'] = $reviewHelper->getReviewAddRoute();

        $this->includeComponentTemplate();
    }

    /**
     * @param $rating
     * @param $serviceId
     * @param $locationId
     * @param $companyCode
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return array
     */
    protected function getReviews($rating, $serviceId, $locationId, $companyCode): array
    {
        $filter = [];
        $result = [];

        if ($rating && $rating > 0 && $rating < 6) {
            $filter['=RATING'] = $rating;
        }

        if ($serviceId) {
            $filter['=SERVICE_ID'] = $serviceId;
        }

        if ($locationId) {
            $filter['=CITY_ID'] = $locationId;
        }

        if ($companyCode) {
            $companyModel =  new Company();
            $filter['=COMPANY_ID'] = $companyModel->getCompanyIdByCode($companyCode);
        }

        $nav = new Paginator('review');
        $nav->setDefaultPerPage($this->arParams['COUNT_REVIEWS']);
        $nav->initFromUri();

        $cityIblockId = (new IblockModel())
            ->getIblockIdByCode(LOCATIONS_IBLOCK_CODE);

        $dbReviews = ReviewTable::getList([
            'filter' => $filter,
            'select' => [
                'ID', 'THEME', 'MESSAGE', 'COMPANY_ID', 'RATING', 'HAS_COMPANY_COMMENT',
                'CREATED_NAME' => 'CREATED.NAME',
                'USER_LOCATION' => 'CITY.NAME',
                'SERVICE_NAME' => 'SERVICE.NAME',
                'COMPANY_PREVIEW_IMG' => 'COMPANY.PREVIEW_IMG',
                'COMPANY_NAME' => 'COMPANY.NAME',
                'COMPANY_CODE' => 'COMPANY.CODE',
            ],
            'order' => [
                'ID' => 'DESC',
            ],
            'offset' => $nav->getOffset(),
            'limit' => $nav->getLimit(),
            'count_total' => true,
            'runtime' => [
                new Reference(
                    'CITY',
                    \Bitrix\Iblock\ElementTable::class,
                    Query\Join::on('this.CITY_ID', 'ref.ID')
                        ->where('ref.IBLOCK_ID', $cityIblockId)
                )
            ],
            'cache' => [
                'ttl' => $this->arParams['CACHE_TIME'],
                'cache_joins' => true,
            ],
        ]);

        $arReviews = $dbReviews->fetchAll();

        $reviewsIds = [];
        foreach ($arReviews as &$review) {
            $reviewsIds[] = $review['ID'];

            if (strlen($review['MESSAGE']) > 350) {
                $review['MESSAGE'] = strip_tags($review['MESSAGE']);
                $review['MESSAGE'] = substr($review['MESSAGE'], 0, 350);
                $review['MESSAGE'] = rtrim($review['MESSAGE'], "!,.-");
                $review['MESSAGE'] = substr($review['MESSAGE'], 0, strrpos($review['MESSAGE'], ' '));
                $review['MESSAGE'] = $review['MESSAGE'] . "… ";
            }

            $reviewHelper = new ReviewHelper();
            $review['ARR_RATING'] = $reviewHelper->makeRatingArray((int)$review['RATING']);

            $review['COMPANY_URL'] = PUBLIC_COMPANIES_DIRECTORY . $review['COMPANY_CODE'] . '/';
            $review['REVIEW_URL'] = PUBLIC_COMPANIES_DIRECTORY . $review['COMPANY_CODE'] . '/' . PUBLIC_PART_URL_FOR_COMPANY_REVIEW . '/' . $review['ID'] . '/';
        }

        $obReviewComments = ReviewCommentsTable::getList([
            'filter' => [
                'REVIEW_ID' => $reviewsIds,
            ],
            'select' => [
                'REVIEW_ID',
                new ExpressionField('COMMENT_CNT', 'COUNT(%s)', 'ID'),
                new ExpressionField('HAS_NEW_COMMENTS', 'SUM(%s)', 'IS_NEW'),
            ],
        ]);

        while ($comment = $obReviewComments->fetch()) {
            foreach ($arReviews as &$review) {
                if ($comment['REVIEW_ID'] == $review['ID']) {
                    $review['COMMENT_CNT'] = $comment['COMMENT_CNT'];
                    $review['HAS_NEW_COMMENTS'] = $comment['HAS_NEW_COMMENTS'];
                }
            }
        }

        $nav->setElementsCount($dbReviews->getCount());
        $nav->setPagination();

        if (!empty($arReviews)) {
            $result['ITEMS'] = $arReviews;
            $result['NAV_PARAMS'] = $nav->getNavParams();
        }

        return $result;
    }
}
