<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KasirTest extends TestCase
{
    use RefreshDatabase;

    private function createKasir(): User
    {
        return User::create([
            'username' => 'kasir01',
            'password' => Hash::make('password123'),
            'role'     => 'kasir',
        ]);
    }

    private function createPendingOrder(): Order
    {
        return Order::create([
            'order_number'  => 'ORD-20260331-001',
            'customer_name' => 'Budi',
            'no_whatsapp'   => '08123456789',
            'total_amount'  => 30000,
            'status'        => 'pending_verification',
        ]);
    }

    // ── Auth ────────────────────────────────────────────────────

    public function test_kasir_login_page_is_accessible(): void
    {
        $response = $this->get('/kasir/login');
        $response->assertStatus(200);
    }

    public function test_kasir_can_login_with_valid_credentials(): void
    {
        $this->createKasir();

        $response = $this->post('/kasir/login', [
            'username' => 'kasir01',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_kasir_cannot_login_with_wrong_password(): void
    {
        $this->createKasir();

        $response = $this->post('/kasir/login', [
            'username' => 'kasir01',
            'password' => 'salah',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/kasir/dashboard');
        $response->assertRedirect('/kasir/login');
    }

    public function test_kasir_can_access_dashboard(): void
    {
        $kasir = $this->createKasir();

        $response = $this->actingAs($kasir)->get('/kasir/dashboard');
        $response->assertStatus(200);
    }

    // ── Order Management ────────────────────────────────────────

    public function test_kasir_can_approve_order(): void
    {
        $kasir = $this->createKasir();
        $order = $this->createPendingOrder();

        $response = $this->actingAs($kasir)
            ->postJson("/kasir/orders/{$order->id}/approve");

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'processing',
        ]);
    }

    public function test_kasir_can_reject_order(): void
    {
        $kasir = $this->createKasir();
        $order = $this->createPendingOrder();

        $response = $this->actingAs($kasir)
            ->postJson("/kasir/orders/{$order->id}/reject", [
                'reason'      => 'Ditolak kasir',
                'allow_retry' => false,
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_kasir_can_update_status_to_ready(): void
    {
        $kasir = $this->createKasir();
        $order = $this->createPendingOrder();
        $order->update(['status' => 'processing']);

        $response = $this->actingAs($kasir)
            ->postJson("/kasir/orders/{$order->id}/update-status", [
                'status' => 'ready',
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'ready',
        ]);
    }

    public function test_kasir_can_update_status_to_completed(): void
    {
        $kasir = $this->createKasir();
        $order = $this->createPendingOrder();
        $order->update(['status' => 'ready']);

        $response = $this->actingAs($kasir)
            ->postJson("/kasir/orders/{$order->id}/update-status", [
                'status' => 'completed',
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => 'completed',
        ]);
    }

    public function test_kasir_poll_returns_correct_structure(): void
    {
        $kasir = $this->createKasir();
        $this->createPendingOrder();

        $response = $this->actingAs($kasir)
            ->getJson('/kasir/dashboard/poll');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'pending_review'    => [['id', 'order_number', 'customer_name', 'no_whatsapp', 'total_amount']],
                     'processing_orders',
                     'ready_orders',
                 ]);
    }

    public function test_invalid_status_update_is_rejected(): void
    {
        $kasir = $this->createKasir();
        $order = $this->createPendingOrder();

        $response = $this->actingAs($kasir)
            ->postJson("/kasir/orders/{$order->id}/update-status", [
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(422);
    }
}
