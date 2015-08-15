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
        $file = $request->file('file');
        if (isset($file) && $file->isValid()) {

            try {
                $ts = time();
                $fname = $file->getClientOriginalName();
                $newfilename = $ts . '.pdf';
                $newdir = '../storage/exports/';
                $file->move($newdir, $newfilename);
                $parser = new Parser($newdir . $newfilename, $fname, $this,
                        $this);

                if ($parser->isFileValid()) {
                    $parser->convert();
                    $converted_file = $newdir . $fname . '.xls';
                    $download_link = './' . $fname . '.xls';
                    
                    rename($converted_file, $download_link);
                    unlink($newdir . $newfilename);
                    return response()->json(['download_url' => $fname . '.xls']);
                } else {
                    return response()->json(['error' => 'File conversion error!'],
                                    415);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        return response()->json(['error' => 'Invalid or Empty File!'], 415);
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
