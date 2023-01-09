<?php

namespace Database\Seeders;

use App\Services\CRM\Store\SettingService;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        SettingService::add([
            'name'  => 'Количество ячеек поваров',
            'key'   => 'kitchenCellCount',
            'value' => '10',
        ]);
        SettingService::add([
            'name'  => 'Количество ячеек курьеров',
            'key'   => 'courierCellCount',
            'value' => '15',
        ]);
        SettingService::add([
            'name'  => 'Сумма для обязательной предоплаты',
            'key'   => 'totalForPreBay',
            'value' => '5000',
        ]);
        SettingService::add([
            'name'  => 'Минимальная сумма заказа',
            'key'   => 'totalForOrder',
            'value' => '500',
        ]);
        SettingService::add([
            'name'  => 'Блюдо для промоакции "пригласительный подарок"',
            'key'   => 'saleWelcomeItem',
            'value' => ['saleModificationMenuItemId' => null, "saleMenuItemId" => null],
        ]);
        SettingService::add([
            'name'  => 'Колличество дней действия промокода дня рождение',
            'key'   => 'countDayActivePromoCodeBirthday',
            'value' => ["after" => 2,"before" => 2],
        ]);
        SettingService::add([
            'name'  => 'Колличество дней действия промокода после дня рождение',
            'key'   => 'countDayActivePromoCodeAfterBirthday',
            'value' => 2,
        ]);
        SettingService::add([
            'name'  => 'Название промокода "пригласительный подарок"',
            'key'   => 'namePromoActionInvite',
            'value' => "пригласительный подарок",
        ]);
        SettingService::add([
            'name'  => 'Email(ы) для уведомлений',
            'key'   => 'emailsForNotifications',
            'value' => ['mikhailbelyakov3533@gmail.com'],
        ]);
    }
}
