<?php

namespace Keyba\Components;

use App\Core\Import\ImportProduct;
use App\Core\Import\XlsxImporter;
use Bitrix\Main\Application;
use CFile;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class ImportComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->isPost()) {
            $xlsxFile = $request->getFile('xlsx');

            $checkFiles = CFile::CheckFile($xlsxFile, 1048576 * 100, false, 'xlsx');

            if (!empty($checkFiles)) {
                $this->arResult['ERRORS'][] = $checkFiles;
            } else {
                $XlsxImporter = new XlsxImporter();
                $import = new ImportProduct($XlsxImporter);
                $this->arResult = $import->save($xlsxFile['tmp_name']);
            }
        }

        $this->includeComponentTemplate();
    }
}