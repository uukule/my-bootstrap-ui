<?php


namespace uukule\BootstrapUi\form;


use uukule\BootstrapUi\Doc;
use uukule\BootstrapUi\InputItem;
use KubAT\PhpSimple\HtmlDomParser;

/**
 * Class Text
 * @property bool $feedback
 * @package uukule\BootstrapUi\form
 */
class Text extends InputItem
{
    protected $inTemp = '<div class="form-group"><label></label><small><span class="text-danger"></span></small><input type="text" class="form-control" ><span class="fas form-control-feedback" aria-hidden="true"></span><small id="" class="form-text text-muted"></small></div>';

    public function __construct(array $option = [])
    {
        $this->options['attr']['type'] = 'text';
        $this->options['feedback'] = false;
        parent::__construct($option);
    }

    public static function __callStatic($name, $arguments){
        $self = new self();
        return $self->__call($name, $arguments);
    }



    /**
     * @return mixed
     */
    public function out()
    {
        $dom = HtmlDomParser::str_get_html($this->inTemp);
        $formGroupClass = $dom->find('.form-group', 0)->class;
        $formGroupClass = explode(' ', $formGroupClass);
        /*************** 开始 设置样式 *****************/
        list($xs, $sm, $md, $lg) = $this->options['col'];
        array_push($formGroupClass, "col-xs-{$xs}", "col-sm-{$sm}", "col-md-{$md}", "col-lg-{$lg}");
        array_push($formGroupClass, "has-{$this->options['status']}");

        array_push($formGroupClass, "form-group-{$this->options['size']}");
        $dom->find('.form-group', 0)->class = join(' ', $formGroupClass);
        /*************** 结束 设置样式 *****************/


        /*************** 开始 设置说明 *****************/
        if (empty($this->options['title'])) {
            $dom->find('label', 0)->outertext = '';
        } else {
            $dom->find('label', 0)->innertext =  $this->options['title'];
            $dom->find('label', 0)->for = $this->item_id;
            $dom->find('input', 0)->id = $this->item_id;
        }
        /*************** 结束 设置说明 *****************/
        /*************** 开始 设置描述与备注 *****************/
        if (empty($this->options['describedby'])) {
            $dom->find('small', 1)->outertext = '';
        } else {
            $did = md5(uniqid() . microtime());
            $dom->find('small', 1)->innertext = $this->options['describedby'];
            $dom->find('input', 0)->setAttribute('aria-describedby', $did);
            $dom->find('small', 1)->id = $did;
        }
        if (empty($this->options['remind'])) {
            $dom->find('small', 0)->outertext = '';
        } else {
            $dom->find('small>span', 0)->innertext = ' * ' . $this->options['remind'];
        }
        /*************** 结束 设置描述 *****************/
        /*************** 开始 设置数据值及属性 *****************/
        $this->options['attr']['value'] = $this->options['value'];
        foreach ($this->data as $k => $v) {
            $this->options['attr']["data-{$k}"] = $v;
        }
        foreach ($this->options['attr'] as $k => $v) {
            $dom->find('input', 0)->$k = $v;
        }

        if($this->options['disabled']){
            $dom->find('input', 0)->disabled = true;
        }
        if($this->options['readonly']){
            $dom->find('input', 0)->readonly = true;
        }
        if($this->options['required']){
            $dom->find('input', 0)->required = true;
        }

        /*************** 结束 设置数据值及属性 *****************/

        $backtrace = array_column(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3), 'function');
        $is_closure = !!array_filter($backtrace, function ($vo){
            return (bool) stripos($vo, 'closure');
        });
        if($is_closure){
            echo $dom;
        }else{
            return $dom;
        }
    }
}