<?php


namespace App\Repositories\System;

use App\Models\System\Client;
use App\Models\System\Polygon as PolygonModel;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryException;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class PolygonRepository
 * @package App\Repositories\System
 * @method PolygonModel getModel()
 */
class PolygonRepository extends BaseRepository
{

    protected array $relations = ['filial'];

    /**
     * UserRepository constructor.
     * @param PolygonModel|null $model
     */
    public function __construct(PolygonModel $model = null)
    {

        if ($model === null) {
            $model = new PolygonModel();
        }

        parent::__construct($model);
    }

    protected function afterModification(array $data = []): void
    {
        if (array_key_exists('points', $data) && is_array($data['points'])) {

            $points = [];

            foreach ($data['points'] as $point) {
                $points[] = new Point($point[0], $point[1]);
            }

            $this->getModel()->area = new Polygon([new LineString($points)]);

            $this->getModel()->save();
        }

    }

    protected function conditionsBuilder(array $conditions = []): Builder
    {
        $builder = parent::conditionsBuilder($conditions); // TODO: Change the autogenerated stub

        if (array_key_exists('type', $conditions) && is_string($conditions['type'])) {
            $builder->where('type', '=', $conditions['type']);
        }

        if (array_key_exists('status', $conditions) && !is_array($conditions['status'])) {
            $builder->where('status', '=', $conditions['status']);
        } else {
            $builder->where('status', '=', 1);
        }

        return $builder;
    }

    public function getPolygonsByFilialId(int $filialId): \Illuminate\Database\Eloquent\Collection
    {
        return PolygonModel::where('filial_id', '=', $filialId)->get();
    }

    public function getAllActivePolygons(): \Illuminate\Database\Eloquent\Collection
    {
        return PolygonModel::where('status', '=', 1)->with('filial')->get();
    }
}