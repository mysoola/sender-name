<?php


namespace App\Core\Traits;

trait AjaxRequestable
{
    public function handleAjax(\Bitrix\Main\HttpRequest $request)
    {
        if ($request->isAjaxRequest()) {
            $response = [];

            try {
                if (method_exists($this, $this->arParams['ACTION'])) {
                    $response = $this->{$this->arParams['ACTION']}($request);

                    if (isset($response['error'])) {
                        $this->arResult['ERRORS'] = $response['error'];
                    } else {
                        $response['success'] = true;
                    }
                } else {
                    $this->arResult['ERRORS'] = 'Неизвестный запрос';
                }
            } catch (\Exception $e) {
                $this->arResult['ERRORS'] = $e->getMessage();
            }

            if ($this->arResult['ERRORS']) {
                return_json([
                    'error' => true,
                    'messages' => [$this->arResult['ERRORS']],
                ]);
            }

            return_json($response);
        }
    }
}
