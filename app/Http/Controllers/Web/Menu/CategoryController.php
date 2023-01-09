<?php

namespace App\Http\Controllers\Web\Menu;

use App\Http\Controllers\Web\BaseWebController;
use App\Http\Resources\Web\CategoryResource;
use App\Libraries\Helpers\SettingHelper;
use App\Services\Web\Menu\CategoryService;

class CategoryController extends BaseWebController
{

    public function __construct(CategoryService $service)
    {
        parent::__construct($service,CategoryResource::class);
    }

    public function index()
    {

        return parent::index(); // TODO: Change the autogenerated stub
    }

    public function show(string $searchParam)
    {
        return parent::show($searchParam); // TODO: Change the autogenerated stub
    }
}