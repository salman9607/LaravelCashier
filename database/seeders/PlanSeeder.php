<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrData = [
            ['name' => 'Bronze Plan', 'price' => 999, 'stripe_plan_id' => ""],
            ['name' => 'Sliver Plan', 'price' => 1999, 'stripe_plan_id' => ""],
            ['name' => 'Golden Plan', 'price' => 2999, 'stripe_plan_id' => ""],
        ];

        foreach ($arrData as $row)
        {
            Plan::create($row);
        }
    }
}
