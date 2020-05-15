<?php

namespace Keyba\Components;

use App\Core\Entities\Duplicates\CompanyDuplicateTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class PublicCompaniesRatingComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['COMPANIES'] = $this->getCompanies();

            $this->includeComponentTemplate();
        }
    }

    protected function getCompanies(): array
    {
        $companies = CompanyDuplicateTable:: getList([
           'limit' => 5,
           'select' => [
               'NAME',
               'REVIEWS_RATE',
           ],
           'order' => [
             'REVIEWS_RATE' => 'DESC',
           ],
        ])->fetchAll();

        return (!empty($companies))? $companies : [];
    }
}
