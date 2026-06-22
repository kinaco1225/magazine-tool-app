<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Machine;
use App\Models\Company;

class MachineSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $makers       = ['DMG MORI', 'OKUMA', 'MAZAK', 'Brother', 'YASDA'];
        $capacities   = [20, 24, 30, 32, 40];
        $locations    = ['工場A-1', '工場A-2', '工場A-3', '工場B-1', '工場B-2'];

        for ($i = 1; $i <= 10; $i++) {
            $capacity = $capacities[($i - 1) % count($capacities)];

            Machine::create([
                'company_id'        => $company->id,
                'name'              => "マシニングセンタ {$i}号機",
                'machine_number'    => 'MC-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'maker'             => $makers[($i - 1) % count($makers)],
                'model'             => 'M-' . (1000 + $i * 10),
                'location'          => $locations[($i - 1) % count($locations)],
                'magazine_capacity' => $capacity,
                'available_spots'   => $capacity,
                'is_active'         => true,
            ]);
        }
    }
}
