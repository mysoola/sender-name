<?php

namespace App\Core\Services;

use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;

/**
 *
 * Class Paginator
 * @package App\Core\Models
 */
class Paginator
{
    protected $page;
    protected $perPage;

    protected $pageParamName = 'page';
    protected $perPageParamName = 'per_page';

    protected $defaultPerPage;

    protected $elementsCount;

    protected $fromElements;
    protected $toElements;
    protected $nextPage;
    protected $prevPage;

    protected $request;

    const DEFAULT_PAGE = 1;
    const DEFAULT_PER_PAGE = 10;
    const ARRAY_DEFAULT_PER_PAGE_VALUES = [10, 25, 50, 100];

    /**
     * Paginator constructor.
     *
     * @param string $id
     *
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(string $id)
    {
        $this->request = Application::getInstance()->getContext()->getRequest();

        $this->pageParamName = $id . '_' . $this->pageParamName;
        $this->perPageParamName = $id . '_' . $this->perPageParamName;
    }

    public function initFromUri(int $page = null)
    {
        if (isset($page)) {
            $reqPage = $page;
        } else {
            $reqPage = (int)$this->request->getQuery($this->pageParamName);
        }

        $reqPerPage = (int)$this->request->getQuery($this->perPageParamName);

        if ($reqPage) {
            $this->page = $reqPage;
        } else {
            $this->page = self::DEFAULT_PAGE;
        }

        if ($reqPerPage) {
            $this->perPage = $reqPerPage;
        } else {
            if ($this->defaultPerPage) {
                $this->perPage = $this->defaultPerPage;
            } else {
                $this->perPage = self::DEFAULT_PER_PAGE;
            }
        }

        if ($this->perPage < 1) {
            $this->perPage = self::DEFAULT_PER_PAGE;
        }
    }

    public function setPagination()
    {
        if ($this->elementsCount) {
            $endPage = $this->getEndPage();

            if ($this->page > $endPage) {
                $this->page = 1;
            }

            if ($this->page == 1) {
                $this->fromElements = 1;
                $this->toElements = $this->page * $this->perPage;
                $this->nextPage = $this->toElements < $this->elementsCount ? 2 : null;
            } elseif ($this->page == $endPage) {
                $this->fromElements = $this->elementsCount - ($this->elementsCount - (($endPage - 1) * $this->perPage));
                $this->toElements = $this->elementsCount;
                $this->prevPage = $this->page - 1;
            } elseif ($this->page > $endPage || $this->page == 0) {
                $uriString = $this->request->getRequestUri();
                $uri = new Uri($uriString);
                $uri->deleteParams([$this->pageParamName]);

                LocalRedirect($uri->getUri());
            } else {
                $this->fromElements = ($this->page - 1) * $this->perPage;
                $this->toElements = $this->fromElements + $this->perPage;
                $this->nextPage = $this->page + 1;
                $this->prevPage = $this->page - 1;
            }
        }
    }

    public function getNavParams()
    {
        return [
            'PAGE' => $this->getPage(),
            'END_PAGE' => $this->getEndPage(),
            'PER_PAGE' => $this->getPerPage(),
            'FROM_ELEMENTS' => $this->getFromElements(),
            'TO_ELEMENTS' => $this->getToElements(),
            'NEXT_PAGE' => $this->getNextPage(),
            'PREV_PAGE' => $this->getPrevPage(),
            'ELEMENTS_COUNT' => $this->getElementsCount(),
            'AR_PER_PAGE' => self::ARRAY_DEFAULT_PER_PAGE_VALUES,
            'PAGE_PARAM_NAME' => $this->getPageParamName(),
            'PER_PAGE_PARAM_NAME' => $this->getPerPageParamName(),
        ];
    }

    public function getEndPage()
    {
        if ($this->elementsCount) {
            $perPage = $this->perPage;

            return (int)ceil($this->elementsCount / $perPage);
        }

        return $this->elementsCount;
    }

    public function setDefaultPerPage(int $defaultPerPage)
    {
        $this->defaultPerPage = $defaultPerPage;
    }

    public function setElementsCount(int $elementsCount)
    {
        $this->elementsCount = $elementsCount;
    }

    public function getOffset()
    {
        return $this->perPage * ($this->page - 1);
    }

    public function getLimit()
    {
        return $this->perPage;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function getPageParamName()
    {
        return $this->pageParamName;
    }

    public function getPerPageParamName()
    {
        return $this->perPageParamName;
    }

    public function getElementsCount()
    {
        return $this->elementsCount;
    }

    public function getFromElements()
    {
        return $this->fromElements;
    }

    public function getToElements()
    {
        return $this->toElements;
    }

    public function getNextPage()
    {
        return $this->nextPage;
    }

    public function getPrevPage()
    {
        return $this->prevPage;
    }
    
    public function isEndPage()
    {
       return $this->page == $this->getEndPage();
    }
}
