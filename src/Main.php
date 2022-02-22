<?php


namespace uukule\BootstrapUi;

/**
 *
 * @author Mr.zhang
 * @create_time 2018-7-14
 * @method Btn btn() Description
 * @method Form form() 表单调用
 * @method Panel panel()
 */

class Main
{

    const UI_FORM_CHECKBOX_SWITCH = 'checkbox_switch';
    public $handler;
    protected $data;
    protected $config = [
        'type' => 'Bootstrap',
        'is_open' => true
    ];
    protected $phone_numbers;
    public $error;

    public function __construct($config = []) {
        if(is_string($config)){
            $type = $config;
        }else{
            if (empty($config)) {
                $config = config('ui');
            }
            if (is_array($config)) {
                $this->config = array_merge($this->config, $config);
            }
            $type = $this->config['type'];
        }


        $driver = '\\ui\\driver\\' . ucfirst($type);
        if (!class_exists($driver)) {
            return false;
        }
        $this->handler = new $driver($config);
    }

    public function __call($name, $arguments) {
        $class = '\\uukule\\BootstrapUi\\' . ucfirst($name);
        return new $class();
    }
    public static function __callStatic($name, $arguments) {
        $ui = new self();
        return call_user_func_array([$ui->handler, $name], $arguments);
    }

}
