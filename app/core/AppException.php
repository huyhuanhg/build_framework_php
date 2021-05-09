<?php


namespace app\core;


use Throwable;

class AppException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        set_exception_handler([$this, 'error_handle']);
        parent::__construct($message, $code, $previous);
    }

    public function error_handle($e)
    {
        echo "<h1 style='color: red'>{$e->getCode()} => {$e->getMessage()}</h1>";
        echo "<h2>{$e->getFile()} => {$e->getLine()}</h2>";
        echo "<p>{$e->getTraceAsString()}</p><hr/>";
        foreach ($e->getTrace() as $trace) {
            $file = $trace['file'] ?? '';
            $line = $trace['line'] ?? '';
            $class = $trace['class'] ?? '';
            $method = $trace['method'] ?? '';
            if (!empty($file)) {
                echo "<h4>File: $file";
                if (!empty($line)) {
                    echo " => Line: $line</h4>";
                } else echo "</h4>";
            }
            if (!empty($class)) {
                echo "<h4>Class: $class";
                if (!empty($method)) {
                    echo " => Function: $method</h4>";
                } else echo "</h4>";
            } elseif (!empty($method)) {
                echo "<h4>Function: $method</h4>";
            }
            echo '<hr/>';
        }
    }
}