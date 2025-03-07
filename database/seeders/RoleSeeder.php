<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            if (!Role::where('name', 'approver_level_1')->exists()) {
                Role::create(['name' => 'approver_level_1']);
            }
            if (!Role::where('name', 'atasan')->exists()) {
                Role::create(['name' => 'atasan']);
            }
            if (!Role::where('name', 'bendahara')->exists()) {
                    Role::create(['name' => 'bendahara']);
            }
            if (!Role::where('name', 'admin')->exists()) {
                Role::create(['name' => 'admin']);
            }

    }
}
