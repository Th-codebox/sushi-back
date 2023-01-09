<?php


namespace App\Services\CRM\Store;


use App\Repositories\Store\NewsRepository;
use App\Services\CRM\CRMBaseService;
use App\Services\CRM\Traits\Slug;

/**
 * Class NewsService
 * @package App\Services\CRM\System
 * @method NewsRepository getRepository()
 */
class NewsService extends CRMBaseService
{
    use Slug;

    public function __construct(?NewsRepository $repository = null)
    {
        parent::__construct($repository);
    }

    public function dataCorrection(array $data)
    {
        $data =  parent::dataCorrection($data);

        if(array_key_exists('slug',$data) ||  array_key_exists('name',$data)) {
            $data['slug'] = self::generateSlug($data, ((array_key_exists('slug', $data) && is_string($data['slug']) && $data['slug'] !== '') ? $data['slug'] : $data['name']));
        }
        return $data;
    }

}
