<?php

namespace Mt\Components;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class PublicPaginationComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $this->arResult = $this->arParams['NAV_PARAMS'];

        $this->includeComponentTemplate();
    }
}
