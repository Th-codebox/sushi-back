<?php


namespace App\Services\Geo;


use App\Models\System\ClientAddress;
use App\Models\System\Polygon;
use App\Repositories\System\PolygonRepository;
use App\Services\CRM\CRMServiceException;
use App\Services\Web\CatalogServiceException;
use Illuminate\Database\Eloquent\Collection;

class PolygonService
{
    private PolygonRepository $polygonRepo;


    public function __construct(PolygonRepository $polygonRepo)
    {
        $this->polygonRepo = $polygonRepo;
    }


    /**
     * @param string $lat
     * @param string $let
     * @return Collection
     */
    public function checkAndGetAvailablePolygonsByPoint(string $lat, string $let): Collection
    {
        $polygonsCollections = $this->polygonRepo->getAllActivePolygons();

        $availablePolygon = new Collection();

        $point = [$lat, $let];

        $polygonsCollections->map(function ($polygon) use ($point, &$availablePolygon) {

            /**
             * @var \App\Models\System\Polygon $polygon
             */

            if ($polygon->area) {
                $polygons = $polygon->getArrayCoords();

                if ($this->checkIfPolygonContainsPoint($polygons, $point)) {
                    $availablePolygon->add($polygon);
                }
            }

        });

        return $availablePolygon;
    }

    /**
     * @return Collection
     */
    public function getPolygons(): Collection
    {
        $polygonsCollections = $this->polygonRepo->getAllActivePolygons();

        $availablePolygon = new Collection();

        $polygonsCollections->map(function ($polygon) use (&$availablePolygon) {

            /**
             * @var \App\Models\System\Polygon $polygon
             */

            if ($polygon->area) {

                $availablePolygon->add($polygon);
            }

        });

        return $availablePolygon;
    }


    /**
     * @param $polygon
     * @param array $point
     * @return bool
     */
    private function checkIfPolygonContainsPoint($polygon, array $point): bool
    {
        $q_patt = [[0, 1], [3, 2]];

        $pred_pt = end($polygon);

        $pred_pt['x'] -= $point[0];
        $pred_pt['y'] -= $point[1];

        $pred_q = $q_patt[$pred_pt['y'] < 0][$pred_pt['x'] < 0];
        $w = 0;

        for ($iter = reset($polygon); $iter !== false; $iter = next($polygon)) {
            $cur_pt = $iter;
            $cur_pt['x'] -= $point[0];
            $cur_pt['y'] -= $point[1];
            $q = $q_patt[$cur_pt['y'] < 0][$cur_pt['x'] < 0];

            switch ($q - $pred_q) {
                case -3:
                    ++$w;
                    break;
                case 3:
                    --$w;
                    break;
                case -2:
                    if ($pred_pt['x'] * $cur_pt['y'] >= $pred_pt['y'] * $cur_pt['x']) {
                        ++$w;
                    }
                    break;
                case 2:
                    if (!($pred_pt['x'] * $cur_pt['y'] >= $pred_pt['y'] * $cur_pt['x'])) {
                        --$w;
                    }
                    break;
            }

            $pred_pt = $cur_pt;
            $pred_q = $q;
        }

        return $w != 0;
    }
}
