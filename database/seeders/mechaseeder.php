<?php

namespace Database\Seeders;

use App\Models\Bengkel;
use App\Models\Mechanic;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\Diagnostic;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class MechaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 Bengkels
        $bengkels = [
            [
                'name' => 'Bengkel Jaya Sejahtera',
                'address' => 'Jl. Merdeka No. 123, Jakarta Selatan',
                'phone' => '021-1234567',
                'email' => 'bengkel.jaya@example.com',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8640,
                'description' => 'Bengkel terpercaya dengan pengalaman 15 tahun',
                'status' => 'aktif',
                'owner_name' => 'Budi Santoso',
                'owner_phone' => '08123456789',
            ],
            [
                'name' => 'Bengkel Mobil Rapi',
                'address' => 'Jl. Gatot Subroto No. 45, Bandung',
                'phone' => '022-7654321',
                'email' => 'bengkel.rapi@example.com',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'latitude' => -6.9271,
                'longitude' => 107.6411,
                'description' => 'Spesialis service mobil modern dan performa tinggi',
                'status' => 'aktif',
                'owner_name' => 'Siti Nurhaliza',
                'owner_phone' => '08234567890',
            ],
            [
                'name' => 'Bengkel Surabaya Motor',
                'address' => 'Jl. Ahmad Yani No. 78, Surabaya',
                'phone' => '031-9876543',
                'email' => 'bengkel.surabaya@example.com',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'latitude' => -7.2575,
                'longitude' => 112.7521,
                'description' => 'Bengkel dengan teknologi diagnostic terkini',
                'status' => 'aktif',
                'owner_name' => 'Ahmad Wijaya',
                'owner_phone' => '08345678901',
            ],
        ];

        foreach ($bengkels as $bengkelData) {
            Bengkel::create($bengkelData);
        }

        // Create Services
        $services = [
            ['name' => 'Oil Change', 'price' => 150000, 'duration_minutes' => 30, 'service_type' => 'perawatan', 'description' => 'Penggantian oli mesin'],
            ['name' => 'Tune Up', 'price' => 250000, 'duration_minutes' => 60, 'service_type' => 'perawatan', 'description' => 'Perawatan berkala'],
            ['name' => 'Wheel Alignment', 'price' => 300000, 'duration_minutes' => 45, 'service_type' => 'perbaikan', 'description' => 'Setel roda'],
            ['name' => 'Brake Service', 'price' => 350000, 'duration_minutes' => 90, 'service_type' => 'perbaikan', 'description' => 'Service rem'],
            ['name' => 'AC Service', 'price' => 200000, 'duration_minutes' => 120, 'service_type' => 'perawatan', 'description' => 'Service AC mobil'],
            ['name' => 'Battery Replacement', 'price' => 450000, 'duration_minutes' => 30, 'service_type' => 'penggantian', 'description' => 'Penggantian baterai'],
            ['name' => 'Tire Replacement', 'price' => 500000, 'duration_minutes' => 45, 'service_type' => 'penggantian', 'description' => 'Penggantian ban'],
            ['name' => 'Engine Diagnostic', 'price' => 100000, 'duration_minutes' => 30, 'service_type' => 'diagnosa', 'description' => 'Diagnosa mesin'],
        ];

        foreach ($services as $serviceData) {
            $serviceData['status'] = 'aktif';
            $serviceData['requirements'] = 'Service standar';
            Service::create($serviceData);
        }

        // Create Spare Parts
        $spareParts = [
            ['name' => 'Oli Mesin Shell', 'code' => 'OIL-001', 'price' => 80000, 'stock' => 50, 'category' => 'engine', 'manufacturer' => 'Shell'],
            ['name' => 'Filter Oli', 'code' => 'FIL-001', 'price' => 35000, 'stock' => 100, 'category' => 'engine', 'manufacturer' => 'Bosch'],
            ['name' => 'Kampas Rem', 'code' => 'BRK-001', 'price' => 150000, 'stock' => 30, 'category' => 'brake', 'manufacturer' => 'Brembo'],
            ['name' => 'Ban Bridgestone', 'code' => 'TIR-001', 'price' => 600000, 'stock' => 20, 'category' => 'tire', 'manufacturer' => 'Bridgestone'],
            ['name' => 'Aki Mobil', 'code' => 'BAT-001', 'price' => 400000, 'stock' => 15, 'category' => 'electrical', 'manufacturer' => 'Sunseeker'],
            ['name' => 'Coolant Radiator', 'code' => 'COL-001', 'price' => 65000, 'stock' => 40, 'category' => 'cooling', 'manufacturer' => 'Coolant Plus'],
            ['name' => 'Spark Plug', 'code' => 'SPK-001', 'price' => 45000, 'stock' => 60, 'category' => 'engine', 'manufacturer' => 'NGK'],
            ['name' => 'Bearing Roda', 'code' => 'BER-001', 'price' => 250000, 'stock' => 25, 'category' => 'suspension', 'manufacturer' => 'SKF'],
        ];

        foreach ($spareParts as $partData) {
            $partData['status'] = 'aktif';
            $partData['min_stock'] = 5;
            $partData['supplier'] = 'PT Supplier Indonesia';
            SparePart::create($partData);
        }

        // Create Mechanics untuk setiap Bengkel
        $bengkelIds = Bengkel::pluck('id')->toArray();
        $mechanicCount = 0;
        $expertiseList = ['Engine', 'Transmission', 'Brake System', 'Suspension'];
        $certificationList = ['Sertifikat Resmi Toyota', 'Sertifikat Resmi Honda', 'Sertifikat Resmi Suzuki'];

        foreach ($bengkelIds as $bengkelId) {
            for ($i = 1; $i <= 3; $i++) {
                $mechanicCount++;
                Mechanic::create([
                    'bengkel_id' => $bengkelId,
                    'name' => "Teknisi Mobil {$mechanicCount}",
                    'phone' => '0812' . str_pad($mechanicCount, 8, '0', STR_PAD_LEFT),
                    'email' => "mekanik{$mechanicCount}@example.com",
                    'expertise' => $expertiseList[$mechanicCount % 4],
                    'salary' => 5000000 + ($mechanicCount * 500000),
                    'experience_years' => rand(1, 20),
                    'certification' => $certificationList[$mechanicCount % 3],
                    'status' => 'aktif',
                    'notes' => "Teknisi profesional dengan pengalaman",
                    'join_date' => now()->subYears(rand(1, 10)),
                ]);
            }
        }

        // Create Vehicles
        $vehicles = [];
        foreach ($bengkelIds as $bengkelId) {
            for ($i = 1; $i <= 5; $i++) {
                $vehicles[] = [
                    'bengkel_id' => $bengkelId,
                    'license_plate' => 'B ' . str_pad($bengkelId . $i, 4, '0', STR_PAD_LEFT) . ' ABC',
                    'owner_name' => "Pemilik Kendaraan " . ($bengkelId * 5 + $i),
                    'owner_phone' => '0812' . str_pad($bengkelId * 5 + $i, 8, '0', STR_PAD_LEFT),
                    'owner_email' => "pemilik{$bengkelId}{$i}@example.com",
                    'vehicle_type' => ['Sedan', 'SUV', 'Truck', 'Minivan'][$i % 4],
                    'brand' => ['Toyota', 'Honda', 'Suzuki', 'Daihatsu'][$i % 4],
                    'model' => ['Avanza', 'Civic', 'Ertiga', 'Gran Max'][$i % 4],
                    'year' => 2015 + ($i % 5),
                    'color' => ['Merah', 'Putih', 'Hitam', 'Silver', 'Biru'][$i % 5],
                    'vin' => 'VIN' . str_pad($bengkelId . $i, 15, '0', STR_PAD_LEFT),
                    'mileage' => rand(10000, 200000),
                    'status' => 'aktif',
                    'notes' => "Kendaraan dalam kondisi baik",
                    'last_service' => now()->subMonths(rand(1, 6)),
                ];
            }
        }

        foreach ($vehicles as $vehicleData) {
            Vehicle::create($vehicleData);
        }

        // Create Orders, OrderItems, Transactions
        $vehicles = Vehicle::all();
        $mechanics = Mechanic::all();
        $services = Service::all();
        $spareParts = SparePart::all();

        foreach ($vehicles->take(10) as $vehicle) {
            // Create 2 orders per vehicle
            for ($j = 0; $j < 2; $j++) {
                $mechanic = $mechanics->random();
                $orderNumber = 'ORD-' . $vehicle->id . '-' . str_pad($j + 1, 4, '0', STR_PAD_LEFT);
                $totalPrice = 0;

                $order = Order::create([
                    'bengkel_id' => $vehicle->bengkel_id,
                    'vehicle_id' => $vehicle->id,
                    'mechanic_id' => $mechanic->id,
                    'order_number' => $orderNumber,
                    'description' => 'Service berkala kendaraan',
                    'total_price' => 0,
                    'discount' => 0,
                    'final_price' => 0,
                    'status' => ['pending', 'in_progress', 'completed'][$j % 3],
                    'priority' => ['low', 'medium', 'high'][$j % 3],
                    'started_at' => now()->subDays(rand(0, 30)),
                    'completed_at' => $j === 0 ? null : now()->subDays(rand(0, 20)),
                    'notes' => 'Service berkala',
                ]);

                // Add 2-3 order items
                $itemCount = rand(2, 3);
                for ($k = 0; $k < $itemCount; $k++) {
                    if ($k === 0) {
                        // Add service
                        $service = $services->random();
                        $itemPrice = $service->price;
                        OrderItem::create([
                            'order_id' => $order->id,
                            'service_id' => $service->id,
                            'quantity' => 1,
                            'unit_price' => $itemPrice,
                            'subtotal' => $itemPrice,
                        ]);
                        $totalPrice += $itemPrice;
                    } else {
                        // Add spare part
                        $sparePart = $spareParts->random();
                        $qty = rand(1, 3);
                        $itemPrice = $sparePart->price * $qty;
                        OrderItem::create([
                            'order_id' => $order->id,
                            'spare_part_id' => $sparePart->id,
                            'quantity' => $qty,
                            'unit_price' => $sparePart->price,
                            'subtotal' => $itemPrice,
                        ]);
                        $totalPrice += $itemPrice;
                    }
                }

                // Update order total
                $discount = rand(0, 1) === 1 ? (int)($totalPrice * 0.1) : 0;
                $finalPrice = $totalPrice - $discount;

                $order->update([
                    'total_price' => $totalPrice,
                    'discount' => $discount,
                    'final_price' => $finalPrice,
                ]);

                // Create transaction
                Transaction::create([
                    'order_id' => $order->id,
                    'transaction_number' => 'TRX-' . $order->order_number,
                    'amount' => $finalPrice,
                    'payment_method' => ['tunai', 'transfer', 'kartu_kredit'][$j % 3],
                    'status' => $order->status === 'completed' ? 'completed' : 'pending',
                    'reference_number' => 'REF-' . str_pad($order->id, 8, '0', STR_PAD_LEFT),
                    'paid_at' => $order->status === 'completed' ? now()->subDays(rand(0, 20)) : null,
                ]);

                // Create rating jika order completed
                if ($order->status === 'completed') {
                    Rating::create([
                        'order_id' => $order->id,
                        'mechanic_id' => $mechanic->id,
                        'service_quality' => rand(4, 5),
                        'professionalism' => rand(4, 5),
                        'timeliness' => rand(3, 5),
                        'overall_rating' => rand(4, 5),
                        'review' => 'Service memuaskan, teknisi profesional',
                        'would_recommend' => true,
                        'tanggal_rating' => now()->subDays(rand(0, 20)),
                    ]);
                }
            }
        }

        $this->command->info('MECHA seeding completed successfully!');
    }
}