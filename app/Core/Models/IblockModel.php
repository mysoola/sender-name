<?php

namespace App\Core\Models;

use \Bitrix\Iblock\IblockTable;
use Bitrix\Main\Loader;

Loader::includeModule('iblock');

/**
 * Class IblockModel
 * @package App\Core\Models
 */
class IblockModel
{
    /**
     * @param $iblockCode
     * @param array $elementFields
     * @param array $arSort
     *
     * @return array|bool
     */
    public function getIblockElements($iblockCode, $elementFields = [], $arSort = ['SORT' => 'ASC'])
    {
        $result = [];

        if (!$iblockCode) {
            return false;
        }

        $rsRes = \CIBlockElement::GetList(
            $arSort,
            ['IBLOCK_CODE' => $iblockCode, 'ACTIVE' => 'Y'],
            false,
            false,
            (empty($elementFields) ? ['ID', 'IBLOCK_ID', 'NAME', 'CODE'] : array_merge($elementFields, ['ID', 'IBLOCK_ID', 'NAME', 'CODE']))
        );

        while ($arRes = $rsRes->GetNext(false, false)) {
            $result[$arRes['ID']] = $arRes;
        }

        return $result;
    }

    /**
     * @param $searchData
     * @param $fields
     * @param $props
     * @param $iblockCode
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return int
     */
    public function addOrUpdate($searchData, $fields, $props, $iblockCode)
    {
        $issetEl = $this->getElementByData($searchData, $iblockCode);
        
        if (isset($issetEl['ID'])) {
            return $this->update($issetEl['ID'], $fields, $props, $iblockCode);
        } else {
            return $this->add($fields, $props, $iblockCode);
        }
    }

    /**
     * @param $searchData
     * @param $iblockCode
     *
     * @return array|bool
     */
    public function getElementByData($searchData, $iblockCode, array $selectFields = [])
    {
        $result = [];

        if (!$searchData) {
            return false;
        }

        $rsRes = \CIBlockElement::GetList(
            ['ID'],
            array_merge($searchData, ['IBLOCK_CODE' => $iblockCode]),
            false,
            false,
            (!empty($selectFields) ? array_merge($selectFields, ['ID', 'IBLOCK_ID', 'NAME', 'CODE']) : [])
        );

        if ($arRes = $rsRes->GetNext()) {
            $result = $arRes;
        }

        return $result;
    }

    /**
     * @param string $iblockCode
     * @param array $filter
     * @param array $fields
     * @param array $sort
     *
     * @return array
     */
    public function find(string $iblockCode, array $filter = [], array $fields = [], array $sort = [], $groupBy = false): array
    {
        $result = [];

        $rsRes = \CIBlockElement::GetList(
            $sort,
            array_merge($filter, ['IBLOCK_CODE' => $iblockCode]),
            $groupBy,
            false,
            (empty($fields) ? ['ID', 'IBLOCK_ID', 'NAME', 'CODE'] : array_merge($fields, ['ID', 'IBLOCK_ID', 'NAME', 'CODE']))
        );

        while ($arRes = $rsRes->GetNext(true, false)) {
            $result[] = $arRes;
        }

        return $result;
    }

    /**
     * @param string $iblockCode
     * @param array $params
     *
     * fields
     * props
     * sort
     * filter
     * groupBy
     * returnBy
     * limit
     *
     * @return array
     */
    public function findByParams($iblockCode, array $params = []): array
    {
        $result = [];

        if (isset($params['props'])) {
            foreach ($params['props'] as $prop) {
                $params['fields'][] = 'PROPERTY_' . $prop;
            }
        }

        if (isset($params['limit'])) {
            $params['navParams'] = [
                'nTopCount' => $params['limit'],
            ];
        }

        $rsRes = \CIBlockElement::GetList(
            $params['sort'] ?? [],
            array_merge($params['filter'] ?? [], ['IBLOCK_CODE' => $iblockCode]),
            $params['groupBy'] ?? false,
            $params['navParams'] ?? false,
            (!$params['fields'] ? ['ID', 'IBLOCK_ID', 'NAME', 'CODE'] : array_merge($params['fields'], ['ID', 'IBLOCK_ID', 'NAME', 'CODE']))
        );

        while ($arRes = $rsRes->Fetch()) {
            if (isset($params['returnBy']) && !is_array($params['returnBy'])) {
                $result[$arRes[$params['returnBy']]] = $arRes;
            } else {
                $result[$arRes['ID']] = $arRes;
            }
        }

        return $result;
    }

    /**
     * @param int $iBlockId
     * @param int $elementId
     * @param $propertyCode
     * @return array
     */
    public function getMultiplePropertyByCode(int $iBlockId, int $elementId, $propertyCode): array
    {
        $rsProperty = \CIBlockElement::GetProperty($iBlockId, $elementId, [], [
            'CODE' => $propertyCode,
        ]);

        $arProperty = [];
        while ($property = $rsProperty->Fetch()) {
            if (isset($property['VALUE'])) {
                $arProperty[] = $property['VALUE'];
            }
        }

        return $arProperty;
    }

