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
        		'name' => 'User Admin',
                'email' => 'hello@world.com',
        		'password' => Hash::make('hello'),
        		'role_id' => 1, // Admin 
                'email_verified_at' => \Carbon\Carbon::now(),
        		'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
                'api_token' => Str::random(80),
                
        	]
        ]);
    }
}
