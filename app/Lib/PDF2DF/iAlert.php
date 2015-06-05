<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Zoe\Lib\PDF2DF;

/**
 * Interface for showing alerts.
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
interface iAlert {
    
    /**
     * This method is meant to show a message dialog to a user.
     * @param string $message
     * @param string $title
     * @param int $messageType
     */
     public function showAlert($message, $title, $messageType);
}
