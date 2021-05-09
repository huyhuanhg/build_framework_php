<?php
use app\core\AppException;
class Autoload
{
    private $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
        spl_autoload_register([$this, 'autoload']);
        $this->autoLoadFile();
    }

    private function autoload($class)
    {
        $filePath = str_replace('\\', '/', $this->rootDir . '/' . $class . '.php');
        if (file_exists($filePath)) {
            require_once($filePath);
        } else {
            throw new AppException("$filePath không tồn tại!");
        }
    }

    private function autoLoadFile()
    {
        foreach ($this->fileLoadDefault() as $file) {
            require_once($this->rootDir . '/' . $file);
        }
    }

    private function fileLoadDefault()
    {
        return [
            'app/core/Router.php',
            'app/routers.php',
        ];
    }
}