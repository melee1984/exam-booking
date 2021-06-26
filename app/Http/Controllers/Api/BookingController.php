<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use App\Booking;
use Auth;

class BookingController extends Controller
{
    /**
     * Display of meetings schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
    	$data = array();	

    	$bookings = Booking::availbleMeetings()
    					->paginate(15);

        foreach ($bookings as $booking) {

        	// setting up the name to display on the page
        	$booking->name = $booking->user->name;
        	$booking->schedule_date = $booking->schedule_at->format('Y-m-d'); // we can format whatever display we want 
        	$booking->schedule_time_display = $booking->from_to->format('g:i A') . " - " . $booking->until_to->format('g:i A');
            $booking->schedule_time = $booking->from_to->format('g:i A');

        	// Just making user that I am not getting 
			// all the information related to the user 
			// include some important record
			unset($booking->user);
        }

		// Getting the timings //
		// We call also create a logic to filter 
		// available timings compares 
		// to the currenct record 
		$timings = Booking::getTimings();
		// 
		$data['bookings'] = $bookings;
		$data['timings'] = $timings;

	  	return response()->json($data, 200);
    }

    /**
     * Fetch user bookings only
     * @return [type] [description]
     */
    public function fetchmyrecord()
    {   
        $data = array();    

        $bookings = Booking::filterByUser()
                                ->paginate(15);
                        
        foreach ($bookings as $booking) {

            // setting up the name to display on the page
            $booking->name = $booking->user->name;
            $booking->schedule_date = $booking->schedule_at->format('Y-m-d'); // we can format whatever display we want 
            $booking->schedule_time_display = $booking->from_to->format('g:i A') . " - " . $booking->until_to->format('g:i A');
            $booking->schedule_time = $booking->from_to->format('g:i A');

            // Just making user that I am not getting 
            // all the information related to the user 
            // include some important record
            unset($booking->user);
        }

       $data['bookings'] = $bookings;

        return response()->json($data, 200);
    }
    /**
     * Store new meeting entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
		$validator = Validator::make($request->all(), [
            'meeting_room_name' => 'required|max:255',
            'schedule_at' => 'required',
            'from_to' => 'required',
            'duration' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        // just to make sure that the until to is filled 
        if ($request->input('duration') == "30 mins") {
            $uptoThisTime = date('g:i A',strtotime('30 mins',strtotime($request->input('from_to'))));
        }
        elseif ($request->input('duration') =="1 hour") {
            $uptoThisTime = date('g:i A',strtotime('1 hour',strtotime($request->input('from_to'))));
        }

        $booking = Booking::create([
		    'meeting_room_name' => $request->input('meeting_room_name'),
		    'schedule_at' => $request->input('schedule_at'), 
		    'from_to' => $request->input('from_to'),
            'until_to' => $uptoThisTime,
            'duration' => $request->input('duration'),
		    'user_id'	=> Auth::User()->id,
		]);

        if ($booking) {
        	$data['status'] = 1;
        	$data['message'] = __('Succesfully add new meeting');
        }

        return response()->json($data, 200);

    }

    /**
     * Update meeting entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {	
		$data = array();

        $validator = Validator::make($request->all(), [
        	'meeting_room_name' => 'required|max:255',
            'schedule_at' => 'required',
            'from_to' => 'required',
            'duration' => 'required',
        ]);
   
        if($validator->fails()){
			return response(['error' => $validator->errors(), 'Validation Error']);
        }
    
        // just to make sure that the until to is filled 
        if ($request->input('duration') == "30 mins") {
            $uptoThisTime = date('g:i A',strtotime('30 mins',strtotime($request->input('from_to'))));
        }
        elseif ($request->input('duration') =="1 hour") {
            $uptoThisTime = date('g:i A',strtotime('1 hour',strtotime($request->input('from_to'))));
        }

        $booking->meeting_room_name = $request->input('meeting_room_name');
        $booking->schedule_at = $request->input('schedule_at');
        $booking->from_to = $request->input('from_to');
        $booking->duration = $request->input('duration');
        $booking->until_to = $uptoThisTime;
        $status = $booking->save();

		if ($status) {
			$data['status'] = 1;
			$data['message'] = __('Succesfully updated meeting');
		} 

		return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {	
    	$data = array();
        $status = $booking->delete();

        if ($status) {
        	$data['status'] = 1;
        	$data['message'] = __('Successfully deleted meeting');
        }

        return response()->json($data, 200);
    }   

    /**
     * populate bookign timings based on the selected date 
     * @return return array 
     */
    public function timings(Request $request) 
    {   
        $data = array();

        $data['timings'] = Booking::getTimings($request->input('schedule_at'));

        return response()->json($data, 200);
    }
}
