<?php

namespace Mt\Components;

use App\Core\Controllers\ApplicationForLetterNameController;
use Bitrix\Main\Application;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class ApplicationForLetterNameComponent extends \CBitrixComponent
{
    private $status = false;

    public function executeComponent()
    {
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->isPost()) {

            $params = [
                'NAME' => trim($request->getPost('name')),
            ];

            $this->arResult['NAME'] = $params['NAME'];

            $this->checkParams($params);

            if ($this->status) {
                $this->save($params);
            }
        }

        $this->includeComponentTemplate();
    }

    protected function checkParams(array $params)
    {
        $this->checkName($params['NAME']);
    }

    protected function save(array $params)
    {
        $applicationForLetterNameController = new ApplicationForLetterNameController();
        $result = $applicationForLetterNameController->save($params);

        if (!$result) {
            $this->arResult['ERRORS'][] = 'Не удалось отправить имя. Попробуйте позже.';
        } else {
            $this->arResult['STATUS'] = true;
        }
    }

    protected function checkName(string $name)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($name, [
            new Length([
                'min' => 3,
                'max' => 11,
                'minMessage' => 'Буквенное SMS-имя должно содержать не меньше {{ limit }} символов.',
                'maxMessage' => 'Буквенное SMS-имя должно содержать не больше {{ limit }} символов.',
            ]),
            new Assert\Regex([
                'pattern' => '/[a-zA-Z]/',
                'message' => 'Буквенное SMS-имя может быть только на латинице.',
            ]),
        ]);

        if (0 !== count($violations)) {
            foreach ($violations as $violation) {
                $this->arResult['ERRORS'][] = $violation->getMessage();
            }

            $this->status = false;
        } else {
            $this->status = true;
        }
    }
}