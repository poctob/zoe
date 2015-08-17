<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApplicationsController extends Controller {
    /*
      |--------------------------------------------------------------------------
      |Applications Controller
      |--------------------------------------------------------------------------
      |
      | This controller performs user applications management
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the subscription screen to the user.
     *
     * @return Response
     */
    public function index(Request $request) {
        if ($request->user() && $request->user()->subscribed()) {
                     
            return view('applications');
        }
        else
        {
            return view('applications',['error' => 'You have no active applications.']);
        }
    }

   

}
