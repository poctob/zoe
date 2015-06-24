<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zoe\Lib\PDF2DF;

/**
 * Processor for the PDF data files.
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class Parser {

    /**
     * Input file.
     */
    private $input;

    /**
     * Alertable object for exception notifications.
     */
    private $alert;

    /**
     * Interface for displaying progress.
     */
    private $progressable;

    /**
     * Flags for different stages of the process.
     */
    private $ready, $headers_set;

    /**
     * PDF parser.
     */
    private $parser;

    /**
     * Table for data storage.
     */
    private $table;

    /**
     * Table columns.
     */
    private $columns;

    /**
     * Delimiters used in the PDF file.
     */
    private $delimiters;

    /**
     * Title page number.
     */
    const TITLE_PAGE = 1;

    /**
     * Principal constructor, initializes member variables.
     * @param String $filename Name of the output file.
     */
    public function __construct($input, $output, iAlert $alert,
            iProgress $progressable) {
        $this->input = $input;
        $this->alert = $alert;
        $this->progressable = progressable;
        $this->ready = false;
        $this->headers_set = false;

        if (file_exists($input)) {
            try {
                $this->parser = new \Smalot\PdfParser\Parser();
                $this->table = new Table(new ExcelWriter($output));
                $this->columns = array();
            } catch (Exception $e) {
                \Log::error('Parser:__construct: ' . $e->getMessage());
                $this->ready = false;
                if (isset($this->alert)) {
                    $this->alert->showAlert('Unable to initialize input file!' . $e->getMessage(),
                            'Converter Error', 'ERROR_MESSAGE');
                }

                $this->ready = true;
            }
            $this->delimiters = config('zoe.delimiters');
            if (isset($this->delimiters) || count($this->delimiters) == 0) {
                if (isset($this->alert)) {
                    $this->alert->showAlert('Unable to initialize properties file!!',
                            'Converter Error', 'ERROR_MESSAGE');
                }
            }
        }
    }

    /**
     * Initiates conversion progress
     * @return True if conversion was successful, false otherwise.
     */
    public function convert() {
        if ($this->ready) {

            $title = null;
            $tables = null;

            try {
                $pdDoc = $this->parser->parseFile($this->input);
                $title = $this->convertTitlePage($pdDoc);
                $pages = $pdDoc->getPages();
                $numberOfPages = count($pages);

                if (isset($this->progressable)) {
                    $this->progressable . setMax(count($numberOfPages));
                }

                for ($i = TITLE_PAGE + 1;
                        $i <= $numberOfPages;
                        $i++) {
                    $tables = $this->extractTables($pages, $i);
                    $this->buildTable($tables);
                    if ($this->progressable != null) {
                        $this->progressable . setCurrent($i);
                    }
                }

                $this->table->exportToXLS();
                return true;
            } catch (Exception $e) {
                \Log::error('Parser:convert: ' . $e->getMessage());
                return false;
            }
        }
        return false;
    }

    /**
     * Builds table values from the page.
     * @param page String of raw text from the PDF page.
     */
    private function buildTable($page) {
        $thead = '';
        $separator = '';
        $chead = '';
        $data = '';
        $tfoot = '';

        splitPage($page, $thead, $separator, $chead, $data, $tfoot);

        if (!$this->headers_set) {
            $this->table->setHeader($thead);
            $this->table->setFooter($tfoot);
            $this->headers_set = true;
            $this->buildColumns($separator, $chead);
        }

        $this->buildData($data, $this->columns);
    }

    /**
     * Builds data for columns.
     * @param data Raw PDF data.
     * @param cols Column array.
     */
    private function buildData($data, $cols) {

        try {

            $separator = "\r\n";
            $line = strtok($data, $separator);
            while ($line !== FALSE) {
                //skip first separator
                $ss = substr(trim($line), 1);
                $cells = array();

                foreach ($cols as
                        $c) {
                    $cell_data = substr($ss, 0, $c->getWidth());
                    $cells[] = $cell_data;
                    $ss = substr($ss, $c->getWidth() + 1);
                }
                if (isset($this->table)) {
                    $this->table->addRow($cells);
                }
                $line = strtok($separator);
            }
        } catch (Exception $ex) {
            \Log::error('Parser:buildData: ' . $e->getMessage());
        }
    }

    /**
     * Builds columns for table.
     * @param separator Column separator.
     * @param heads Column headers.
     */
    private function buildColumns($separator, $heads) {
        $sep = trim($separator);
        $seps = explode("\\" . $this->delimiters['BOX_SEPARATOR'], $sep);

        foreach ($seps as
                $s) {
            if (strlen($s) > 0) {
                $col = new Column();
                $col->setWidth(strlen($s));
                $this->columns[] = $col;
            }
        }

        try {
            $separator = "\r\n";
            $line = strtok($heads, $separator);
            while ($line !== FALSE) {
                //skip first separator
                $ss = substr(trim($line), 1);
                foreach ($this->columns as
                        $c) {
                    $header = substr($ss, 0, $c->getWidth());
                    ;
                    $c->setHeader($c->getHeader() . "\n" . trim($header));
                    $ss = substr($ss, $c->getWidth() + 1);
                }
                $line = strtok($separator);
            }
        } catch (Exception $ex) {
            \Log::error('Parser:buildColumns: ' . $e->getMessage());
        }
        $this->table->setColumns($this->columns);
    }

    /**
     * Separates page into sections.
     * @param page Raw PDF text of an entire page.
     * @param thead Reference to header.
     * @param separator Reference to separator.
     * @param chead Reference to column header.
     * @param data Reference to data.
     * @param tfoot Reference to footer.
     */
    private function splitPage($page, &$thead, &$separator, &$chead, &$data,
            &$foot) {
        if (isset($page) && isset($thead) && isset($chead) && isset($data) && isset($tfoot) && isset($separator) && strlen($page) > 0) {

            try {
                $separator = "\r\n";
                $line = strtok($page, $separator);

                //Extract table head first
                while ($line !== FALSE && !$this->isHeaderSeparator($line)) {

                    $thead .= $line . "\n";
                    $line = strtok($separator);
                }

                if ($line == FALSE) {
                    return;
                }

                //Skip separator
                $separator .= $line;
                $line = strtok($separator);

                //Extract column heads next
                while ($line !== FALSE && !$this->isHeaderSeparator($line)) {
                    $chead .= $line . "\n";
                    $line = strtok($separator);
                }

                if ($line == FALSE) {
                    return;
                }

                //Skip separator
                $line = strtok($separator);

                //Data next
                while ($line !== FALSE && !$this->isHeaderSeparator($line)) {
                    $data .= $line . "\n";
                    $line = strtok($separator);
                }

                if ($line == FALSE) {
                    return;
                }


                //Skip separator
                $line = strtok($separator);

                //Table footer
                while ($line !== FALSE) {
                    $tfoot .= $line . "\n";
                    $line = strtok($separator);
                }
            } catch (Exception $ex) {
                \Log::error('Parser:splitPage: ' . $e->getMessage());
            }
        }
    }

    /**
     * Checks to see if the line is a header separator.
     * @param line Line to check.
     * @return 
     */
    private function isHeaderSeparator($line) {
        if (isset($line) && strlen($line) > 0) {
            $l_line = trim($line);
            if (substr($l_line, 0, 1) == $this->delimiters['BOX_SEPARATOR'] && substr($l_line,
                            -1) == $this->delimiters['BOX_SEPARATOR']) {
                $bx = substr($this->delimiters['BOX_SEPARATOR'], 0, 1);
                $ln = substr($this->delimiters['ROW_SEPARATOR'], 0, 1);
                for ($i = 0;
                        $i < strlen($l_line);
                        $i++) {
                    $ch = substr($l_line, $i, 1);
                    if ($ch != $bx && $ch != $ln) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Extracts table data from the document.
     * @param doc PDF document.
     * @param page page number.
     * @return Raw string containing the table.
     */
    private function extractTables($doc, $page) {
        $retVal = null;
        if (isset($doc) && count($doc) >= $page) {
            try {
                return $doc[$page]->getText();
            } catch (Exception $ex) {
                \Log::error('Parser:extractTables: ' . $e->getMessage());
            }
        }
        return $retVal;
    }

    /**
     * Converts title page of the PDF document.
     * @param doc PDF document to use.
     * @return Raw String with page data.
     */
    private function convertTitlePage($doc) {
        try {
            return $doc[TITLE_PAGE]->getText();
        } catch (Exception $ex) {
            \Log::error('Parser:convertTitlePage: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * getter
     * @return 
     */
    public function getInput() {
        return $this->input;
    }

    /**
     * setter
     * @param input 
     */
    public function setInput($input) {
        $this->input = $input;
    }

}
