<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_can_list_products()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'products'
            ]);
    }

    public function test_can_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'product' => $product->toArray()
            ]);
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();
        $updatedData = [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'price' => 100.00,
            'stock' => 10,
            'is_active' => false
        ];

        $response = $this->putJson('/api/products/' . $product->id, $updatedData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'product' => $updatedData
            ]);

        $this->assertDatabaseHas('products', $updatedData);
    }
}
