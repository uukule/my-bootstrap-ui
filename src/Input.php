<?php

namespace uukule\BootstrapUi;

use uukule\BootstrapUi\Doc;
use KubAT\PhpSimple\HtmlDomParser;

class Input
{
    public $items = [];
    public $formDemo = '<form class="form-horizontal" data-parsley-validate="true" name="demo-form"></form>';
    public $formDom;

    public function __construct()
    {
        $this->formDom = HtmlDomParser::str_get_html($this->formDemo);
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
            $this->formDom->find('form', 0)->innertext = $content;
        }
        return $this;
    }

    public function show(){
        $this->formDom->find('form', 0)->innertext .= ui()->btn()->type('submit')->success()->content('提交')->show();
        return $this->formDom;
    }
}
