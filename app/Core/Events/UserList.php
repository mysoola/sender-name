<?php


namespace App\Core\Events;

class UserList
{
    public function clearCache()
    {
        $cache = \Bitrix\Main\Data\Cache::createInstance();
        $cache->cleanDir(USERS_LIST_CACHE_DIR);
    }
}