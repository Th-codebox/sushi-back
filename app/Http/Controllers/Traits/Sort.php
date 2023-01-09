<?php

namespace App\Http\Controllers\Traits;

/**
 * Trait Sort
 * @package App\Http\Controllers\Traits
 */
trait Sort
{
    private array $sortParams = [];

    /**
     * @return array
     */
    protected function getSort(): array
    {
        $this->sortParams = [
            'sortField' => $this->getSortParam(),
            'sortMethod' => $this->getSortDirection(),
        ];


        return $this->sortParams;
    }

    /**
     * @return bool
     */
    protected function hasSortRequest(): bool
    {
        return ($this->getSortParam() !== '');
    }

    /**
     * @return array
     */
    private function getSortParam(): ?string
    {
        return request()->input('sortField');

    }

    /**
     * @return string
     */
    private function getSortDirection(): ?string
    {
        $sortMethod =  (string)request()->input('sortMethod');

        if(!in_array(mb_strtolower($sortMethod),['asc','desc'])) {
            $sortMethod = 'desc';
        }

        return mb_strtoupper($sortMethod);

    }
}
