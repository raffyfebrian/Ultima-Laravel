<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\InventoryBatch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            RoomSeeder::class,
            SuperAdminSeeder::class,
        ]);

        $this->seedStaff();
        $this->seedMenu();
        $this->seedIngredients();
        $this->seedOrders();
    }

    private function seedStaff(): void
    {
        $staff = [
            ['name' => 'Kasir Ngoffe', 'email' => 'kasir@ngoffe.id', 'role' => 'cashier'],
            ['name' => 'Chef Ngoffe', 'email' => 'dapur@ngoffe.id', 'role' => 'kitchen'],
            ['name' => 'Admin Operasional', 'email' => 'admin2@ngoffe.id', 'role' => 'admin'],
        ];

        foreach ($staff as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => 'cle',
                ],
            );

            $role = Role::firstOrCreate([
                'name' => $data['role'],
                'guard_name' => 'web',
            ]);

            if (! $user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }

    private function seedMenu(): void
    {
        $categories = [
            ['name' => 'Kopi', 'description' => 'Espresso based & manual brew', 'sort_order' => 1],
            ['name' => 'Non-Kopi', 'description' => 'Teh, cokelat, dan minuman segar', 'sort_order' => 2],
            ['name' => 'Makanan', 'description' => 'Menu utama dan camilan gurih', 'sort_order' => 3],
            ['name' => 'Dessert', 'description' => 'Pencuci mulut manis', 'sort_order' => 4],
        ];

        $categoryMap = [];

        foreach ($categories as $category) {
            $categoryMap[$category['name']] = Category::firstOrCreate(
                ['name' => $category['name']],
                $category + ['is_active' => true],
            );
        }

        $products = [
            ['Kopi', 'Espresso', 18000, 'Single shot espresso pekat.'],
            ['Kopi', 'Americano', 22000, 'Espresso dengan air panas.'],
            ['Kopi', 'Cappuccino', 28000, 'Espresso, susu, dan foam lembut.'],
            ['Kopi', 'Caffè Latte', 30000, 'Espresso dengan susu steamed.'],
            ['Kopi', 'Mocha', 32000, 'Kopi cokelat dengan whipped cream.'],
            ['Kopi', 'V60 Manual Brew', 35000, 'Seduhan manual single origin.'],
            ['Non-Kopi', 'Matcha Latte', 32000, 'Matcha premium dengan susu.'],
            ['Non-Kopi', 'Chocolate', 28000, 'Cokelat hangat creamy.'],
            ['Non-Kopi', 'Lychee Tea', 25000, 'Teh leci dingin menyegarkan.'],
            ['Non-Kopi', 'Lemon Squash', 24000, 'Perasan lemon dengan soda.'],
            ['Makanan', 'Nasi Goreng Ngoffe', 45000, 'Nasi goreng spesial dengan telur.'],
            ['Makanan', 'Chicken Rice Bowl', 42000, 'Ayam panggang dengan saus spesial.'],
            ['Makanan', 'Beef Burger', 55000, 'Patty sapi dengan keju dan sayur.'],
            ['Makanan', 'French Fries', 25000, 'Kentang goreng renyah.'],
            ['Makanan', 'Club Sandwich', 38000, 'Sandwich ayam dengan telur.'],
            ['Dessert', 'Tiramisu', 35000, 'Tiramisu klasik dengan kopi.'],
            ['Dessert', 'Croissant', 22000, 'Croissant butter panggang.'],
            ['Dessert', 'Cheesecake', 38000, 'Cheesecake lembut dengan berry.'],
        ];

        foreach ($products as $index => [$category, $name, $price, $description]) {
            Product::firstOrCreate(
                ['name' => $name],
                [
                    'category_id' => $categoryMap[$category]->id,
                    'description' => $description,
                    'price' => $price,
                    'is_available' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }

    private function seedIngredients(): void
    {
        $ingredients = [
            ['name' => 'Biji Kopi Arabika', 'unit' => 'kg', 'description' => 'Biji kopi single origin untuk espresso.', 'stock' => 25.5, 'expires' => 90],
            ['name' => 'Susu UHT Full Cream', 'unit' => 'liter', 'description' => 'Susu untuk latte dan cappuccino.', 'stock' => 40, 'expires' => 14],
            ['name' => 'Gula Aren Cair', 'unit' => 'liter', 'description' => 'Pemanis khas untuk kopi susu.', 'stock' => 12, 'expires' => 60],
            ['name' => 'Bubuk Matcha', 'unit' => 'gram', 'description' => 'Matcha premium grade.', 'stock' => 2500, 'expires' => 120],
            ['name' => 'Cokelat Bubuk', 'unit' => 'gram', 'description' => 'Bubuk cokelat untuk mocha dan chocolate.', 'stock' => 3000, 'expires' => 120],
            ['name' => 'Beras Premium', 'unit' => 'kg', 'description' => 'Beras untuk menu nasi goreng.', 'stock' => 50, 'expires' => 180],
            ['name' => 'Ayam Fillet', 'unit' => 'kg', 'description' => 'Fillet ayam segar untuk rice bowl.', 'stock' => 18, 'expires' => 3],
            ['name' => 'Keju Cheddar', 'unit' => 'kg', 'description' => 'Keju untuk burger dan sandwich.', 'stock' => 8, 'expires' => 30],
            ['name' => 'Tepung Terigu', 'unit' => 'kg', 'description' => 'Tepung serbaguna untuk pastry.', 'stock' => 30, 'expires' => 150],
            ['name' => 'Kentang Beku', 'unit' => 'kg', 'description' => 'Kentang potong beku untuk french fries.', 'stock' => 22, 'expires' => 90],
        ];

        foreach ($ingredients as $index => $data) {
            $ingredient = Ingredient::firstOrCreate(
                ['name' => $data['name']],
                [
                    'unit' => $data['unit'],
                    'description' => $data['description'],
                    'is_active' => true,
                ],
            );

            if ($ingredient->batches()->exists()) {
                continue;
            }

            InventoryBatch::create([
                'ingredient_id' => $ingredient->id,
                'batch_number' => 'BATCH-'.now()->format('Ym').'-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'initial_quantity' => $data['stock'],
                'remaining_quantity' => $data['stock'],
                'received_at' => now()->subDays(2)->toDateString(),
                'expires_at' => now()->addDays($data['expires'])->toDateString(),
            ]);
        }
    }

    private function seedOrders(): void
    {
        $rooms = Room::orderBy('room_number')->get();

        if ($rooms->isEmpty()) {
            return;
        }

        $products = Product::orderBy('id')->get();

        if ($products->isEmpty()) {
            return;
        }

        $scenarios = [
            ['pending', 'unpaid', null],
            ['confirmed', 'unpaid', null],
            ['preparing', 'paid', 'qris'],
            ['ready', 'paid', 'cash'],
            ['completed', 'paid', 'qris'],
            ['completed', 'paid', 'transfer'],
            ['completed', 'paid', 'cash'],
            ['completed', 'paid', 'qris'],
            ['cancelled', 'refunded', 'qris'],
        ];

        for ($i = 0; $i < 32; $i++) {
            $daysAgo = $i < 10 ? 0 : (($i - 10) % 7) + 1;
            $date = now()->subDays($daysAgo);

            // Pesanan hari ini dibuat di masa lalu (beberapa menit/jam lalu),
            // bukan di jam yang lebih besar dari waktu sekarang.
            $placedAt = $daysAgo === 0
                ? now()->subMinutes(10 + (($i * 17) % 300))
                : $date->copy()->setTime(8 + (($i * 3) % 12), ($i * 11) % 60);

            $number = 'NG-'.$date->format('Ymd').'-'.str_pad((string) (100 + $i), 3, '0', STR_PAD_LEFT);

            [$status, $paymentStatus, $method] = $scenarios[$i % count($scenarios)];

            // Pesanan lama hanya boleh berstatus selesai/dibatalkan agar antrian dapur
            // tidak menampilkan pesanan berhari-hari yang masih "ready" atau "preparing".
            if ($daysAgo > 0) {
                $status = $i % 11 === 0 ? 'cancelled' : 'completed';
                $paymentStatus = $status === 'cancelled' ? 'refunded' : 'paid';
                $method = $method ?: 'qris';
            }

            $itemCount = 1 + ($i % 3);
            $chosen = [];

            for ($j = 0; $j < $itemCount; $j++) {
                $chosen[] = [
                    'product' => $products[($i * 3 + $j) % $products->count()],
                    'quantity' => 1 + (($i + $j) % 3),
                ];
            }

            $subtotal = collect($chosen)->sum(
                fn (array $item) => (float) $item['product']->price * $item['quantity'],
            );

            $order = Order::firstOrCreate(
                ['order_number' => $number],
                [
                    'room_id' => $rooms[$i % $rooms->count()]->id,
                    'order_status' => $status,
                    'payment_status' => $paymentStatus,
                    'payment_method' => $method,
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                    'placed_at' => $placedAt,
                    'payment_confirmed_at' => $paymentStatus === 'paid'
                        ? $placedAt->copy()->addMinutes(6)
                        : null,
                ],
            );

            if (! $order->wasRecentlyCreated) {
                continue;
            }

            foreach ($chosen as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'unit_price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => (float) $item['product']->price * $item['quantity'],
                ]);
            }
        }
    }
}
