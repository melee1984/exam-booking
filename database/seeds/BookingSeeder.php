<?php

use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bookings')->insert($this->data());
    }

    public function data()
    {
        return [
        	[
        		'user_id' => '1',
                'meeting_room_name' => 'Room 1',
                'schedule_at' => \Carbon\Carbon::now(),
                'from_to' => '2021-06-27 08:00:00',
                'until_to' => '2021-06-27 08:30:00',
                'duration'  => '30 mins',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
        	],
        	[
        		'user_id' => '1',
                'meeting_room_name' => 'Room 2',
                'schedule_at' => \Carbon\Carbon::now(),
                'from_to' => '2021-06-27 09:00:00',
                'until_to' => '2021-06-27 09:30:00',
                'duration'  => '30 mins',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
        	],
        	[
        		'user_id' => '1',
                'meeting_room_name' => 'Room 3',
                'schedule_at' => \Carbon\Carbon::now(),
                'from_to' => '2021-06-27 12:00:00',
                'until_to' => '2021-06-27 13:00:00',
                'duration'  => '1 hour',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
        	],
         	
        ];
    }
}
