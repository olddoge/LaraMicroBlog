<?php

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 只为前 3 个用户生成微博数据
        $user_ids = ['1', '2', '3'];
        $faker    = app(Faker\Generator::class);

        $statuses = factory(Status::class)
            ->times(100)
            ->make()
            ->each(function ($status) use ($faker, $user_ids) {
                $status->user_id = $faker->randomElement($user_ids);
            });

        Status::insert($statuses->toArray());
    }
}
