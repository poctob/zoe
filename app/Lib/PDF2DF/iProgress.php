<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Zoe\Lib\PDF2DF;

/**
 * Interface that will show progress to a user.
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
interface iProgress {
    
    /**
     * Set maximum
     * @param max 
     */
    function setMax($max);
    
    /**
     * Set current progress (update)
     * @param current 
     */
    function setCurrent($current);
}
