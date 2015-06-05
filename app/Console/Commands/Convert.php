<?php

namespace Zoe\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Zoe\Commands\ConvertPDF;

class Convert extends Command {

    use \Illuminate\Foundation\Bus\DispatchesCommands;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zoe:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert PDF file to Excel format.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        $this->info('Starting the conversion with the following arguments:');
        $this->info('Input File:' . $this->argument('input'));
        $this->info('Output File:' . $this->argument('output'));
        
        $this->dispatch(
                new ConvertPDF(
                        $this->argument('input'), 
                        $this->argument('output'))
        );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [
            ['input', InputArgument::REQUIRED, 'Input file.'],
            ['output', InputArgument::OPTIONAL, 'Output file.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    /* 	protected function getOptions()
      {

      } */
}
