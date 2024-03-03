<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductService
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function verifyIfProductsIsInStock(array $products): bool
    {
        foreach ($products as $product) {
            if (!$this->hasProductInStock($product['id'], $product['quantity'])) {

                return false;
            }
        }

        return true;
    }

    private function hasProductInStock(int $productId, int $quantity): bool
    {
        $product = $this->product->find($productId);

        return $product->stock >= $quantity;
    }

    public function removeProductsFromStock(array $products): void
    {
        foreach ($products as $product) {
            $productModel = $this->product->find($product['id']);
            $productModel->stock -= $product['quantity'];
            $productModel->save();
        }
    }

    public function returnProductsToStock(Collection $products): void
    {
        foreach ($products as $product) {
            $productModel = $this->product->find($product->id);
            $productModel->stock += $product->quantity;
            $productModel->save();
        }
    }

    public function verifyIfProductsIsInStockBeforeUpdate(array $newProducts, Collection $oldProducts): bool
    {
        foreach ($newProducts as $newProduct) {
            $productModel = $this->product->find($newProduct['id']);
            $oldProduct = $oldProducts->firstWhere('id', $newProduct['id']);
            $diference = $newProduct['quantity'] - $oldProduct->quantity;

            if ($productModel->stock < $diference) {
                return false;
            }
        }

        return true;
    }

    public function returnProductsToStockAfterUpdate(array $newProducts, Collection $oldProducts): void
    {
        foreach ($newProducts as $newProduct) {
            $productModel = $this->product->find($newProduct['id']);
            $oldProduct = $oldProducts->firstWhere('id', $newProduct['id']);
            $diference = $newProduct['quantity'] - $oldProduct->quantity;

            if($diference > 0) {
                $productModel->stock -= $diference;
                $productModel->save();
            }

            if($diference < 0) {
                $productModel->stock += abs($diference);
                $productModel->save();
            }
        }
    }
}

