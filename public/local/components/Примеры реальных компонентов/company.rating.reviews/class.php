<?php

namespace Keyba\Components;

use App\Core\Entities\Duplicates\CompanyDuplicateTable;
use App\Core\Entities\Review\ReviewTable;
use Bitrix\Main\ORM\Fields\ExpressionField;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyRatingReviewsComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['RATING_REVIEWS'] = $this->getRatings($this->arParams['COMPANY_CODE'], $this->arParams['COMPANY_ID']);

            $this->includeComponentTemplate();
        }
    }

    protected function getRatings(string $companyCode, int $companyId): array
    {
        $dbReviews = ReviewTable::getList([
            'filter' => [
                'COMPANY_ID' => $companyId,
            ],
            'select' => [
                'RATING',
                new ExpressionField('RATING_SUM', 'COUNT(%s)', 'RATING'),
            ],
            'order' => [
                'RATING' => 'DESC',
            ],
        ]);

        $arReviews = [];
        while ($review = $dbReviews->fetch()) {
            $arReviews[$review['RATING']]['COUNT'] = $review['RATING_SUM'];

            $arReviews[$review['RATING']]['URL'] = PUBLIC_COMPANIES_DIRECTORY . $companyCode . '/' . PUBLIC_PART_URL_FOR_COMPANY_REVIEWS . '/?rating=' . $review['RATING'];
        }

        if (!empty($arReviews)) {
            for ($i = 1; $i < 5; $i++) {
                if (!$arReviews[$i]) {
                    $arReviews[$i]['COUNT'] = 0;
                }
            }

            krsort($arReviews);
        }

        return $arReviews;
    }
}
