<?php

namespace App\Core\Export\UsersExport;

use Bitrix\Main\DB\Exception;

class UsersExportFactory
{
    public function exportUsers(string $type)
    {
        switch ($type) {
            case 'xml': return new XmlExporter();
            case 'csv': return new CsvExporter();
            default: throw new Exception('Errors type');
        }
    }
}