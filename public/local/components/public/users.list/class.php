<?php

namespace Mt\Components;

use App\Core\Services\Paginator;
use App\Core\Traits\AjaxRequestable;
use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\UserTable;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Positive;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class Component extends \CBitrixComponent
{
    use AjaxRequestable;

    const SHOW_ELEMENTS_COUNT = 2; // TODO: Тестовое значение. Изменить на необходимое.
    const CACHE_TIME = 3600;

    public function executeComponent()
    {
        $this->request = Application::getInstance()->getContext()->getRequest();
        $this->handleAjax($this->request);

        $page = 1;
        $requestPage = (int)$this->request->get('users_page');
        if (isset($requestPage)) {
            $pageParamIsValid = $this->pageValidate($requestPage);
            if ($pageParamIsValid['STATUS'] === true) {
                $page = $requestPage;
            }
        }

        $this->arResult['USERS'] = $this->getUsers($page);

        $this->includeComponentTemplate();
    }

    protected function getUsers(int $page): array
    {
        $arUsers = [];

        $nav = new Paginator('users');
        $nav->setDefaultPerPage(self::SHOW_ELEMENTS_COUNT);
        $nav->initFromUri($page);

        $cache = Cache::createInstance();

        if ($cache->initCache(
            self::CACHE_TIME,
            'users_list_' . $page,
            USERS_LIST_CACHE_DIR
        )) {
            $arUsers = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $usersObj = UserTable::getList([
                'select' => [
                    'ID',
                    'NAME',
                    'SECOND_NAME',
                    'LAST_NAME',
                    'PERSONAL_PHONE',
                    'EMAIL',
                    'ACTIVE',
                    'DATE_REGISTER',
                ],
                'order' => [
                    'NAME' => 'ASC',
                ],
                'count_total' => true,
                'limit' => $nav->getLimit(),
                'offset' => $nav->getOffset(),
            ]);

            while ($user = $usersObj->fetch()) {
                $user['DATE_REGISTER'] = $user['DATE_REGISTER']->toString();
                $user['ACTIVE'] = ($user['ACTIVE'] == 'Y');

                $arUsers['ITEMS'][$user['ID']] = $user;
            }

            if (!empty($arUsers)) {
                $countFindElements = $usersObj->getCount();
                $nav->setElementsCount($countFindElements);
                $nav->setPagination();

                $arUsers['IS_END_PAGE'] = $nav->isEndPage();
                $arUsers['NAV_PARAMS'] = $nav->getNavParams();
            }

            $cache->endDataCache($arUsers);
        }

        return $arUsers;
    }

    public function getUsersAjax(HttpRequest $request)
    {
        $page = (int)$request->get('page');
        $pageParamIsValid = $this->pageValidate($page);

        if ($pageParamIsValid['STATUS'] === false) {
            $result['errors'] = $pageParamIsValid['ERRORS'];
            $result['status'] = false;
        } else {
            $this->arResult['USERS'] = $this->getUsers($page);

            ob_start();

            $this->includeComponentTemplate();

            $html = ob_get_contents();

            ob_end_clean();

            $result['html'] = $html;
            $result['status'] = true;
        }

        return $result;
    }

    protected function pageValidate(int $page): array
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($page, [
            new Positive([
                'message' => 'Номер страницы должен быть положительным числом.'
            ])
        ]);

        if (0 !== count($violations)) {
            $result = [];
            foreach ($violations as $violation) {
                $result['ERRORS'][] = $violation->getMessage();
            }
            $result['STATUS'] = false;
        } else {
            $result['STATUS'] = true;
        }

        return $result;
    }
}