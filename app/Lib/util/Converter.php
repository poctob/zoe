<?php

namespace Zoe\Lib\util;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Utility class for converting string data into supported formats.
 *
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class Converter {

    /**
     * Converts string data into float number.
     * @param string $data
     * @return float Convereted float value.
     */
    public static function convertCurrency($data) {
        $retval = 0;
        $matches = array();
        if (isset($data) && is_string($data) && strlen($data > 0)) {
            $pattern = '/[0-9]+\\.{1}[0-9]{2}/';
            if (preg_match($pattern, $data, $matches)) {
                $retval = floatval($matches[0]);
            }
        }
        return $retval;
    }

    /**
     * Converts date in the MMDDYY format to MySQL date.
     * @param string $data
     * @return MySQL formatted date
     */
    public static function ConvertDate($data) {

        $retval = 0;
        $matches = array();
        if (isset($data) && is_string($data) && strlen($data > 0)) {
            $pattern = '/[0-9]{6}/';
            if (preg_match($pattern, $data, $matches)) {
                $date_str = $matches[0];
                $month_str = substr($date_str, 0, 2);
                $day_str = substr($date_str, 2, 2);
                $year_str = substr($date_str, 4, 2);                
                
                $month = intval($month_str);                               
                $day = intval($day_str);
                $year = intval($year_str);

                if ($month > 0 && $month <= 12 && $day > 0 && $day <= 31) {
                    $unix_date = mktime(0,0,0,$day,$month,$year);
                    $retval = date('Y-m-d H:i:s',$unix_date);
                }
            }
        }
        return $retval;
    }

}
