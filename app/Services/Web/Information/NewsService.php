<?php


namespace App\Services\Web\Information;



use App\Repositories\Store\NewsRepository;
use App\Services\Web\CatalogBaseService;

class NewsService extends CatalogBaseService
{

    public function __construct(NewsRepository $repository)
    {
        parent::__construct($repository);
    }


}
