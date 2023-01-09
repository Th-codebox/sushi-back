<?php

namespace Database\Seeders;

use App\Enums\PromoCodeAction;
use App\Enums\PromoCodeType;
use App\Services\CRM\Store\PromoCodeService;
use Illuminate\Database\Seeder;

class PromoCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PromoCodeService::add([
            'name'         => 'С днём рождения!',
            'code'         => 'BIRTHDAY',
            'type'         => PromoCodeType::Personal,
            'action'       => PromoCodeAction::BirthDay,
            'sale_percent' => 30,
            'status'       => true,
        ]);

        PromoCodeService::add([
            'name'         => 'Скидка за друга',
            'code'         => 'FRIEND_SALE',
            'type'         => PromoCodeType::Personal,
            'action'       => PromoCodeAction::FriendPercent,
            'sale_percent' => 20,
            'status'       => true,
        ]);


        PromoCodeService::add([
            'name'   => 'Удвоение заказа',
            'code'   => 'DOUBLE',
            'type'   => PromoCodeType::All,
            'action' => PromoCodeAction::Doubling(),
            'status' => true,
        ]);

        PromoCodeService::add([
            'name'   => 'Скидка 300 рублей',
            'code'   => '300SALE',
            'saleSubtraction'   => '300',
            'type'   => PromoCodeType::All,
            'action' => PromoCodeAction::Subtract(),
            'status' => true,
        ]);


    }
}
