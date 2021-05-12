<?php


namespace uukule\BootstrapUi;

use KubAT\PhpSimple\HtmlDomParser;
/**
 * Class InputItem
 * @property string $title
 * @method InputItem title(string $value)
 * @method InputItem value(string $value)
 * @method InputItem data(string $name, $value)
 * @method InputItem attr(string $name, string $value)
 * @package uukule\BootstrapUi
 */
abstract class InputItem
{
    public $is_inline = false;

    public $attr = [];
    public $data = [];
    public $value = '';
    public $describedby = null;
    public $title = null;

    /**
     * @var string
     */
    public $item_id;

    /**
     * @var array
     */
    public $col = [12, 12, 12, 12];

    public function __construct(array $config = [])
    {
        $this->item_id = md5(microtime());
    }



    public function __set($name, $value)
    {
        switch ($name) {
            case 'title':
                $this->title = $value;
                break;
            default:
                $this->attr[$name] = $value;
                break;
        }
    }

    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this|string
     */
    public function __call($name, $arguments)
    {
        switch ($name) {
            case 'title':
            case 'value':
            case 'describedby':
                $this->$name = $arguments[0];
                return $this;
                break;
            case 'attr':
            case 'data':
                $this->$name[$arguments[0]] = $arguments[1];
                return $this;
                break;
            default:
                break;
        }
    }


    public function col(int $xs = 12, int $sm = null, int $md = null, int $lg = null)
    {
        $sm = $sm ?? $xs;
        $md = $md ?? $sm;
        $lg = $lg ?? $md;
        $this->col = [$xs, $sm, $md, $lg];
        return $this;
    }


    public function out()
    {
        $dom = HtmlDomParser::str_get_html($this->inTemp);
        /*************** 开始 设置栅格单位 *****************/
        list($xs, $sm, $md, $lg) = $this->col;
        $c_col = " col-xs-{$xs} col-sm-{$sm} col-md-{$md} col-lg-{$lg}";
        $dom->find('.form-group', 0)->class .=  $c_col;
        /*************** 结束 设置栅格单位 *****************/

        /*************** 开始 设置说明 *****************/
        if(empty($this->title)){
            $dom->find('label',0)->outertext = '';
        }else{
            $dom->find('label',0)->innertext = $this->title;
            $dom->find('label',0)->for = $this->item_id;
            $dom->find('input',0)->id = $this->item_id;
        }
        /*************** 结束 设置说明 *****************/
        /*************** 开始 设置描述 *****************/
        if(empty($this->describedby)){
            $dom->find('small',0)->outertext = '';
        }else{
            $did = md5(uniqid() . microtime());
            $dom->find('small',0)->innertext = $this->describedby;
            $dom->find('input',0)->setAttribute('aria-describedby', $did);
            $dom->find('small',0)->id = $did;
        }
        /*************** 结束 设置描述 *****************/
        /*************** 开始 设置数据值及属性 *****************/
        $this->attr['value'] = $this->value;
        foreach ($this->data as $k => $v){
            $this->attr["data-{$k}"] = $v;
        }
        foreach ($this->attr as $k=> $v){
            $dom->find('input',0)->$k = $v;
        }

        /*************** 结束 设置数据值及属性 *****************/

        return $dom;
    }
}