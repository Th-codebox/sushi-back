<?php


namespace App\Http\Controllers\Common;


use App\Http\Controllers\Controller;
use App\Libraries\DaData;
use App\Models\System\Polygon;
use App\Repositories\System\PolygonRepository;
use App\Services\CRM\CRMServiceException;
use App\Services\Geo\PolygonService;
use App\Services\Web\CatalogServiceException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DaDataController extends Controller
{

    public DaData $daData;

    public function __construct(DaData $daData)
    {
        $this->daData = $daData;
    }


    /**
     * @return JsonResponse
     */
    public function getSuggestName(): JsonResponse
    {
        try {
            $suggestNames = $this->daData->getSuggestName(request()->get('name'), request()->get('gender'), request()->get('count'));

            return $this->responseSuccess(['suggests' => $suggestNames]);
        } catch (\Throwable $exception) {

            return $this->responseError('Введите имя');
        }

    }

    /**
     * @return JsonResponse
     */
    public function getSuggestAddress(): JsonResponse
    {
        try {
            $suggestAddress = $this->daData->getSuggestAddress(request()->get('address'), request()->get('field'));

            return $this->responseSuccess(['suggests' => $suggestAddress]);
        } catch (\Throwable $exception) {

            return $this->responseError('Введите корректный адрес');
        }
    }

    /**
     * @param $string
     * @return JsonResponse
     * @throws CRMServiceException
     * @throws CatalogServiceException
     */
    public function checkAddressIsDeliveryByString()
    {
        $geoInfo = $this->daData->getInfoByAddressString(request()->get('address'));

        if (!in_array($geoInfo->qc_geo, [0, 1])) {

            Log::channel('dadata')->debug('Incorrect qc_geo', [
                'request' => request()->all(),
                'qc_geo' => $geoInfo->qc_geo
            ]);

            return $this->responseSuccess(['status' => true, 'data' => [], 'message' => 'Адрес определен некоректно']);
            //throw new CatalogServiceException('Возможность доставки по данному адресу уточнит менеджер по телефону после оформления заказа');
        }

        $polygon = (new PolygonService(new PolygonRepository()))->checkAndGetAvailablePolygonsByPoint($geoInfo->geo_lat, $geoInfo->geo_lon);

        if ($polygon->isEmpty()) {

            Log::channel('dadata')->debug('Empty polygon', [
                'request' => request()->all(),
                'qc_geo' => $geoInfo->qc_geo,
                'coord' => [$geoInfo->geo_lat, $geoInfo->geo_lon]
            ]);

            return $this->responseSuccess(['status' => true, 'data' => [], 'message' => 'По данному адресу доставка не возможна']);
            //return $this->responseError('По данному адресу доставка не возможна');
        }
        $data = [];
        if ($availablePolygon = $polygon->sortBy('price')->first()) {
            /**
             * @var Polygon $availablePolygon
             */
            $data['timeInDelivery'] = $availablePolygon->time ? Carbon::createFromFormat('H:i:s', $availablePolygon->time)->secondsSinceMidnight() : null;
            $data['freeDelivery'] = $availablePolygon->free_from_price / 100;
            $data['deliveryPrice'] = $availablePolygon->price;
            $data['color'] = $availablePolygon->color;
            $data['type'] = $availablePolygon->type;
        }
        return $this->responseSuccess(['status' => true, 'data' => $data]);
    }
}
