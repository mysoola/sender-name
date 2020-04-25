<?php

namespace App\Core\Export\UsersExport;


class CsvExporter implements UsersExportInterface
{
    const DELIMITER = ';';
    const FILE_PATH = 'public/upload/export/users/users.csv';

    private function saveToXml(array $arUsers): bool
    {

        $buffer = fopen(self::FILE_PATH, 'w');
        fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));

        $firstElement = array_shift($arUsers);
        $arHeader = array_keys($firstElement);
        fputcsv($buffer, $arHeader, self::DELIMITER);

        foreach ($arUsers as $val) {
            fputcsv($buffer, $val, self::DELIMITER);
        }

        fclose($buffer);

        return file_exists(self::FILE_PATH);
    }

    public function exportUsers($arUsers): void
    {
        $this->saveToXml($arUsers);
    }
}