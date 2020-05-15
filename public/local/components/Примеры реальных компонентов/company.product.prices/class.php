<?php

namespace Keyba\Components;

use App\Core\Builders\UrlBuilders\CompanyUrlBuilder;
use App\Prices\Entities\Configuration\PriceConfigurationPreparedTable;
use App\Prices\Entities\Configuration\PriceConfigurationTable;
use App\Prices\Entities\Structure\PricePriceListTable;
use App\Prices\Entities\Structure\PriceStructureTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyProductPricesComponent extends \CBitrixComponent
{
    const CONFIGURATION = 'configuration';
    const CONFIGURATION_PREPARED = 'configuration_prepared';

    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $structure = new PriceStructureTable();
            $this->arResult['STRUCTURE'] = $structure->getStructureById($this->arParams['STRUCTURE_ID']);

            if (empty($this->arResult['STRUCTURE'])) {
                LocalRedirect('/404.php');
            }

            $this->arResult['CONFIGURATION']['PRICES'] = $this->getPricesConfigurationsByTypes(
                $this->arParams['STRUCTURE_ID'],
                self::CONFIGURATION
            );
            $this->arResult['CONFIGURATION']['DESCRIPTION'] = $this->getConfigurationDescription($this->arResult['STRUCTURE']['OPTION_CONFIGURATION_ID']);

            $this->arResult['CONFIGURATION_PREPARED']['PRICES'] = $this->getPricesConfigurationsByTypes(
                $this->arParams['STRUCTURE_ID'],
                self::CONFIGURATION_PREPARED
            );
            $this->arResult['CONFIGURATION_PREPARED']['DESCRIPTION'] = $this->getConfigurationPreparedDescription($this->arResult['STRUCTURE']['OPTION_CONFIGURATION_PREPARED_ID']);

            $this->includeComponentTemplate();
        }
    }

    protected function getPricesConfigurationsByTypes(int $structureId, string $type): array
    {
        $result = [];

        $filter = [
            'STRUCTURE_ID' => $structureId,
        ];

        if ($type == self::CONFIGURATION) {
            $filter['>CONFIGURATION_ID'] = 0;
        } else {
            $filter['>CONFIGURATION_PREPARED_ID'] = 0;
        }

        $arStructurePricesDb = PricePriceListTable::getList([
            'filter' => $filter,
            'select' => [
                'ID',
                'CONFIGURATION_ID',
                'CONFIGURATION_PREPARED_ID',
                'WIDTH',
                'HEIGHT',
                'PRICE',
            ],
            'order' => [
                'HEIGHT' => 'ASC',
            ],
        ]);

        $companyUrl = new CompanyUrlBuilder($this->arParams['COMPANY_CODE']);

        while ($arPrice = $arStructurePricesDb->fetch()) {
            $result['table'][$arPrice['HEIGHT']][$arPrice['WIDTH']] = [
                'PRICE' => $arPrice['PRICE'],
                'LINK' => $companyUrl->getConstructionDetailPricePath($this->arParams['PRODUCT_CODE'], $structureId, $arPrice['WIDTH'], $arPrice['HEIGHT']),
            ];
        }

        if ($result['table']) {
            foreach ($result['table'] as $height => $widths) {
                $result['heights'][$height] = true;

                foreach ($widths as $width => $data) {
                    $result['widths'][$width] = true;
                }
            }

            $result['heights'] = collect($result['heights'])->keys()->sort()->toArray();
            $result['widths'] = collect($result['widths'])->keys()->sort()->toArray();
        }

        return $result;
    }

    protected function getConfigurationDescription($configurationId): string
    {
        $arConfiguration = PriceConfigurationTable::getList([
            'filter' => [
                'ID' => $configurationId,
            ],
            'select' => [
                'RESULT_STR',
            ],
        ])->fetch();

        return ($arConfiguration)? $arConfiguration['RESULT_STR'] : '';
    }

    protected function getConfigurationPreparedDescription($configurationPreparedId): string
    {
        $arConfigurationPrepared = PriceConfigurationPreparedTable::getList([
            'filter' => [
                'ID' => $configurationPreparedId,
            ],
            'select' => [
                'RESULT_STR',
            ],
        ])->fetch();

        return ($arConfigurationPrepared)? $arConfigurationPrepared['RESULT_STR'] : '';
    }
}
