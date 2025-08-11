<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role admin
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Buat user admin
        $admin = User::updateOrCreate([
            'name' => 'Admin',
            'email' => 'admin@kemenkum.com',
            'password' => bcrypt('kemenkum123'),
        ]);

        $admin->assignRole($adminRole);

        // Buat beberapa pegawai
        Employee::updateOrCreate([
            'nip' => '1234567890',
            'name' => 'John Doe',
        ]);

        Employee::updateOrCreate([
            'nip' => '0987654321',
            'name' => 'Jane Smith',
        ]);
    }
}
