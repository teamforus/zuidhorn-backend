<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'id'    => 1,
            'key'   => 'upload_bugets',
            'name'  => 'Upload bugets',
            ]);
        
        Permission::create([
            'id'    => 2,
            'key'   => 'manage_categories',
            'name'  => 'Manage categories',
            ]);
        
        Permission::create([
            'id'    => 3,
            'key'   => 'manage_citizens',
            'name'  => 'Manage citizens',
            ]);
        
        Permission::create([
            'id'    => 4,
            'key'   => 'manage_shopers',
            'name'  => 'Manage shopers',
            ]);

        Permission::create([
            'id'    => 5,
            'key'   => 'manage_permissions',
            'name'  => 'Manage permissions',
            ]);

        Permission::create([
            'id'    => 6,
            'key'   => 'manage_bugets',
            'name'  => 'Manage bugets',
            ]);

        Permission::create([
            'id'    => 7,
            'key'   => 'manage_vouchers',
            'name'  => 'Manage vouchers',
            ]);

        Permission::create([
            'id'    => 9,
            'key'   => 'manage_vouchers_transactions',
            'name'  => 'Manage vouchers transactions',
            ]);
    }
}
