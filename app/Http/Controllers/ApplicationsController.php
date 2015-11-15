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
        if ($request->user()) {

            $app = config('zoe.application')['NAME'];

            if ($request->user()->canAccessApp($app)) {

                $cols = config('zoe.columns');
                $columns = array();

                foreach ($cols as
                        $k =>
                        $v) {
                    if ($k != 'MAX') {
                        $c = array();
                        $c['name'] = $k;
                        $c['checked'] = true;
                        $columns[] = $c;
                    }
                }
                return view('convert', ['columns' => $columns]);
            } else {
                \Session::flash('growl',
                        ['type' => 'danger', 'message' => 'You have no active subscriptions!']);


                $allow_trial = $request->user()->isTrialAllowed($app);
                return view('subscribe', ['allow_trial' => $allow_trial]);
            }
        }
    }

}
