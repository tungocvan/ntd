<?php

namespace Modules\Users\database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAdmin = User::updateOrCreate(
            [
                'email' => 'tungocvan@gmail.com',
            ],
            [
                'name' => 'Từ Ngọc Vân',
                'password' => Hash::make('123456'),
            ]
        );

        $role = Role::findByName('Super Admin');

        $userAdmin->assignRole($role);
    }
}
