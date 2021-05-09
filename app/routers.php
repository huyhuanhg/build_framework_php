<?php

use app\core\Controller;
use app\core\QueryBuider;

Router::get('/', function (){
    echo 'this is home page';
});
Router::get('/news', function (){ // get list news
    echo 'this is news page';
});
Router::get('/news/show', function (){
    echo 'this is news show page';
});
Router::get('/news/{param}', function ($param){ //get new by id
    echo 'this is news page<br/>';
    echo 'param = '.$param;
});
Router::post('/news/show/{param}', function ($param){
    echo 'this is news show page<br/>';
    echo 'param = '.$param;
});
Router::get('/news/{param1}/{param2}', function ($param1, $param2){
    echo 'this is news page<br/>';
    echo 'param 1 = '.$param1;
    echo '<br/>param 2 = '.$param2;
});
Router::get('/news/show/{param1}/{param2}', function ($param1, $param2){
    echo 'this is news show page<br/>';
    echo 'param 1 = '.$param1;
    echo '<br/>param 2 = '.$param2;
});
Router::get('/home', 'HomeController@index');
Router::get('/', function () {
    $builder = QueryBuider::table('words')->insert(['1'=>0]);

//    echo '<pre>';
//    print_r(json_decode($builder));
//    $data = [];
//    while ($row = mysqli_fetch_assoc($builder)){
//        $data[] = $row;
//    }
//    print_r($builder);
    echo $builder;
//    $ct = new Controller();
//    $ct->render('index');
});
//Router::get('/{param}', function ($param){
//    echo 'this is home page<br/>';
//    echo 'param = '.$param;
//});
Router::any('*', function () {
    echo '404 not found';
});