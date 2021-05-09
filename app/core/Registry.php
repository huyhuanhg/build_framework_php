<?php

namespace app\core;

use \PDO;
use \PDOException;
//use app\core\AppException;

class Registry
{
    private static $intance;
    private $storage;

    private function __construct($config = null)
    {
        if (isset($config)) {
            $this->config = $config;
            $this->conn = $this->connectDB($config['db']);
        }
    }

    private function connectDB($dbInfo)
    {
//        $con = new PDO("mysql:host=" . $dbInfo['host'] . ";dbname=" . $dbInfo['dbName'] . ";charset=utf8;", $dbInfo['user'], $dbInfo['pass']);
//        if ($con !== null) {
//        return $con;
//        } else {
//            throw new AppException($con . " : Kết nối thất bại!");
//        }
        try {
            // Kết nối
            $con = new PDO("mysql:host=" . $dbInfo['host'] . ";dbname=" . $dbInfo['dbName'] . ";charset=utf8;", $dbInfo['user'], $dbInfo['pass']);

//            $this->con ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;
            // Thiết lập chế độ lỗi

            // Thông báo thành công
        }
// Nhánh kết nối thất bại
        catch (PDOException $e) {
            new AppException("$e->getMessage()");
        }
    }

    /**
     * @return mixed
     */
    public static function getIntance($config = null)
    {
        if (!isset(self::$intance)) {
            self::$intance = new self($config);
        }
        return self::$intance;
    }
//    /**
// * @return mixed
// */
//    public static function getIntance()
//    {
//        if (!isset(self::$intance)) {
//            self::$intance = new self;
//        }
//        return self::$intance;
//    }

    public function __get($name)
    {
        if (isset($this->storage[$name])) {
            return $this->storage[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        if (!isset($this->storage[$name])) {
            $this->storage[$name] = $value;
        }
    }
}