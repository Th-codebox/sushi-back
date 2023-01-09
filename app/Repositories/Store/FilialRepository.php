<?php

namespace App\Repositories\Store;

use App\Models\Store\Filial as FilialModel;
use App\Models\Store\Setting;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryException;
use App\Services\CRM\Store\SettingService;

/**
 * Class FilialRepository
 * @package App\Repositories\Store
 * @method FilialModel getModel()
 */
class FilialRepository extends BaseRepository
{

    protected array $relations = ['settings'];

    /**
     * UserRepository constructor.
     * @param FilialModel|null $model
     */
    public function __construct(FilialModel $model = null)
    {
        if ($model === null) {
            $model = new FilialModel();
        }
        parent::__construct($model);
    }

    /**
     * @param array $data
     */
    protected function afterModification(array $data = []): void
    {
        if (array_key_exists('settings', $data) && is_array($data['settings'])) {
            $this->saveSettings($data['settings']);
        }
    }

    /**
     * @param array $settings
     */
    protected function saveSettings(array $settings): void
    {

        $settings = array_filter($settings, static fn($v) => (is_array($v) && array_key_exists('settingId', $v) && is_numeric($v['settingId']) && $v['settingId']));

        foreach ($settings as $setting) {
            try {
                $settingEntry = $this->getModel()->settings()->where('setting_id', '=', $setting['settingId'])->first();

                if ($settingEntry === null) {
                    $settingEntry = $this->getModel()->settings()->newModelInstance([
                        'filial_id'                 => $this->getModel()->id,
                        'setting_id' => $setting['settingId'],
                    ]);
                }

                if (array_key_exists('value', $setting) && is_array($setting['value'])) {
                    $setting['json'] = true;
                    $setting['value'] = json_encode($setting['value']);
                }

                if (array_key_exists('value', $setting) && !is_array($setting['value']) && $setting['value'] === false) {
                    $setting['value'] = null;
                }

                $settingEntry->fill($setting);
                $settingEntry->save();
            } catch (\Throwable $e) {
                throw new RepositoryException('Невалидные ID дефолтных настроек, проверьте данные');
            }

        }
    }


    /**
     * @return \Illuminate\Support\Collection
     * @throws RepositoryException
     * @throws \ReflectionException
     */
    public function getSettings()
    {

        $settingRepos = (new SettingRepository)->findList();


        $filialSettings = $this->getModel()->settings->toArray();

        foreach ($settingRepos as &$settingRepo) {

            /**
             * @var SettingRepository $settingRepo
             */

            foreach ($filialSettings as $filialSetting) {
                if ((int)$filialSetting['setting_id'] === $settingRepo->getModel()->id) {
                    $settingRepo->getModel()->value = $filialSetting['value'];
                    $settingRepo->getModel()->json = $filialSetting['json'];
                }
            }
        }

        return (new SettingRepository)->getModelCollections($settingRepos);

    }
}
