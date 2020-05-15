<?php

use App\Core\Entities\Company\CompanyDeliveryLocationsTable;
use App\Core\Entities\Company\CompanyDeliveryTimesTable;
use App\Core\Helpers\TimeHelper;
use App\Core\Models\IblockModel;
use Bitrix\Main\ORM\Fields\ExpressionField;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyPaymentDeliveryComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['PAYMENT_TYPES'] = $this->arParams['PAYMENT_TYPES'];
            $this->arResult['INSTALLMENT'] = $this->arParams['INSTALLMENT'];
            $this->arResult['REQUIREMENTS'] = $this->arParams['REQUIREMENTS'];

            if ($this->arParams['PARTNER_BANKS']['VALUE']) {
                $this->arResult['PARTNER_BANKS'] = $this->getPartnersBanks($this->arParams['PARTNER_BANKS']['VALUE']);
            }

            $this->arResult['PREPAYMENT'] = $this->arParams['PREPAYMENT'];
            $this->arResult['SELECTED_REGIONS'] = $this->getRegions($this->arParams['COMPANY_ID']);
            $this->arResult['DELIVERY_TIME'] = $this->getDeliveryTime($this->arParams['COMPANY_ID']);

            $this->includeComponentTemplate();
        }
    }

    protected function getPartnersBanks(array $partnersBanksIds): array
    {
        $iBlockModel = new IblockModel();
        $banks = $iBlockModel->findByParams(PARTNER_BANKS_IBLOCK_CODE, [
            'filter' =>  [
                'ID' => $partnersBanksIds,
            ],
        ]);
        
        return ($banks)? $banks : [];
    }

    protected function getRegions(int $companyId): array
    {
        $selectedRegions = CompanyDeliveryLocationsTable::getList([
            'filter' => [
                '=COMPANY_ID' => $companyId,
            ],
            'select' => [
                'TYPE',
                'COUNT',
                'DELIVERY_PRICE' => 'PRICE',
                'REGION_NAME' => 'REGION.NAME',
                new ExpressionField(
                    'DISTRICTS',
                    'GROUP_CONCAT(CONCAT(%s, " ", %s) SEPARATOR ", ")',
                    ['LOCATION.NAME', 'LOCATION.PROPERTY_TYPE_SHORT_VALUE']
                ),
            ]
        ])->fetchAll();

        return ($selectedRegions)? $selectedRegions : [];
    }

    protected function getDeliveryTime(int $companyId): array
    {
        $deliveryTime = CompanyDeliveryTimesTable::getList([
            'filter' => [
                '=COMPANY_ID' => $companyId,
            ],
        ])->fetch();

        $arSchedule = [];
        if ($deliveryTime) {
            $arSchedule = TimeHelper::getArrSchedule($deliveryTime);
        }
        
        return $arSchedule;
    }
}
