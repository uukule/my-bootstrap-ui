<?php


namespace uukule\BootstrapUi\Table;


use uukule\BootstrapUi\Traits\Attr;
use uukule\BootstrapUi\Traits\StatusStyle;

class Table
{

    use Attr;


    protected string $pre = 'table-';

    protected int $maxColumn = 0;

    protected array $table = [
        'thead' =>[],
        'tbody' => [],
        'tfoot' => []
    ];



    protected string $content;

    public function __construct()
    {
        $this->addClass('table');
    }



    public function __set($name, $value)
    {
        $this->content = $value;
    }

    public function thead($param)
    {

        return $this->tr('thead', $param);
    }

    public function tbody($param)
    {
        return $this->tr('tbody', $param);
    }

    public function tfoot($param){
        return $this->tr('tfoot', $param);
    }

    public function tr($type, $params){
        if(!is_array($params)){
            $params = [$params];
        }
        foreach ($params as $param){
            $tr = null;
            if(is_string($param)){
                $tr = new Tr($param);
            }elseif($param instanceof Tr){
                $tr=$param;
            }elseif ($param instanceof \Closure){
                $tr = new Tr();
                $param($tr);
            }
            array_push($this->table[$type], $tr);
            if(($tr->column() <=> $this->maxColumn) === 1){
                $this->maxColumn = $tr->column();
            }
        }
        return $this;
    }

    /**
     * 鼠标悬停样式
     * 每一行对鼠标悬停状态作出响应
     * @return Table
     */
    public function hover(){
        return $this->addClass('hover', 'table');
    }

    /**
     * 条纹状表格
     * 每一行增加斑马条纹样式
     * @return Table
     */
    public function striped(){
        return $this->addClass('striped', 'table');
    }
    /**
     * 带边框的表格
     * 为表格和其中的每个单元格增加边框
     * @return Table
     */
    public function bordered(){
        return $this->addClass('bordered', 'table');
    }
    /**
     * 紧凑型表格
     * 让表格更加紧凑，单元格中的内补（padding）均会减半。
     * @return Table
     */
    public function condensed(){
        return $this->addClass('condensed', 'table');
    }


    public function html():string {
        $dom = sprintf('<table %s>', $this->getAttrString());
        foreach ($this->table as $type =>$item){
            $dom .= "\r\n<{$type}>";
            /** @var Tr $tr */
            foreach ($item as $tr){
                $dom .= $tr->html();
            }
            $dom .= "\r\n</{$type}>";
        }
        $dom .= '</table>';
        return $dom;
    }
}
