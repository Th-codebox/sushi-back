<?php


namespace App\Repositories\System;

use App\Models\System\Utm as UtmModel;
use App\Repositories\BaseRepository;

/**
 * Class UtmRepository
 * @package App\Repositories\System
 * @method UtmModel getModel()
 */
class UtmRepository extends BaseRepository
{

    /**
     * UtmRepository constructor.
     * @param UtmModel|null $model
     */
    public function __construct(UtmModel $model = null)
    {
        if($model === null) {
            $model = new UtmModel();
        }
        parent::__construct($model);
    }

    public function getId(): ?int
    {
        return $this->getModel()->id;
    }

    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {
    }

}
