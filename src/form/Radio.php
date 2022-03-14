<?php


namespace uukule\BootstrapUi\form;


use uukule\BootstrapUi\Doc;
use uukule\BootstrapUi\InputItem;
use KubAT\PhpSimple\HtmlDomParser;

/**
 * Class Radio
 * @property bool $feedback
 * @package uukule\BootstrapUi\form
 */
class Radio extends InputItem
{
    protected $inTemp = '<div class="form-group"><label></label><small><span class="text-danger"></span></small><select type="text" class="form-control" ></select><span class="fas form-control-feedback" aria-hidden="true"></span><small id="" class="form-text text-muted"></small></div>';
    protected $temp = [
        'default'=>'<div class="form-group"><label></label><small><span class="text-danger"></span></small>

<div class="col-xs-12 options-dom">
</div>
<span class="fas form-control-feedback" aria-hidden="true"></span></div>'
    ];


    public function __construct(array $option = [])
    {
        $options['attr']['type'] = 'text';
        parent::__construct($option);
    }

    public static function __callStatic($name, $arguments){
        $self = new self();
        return $self->__call($name, $arguments);
    }



    public function options(array $data){
        $this->options['options'] = $data;
        return $this;
    }


    /**
     * @return mixed
     */
    public function out()
    {
        $dom = HtmlDomParser::str_get_html($this->temp['default']);
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
        }
        /*************** 结束 设置说明 *****************/
        /*************** 开始 设置描述与备注 *****************/
        if (empty($this->options['remind'])) {
            $dom->find('small', 0)->outertext = '';
        } else {
            $dom->find('small>span', 0)->innertext = ' * ' . $this->options['remind'];
        }
        /*************** 结束 设置描述 *****************/

        /*************** 开始 设置选项值 *****************/
        $optionsDom = [];
        foreach ($this->options['options'] as $value => $name){
            $selected = $this->options['value'] == $value ? 'checked':'';
            $checkId = md5(uniqid() . microtime());
            $optionsDom[] = "<div class=\"form-check form-check-inline\">
						<input type=\"radio\" value=\"{$value}\" id=\"{$checkId}\" class=\"form-check-input\" {$selected} name='{$this->options['name']}'/>
						<label class=\"form-check-label\" for=\"{$checkId}\">{$name}</label>
					</div>";

        }
        $dom->find('.options-dom', 0)->innertext = join('', $optionsDom);

        /*************** 结束 设置选项值 *****************/

        /*************** 开始 设置数据值及属性 *****************/
//        foreach ($this->data as $k => $v) {
//            $this->options['attr']["data-{$k}"] = $v;
//        }
//        foreach ($this->options['attr'] as $k => $v) {
//            $dom->find('select', 0)->$k = $v;
//        }
//
//        if($this->options['disabled']){
//            $dom->find('select', 0)->disabled = true;
//        }
//        if($this->options['readonly']){
//            $dom->find('select', 0)->readonly = true;
//        }
//        if($this->options['required']){
//            $dom->find('select', 0)->required = true;
//        }
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