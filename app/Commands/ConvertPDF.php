<?php namespace Zoe\Commands;

use Zoe\Commands\Command;

class ConvertPDF extends Command {
    
        /**
         * Input file name.
         * @var String
         */
        private $inputFileName;
        
        /**
         * Output file name
         * @var String
         */
        private $outputFileName;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($inputFileName, $outputFileName)
	{            
		$this->inputFileName = $inputFileName;
                $this->outputFileName = $outputFileName;
                
	}
        
        /**
         * Getter
         * @return String
         */
        function getInputFileName() {
            return $this->inputFileName;
        }

        /**
         * Getter
         * @return type
         */
        function getOutputFileName() {
            return $this->outputFileName;
        }

        /**
         * Setter
         * @param inputFileName
         */
        function setInputFileName($inputFileName) {
            $this->inputFileName = $inputFileName;
        }

        /**
         * Setter
         * @param $outputFileName
         */
        function setOutputFileName($outputFileName) {
            $this->outputFileName = $outputFileName;
        }



}
