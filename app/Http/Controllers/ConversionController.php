<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Zoe\Lib\PDF2DF\iProgress;
use Zoe\Lib\PDF2DF\iAlert;
use Zoe\Lib\PDF2DF\Parser;
use Illuminate\Http\Response;

class ConversionController extends Controller implements iProgress, iAlert {
    /*
      |--------------------------------------------------------------------------
      |Conversion Controller
      |--------------------------------------------------------------------------
      |
      | This controller performs document conversion.
      |
     */

    private $progress;
    private $progress_max;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
        $this->progress = 0;
        $this->progress_max = 100;
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index() {
        return view('convert');
    }

    /**
     * Initiates file conversion.
     *
     * @return Response
     */
    public function convert(Request $request) {
        $file = $request->file('inputFile');
        if (isset($file) && $file->isValid()) {
            $ts = time();
            $fname = $file->getClientOriginalName();
            $file->move('/', $ts . 'pdf');
            $parser = new Parser($ts . 'pdf', $fname, $this, $this);

            if ($parser->isFileValid()) {
                $parser->convert();
                return response()->download('storage/exports/' . $fname . '.xls');
            }
        }
        return response()->json(['error' => 'File conversion error!']);
    }

    public function setCurrent($current) {

        if ($current >= 0) {
            $this->progress = $current <= $this->progress_max ? $current : $this->progress_max;
        }
    }

    public function setMax($max) {
        $this->progress_max = $max;
    }

    public function showAlert($message, $title, $messageType) {
        
    }

}
