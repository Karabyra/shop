<?php


namespace core\base\controller;

use core\base\exception\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;


class RouteController
{
    static private $_instance;

    protected $routes;
    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    private function __clone()
    {
    }

    static public function getInstance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }

        return self::$_instance = new self;
    }

    private function __construct()
    {

// Getting Address Bar
        $address_str = $_SERVER['REQUEST_URI'];

// User Redirection
        if (strrpos($address_str, '/') === strlen($address_str) - 1 && strrpos($address_str, '/') !== 0) {
            $this->redirect(rtrim($address_str, '/'), 301);
        }

// Script Execution Name
        $path = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], 'index.php'));

// Comparison of script root and constant
        if ($path === PATH) {

// settings paths (class Settings)
            $this->routes = Settings::get('routes');
            if (!$this->routes) throw new RouteException('Сайт находится на техническом обслуживании');

//Check for Admin
            if (strpos($address_str, $this->routes['admin']['alias']) === strlen(PATH)) {
                // To be continue!!!
            } else {
//Address bar split into mass
                $url = explode('/', substr($address_str, strlen(PATH)));
                $hrUrl = $this->routes['user']['hrUrl'];

// Where connect controller
                $this->controller = $this->routes['user']['path'];
                $route = 'user';
            }
            $this->createRoute($route, $url);
            exit();
        } else {
            try {
                throw new \Exception('Некоректная дериктория сайта');
            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }
    }
//Route creation method
    private function createRoute($var, $arr){
        $route =[];

// Check mass
        if(!empty($arr[0])){
            if($this->routes[$var]['routes'][$arr[0]]){
                $route = explode('/',$this->routes[$var]['routes'][$arr[0]]);
                $this->controller .= ucfirst($route[0].'Controller');

            }else{
                $this->controller.=ucfirst($arr[0].'Controller');
            }
//Connect default address
        }else{
            $this->controller .= $this->routes['default']['controller'];
        }
        $this->inputMethod = $route[1]? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2]? $route[2] : $this->routes['default']['outputMethod'];

        return;
    }
}
