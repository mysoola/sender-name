<?php

namespace Keyba\Components;

use App\Core\Helpers\CompaniesFilterHelper;
use App\Prices\Entities\PriceOptionStatusTable;
use App\Prices\Entities\PriceOptionTable;
use Bitrix\Main\ORM\Fields\ExpressionField;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CompanyPricesComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        if ($this->StartResultCache()) {
            $this->arResult['OPTIONS'] = $this->getOptions($this->arParams['COMPANY_ID']);

            $this->includeComponentTemplate();
        }
    }

    protected function getOptions(int $companyId): array
    {
        $obOptions = PriceOptionTable::getList([
            'filter' => [
                'COMPANY_ID' => $companyId,
                'STATUS_ID' => PriceOptionStatusTable::getIdByCode(OPTION_STATUS_APPROVED),
            ],
            'select' => [
                'PRODUCT_OPTION_ID' => 'PRODUCT.ID',
                'PRODUCT_MATERIAL_ID' => 'MATERIAL.ID',
                'PRODUCT_OPTION_CODE' => 'PRODUCT.CODE',
                'PRODUCT_MATERIAL_CODE' => 'MATERIAL.CODE',
                new ExpressionField(
                    'MIN_PRICE',
                    'MIN(%s)',
                    ['STRUCTURES.P_MIN']
                ),
                new ExpressionField(
                    'MAX_PRICE',
                    'MAX(%s)',
                    ['STRUCTURES.P_MAX']
                ),
                new ExpressionField(
                    'STRUCTURES_COUNT',
                    'COUNT(%s)',
                    ['STRUCTURES.ID']
                ),
            ],
            'order' => [
                'PRODUCT_MATERIAL_ID' => 'ASC',
                'PRODUCT_OPTION_ID' => 'ASC',
            ]
        ]);

        $arOptions = [];
        while ($option = $obOptions->fetch()) {
            if ($option['STRUCTURES_COUNT'] > 0) {
                $arOptions[$option['PRODUCT_OPTION_CODE'] . '_' . $option['PRODUCT_MATERIAL_CODE']] = $option;
            }
        }

        foreach ($arOptions as $type => $options) {
            $product = CompaniesFilterHelper::getByMaterialProduct([$options['PRODUCT_MATERIAL_CODE']], [$options['PRODUCT_OPTION_CODE']]);
            if (!empty($product)) {
                $arOptions[$type]['TYPE_NAME'] = $product['TITLE'];
                $arOptions[$type]['TYPE_IMAGE'] = PUBLIC_ASSET_ROOT_PATH .  '/images/icon.svg#icon_icon_' . $options['PRODUCT_OPTION_CODE'];
            } else {
                unset($arOptions[$type]);
            }
        }

        return $arOptions;
    }
}
