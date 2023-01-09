<?php


namespace App\Libraries\Helpers;

use App\Services\CRM\Store\SettingService;

class SettingHelper
{

    /**
     * @param $key
     * @param $filialId
     * @return mixed|null
     */
    public static function getSettingValue($key, ?int $filialId = null)
    {
        $conditions = [
            'key'      => $key,
            'filialId' => $filialId,
        ];

        try {
            $settingService = SettingService::findOne($conditions);

            return $settingService->getRepository()->formatSetting($conditions['filialId'])['value'];
        } catch (\Throwable $e) {
            return null;
        }

    }


}
