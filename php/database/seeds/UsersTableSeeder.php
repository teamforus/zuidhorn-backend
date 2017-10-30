<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UsersTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id'            => 1,
            'name'          => 'Administrator',
            'email'         => 'forus-admin@dev-weget.nl',
            'password'      => Hash::make('mvp-admin-pass'),
            ]);

        // budget-uploader
        User::create([
            'id'            => 2,
            'name'          => 'Margrieta Maatjes',
            'email'         => 'mmaatjes@zuidhorn.nl',
            'password'      => '$2y$10$amPG2VvsZE3VtfKGaxxLSez62dpSOUmct7pt0Cl0lAubFw4LKV5FW',
            ]);

        // budget-manager
        User::create([
            'id'            => 3,
            'name'          => 'Jan Pastoor',
            'email'         => 'jpastoor@zuidhorn.nl',
            'password'      => '$2y$10$/bam6vm48gOETCXN2jj89ulvW.dfZOcpliHVgsElkM.5PZ4Biv2li',
            ]);

        // shopkeepers-manager
        User::create([
            'id'            => 4,
            'name'          => 'Marleen Bolwijn',
            'email'         => 'mebolwijn@zuidhorn.nl',
            'password'      => '$2y$10$enMKn9SlQBsxjQhTCuPatOe3WdbR7iUgjXzIPb1iM2Cr7WoJ1L4Me',
            ]);
    }
}