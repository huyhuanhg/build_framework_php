<?php

namespace app\core;

use \App;

class Controller
{
    private $layout = null;
    private $config;
    public function __construct()
    {
        $this->config = Registry::getIntance()->config;
        $this->layout = $this->config['layout'];
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @param $url
     * @param bool $isEnd
     * @param int $resPonseCode
     */
    public function redirect($url, $isEnd = true, $resPonseCode = 302)
    {
        header('Location:' . $url, true, $resPonseCode);
        if ($isEnd) {
            die();
        }
    }

    public function render($viewFile, $data = null)
    {
        $rootDir = $this->config['rootDir'];
        $viewContent = $this->getViewContent($viewFile, $data);
        if ($this->layout !== null) {
            $layoutPath = $rootDir . '/app/views/' . $this->layout . '.php';
            if (file_exists($layoutPath)) {
                require_once($layoutPath);
            }
        }
    }

    public function getViewContent($viewFile, $data = null)
    {
        $viewFolder = strtolower(str_replace('Controller', '', Registry::getIntance()->controller));
        $rootDir = $this->config['rootDir'];
        $viewPath = $rootDir . '/app/views/' . $viewFolder . '/' . $viewFile . '.php';
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $data;
        }
        if (file_exists($viewPath)) {
            ob_start();
            require($viewPath);
            return ob_get_clean();
        }
    }

    public function renderPartial($view, $data = null)
    {
        $rootDir = $this->config['rootDir'];
        $viewPath = $rootDir . '/app/views/' . $view . '.php';
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $data;
        }
        if (file_exists($viewPath)) {
            require($viewPath);
        }
    }
}