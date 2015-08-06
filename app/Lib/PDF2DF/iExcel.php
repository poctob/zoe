<?php

namespace Zoe\Lib\PDF2DF;

/**
 * Interface for interaction with excel spreasheets.
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
interface iExcel {

    /**
     * Adds header to the excel sheet.
     * @param string header
     */
    public function addHeader($header, $width);

    /**
     * Writes file to disk.
     */
    public function writeToDisk();

    /**
     * Adds string cell.
     * @param string $data
     * @param int $row
     * @param int $col
     */
    public function addString($data, $row, $col);

    /**
     * Adds date cell.
     * @param string $data
     * @param int $row
     * @param int $col
     */
    public function addDate($data, $row, $col);
    
    
    /**
     * Adds currency cell.
     * @param string $data
     * @param int $row
     * @param int $col
     */
    public function addCurrency($data, $row, $col);
}