    /**
     * @param array $searchData
     * @param string $iblockCode
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return int
     */
    public function findOrAddByFields(array $searchData, string $iblockCode): int
    {
        $result = [];

        if (!$searchData) {
            return false;
        }

        if ($searchData['NAME']) {
            $searchData['NAME'] = trim($searchData['NAME']);
        } else {
            return false;
        }

        $rsRes = \CIBlockElement::GetList(
            ['ID'],
            array_merge($searchData, ['IBLOCK_CODE' => $iblockCode]),
            false,
            false,
            []
        );

        if ($arRes = $rsRes->GetNext()) {
            $result = $arRes['ID'];
        } else {
            $result = $this->add($searchData, [], $iblockCode);
        }

        return $result;
    }

    /**
     * @param array $fields
     * @param array $props
     * @param string $iblockCode
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return int
     */
    public function add(array $fields = [], array $props = [], string $iblockCode): int
    {
        $el = new \CIBlockElement;

        $arFields = $fields;

        if (!empty($props)) {
            $arFields['PROPERTY_VALUES'] = $props;
        }

        $arFields['NAME'] = ($fields['NAME']) ? $fields['NAME'] : 'name';

        $iblockModel = new IblockModel();
        $arFields['IBLOCK_ID'] = $iblockModel->getIblockIdByCode($iblockCode);
        $arFields['ACTIVE'] = ($fields['ACTIVE']) ? $fields['ACTIVE'] : 'Y';
        
        $id = $el->Add($arFields);

        if (!$id) {
            throw new \Exception($el->LAST_ERROR);
        }

        return $id;
    }

    /**
     * @param int $id
     * @param array $fields
     * @param array $props
     * @param string $iblockCode
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return int
     */
    public function update(int $id, array $fields = [], array $props = [], string $iblockCode): int
    {
        $el = new \CIBlockElement;

        $arFields = $fields;

        if (!empty($props)) {
            $arFields['PROPERTY_VALUES'] = $props;
        }

        $iblockModel = new IblockModel();
        $arFields['IBLOCK_ID'] = $iblockModel->getIblockIdByCode($iblockCode);
        $arFields['ACTIVE'] = ($fields['ACTIVE']) ? $fields['ACTIVE'] : 'Y';

        $res = $el->Update($id, $arFields);

        if (!$res) {
            throw new \Exception($el->LAST_ERROR);
        }

        return $id;
    }

    /**
     * @param $id
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return bool
     */
    public function getIblockCodeById($id)
    {
        if (!$id) {
            return false;
        }

        $result = false;

        $res = IblockTable::getList([
            'select' => ['CODE'],
            'filter' => ['ID' => $id]
        ]);

        if ($row = $res->Fetch()) {
            $result = $row;
        }

        return $result['CODE'];
    }

    /**
     * @param $code
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return bool
     */
    public function getIblockIdByCode($code)
    {
        if (!$code) {
            return false;
        }

        $result = false;

        $res = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['CODE' => $code]
        ]);

        if ($row = $res->Fetch()) {
            $result = $row;
        }

        return $result['ID'];
    }

    /**
     * @param int $iblockId
     * @param string $fieldName
     *
     * @return array
     */
    public function getPropertyEnumList(int $iblockId, string $fieldName): array
    {
        $result = [];

        $db = \CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $iblockId, 'CODE' => $fieldName]);

        while ($ar = $db->GetNext()) {
            $result[$ar['ID']] = $ar;
        }

        return $result;
    }

    /**
     * @param int $iblockId
     * @param string $fieldName
     *
     * @return array
     */
    public function getPropertyEnumListWithXmlId(int $iblockId, string $fieldName): array
    {
        $result = [];

        $db = \CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $iblockId, 'CODE' => $fieldName]);

        while ($ar = $db->GetNext()) {
            $result[$ar['XML_ID']] = $ar;
        }

        return $result;
    }

    /**
     * @param int $iblockId
     * @param string $fieldName
     * @param string $xmlId
     *
     * @throws \Exception
     * @return int
     */
    public function getPropertyEnumIdByXmlId(int $iblockId, string $fieldName, string $xmlId): int
    {
        $result = [];

        $db = \CIBlockPropertyEnum::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $fieldName, 'XML_ID' => $xmlId]);

        if ($ar = $db->GetNext()) {
            return $ar['ID'];
        }

        throw new \Exception('Не найден элемент списка по xml id');
    }

    public function getPropertyEnumArrayByXmlId(int $iblockId, string $fieldName, string $xmlId): array
    {
        $result = [];

        $db = \CIBlockPropertyEnum::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $fieldName, 'XML_ID' => $xmlId]);

        if ($ar = $db->GetNext()) {
            $result = $ar;
            return $result;
        }

        throw new \Exception('Не найден элемент списка по xml id');
    }

    /**
     * @param int $iblockId
     * @param string $fieldName
     * @param string $value
     *
     * @throws \Exception
     * @return int
     */
    public function getPropertyEnumIdByValue(int $iblockId, string $fieldName, string $value)
    {
        $result = '';

        $db = \CIBlockPropertyEnum::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $fieldName, 'VALUE' => $value]);

        if ($ar = $db->GetNext()) {
            $result = $ar['ID'];
        }

        return $result;
    }

    /**
     * @param int $elementId
     * @param array $props
     *
     * @throws \Exception
     */
    public function updateElementPropsArray(int $elementId, array $props)
    {
        foreach ($props as $code => $value) {
            \CIBlockElement::SetPropertyValuesEx($elementId, false, [$code => $value]);

            global $APPLICATION;
            if ($ex = $APPLICATION->GetException()) {
                throw new \Exception($ex->GetString());
            }
        }
    }
}
