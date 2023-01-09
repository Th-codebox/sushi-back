<?php


namespace App\Services\Web\Information;



use App\Repositories\Store\SliderRepository;
use App\Services\Web\CatalogBaseService;


class SliderService extends CatalogBaseService
{

    public function __construct(SliderRepository $repository)
    {
        parent::__construct($repository);
    }


}
