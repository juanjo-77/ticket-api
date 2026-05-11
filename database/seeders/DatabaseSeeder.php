<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios solo si no existen
        User::firstOrCreate(
            ['email' => 'admin@ticketapi.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'tecnico@ticketapi.com'],
            [
                'name'     => 'Técnico 1',
                'password' => Hash::make('password123'),
                'role'     => 'technician',
            ]
        );

        // Crear dispositivos solo si no existen
        Device::firstOrCreate(
            ['serial_number' => 'DELL-001'],
            [
                'name'   => 'Laptop Dell XPS',
                'type'   => 'laptop',
                'status' => 'available',
            ]
        );

        Device::firstOrCreate(
            ['serial_number' => 'APL-001'],
            [
                'name'   => 'iPhone 14',
                'type'   => 'mobile',
                'status' => 'available',
            ]
        );
    }
}
