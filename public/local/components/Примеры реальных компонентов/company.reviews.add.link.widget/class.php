<?php


namespace Keyba\Components;

use App\Core\Helpers\ReviewHelper;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyReviewsAddLinkWidgetComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $strParamCompanyId = (isset($this->arParams['COMPANY_ID']))? '?company=' . $this->arParams['COMPANY_ID'] : '' ;

        $reviewHelper = new ReviewHelper();
        $this->arResult['REVIEW_ADD_URL'] = $reviewHelper->getReviewAddRoute() . $strParamCompanyId;

        $this->includeComponentTemplate();
    }
}
