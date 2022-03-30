<?php


namespace uukule\BootstrapUi\Table;


use uukule\BootstrapUi\Traits\Attr;
use uukule\BootstrapUi\Traits\StatusStyle;

class Tr
{

    use Attr;
    use StatusStyle;

    protected  $statusStylePreOpen = null;

    protected $style = [];

    protected $row = [];


    public function __construct($param=null)
    {
        if(is_null($param)){
            return;
        }
        if(!is_array($param)){
            $param = [$param];
        }
        foreach ($param as $_param){

            if(is_string($_param)){
                array_push($this->row, new Td($_param));
            }elseif($_param instanceof Td){
                array_push($this->row, $_param);
            }elseif($_param instanceof Th){
                array_push($this->row, $_param);
            }elseif ($_param instanceof \Closure){
                $td = new Td();
                $_param($td);
                array_push($this->row, $td);
            }
        }
        return $this;
    }

    public function th($param)
    {
        if(is_string($param)){
            array_push($this->row, new Th($param));
        }elseif($param instanceof Th){
            array_push($this->row, $param);
        }elseif ($param instanceof \Closure){
            $th = new Th();
            $param($th);
            array_push($this->row, $th);
        }
        return $this;
    }

    public function td($param)
    {
        if(is_string($param)){
            array_push($this->row, new Td($param));
        }elseif($param instanceof Td){
            array_push($this->row, $param);
        }elseif ($param instanceof \Closure){
            $th = new Td();
            $param($th);
            array_push($this->row, $th);
        }
        return $this;
    }

    public function column():int
    {
        return count($this->row);
    }

    public function html():string
    {
        $html = sprintf("\r\n<tr %s>", $this->getAttrString());
        /** @var Th $tr */
        foreach ($this->row as $dom){
            $html .= $dom->html();
        }
        $html .= '</tr>';
        return $html;
    }
}
