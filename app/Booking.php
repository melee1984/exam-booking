<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DateTime;
use DateInterval;
use DatePeriod;
use Cache;
use Auth;

class Booking extends Model
{	
	// 
    protected $fillable = array(
                                'user_id',
                                'meeting_room_name',
								'schedule_at',
								'from_to',
                                'duration',
                                'until_to');
    
	public $timestamps = true;

	 protected $dates = [
       'schedule_at',
       'from_to',
       'until_to',
    ];

	/**
	 * Get user
	 * @return User Object 
	 */
	public function user()
    {
        return $this->hasOne('App\User','id', 'user_id');
    }
    /**
     * [scopeAvailbleMeetings description]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
	public static function scopeAvailbleMeetings($query) 
    {      
        $date = now();
        return  $query->whereDate('schedule_at', '>=', date('Y-m-d', strtotime($date)));
    					
    }
    /**
	 * Just filtering the time available on that day. 
     * I need more time to explore more on the time management  comparation.
	 * @return available time in a array 
	 */
	public static function getTimings($date="06/30/2021") {

        if ($date == "") {
            $date = now();
        }
        // echo date("Y-d-m 00:00:00", strtotime($date));
        // echo "<br>";
        // echo date("Y-d-m 11:59:59", strtotime($date));
        $bookings = Booking::whereDate('schedule_at', '=', date('Y-m-d', strtotime($date)))
                        ->orderBy('schedule_at', 'asc')
                        ->orderBy('from_to', 'asc')
                        ->get();

        // dd($bookings);
        $times_occupied = array();

        // get all record by date and added into array ;
        // This will be compare to the available dates populate later on. 
        // 
        foreach($bookings as $booking) {

            $begin = new DateTime($booking->from_to->format('g:i A'));
            $end   = new DateTime($booking->until_to->format('g:i A'));

            $interval = DateInterval::createFromDateString('30 min');
            $times    = new DatePeriod($begin, $interval, $end);

            array_push($times_occupied, $booking->from_to->format('g:i A'));     
            foreach ($times as $time) {
                $occupied = $time->add($interval)->format('g:i A');
                array_push($times_occupied, $occupied); 
            }
        }

        $data = array();

        // Initialize the dates to compare 
        // and then compare if the time is a vailable otherwise set the time lock or unlock
        // 
        $begin = new DateTime("07:30");
        $end   = new DateTime("16:30");
        // Interval 
        $interval = DateInterval::createFromDateString('30 min');
        $times    = new DatePeriod($begin, $interval, $end);

        // this will loop the dates into the occupied times 
        // 
        foreach ($times as $time) {
           $new = $time->add($interval)->format('g:i A');
           if (!in_array($new, $times_occupied)) {
               array_push($data, array('time' => $new, 'lock' => false)); 
            } 
            else {
                array_push($data, array('time' => $new, 'lock' => true)); 
            }
        }

        return $data;
    }

}
