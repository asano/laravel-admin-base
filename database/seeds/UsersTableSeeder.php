<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        DB::table('users')->insert([
            'name'              => 'user',
            'email'             => 'user@example.com',
            'password'          => Hash::make('passpass'),
            'remember_token'    => Str::random(10),
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
        for ($i = 1; $i <= 100; ++$i) {
            DB::table('users')->insert([
                'name'              => 'user'.$i,
                'email'             => 'user'.$i.'@example.com',
                'password'          => Hash::make('passpass'),
                'remember_token'    => Str::random(10),
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
        }
    }
}
