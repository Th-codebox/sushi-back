<?php

namespace Database\Factories\Courier;

use App\Enums\CourierOrderPaymentType;
use App\Enums\CourierOrderStatus;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourierOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {


        $points = [[59.87084470325698, 30.34177181191543], [59.898052, 30.391500], [59.89163975916802, 30.484782552421464], [59.89163975916802, 30.484782552421464], [59.891196936756224, 30.299366705799514]];

        $coord = $this->faker->randomElements($points)[0];
        $courier = $this->faker->randomElements([null, null, null, null, 1, 2, 3])[0];

        $date = $this->faker->dateTimeBetween('2021-02-01 12:00:00', '2021-02-05 12:00:00', '+5')->getTimestamp();
        $status = $courier === null ? $this->faker->randomElements([CourierOrderStatus::ReadyForIssue, CourierOrderStatus::Preparing])[0] : $this->faker->randomElements([CourierOrderStatus::InDelivery, CourierOrderStatus::Canceled, CourierOrderStatus::Completed])[0];

        return [
            "order_number" => $this->faker->numberBetween(1000, 9999) . '-' . $this->faker->numberBetween(01, 20),  //
            "price"        => $this->faker->numberBetween(0, 3000),  // сумма/цена заказа
            "address"      => $this->faker->address,
            "latitude"     => $coord[0],
            "longitude"    => $coord[1],
            "comment"      => $this->faker->text(100),
            "client_name"  => $this->faker->name,
            "phone"        => $this->faker->phoneNumber,
            "date"         => $date,  // дата заказа
            "dead_line"    => $date + 7200,
            "start_at"     => !in_array($status, [CourierOrderStatus::ReadyForIssue, CourierOrderStatus::Preparing], true) ? $date + 3000 : null,
            "completed_at" => $status === CourierOrderStatus::Completed ? $date + $this->faker->numberBetween(6500, 8000) : null,
            "status"       => $status,
            "payment_type" => $this->faker->randomElements(CourierOrderPaymentType::getValues())[0],
            "client_money" => $this->faker->numberBetween(0, 3000),
            "user_id"   => $courier,
        ];
    }
}
