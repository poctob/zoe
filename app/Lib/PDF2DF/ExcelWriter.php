<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zoe\Lib\PDF2DF;

/**
 * Facade for writing excel data to file.
 *
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class ExcelWriter implements iExcel {

    /**
     * Workbook used for data.
     */
    private $workbook;

    /**
     * Headers for the cells.
     */
    private $headers;

    /**
     * Flag showing if the file has been initialized.
     */
    private $initialized;

    /**
     * Format for currency.
     */
    const CURRENCY_FORMAT = \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE;

    /**
     * Format for date/time
     */
    const CUSTOM_DATE_FORMAT = \PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14;

    /**
     * Principal constructor, initializes member variables.
     * @param String $filename Name of the output file.
     */
    public function __construct($filename) {
        $this->initialized = FALSE;
        $this->headers = array();

        try {
            $this->workbook = \Excel::create($filename);
            $this->workbook->getProperties()
                    ->setCreator("MedConverter")
                    ->setLastModifiedBy("MedConverter")
                    ->setTitle("Imported Data")
                    ->setSubject("Imported Data")
                    ->setDescription("Converted Medicaid EOB data.");
            
            $this->workbook->sheet('Exported Data');
            $this->initialized = TRUE;
        } catch (Exception $e) {
            \Log::error('ExcelWriter:__construct: ' . $e->getMessage());
        }
    }
    
  

    /**
     * Adds currency cell to the spreadsheet.
     * @param int $data
     * @param int $row
     * @param int $col
     */
    public function addCurrency($data, $row, $col) {
        if ($this->initialized && isset($data) && $data > 0) {

            if (isset($this->workbook)) {
                $this->workbook
                        ->getSheet()
                        ->getCellByColumnAndRow($col, $row)
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(self::CURRENCY_FORMAT);

                $this->workbook
                        ->getSheet()
                        ->getCellByColumnAndRow($col, $row)
                        ->setValueExplicit($data, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
            }
        }
    }

    /**
     * Adds date to the worksheet.
     * @param datetime $data
     * @param int $row
     * @param int $col
     */
    public function addDate($data, $row, $col) {
        if ($this->initialized && isset($data) && $data > 0) {

            if (isset($this->workbook)) {
                $this->workbook
                        ->getSheet()
                        ->getCellByColumnAndRow($col, $row)
                        ->getStyle()
                        ->getNumberFormat()
                        ->setFormatCode(self::CUSTOM_DATE_FORMAT);
                
                $this->workbook
                        ->getSheet()
                        ->getCellByColumnAndRow($col, $row)
                        ->setValue(\PHPExcel_Shared_Date::PHPToExcel($data));
            }
        }
    }

    /**
     * Adds header to column
     * @param string $header
     */
    public function addHeader($header) {
        if ($this->initialized && isset($header) && strlen($header) > 0) {

            if (isset($this->workbook)) {
                $this->headers[]=$header;
                $this->addString($header, 1, count($this->headers) - 1);
                $styleArray = array(
                    'font' => array(
                        'bold' => true,
                    ),
                    'alignment' => array(
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ));

                $this->workbook
                        ->getSheet()
                        ->getCellByColumnAndRow(count($this->headers) - 1, 1)
                        ->getStyle()
                        ->applyFromArray($styleArray);
            }
        }
    }

    /**
     * Adds string to the cell
     * @param string $data
     * @param int $row
     * @param int $col
     */
    public function addString($data, $row, $col) {
        if ($this->initialized && isset($data) && strlen($data) > 0) {

            if (isset($this->workbook)) {
                $this->workbook
                        ->getSheet()
                        ->getCellByColumnAndRow($col, $row)
                        ->setValueExplicit($data, \PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
    }

    /**
     * Writes workbook to file.
     */
    public function writeToDisk() {

        if (isset($this->workbook)) {
            $this->workbook->store('xls');
        }
    }

}
