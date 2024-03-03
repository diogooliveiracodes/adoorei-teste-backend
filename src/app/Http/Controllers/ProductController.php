<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends ApiBaseController
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        try {
            $products = $this->product->getActiveProducts();

            return response([
                'products' => $products
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return $this->handleError($e);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product): Response
    {
        try {
            return response([
                'product' => $product
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return $this->handleError($e);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): Response
    {
        try {
            $product->update($request->validated());

            return response([
                'product' => $product
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return $this->handleError($e);
        }
    }

}
