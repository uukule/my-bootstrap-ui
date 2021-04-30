<?php


namespace uukule\BootstrapUi;

use KubAT\PhpSimple\HtmlDomParser;
/**
 * Class InputItem
 * @property string $title
 * @method InputItem title(string $value)
 * @package uukule\BootstrapUi
 */
abstract class InputItem
{
    public $is_inline = false;

    public $attr = [];
    public $data = [];

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

    public function __call($name, $arguments)
    {
        switch ($name) {
            default:
                if (empty($arguments[0])) {
                    return $this->title;
                } else {
                    $this->title = $arguments[0];
                    return $this;
                }
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
        /*************** 开始 设置数据值及属性 *****************/
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