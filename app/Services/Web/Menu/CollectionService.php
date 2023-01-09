<?php


namespace App\Services\Web\Menu;


use App\Repositories\Store\CollectionRepository;
use App\Services\Web\CatalogBaseService;

/**
 * Class CollectionService
 * @package App\Services\Catalog\Store
 *

 */
class CollectionService extends CatalogBaseService
{

    public function __construct(CollectionRepository $repository)
    {
        parent::__construct($repository);
    }


}
