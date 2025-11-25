<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaundryServicesSeeder extends Seeder
{
    public function run()
    {
        $items = [
            // KILOAN
            [
                'code' => 'pakaian_ck_setrika',
                'name' => 'Pakaian (Cuci Kering Setrika)',
                'type' => 'kiloan',
                'price_reguler' => 6000,
                'price_express' => 9000
            ],
            [
                'code' => 'pakaian_cuci_kering',
                'name' => 'Pakaian (Cuci Kering)',
                'type' => 'kiloan',
                'price_reguler' => 5000,
                'price_express' => 8000
            ],
            [
                'code' => 'pakaian_setrika',
                'name' => 'Pakaian (Setrika)',
                'type' => 'kiloan',
                'price_reguler' => 6000,
                'price_express' => 8000
            ],

            // NON-KILOAN
            [
                'code' => 'laundry_selimut',
                'name' => 'Laundry Selimut',
                'type' => 'non-kiloan',
                'price_reguler' => 25000,
                'price_express' => 45000
            ],
            [
                'code' => 'laundry_sprei',
                'name' => 'Laundry Sprei',
                'type' => 'non-kiloan',
                'price_reguler' => 15000,
                'price_express' => 25000
            ],
            [
                'code' => 'laundry_boneka',
                'name' => 'Laundry Boneka',
                'type' => 'non-kiloan',
                'price_reguler' => 10000,
                'price_express' => 20000
            ],
        ];

        foreach ($items as $i) {
            DB::table('laundry_services')->updateOrInsert(
                ['code' => $i['code']], // cari berdasarkan code unik
                $i // update semua kolom termasuk type
            );
        }
    }
}
