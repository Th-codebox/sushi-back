<?php


namespace App\Services\Web\Menu;


use App\Repositories\Store\MenuItemRepository;
use App\Services\Web\CatalogBaseService;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuItemService
 * @package App\Services\Catalog\Store
 */
class MenuItemService extends CatalogBaseService
{

    public function __construct(MenuItemRepository $repository)
    {
        parent::__construct($repository);
    }




}
