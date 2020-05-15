<?php

namespace Keyba\Components;

use App\Core\Entities\Company\CompanyDeliveryLocationsTable;
use App\Core\Entities\Company\CompanyDeliveryTimesTable;
use App\Core\Helpers\TimeHelper;
use App\Prices\Entities\Structure\PricePriceListTable;
use App\Prices\Entities\Structure\PriceStructureTable;
use Bitrix\Main\ORM\Fields\ExpressionField;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class PublicCompanyProductCardComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $productParams = $this->preparingProductParameters($this->arParams['STR_PRODUCT_PARAMS']);

        if ($this->StartResultCache()) {
            if (isset($productParams['WEIGHT']) &&
                isset($productParams['HEIGHT']) &&
                isset($this->arParams['STRUCTURE_ID'])
            ) {
                $this->arResult['PRODUCT'] = $this->getProduct($productParams['WEIGHT'], $productParams['HEIGHT'], $this->arParams['STRUCTURE_ID']);

                if (empty($this->arResult['PRODUCT'])) {
                    LocalRedirect('/404.php');
                }

                $structure = new PriceStructureTable();
                $this->arResult['STRUCTURE'] = $structure->getStructureById($this->arParams['STRUCTURE_ID']);

                $this->arResult['COMPANY']['PAYMENTS'] = $this->arParams['PAYMENT_TYPES'];
                $this->arResult['COMPANY']['REGIONS'] = $this->getRegions($this->arParams['COMPANY_ID']);
                $this->arResult['COMPANY']['DELIVERY_TIME'] = $this->getDeliveryTime($this->arParams['COMPANY_ID']);
                $this->arResult['COMPANY']['NAME'] = $this->arParams['COMPANY_NAME'];
                $this->arResult['COMPANY']['URL'] = PUBLIC_COMPANIES_DIRECTORY . $this->arParams['COMPANY_CODE'] . '/';
                $this->arResult['COMPANY']['PAY_AND_DELIVERY_URL'] = PUBLIC_COMPANIES_DIRECTORY . $this->arParams['COMPANY_CODE'] . '/' . PUBLIC_PART_URL_FOR_COMPANY_PAYMENT_DELIVERY . '/';

                $this->includeComponentTemplate();
            } else {
                LocalRedirect('/404.php');
            }
        }
    }

    protected function preparingProductParameters(string $strProductParams): array
    {
        $result = [];

        if (!empty($strProductParams)) {
            $arProductParams = explode('-', $strProductParams);

            $result = [
                'WEIGHT' => (int)$arProductParams[0],
                'HEIGHT' => (int)$arProductParams[1],
                'STRUCTURE_ID' => $this->arParams['STRUCTURE_ID'],
            ];
        }

        return $result;
    }

    protected function getProduct(int $weight, int $height, int $structureId): array
    {
        $productObj = PricePriceListTable::getList([
            'filter' => [
                '=WIDTH' => $weight,
                '=HEIGHT' => $height,
                '=STRUCTURE_ID' => $structureId,
                '=ACTIVE' => true,
            ],
            'select' => [
                'ID',
                'WIDTH',
                'HEIGHT',
                'PRICE',
                'STRUCTURE_ID',
                'PROFILE_BRAND_NAME' => 'OPTION.CONFIGURATION.PROFILE.BRAND.NAME',
                'PROFILE_LINE_NAME' => 'OPTION.CONFIGURATION.PROFILE.LINE.NAME',
                'FITTINGS_BRAND_NAME' => 'OPTION.CONFIGURATION.FITTINGS.BRAND.NAME',
                'FITTINGS_LINE_NAME' => 'OPTION.CONFIGURATION.FITTINGS.LINE.NAME',
                'GLASS_TYPE_NAME' => 'OPTION.CONFIGURATION.GLASS.TYPE.GLASS_TYPE.NAME',
                'GLASS_FORMULA' => 'OPTION.CONFIGURATION.GLASS.FORMULA',
                'GLASS_WIDTH' => 'OPTION.CONFIGURATION.GLASS.TYPE.WIDTH',
                'WOOD_SPECIES_LAYER_NAME' => 'OPTION.CONFIGURATION.SPECIES.TYPE.SPECIES_LAYER.NAME',
                'WOOD_SPECIES_WIDTH' => 'OPTION.CONFIGURATION.SPECIES.TYPE.WIDTH',
                'WOOD_SPECIES_HEIGHT' => 'OPTION.CONFIGURATION.SPECIES.TYPE.HEIGHT',
                'WOOD_SPECIES_NAME' => 'OPTION.CONFIGURATION.SPECIES.TYPE.WOOD_SPECIES.NAME',
                'WOOD_OVERLAY' => 'OPTION.CONFIGURATION.OVERLAY.OVERLAY_IBLOCK.NAME',
                'PREPARED' => 'CONFIGURATION_PREPARED.RESULT_STR',
                'WARRANTY' => 'OPTION.CONFIGURATION.WARRANTY',
                'MATERIAL_CODE' => 'OPTION.MATERIAL.CODE',
                'MATERIAL_NAME' => 'OPTION.MATERIAL.NAME',

            ],
        ]);

        $arProduct = [];
        while ($product = $productObj->fetch()) {
            if (isset($product['PREPARED'])) {
                $arProduct['CONFIGURATION_PREPARED'] = $product;
                $arProduct['CONFIGURATION_PREPARED']['IS_PREPARED'] = true;
                $arProduct['CONFIGURATION_PREPARED']['PREPARED_PRICE'] = $product['PRICE'];
            } else {
                $arProduct['CONFIGURATION'] = $product;
                $arProduct['CONFIGURATION']['CONFIGURATION_PRICE'] = $product['PRICE'];
                $arProduct['CONFIGURATION']['IS_CONFIGURATION'] = true;
            }
        }

        if (isset($arProduct['CONFIGURATION_PREPARED']) && !isset($arProduct['CONFIGURATION'])) {
            $arProduct['CONFIGURATION'] = $arProduct['CONFIGURATION_PREPARED'];
        } elseif (isset($arProduct['CONFIGURATION_PREPARED'])  && isset($arProduct['CONFIGURATION'])) {
            $arProduct['CONFIGURATION'] = array_merge($arProduct['CONFIGURATION'], $arProduct['CONFIGURATION_PREPARED']);
        }

        unset($arProduct['CONFIGURATION_PREPARED']);

        return ($arProduct) ? $arProduct : [];
    }

    protected function getRegions(int $companyId): array
    {
        $regionsObj = CompanyDeliveryLocationsTable::getList([
            'filter' => [
                '=COMPANY_ID' => $companyId,
            ],
            'select' => [
                'REGION_NAME' => 'REGION.NAME',
                'REGION_TYPE_SHORT' => 'REGION.PROPERTY_TYPE_SHORT_VALUE',
                new ExpressionField(
                    'DISTRICTS',
                    'GROUP_CONCAT(CONCAT(%s, " ", %s) SEPARATOR ", ")',
                    ['LOCATION.NAME', 'LOCATION.PROPERTY_TYPE_SHORT_VALUE']
                ),
            ]
        ]);

        $arRegions = [];
        while ($region = $regionsObj->fetch()) {
            $region['DISTRICTS'] = explode(',', $region['DISTRICTS']);

            $arRegions[] = $region;
        }

        return $arRegions;
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
