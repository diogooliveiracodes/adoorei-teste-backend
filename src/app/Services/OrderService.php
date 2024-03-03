<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function createOrder(array $data): Order
    {
        $data['products'] = json_encode($data['products']);
        return Order::create($data);
    }

    public function attachProductsToOrder(int $orderId, array $products): void
    {
        $order = $this->order->find($orderId);
        $order->products()->detach();
        foreach($products as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }
    }

}

