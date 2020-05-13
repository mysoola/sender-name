<?php

namespace App\Core\Export\UsersExport;

interface UsersExportInterface
{
    public function exportUsers(array $arUsers);
}