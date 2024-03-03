<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Log;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class OrderController extends ApiBaseController
{
    private $productService;
    private $orderService;
    public $log;

    public function __construct(ProductService $productService, OrderService $orderService, Log $log)
    {
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->log = $log;
        parent::__construct($this->log);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        try {
            return response([
                'orders' => Order::all()
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): Response
    {
        try {
            if (!$this->productService->verifyIfProductsIsInStock($request->products)) {

                return response([
                    'message' => 'Product out of stock'
                ], Response::HTTP_BAD_REQUEST);
            }

            $this->productService->removeProductsFromStock($request->products);
            $order = $this->orderService->createOrder($request->all());
            $this->orderService->attachProductsToOrder($order->id, $request->products);

            return response([
                'order' => $order
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Response
    {
        try {
            return response([
                'order' => $order
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): Response
    {
        try {
            $newProducts = $request->products;
            $oldProducts = new Collection(json_decode($order->products));

            if (!$this->productService
                ->verifyIfProductsIsInStockBeforeUpdate($newProducts, $oldProducts)) {

                return response([
                    'message' => 'Product out of stock'
                ], Response::HTTP_BAD_REQUEST);
            }

            $order->update($request->validated());
            $this->productService->returnProductsToStockAfterUpdate($newProducts, $oldProducts);
            $this->orderService->attachProductsToOrder($order->id, $request->products);

            return response([
                'order' => $order
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): Response
    {
        try {
            if (!$order) {
                return response([
                    'message' => 'Order not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $order->products()->detach();
            $order->delete();

            $products = new Collection(json_decode($order->products));
            $this->productService->returnProductsToStock($products);

            return response([
                'message' => 'Order deleted'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}
