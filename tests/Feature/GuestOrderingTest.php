<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Room;
use App\Services\Orders\CreateOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GuestOrderingTest extends TestCase
{
    use RefreshDatabase;

    private function makeRoom(string $number = '101'): Room
    {
        return Room::query()->create([
            'room_number' => $number,
            'qr_token' => Str::random(64),
            'is_active' => true,
        ]);
    }

    private function makeProduct(float $price = 10000): Product
    {
        $category = Category::query()->create([
            'name' => 'Kopi',
            'description' => 'Espresso based',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Kopi Susu',
            'description' => 'Kopi susu gula aren',
            'price' => $price,
            'is_available' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_landing_page_has_public_room_qr_section(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="kamar-qr"', false)
            ->assertSee('Dari kamar, langsung');
    }

    public function test_valid_room_qr_opens_guest_menu_without_login(): void
    {
        $room = $this->makeRoom();
        $this->makeProduct();

        $this->get(route('guest.order.show', $room->qr_token))
            ->assertOk()
            ->assertSee('Kamar 101')
            ->assertSee('Mau pesan')
            ->assertSee('Kopi Susu');

        $this->assertGuest();
    }

    public function test_invalid_room_qr_shows_friendly_page(): void
    {
        $this->get('/order/'.Str::random(64))
            ->assertNotFound()
            ->assertSee('QR kamar tidak dikenali')
            ->assertSee('Buka Menu dengan Nomor Kamar');
    }

    public function test_room_number_entry_opens_public_menu(): void
    {
        $room = $this->makeRoom();
        $this->makeProduct();

        $this->post(route('guest.order.open'), ['room_number' => '101'])
            ->assertRedirect(route('guest.order.menu'));

        $this->get(route('guest.order.menu'))
            ->assertOk()
            ->assertSee('Kamar 101')
            ->assertSee('Kopi Susu');

        $this->assertGuest();
        $this->assertSame($room->id, session('guest_room_id'));
    }

    public function test_room_number_entry_rejects_unknown_room(): void
    {
        $this->from(route('guest.order.entry'))
            ->post(route('guest.order.open'), ['room_number' => '999'])
            ->assertRedirect(route('guest.order.entry'))
            ->assertSessionHasErrors('room_number');
    }

    public function test_order_status_is_only_accessible_with_public_token(): void
    {
        $room = $this->makeRoom();
        $order = Order::query()->create([
            'order_number' => 'NGF-TEST-0001',
            'public_token' => Str::random(64),
            'room_id' => $room->id,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'subtotal' => 10000,
            'total' => 10000,
            'placed_at' => now(),
        ]);

        $this->get(route('guest.order.status', $order->public_token))
            ->assertOk()
            ->assertSee('NGF-TEST-0001')
            ->assertSee('Kamar 101');

        $this->get('/pesanan/'.Str::random(64))->assertNotFound();
    }

    public function test_cart_component_adds_items_and_redirects_to_confirmation(): void
    {
        $room = $this->makeRoom();
        $product = $this->makeProduct(15000);

        $this->withSession([
            'guest_room_id' => $room->id,
            'guest_room_token' => $room->qr_token,
        ]);

        \Livewire\Livewire::test('guest.cart')
            ->call('add', $product->id, 2, 'extra shot')
            ->assertDispatched('cart-updated', count: 2)
            ->assertSet('isOpen', false)
            ->call('checkout')
            ->assertRedirectContains('/order/confirmation/');

        $order = Order::query()->latest('id')->first();

        $this->assertNotNull($order);
        $this->assertSame($room->id, $order->room_id);
        $this->assertSame('extra shot', $order->items()->first()->notes);
        $this->assertSame(2, $order->items()->first()->quantity);
    }

    public function test_checkout_is_idempotent_and_keeps_item_notes(): void
    {
        $room = $this->makeRoom();
        $product = $this->makeProduct(10000);
        $service = app(CreateOrderService::class);
        $key = (string) Str::uuid();

        $cart = [[
            'product_id' => $product->id,
            'quantity' => 2,
            'notes' => 'tanpa gula',
        ]];

        $first = $service->handle($room->id, $cart, 'cash', $key);
        $second = $service->handle($room->id, $cart, 'cash', $key);

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, Order::query()->count());
        $this->assertSame('tanpa gula', $first->items()->first()->notes);
        $this->assertSame('20000.00', (string) $first->total);
    }

    public function test_cart_quantity_is_validated_by_server_and_rolls_back_unavailable_menu(): void
    {
        $room = $this->makeRoom();
        $product = $this->makeProduct(10000);

        $this->withSession([
            'guest_room_id' => $room->id,
            'guest_room_token' => $room->qr_token,
        ]);

        $component = \Livewire\Livewire::test('guest.cart')
            ->call('add', $product->id, 2)
            ->call('setQuantity', $product->id, 5)
            ->assertSet("items.{$product->id}.quantity", 5)
            ->assertDispatched('cart-updated', count: 5);

        $product->update(['is_available' => false]);

        $component
            ->call('setQuantity', $product->id, 8)
            ->assertHasErrors('cart')
            ->assertDispatched('cart-rejected', productId: $product->id)
            ->assertSet("items.{$product->id}.quantity", 5)
            ->call('setQuantity', $product->id, 0)
            ->assertSet('items', []);
    }
}
