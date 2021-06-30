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
        // $rooms = array(1,2,3,4,5,6,7,8,9,10);
        // $data = array();

        // foreach($rooms as $room) {

        //         array_push($data,[
        //             'user_id' => '1',
        //             'meeting_room_name' => 'Room '.$room,
        //             'schedule_at' => \Carbon\Carbon::now()->addDays(5),
        //             'from_to' => '2021-07-02 09:00:00',
        //             'until_to' => '2021-07-02 09:30:00',
        //             'duration'  => '30 mins',
        //             'created_at' => \Carbon\Carbon::now(),
        //             'updated_at' => \Carbon\Carbon::now(),
        //         ]);

        // }

        // return $data;
        // 
        return [
            [
                'user_id' => '1',
                'meeting_room_name' => 'Room 1',
                'schedule_at' =>\Carbon\Carbon::now()->addDays(5),
                'from_to' => '2021-06-27 08:00:00',
                'until_to' => '2021-06-27 08:30:00',
                'duration'  => '30 mins',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'user_id' => '1',
                'meeting_room_name' => 'Room 2',
                'schedule_at' =>\Carbon\Carbon::now()->addDays(5),
                'from_to' => '2021-06-27 09:00:00',
                'until_to' => '2021-06-27 09:30:00',
                'duration'  => '30 mins',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'user_id' => '1',
                'meeting_room_name' => 'Room 3',
                'schedule_at' => \Carbon\Carbon::now()->addDays(5),
                'from_to' => '2021-06-27 12:00:00',
                'until_to' => '2021-06-27 13:00:00',
                'duration'  => '1 hour',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            // [
            //     'user_id' => '1',
            //     'meeting_room_name' => 'Room 4',
            //     'schedule_at' => \Carbon\Carbon::now()->addDays(5),
            //     'from_to' => '2021-06-27 13:00:00',
            //     'until_to' => '2021-06-27 13:30:00',
            //     'duration'  => '30 mins',
            //     'created_at' => \Carbon\Carbon::now(),
            //     'updated_at' => \Carbon\Carbon::now(),
            // ],

            // [
            //     'user_id' => '1',
            //     'meeting_room_name' => 'Room 5',
            //     'schedule_at' => \Carbon\Carbon::now()->addDays(5),
            //     'from_to' => '2021-06-27 13:30:00',
            //     'until_to' => '2021-06-27 14:30:00',
            //     'duration'  => '30 mins',
            //     'created_at' => \Carbon\Carbon::now(),
            //     'updated_at' => \Carbon\Carbon::now(),
            // ],

        ];


    }

}
