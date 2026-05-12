<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function createMenu(): Menu
    {
        $category = Category::create([
            'name' => 'Test Category',
            'display_order' => 1,
            'status' => 'active',
        ]);

        return Menu::create([
            'category_id'  => $category->id,
            'name'         => 'Kopi Test',
            'price'        => 15000,
            'is_available' => true,
            'stock'        => 10,
        ]);
    }

    // base64 PNG 1x1 pixel merah — dummy proof image untuk test
    private function dummyProof(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwADhQGAWjR9awAAAABJRU5ErkJggg==';
    }

    // ── Customer Order ──────────────────────────────────────────

    public function test_customer_can_view_order_page(): void
    {
        $response = $this->get('/order');
        $response->assertStatus(200);
    }

    public function test_customer_can_create_order(): void
    {
        $menu = $this->createMenu();

        $response = $this->postJson('/orders', [
            'customer_name' => 'Budi',
            'no_whatsapp'   => '08123456789',
            'notes'         => '',
            'proof_image'   => $this->dummyProof(),
            'items'         => [
                ['menu_id' => $menu->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi',
            'no_whatsapp'   => '08123456789',
            'status'        => 'pending_verification',
            'total_amount'  => 30000,
        ]);
    }

    public function test_order_requires_name(): void
    {
        $menu = $this->createMenu();

        $response = $this->postJson('/orders', [
            'customer_name' => '',
            'no_whatsapp'   => '08123456789',
            'proof_image'   => $this->dummyProof(),
            'items'         => [['menu_id' => $menu->id, 'quantity' => 1]],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['customer_name']);
    }

    public function test_order_requires_whatsapp(): void
    {
        $menu = $this->createMenu();

        $response = $this->postJson('/orders', [
            'customer_name' => 'Budi',
            'no_whatsapp'   => '',
            'proof_image'   => $this->dummyProof(),
            'items'         => [['menu_id' => $menu->id, 'quantity' => 1]],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['no_whatsapp']);
    }

    public function test_order_requires_at_least_one_item(): void
    {
        $response = $this->postJson('/orders', [
            'customer_name' => 'Budi',
            'no_whatsapp'   => '08123456789',
            'proof_image'   => $this->dummyProof(),
            'items'         => [],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['items']);
    }

    public function test_customer_can_view_order_status(): void
    {
        $menu  = $this->createMenu();
        $order = Order::create([
            'order_number'  => 'ORD-20260331-001',
            'customer_name' => 'Budi',
            'no_whatsapp'   => '08123456789',
            'total_amount'  => 15000,
            'status'        => 'pending_verification',
        ]);

        $response = $this->getJson('/orders/ORD-20260331-001');

        $response->assertStatus(200)
                 ->assertJsonFragment(['order_number' => 'ORD-20260331-001'])
                 ->assertJsonFragment(['status' => 'pending_verification']);
    }

    public function test_order_number_is_unique_and_sequential(): void
    {
        $menu = $this->createMenu();

        $this->postJson('/orders', [
            'customer_name' => 'Budi',
            'no_whatsapp'   => '08111',
            'proof_image'   => $this->dummyProof(),
            'items'         => [['menu_id' => $menu->id, 'quantity' => 1]],
        ]);

        $this->postJson('/orders', [
            'customer_name' => 'Ani',
            'no_whatsapp'   => '08222',
            'proof_image'   => $this->dummyProof(),
            'items'         => [['menu_id' => $menu->id, 'quantity' => 1]],
        ]);

        $orders = Order::orderBy('id')->get();
        $this->assertCount(2, $orders);
        $this->assertNotEquals($orders[0]->order_number, $orders[1]->order_number);
    }
}
