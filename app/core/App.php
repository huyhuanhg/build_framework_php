<?php
require_once(dirname(__FILE__) . '/Autoload.php');

use app\core\Registry;

class App
{
    private $router;

    public function __construct($config)
    {
        new Autoload($config['rootDir']);
        $this->router = new Router($config['basePath'], $config['namespace']);
        Registry::getIntance($config);
    }

    public function run()
    {
        $this->router->run();
    }
}