<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = ProductFactory::new()->create();
        return [
            'order_status_id' => 1,
            'total_price' => $order->price * 5,
            'total_amount' => 5,
            'products' => json_encode([
                [
                    'id' => $order->id,
                    'quantity' => 5,
                ],
            ]),

        ];
    }
}
