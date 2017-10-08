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
            'key'   => 'buget_upload',
            'name'  => 'Upload bugets',
            ]);
        
        Permission::create([
            'id'    => 2,
            'key'   => 'buget_manage',
            'name'  => 'Manage bugets',
            ]);
        
        Permission::create([
            'id'    => 3,
            'key'   => 'shopkeeper_manage',
            'name'  => 'Manage shopkeepers',
            ]);
        
        // Admin
        Permission::create([
            'id'    => 4,
            'key'   => 'upload_bugets',
            'name'  => 'Upload bugets',
            ]);
        
        Permission::create([
            'id'    => 5,
            'key'   => 'manage_categories',
            'name'  => 'Manage categories',
            ]);
        
        Permission::create([
            'id'    => 6,
            'key'   => 'manage_citizens',
            'name'  => 'Manage citizens',
            ]);
        
        Permission::create([
            'id'    => 7,
            'key'   => 'manage_shop-keepers',
            'name'  => 'Manage Shop Keepers',
            ]);

        Permission::create([
            'id'    => 8,
            'key'   => 'manage_permissions',
            'name'  => 'Manage permissions',
            ]);

        Permission::create([
            'id'    => 9,
            'key'   => 'manage_bugets',
            'name'  => 'Manage bugets',
            ]);

        Permission::create([
            'id'    => 10,
            'key'   => 'manage_vouchers',
            'name'  => 'Manage vouchers',
            ]);
        
        Permission::create([
            'id'    => 11,
            'key'   => 'manage_voucher_transactions',
            'name'  => 'Manage voucher transactions',
            ]);
    }
}
