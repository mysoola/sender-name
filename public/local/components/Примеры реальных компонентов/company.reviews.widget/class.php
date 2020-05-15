<?php

namespace Keyba\Components;

use App\Core\Entities\Review\ReviewTable;
use App\Core\Helpers\ReviewHelper;
use App\Core\Models\IblockModel;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class PublicCompanyReviewsWidgetComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['REVIEWS'] = $this->getReviews($this->arParams['COMPANY_ID'], $this->arParams['REVIEWS_COUNT']);

            $this->includeComponentTemplate();
        }
    }

    protected function getReviews(int $companyId, int $reviewsCount): array
    {
        $cityIblockId = (new IblockModel())
            ->getIblockIdByCode(LOCATIONS_IBLOCK_CODE);

        $dbReviews = ReviewTable::getList([
            'filter' => [
                'COMPANY_ID' => $companyId,
            ],
            'select' => [
                'ID', 'THEME', 'MESSAGE', 'RATING',
                'CREATED_NAME' => 'CREATED.NAME',
                'USER_LOCATION' => 'CITY.NAME',
                'COMPANY_PREVIEW_IMG' => 'COMPANY.PREVIEW_IMG',
                'COMPANY_NAME' => 'COMPANY.NAME',
                'COMPANY_CODE' => 'COMPANY.CODE',
            ],
            'order' => [
                'ID' => 'DESC',
            ],
            'limit' => $reviewsCount,
            'runtime' => [
                new Reference(
                    'CITY',
                    \Bitrix\Iblock\ElementTable::class,
                    Query\Join::on('this.CITY_ID', 'ref.ID')
                        ->where('ref.IBLOCK_ID', $cityIblockId)
                )
            ],
        ]);

        $arReviews = $dbReviews->fetchAll();

        foreach ($arReviews as &$review) {
            if (strlen($review['MESSAGE']) > 150) {
                $review['MESSAGE'] = strip_tags($review['MESSAGE']);
                $review['MESSAGE'] = substr($review['MESSAGE'], 0, 150);
                $review['MESSAGE'] = rtrim($review['MESSAGE'], "!,.-");
                $review['MESSAGE'] = substr($review['MESSAGE'], 0, strrpos($review['MESSAGE'], ' '));
                $review['MESSAGE'] = $review['MESSAGE'] . "… ";
            }

            $reviewHelper = new ReviewHelper();
            $review['ARR_RATING'] = $reviewHelper->makeRatingArray((int)$review['RATING']);

            $review['COMPANY_URL'] = PUBLIC_COMPANIES_DIRECTORY . $review['COMPANY_CODE'] . '/';
            $review['REVIEW_URL'] = PUBLIC_COMPANIES_DIRECTORY . $review['COMPANY_CODE'] . '/' . PUBLIC_PART_URL_FOR_COMPANY_REVIEW . '/' . $review['ID'] . '/';
        }

        return ($arReviews)? $arReviews : [];
    }
}
