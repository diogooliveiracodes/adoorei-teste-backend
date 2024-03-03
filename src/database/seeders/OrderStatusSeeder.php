<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Order::STATUSES as $key => $value) {
            OrderStatus::create([
                'id' => $value,
                'name' => $key,
            ]);
        }
    }
}
