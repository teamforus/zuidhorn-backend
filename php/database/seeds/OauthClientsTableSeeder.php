<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = collect([
            'id', 'user_id', 'name', 'secret', 'redirect', 
            'personal_access_client', 'password_client', 'revoked', 
            'created_at', 'updated_at'
            ]);

        $rows = collect([
            [1, null, 'Laravel Personal Access Client', 
            '5TvvFJnsd0UWvbgzACZzkHpBkFxjOf9zOWfJulPz', 'http://localhost', 
            1, 0, 0, '2017-08-13 18:33:44', '2017-08-13 18:33:44'],
            [2, null, 'Laravel Password Grant Client', 
            'DKbwNT3Afz8bovp0BXvJX5jWudIRRW9VZPbzieVJ', 'http://localhost', 
            0, 1, 0, '2017-08-13 18:33:44', '2017-08-13 18:33:44']
            ]);

        foreach ($rows as $key => $row) {
            DB::insert(
                'INSERT INTO `oauth_clients` (`' . $fields->implode('`, `') . 
                '`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $row);
        }

        $fields = collect(['id', 'client_id', 'created_at', 'updated_at']);
        $rows = collect([[1, 1, '2017-08-13 18:33:44', '2017-08-13 18:33:44']]);

        foreach ($rows as $key => $row) {
            DB::insert(
                'INSERT INTO `oauth_personal_access_clients` (`' . $fields->implode('`, `') . 
                '`) VALUES (?, ?, ?, ?)', $row);
        }
    }
}
