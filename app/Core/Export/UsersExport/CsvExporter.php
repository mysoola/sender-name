<?php

namespace App\Core\Export\UsersExport;


class CsvExporter implements UsersExportInterface
{
    const DELIMITER = ';';
    const FILE_PATH = 'public/upload/export/users/users.csv';

    private function saveToCsv(array $arUsers): bool
    {
        $arUsers = array_map(function ($user) {
            foreach ($user as &$value) {
                if (empty($value)) {
                    $value = 'empty';
                }
            }

            return $user;
        }, $arUsers);

        $buffer = fopen(self::FILE_PATH, 'w');
        fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));

        $firstElement = array_shift($arUsers);
        $arHeader = array_keys($firstElement);
        fputcsv($buffer, $arHeader, self::DELIMITER);

        foreach ($arUsers as $user) {
            fputcsv($buffer, $user, self::DELIMITER);
        }

        fclose($buffer);

        return file_exists(self::FILE_PATH);
    }

    public function exportUsers(array $arUsers): bool
    {
        return $this->saveToCsv($arUsers);
    }
}