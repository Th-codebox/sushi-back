<?php


namespace App\Http\Controllers\Traits;

use Illuminate\Support\Str;

/**
 * Trait Search
 * @package App\Http\Controllers\Traits
 */
trait Search
{
    private array $searchParams = [];

    /**
     * @return array
     */
    protected function getSearchParams(): array
    {
        if (count($this->searchParams) === 0) {
            $this->searchParams = [
                'search' => Str::snake($this->getSearchParam()),
                'text'   => $this->getSearchText(),
            ];
        }
        return $this->searchParams;
    }

    /**
     * @return bool
     */
    protected function hasSearchRequest(): bool
    {
        return ($this->getSearchParam() !== '' && $this->getSearchText() !== '');
    }

    /**
     * @return string
     */
    private function getSearchParam(): string
    {
        $searchParam = request()->input('search', '');
        return ((is_string($searchParam)) ? $searchParam : '');
    }

    /**
     * @return string
     */
    private function getSearchText(): string
    {
        $searchText = request()->input('text', '');
        return ((is_string($searchText)) ? urldecode($searchText) : '');
    }
}
