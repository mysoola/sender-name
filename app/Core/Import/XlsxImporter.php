<?php

namespace App\Core\Import;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validation;

class XlsxImporter implements ImportProvider
{
    public function getDataFromFile(string $filePath): array
    {
        $result = [];

        $oSpreadsheet = IOFactory::load($filePath);
        $oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();

        if ($oCells->getCurrentRow() === 0) {
            $result['ERRORS'][] = 'Файл не должен быть пустым';
        } elseif ($oCells->getCurrentRow() === 1) {
            $result['ERRORS'][] = 'В файле должен быть хотя бы один товар';
        } else {

            $arHeader = [];
            for ($iRow = 1; $iRow <= 1; $iRow++) {
                for ($iCol = 'A'; $iCol <= $oCells->getHighestColumn(); $iCol++) {
                    $oCell = $oCells->get($iCol . $iRow);
                    $arHeader[] = $oCell->getValue();
                }
            }

            for ($iRow = 2; $iRow <= $oCells->getHighestRow(); $iRow++) {
                $i = 0;
                for ($iCol = 'A'; $iCol <= $oCells->getHighestColumn(); $iCol++) {
                    $oCell = $oCells->get($iCol . $iRow);
                    if ($oCell) {
                        $result['ROWS'][$iRow][$arHeader[$i++]] = $oCell->getValue();
                    } else {
                        $result['ROWS'][$iRow][$arHeader[$i++]] = false;
                    }
                }
            }

            foreach ($result['ROWS'] as $kRow => $vRow) {
                if (!$vRow['price']) {
                    $result['ERRORS'][] = 'В строке ' . $kRow . ' поле price обязательно для заполнения';
                }

                if ($vRow['estimatedCost'] && !$vRow['ean']) {
                    $result['ERRORS'][] = 'В строке ' . $kRow . ' поле ean обязательно для заполнения';
                    break;
                }

                if ($vRow['estimatedCost'] && !$vRow['ean']) {
                    $result['ERRORS'][] = 'В строке ' . $kRow . ' поле ean обязательно для заполнения';
                    break;
                }

                if ($vRow['ean']) {
                    $validator = Validation::createValidator();
                    $violations = $validator->validate($vRow['ean'], [
                        new Length([
                            'min' => 13,
                            'max' => 13,
                            'exactMessage' => 'В строке ' . $kRow . ' поле ean должно быть {{ limit }} символов',
                        ]),
                    ]);

                    if (0 !== count($violations)) {
                        foreach ($violations as $violation) {
                            $result['ERRORS'][] = $violation->getMessage();
                        }
                    }
                }

            }
        }

        return $result;
    }
}