<?php

if (!function_exists('return_json')) {
    function return_json($response)
    {
        echo \Bitrix\Main\Web\Json::encode($response);
        die();
    }
}