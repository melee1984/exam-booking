<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    private $apiToken;
	public function __construct()
	{	
		// Unique Token
		$this->apiToken = uniqid(base64_encode(Str::random(60)));
	}

	/**
   * Update the authenticated user's API token.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
	public function accessToken(Request $request)
	{	
		$data = array();
	  	if (Auth::check()) {
	  		$data['api_token'] = Auth::User()->api_token;
	  	}

	  	return response()->json($data, 200);
	}
}
