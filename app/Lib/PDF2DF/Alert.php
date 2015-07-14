<?php

namespace Zoe\Lib\PDF2DF;


/**
 * Description of Alert
 *
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class Alert implements iAlert{
    
    public function showAlert($message, $title, $messageType) {
        
        echo $message;
        
    }
}
