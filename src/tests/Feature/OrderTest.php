<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_if_can_list_orders()
    {
        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'orders' => [],
            ]);
    }

    public function test_if_can_create_an_order()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test Product Description',
            'price' => 310.00,
            'stock' => 10,
            'is_active' => true,
            'published_at' => now(),
        ]);
        $orderData = [
            'order_status_id' => Order::STATUSES['pending'],
            'total_price' => $product->price * 5,
            'total_amount' => 5,
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'order' => [
                    'order_status_id',
                    'total_price',
                    'total_amount',
                    'products' => [
                        '*' => [
                            'id',
                            'quantity',
                        ],
                    ],
                ],
            ]);
    }

    public function test_if_can_show_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->getJson('/api/orders/' . $order->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'order' => [
                    'order_status_id',
                    'total_price',
                    'total_amount',
                    'products' => [
                        '*' => [
                            'id',
                            'quantity',
                        ],
                    ],
                ],
            ]);
    }
}
