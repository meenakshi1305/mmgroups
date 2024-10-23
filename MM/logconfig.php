<?php

function logException($exception) {
    $logMessage = "[" . date('Y-m-d H:i:s') . "] Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
    
    // You can customize this part based on how you want to log the exceptions
    file_put_contents('error.log', $logMessage, FILE_APPEND);
}

?>