<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CompanySeeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        
        $this->call([
            CompanySeeder::class,
            UserSeeder::class,
            MachineSeeder::class,
            ToolCategorySeeder::class,
            ToolSeeder::class,
        ]);
    }

    
}
