<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller {
    /*
      |--------------------------------------------------------------------------
      |Profile Controller
      |--------------------------------------------------------------------------
      |
      | This controller performs user profile management
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
        if ($request->user()) {
            $fuser = $request->user()->getFillableFields();           
            return view('profile', ['user' => $fuser]);
        }
    }

    public function update(Request $request) {
        if ($request->user()) {
            try {
                if ($request->has('name')) {
                    $name = $request->get('name');
                    if (strlen($name) > 0) {
                        $request->user()->name = $name;
                    }
                }

                if ($request->has('email')) {
                    $email = $request->get('email');
                    if (strlen($email) > 0) {
                        $request->user()->email = $email;
                    }
                }

                $request->user()->push();
                $fuser = $request->user()->getFillableFields();
                return view('profile',
                        ['user' => $fuser, 'success' => 'Thank You! '
                    . 'Your profile has been updated!']);
            } catch (Exception $e) {
                return view('profile',
                        ['user' => $fuser, 'error' => $e->getMessage()]);
            }
        }
    }    

}
