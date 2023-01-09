<?php

namespace App\Http\Controllers\Web\Menu;


use App\Http\Controllers\Web\BaseWebController;
use App\Http\Resources\Web\CollectionResource;

use App\Libraries\Cache;
use App\Services\Web\Menu\CollectionService;

/**
 * Class CollectionController
 * @package App\Http\Controllers\Web\Catalog
 * @method CollectionService getCollectionsByTypeAndTarget()
 */
class CollectionController extends BaseWebController
{

    /**
     * CollectionController constructor.
     * @param CollectionService $service
     */
    public function __construct(CollectionService $service)
    {
        parent::__construct($service, CollectionResource::class);
    }

    /**
     * @param string $type
     * @param string $target
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionsByParam(string $type, string $target)
    {
        $conditions = [
            'type'   => $type,
            'target' => $target,
        ];

        if(request()->get('relations')) {
            $conditions['relations'] = request()->get('relations');
        }
        return Cache::getInstance()->remember(get_class($this->service) . http_build_query($conditions), 1800, function () use ($conditions) {
             $itemsModel =  $this->service->getItemsByConditions($conditions);

            return $this->respondWithCollection($itemsModel);
        });



    }
}
