<?php


namespace App\Services\Web\Menu;



use App\Repositories\Store\CategoryRepository;
use App\Services\Web\CatalogBaseService;
use Illuminate\Database\Eloquent\Model;

class CategoryService extends CatalogBaseService
{

    public function __construct(CategoryRepository $repository)
    {
        parent::__construct($repository);
    }


}
