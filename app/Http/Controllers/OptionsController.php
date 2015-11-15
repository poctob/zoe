<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Zoe\UserOptions;

class OptionsController extends Controller {
    /*
      |--------------------------------------------------------------------------
      |Options Controller
      |--------------------------------------------------------------------------
      |
      | This controller performs user application options management
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
     * Show the options screen to the user.
     *
     * @return Response
     */
    public function index(Request $request) {
        if ($request->user()) {

            $app = config('zoe.application')['NAME'];
            $columns = array();

            if ($request->user()->canAccessApp($app)) {

                $cols = $request->user()->getOption('columns');
                if(isset($cols)){
                    $columns = $this->getDefaultOptions();
                    $user_cols = explode(";", $cols);
                    $this->enableColumns($columns, $user_cols);
                }
                else{
                    $columns = $this->getDefaultOptions(true);
                }
            }
        } else {
            \Session::flash('growl',
                    ['type' => 'danger', 'message' => 'You have no active subscriptions!']);
        }

        return view('options', ['columns' => $columns]);
    }
    
    public function save(Request $request)
    {
        if ($request->user()) {
            $data = $request->all();
            
            $options = array();
            
            foreach( $data as $k => $v){
                
                $key_name = explode("_", $k);
                if(count($key_name) == 2 && $key_name[0] == "option"){
                    
                    $option = new UserOptions();
                    $option->option = $key_name[1];
                    $option->value = implode(";",$v);
                    
                    $request->user()->options()->save($option);
                }
            }
        }
        return $this->index($request);
    }
    
    private function enableColumns(&$defaults, $selection)
    {
        foreach($defaults as $d)
        {
            if( in_array($d['name'], $selection))
            {
                $d['checked'] = true;
            }
        }                
    }

    /**
     * Populates default options object
     * @return string array of options
     */
    private function getDefaultOptions($enable = false) {
        
        $columns = array();
        
        $cols = config('zoe.columns');
        
        foreach ($cols as
                $k =>
                $v) {
            if ($k != 'MAX') {
                $c = array();
                $c['name'] = $k;
                $c['checked'] = $enable;
                $columns[] = $c;
            }
        }
        
        return $columns;
    }

}
