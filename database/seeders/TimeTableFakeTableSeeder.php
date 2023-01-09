<?php

namespace Database\Seeders;

use App\Repositories\Courier\CourierOrderRepository;
use App\Repositories\System\WorkScheduleRepository;
use Illuminate\Database\Seeder;

class TimeTableFakeTableSeeder extends Seeder
{
    /**
     * @throws \App\Repositories\RepositoryException
     * @throws \Throwable
     */
    public function run()
    {
        (new WorkScheduleRepository())->test();
    }
}
