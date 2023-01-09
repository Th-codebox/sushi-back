<?php

namespace App\Console\Commands;

use App\Enums\PolygonType;
use App\Services\CRM\CRMServiceException;
use App\Services\CRM\Store\FilialService;
use App\Services\CRM\System\FilialCashBoxService;
use App\Services\CRM\System\PolygonService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Class ClearApp
 * @package App\Console\Commands
 */
class ParsePolygonFile extends Command
{

    protected $signature = 'parse_polygon';

    protected $description = 'parse_polygon';

    /**
     * ClearApp constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws CRMServiceException
     * @throws \App\Repositories\RepositoryException
     * @throws \ReflectionException
     */
    public function handle()
    {

        $polygonsJson = file_get_contents('polygons.json');
        $polygonsArray = json_decode($polygonsJson, true);

        $polygons = $polygonsArray['features'] ?? [];
        $polygonsToSave = [];

        foreach ($polygons as $polygon) {
            if (array_key_exists('geometry', $polygon) && is_array($polygon['geometry']) && array_key_exists('type', $polygon['geometry']) && $polygon['geometry']['type'] === 'Polygon') {
                $polygonToSave = [];

                $coordsArray = $polygon['geometry']['coordinates'][0] ?? [];
                foreach ($coordsArray as $coordinate) {
                    $polygonToSave['cords'][] = [$coordinate[1], $coordinate[0]];
                }

                $polygonToSave['name'] = $polygon['properties']['description'] ?? '';
                $polygonToSave['fill'] = $polygon['properties']['fill'] ?? '';

                $polygonsToSave[] = $polygonToSave;
            }
        }

        //print_r($polygonsToSave); exit;




        $greenInc = 1;
        $yellowInc = 1;
        $redInc = 1;

        foreach ($polygonsToSave as $key => $item) {
            $type = '';
            $color = '';
            $name = '';


            switch ($item['fill']) {
                case '#56db40' :
                    $color = trim($item['fill'], '#');
                    $type = PolygonType::Green;
                    $name = 'Зеленая ' . $greenInc;
                    $greenInc++;
                    break;
                case '#ffd21e' :
                    $color = trim($item['fill'], '#');
                    $type = PolygonType::Yellow;
                    $name = 'Жёлтая ' . $yellowInc;
                    $yellowInc++;
                    break;
                case '#ed4543' :
                    $color = trim($item['fill'], '#');
                    $type = PolygonType::Red;
                    $name = 'Красная ' . $redInc;
                    $redInc++;
                    break;
            }


            //try {

                PolygonService::add([
                    'filialId' => 2,
                    'points'   => $item['cords'],
                    'color'    => $color,
                    'type'     => $type,
                    'name'     => $name,
                ]);
            //} catch (\Throwable $e) {

            //}
        }

    }
}
