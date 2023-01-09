<?php


namespace App\Services\Web\Information;



use App\Repositories\Store\PromotionRepository;
use App\Services\Web\CatalogBaseService;
use Illuminate\Database\Eloquent\Model;

class PromotionService extends CatalogBaseService
{

    public function __construct(PromotionRepository $repository)
    {
        parent::__construct($repository);
    }


}
