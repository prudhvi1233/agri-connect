<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample farmers
        $farmer1 = User::create([
            'name' => 'John Farmer',
            'email' => 'farmer@example.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'phone' => '+1 555-0101',
            'address' => '123 Farm Road, Agriculture Valley',
        ]);

        $farmer2 = User::create([
            'name' => 'Sarah Grower',
            'email' => 'sarah.farm@example.com',
            'password' => Hash::make('password'),
            'role' => 'farmer',
            'phone' => '+1 555-0102',
            'address' => '456 Green Lane, Crop County',
        ]);

        // Create sample buyers
        $buyer1 = User::create([
            'name' => 'Mike Buyer',
            'email' => 'buyer@example.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone' => '+1 555-0201',
            'address' => '789 Market Street, Commerce City',
        ]);

        $buyer2 = User::create([
            'name' => 'Lisa Wholesale',
            'email' => 'lisa.buyer@example.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
            'phone' => '+1 555-0202',
            'address' => '321 Trade Avenue, Business District',
        ]);

        // Create sample listings
        Listing::create([
            'farmer_id' => $farmer1->id,
            'crop_name' => 'Organic Tomatoes',
            'description' => 'Fresh organic tomatoes grown without pesticides. Perfect for restaurants and retailers.',
            'quantity' => 5000,
            'unit' => 'kg',
            'expected_price' => 2.50,
            'harvest_date' => '2026-05-15',
            'status' => 'active',
        ]);

        Listing::create([
            'farmer_id' => $farmer1->id,
            'crop_name' => 'Sweet Corn',
            'description' => 'High-quality sweet corn, freshly harvested. Available in bulk quantities.',
            'quantity' => 10000,
            'unit' => 'kg',
            'expected_price' => 1.80,
            'harvest_date' => '2026-06-01',
            'status' => 'active',
        ]);

        Listing::create([
            'farmer_id' => $farmer2->id,
            'crop_name' => 'Premium Wheat',
            'description' => 'Premium grade wheat suitable for flour production. Excellent quality guaranteed.',
            'quantity' => 20000,
            'unit' => 'kg',
            'expected_price' => 0.85,
            'harvest_date' => '2026-07-10',
            'status' => 'active',
        ]);

        Listing::create([
            'farmer_id' => $farmer2->id,
            'crop_name' => 'Fresh Lettuce',
            'description' => 'Crisp and fresh lettuce, hydroponically grown. Perfect for salad bars and grocery stores.',
            'quantity' => 3000,
            'unit' => 'kg',
            'expected_price' => 3.20,
            'harvest_date' => '2026-05-20',
            'status' => 'active',
        ]);

        Listing::create([
            'farmer_id' => $farmer1->id,
            'crop_name' => 'Red Potatoes',
            'description' => 'Premium red potatoes, ideal for retail and food service. Long shelf life.',
            'quantity' => 15000,
            'unit' => 'kg',
            'expected_price' => 1.20,
            'harvest_date' => '2026-06-15',
            'status' => 'active',
        ]);
    }
}
