<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('products')->truncate();
        DB::table('tables')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('tables')->insert([
            ['table_number' => '01', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '02', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '03', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '04', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '05', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '06', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '07', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '08', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('products')->insert([
            ['name' => 'Kopi Susu Brader',      'category' => 'coffee',    'price' => 28000, 'description' => 'Espresso double shot + susu fresh milk.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Es Americano',           'category' => 'coffee',    'price' => 22000, 'description' => 'Shot espresso + air dingin + es batu.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cappuccino',             'category' => 'coffee',    'price' => 28000, 'description' => 'Espresso + steamed milk + milk foam.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Caramel Latte',          'category' => 'coffee',    'price' => 32000, 'description' => 'Espresso + susu + caramel sauce.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Matcha Latte',           'category' => 'noncoffee', 'price' => 32000, 'description' => 'Matcha premium grade A + susu oat.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Coklat Panas',           'category' => 'noncoffee', 'price' => 25000, 'description' => 'Belgian chocolate + susu full cream.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thai Tea',               'category' => 'noncoffee', 'price' => 22000, 'description' => 'Teh thai asli dengan susu kental manis.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Roti Bakar Nutella',     'category' => 'food',      'price' => 25000, 'description' => 'Roti sourdough dibakar + Nutella + pisang.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nasi Goreng Kampung',    'category' => 'food',      'price' => 35000, 'description' => 'Nasi goreng khas dengan aroma bawang merah goreng.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Croissant Mentega',      'category' => 'snack',     'price' => 18000, 'description' => 'Croissant butter import, flaky crispy.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keripik Singkong Balado','category' => 'snack',     'price' => 12000, 'description' => 'Singkong lokal, bumbu balado level sedang.', 'is_ready' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}