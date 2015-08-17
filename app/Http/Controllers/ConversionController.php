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
    private $files;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->progress = 0;
        $this->progress_max = 100;
        $this->files = array();
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index(Request $request) {
        if ($request->user()) {
            if ($request->user()->subscribed()) {
                return view('convert');
            }
            else
            {
                return view('subscribe');
            }
        }
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

                $fname = $file->getClientOriginalName();

                /**
                 * We need to generate a unique file name here is case of
                 * a concurrent access with the same file name.  Not a likely
                 * scenario, but better safe than sorry.
                 */
                $newfilename = hash('md5', $fname);

                /**
                 * Move the source file out of the public directory.
                 */
                $newdir = '../storage/exports/';
                $file->move($newdir, $newfilename);

                $parser = new Parser($newdir . $newfilename, $newfilename,
                        $this, $this);

                if ($parser->isFileValid()) {
                    $parser->convert();

                    /**
                     * Delete source file.
                     */
                    unlink($newdir . $newfilename);

                    /**
                     * Push converted file information to the session.
                     */
                    $this->storeFileName($request, $newfilename . '.xls',
                            $fname . '.xls');

                    /**
                     * Let the user know that we are done.
                     */
                    return response()->json(['filename' => $newfilename . '.xls']);
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

    /**
     * Looks up new and original file names and pushes them back to user.
     * @param Request $request Original request
     * @param type $filename File name passed in as a parameter
     * @return Response with file as a payload
     */
    public function downloadFile(Request $request, $filename) {
        if (isset($filename) && strlen($filename) > 0 && $request->session()->has('convertedFiles')) {

            /**
             * Get file info from the session.
             */
            $files = $request->session()->get('convertedFiles');

            /**
             * Double check to make sure that this is the file we are looking for.
             */
            if ($files['name'] == $filename) {

                /**
                 * We are only letting user know the file name, so we need
                 * build the path here.
                 */
                $fname = '../storage/exports/' . $filename;

                if (file_exists($fname)) {
                    return response()->download($fname, $files['original_name']);
                } else {
                    return response()->json(['error' => 'Invalid file requests!'],
                                    415);
                }
            }
        } else {
            return response()->json(['error' => 'Invalid hash parameter!'], 415);
        }
    }

    /**
     * Saves file parameters in session for later retrieval.
     * @param type $request Request with session
     * @param type $filename Actual file name
     * @param type $original_name Original file name
     */
    private function storeFileName($request, $filename, $original_name) {
        $files = array();

        if (isset($filename) && strlen($filename) > 0) {
            $files['name'] = $filename;
            $files['original_name'] = $original_name;

            if ($request->session()->has('convertedFiles')) {
                $request->session()->forget('convertedFiles');
            }

            $request->session()->put('convertedFiles', $files);
        }
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
