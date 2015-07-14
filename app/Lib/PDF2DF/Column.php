<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zoe\Lib\PDF2DF;

/**
 *  Representation of the a single column in a table.
 *
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class Column {
    /**
     * Character width of a column.
     * @var 
     */
    private $width;
    
    /**
     * Header (title) of the column.
     * @var 
     */
    private $header;
    
    /**
     * Default constructor, initializes member variables.
     */
    public function __construct() {
        $this->width = 0;
        $this->header = '';
    }
    
    /**
     * Getter
     * @return
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Getter
     * @return
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * Setter
     * @return
     */
    public function setWidth($width) {
        
        if(isset($width) && is_numeric($width) && $width > 0)
        {
            $this->width = $width;
        }
        else
        {
            \Log::error('Column:setWidth Invalid value provided!');
        }
        
    }

    /**
     * Setter
     * @return
     */
    public function setHeader($header) {
        if(isset($header) && is_string($header))
        {
            $this->header = $header;
        }
        else
        {
            \Log::error('Column:setHeader Invalid value provided!');
        }
    }



    
}
