<?php


namespace App\Libraries;


use Fomvasss\Dadata\Facades\DadataClean;
use Fomvasss\Dadata\Facades\DadataSuggest;

class DaData
{

    public function getInfoByAddressString(string $address)
    {
        return DadataClean::cleanAddress($address);
    }


    /**
     * @param string $name
     * @param string|null $gender
     * @param int|null $count
     * @return array
     */
    public function getSuggestName(string $name, ?string $gender, ?int $count = 10)
    {
        if (!in_array($gender, ['MALE', 'FEMALE'])) {
            $gender = 'UNKNOWN';
        }

        $suggests = DadataSuggest::suggest("fio", ["query" => $name, 'parts' => 'name', 'gender' => $gender, "count" => $count]);

        $suggests = array_map(function ($suggest) {
            return $suggest['value'] ?? $suggest['name'] ?? '';
        }, $suggests);

        $suggests = array_filter($suggests, function($element) {
            return !empty($element);
        });

        return $suggests;
    }

    /**
     * @param string $address
     * @param string|null $field
     * @return array|array[]
     */
    public function getSuggestAddress(string $address, ?string $field = null)
    {

        $suggests = DadataSuggest::suggest("address", [
            "query" => $address,
            "locations_geo" => [
                [
                    'lat' => '60.01188445553647',
                    'lon' => ' 30.394088190655864',
                    'radius_meters' => '15000'
                ]
            ],
        ]);

        $result = [];

        if (array_key_exists('value', $suggests)) {

            $result[] = [
                'full'    => $suggests['value'],
                'city'    => $this->makeCityString($suggests),
                'street'  => $suggests['data']['street_with_type'] ?? null,
                'house'   => $suggests['data']['house'] ?? null,
                'block'   => $suggests['data']['block'] ?? null,
                'geo_lat' => $suggests['data']['geo_lat'] ?? null,
                'geo_lon' => $suggests['data']['geo_lon'] ?? null,
            ];

        } else {
            $result = array_map(function ($suggest) {

                return [
                    'full'    => $suggest['value'],
                    'city'    => $this->makeCityString($suggest),
                    'street'  => $suggest['data']['street_with_type'] ?? null,
                    'house'   => $suggest['data']['house'] ?? null,
                    'block'   => $suggest['data']['block'] ?? null,
                    'geo_lat' => $suggest['data']['geo_lat'] ?? null,
                    'geo_lon' => $suggest['data']['geo_lon'] ?? null,
                ];
            }, $suggests);
        }

        try {
            if ($field) {
                $arrayFilter = [];
                foreach ($result as $item) {
                    $arrayFilter[$item[$field]] = $item;
                }

                $result = array_values($arrayFilter);
            }
        } catch (\Throwable $e) {

        }


        return $result;
    }

    private function makeCityString(array $suggest): string
    {
        $cityElements = [];

        if ($suggest['data']['city_with_type']) {
            $cityElements[] = $suggest['data']['city_with_type'];
        }

        if ($suggest['data']['settlement_with_type']) {
            $cityElements[] = $suggest['data']['settlement_with_type'];
        }

        return implode(", ",  $cityElements);
    }

}
