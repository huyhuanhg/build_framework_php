<?php
namespace app\controllers;
use app\core\Controller;
class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
//    $this->redirect('http://google.com');
    $this->render(index, ['name'=>'Huấn', 'age'=>26]);
}
}