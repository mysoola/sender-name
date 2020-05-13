<?php

namespace App\Core\Export\UsersExport;

class XmlExporter implements UsersExportInterface
{
    const FILE_PATH = 'public/upload/export/users/users.xml';

    private function saveToXml(array $arUsers): bool
    {
        $export = new \Bitrix\Main\XmlWriter([
            'file' => '/upload/export/users/users.xml',
            'create_file' => true,
            'charset' => 'UTF-8',
            'lowercase' => true,
        ]);

        $export->openFile();
        $export->writeBeginTag('items');
        foreach ($arUsers as $user) {
            $export->writeItem($user, 'item');
        }

        if (!empty($export->getErrors())) {
            throw new \Exception('При записи произошли ошибки');
        }
        $export->writeEndTag('items');
        $export->closeFile();

        return file_exists(self::FILE_PATH);
    }

    public function exportUsers(array $arUsers): bool
    {
        try {
            return $this->saveToXml($arUsers);
        } catch (\Exception $e) {
        }
    }
}