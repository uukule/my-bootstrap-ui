<?php

namespace uukule\BootstrapUi;


class Input
{
    public $items = [];
    public $formDemo = '<form class="form-horizontal" data-parsley-validate="true" name="demo-form"></form>';

    public function __construct()
    {
    }

    public function item($items){
        if(is_array($items)){
            foreach ($items as $item){
                if($item instanceof InputItem){
                    $this->items[] = $item;
                }else{
                    throw new \Exception('非法表单', 10001);
                }
            }
        }elseif($items instanceof InputItem){
            $this->items[] = $items;
        }
    }

    public function group($data){
        if($data instanceof \Closure){
            ob_start();
            $data();
            // 获取并清空缓存
            $content = ob_get_clean();
            return $content;
        }
    }
}
