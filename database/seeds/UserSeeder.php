<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	[
        		'id' => 1,
        		'name' => 'Admin User',
                'email' => 'admin@user.com',
        		'password' => Hash::make('admin'),
        		'role_id' => 1, // Admin 
                'email_verified_at' => \Carbon\Carbon::now(),
        		'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
                'api_token' => Str::random(80),
                
        	],
            [
                'id' => 2,
                'name' => 'Guest User',
                'email' => 'guest@user.com',
                'password' => Hash::make('guest'),
                'role_id' => 0, // Guest  
                'email_verified_at' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
                'api_token' => Str::random(80),
                
            ],
        ]);
    }
}
