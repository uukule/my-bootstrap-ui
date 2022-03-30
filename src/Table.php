<?php

namespace uukule\BootstrapUi;

/**
 *
 * @author Mr.zhang
 * @create_time 2018-7-14
 * @method Table striped() 条纹状表格
 * @method Table bordered() 带边框的表格
 * @method Table hover() 鼠标悬停
 * @method Table condensed() 紧缩表格
 */
class Table
{

    protected $table = [
        'thead' => [
            []
        ],
        'tbody' => [],
        'tfoot' => []
    ];

    protected $row = [];

    public function __construct()
    {
    }

    public function thead()
    {

    }

    public function tbody($param)
    {
        if($param instanceof \Closure){

        }

    }

    public function tfoot()
    {

    }

    public function tr()
    {

    }

    public function th()
    {

    }

    public function td($param)
    {
        if(is_string($param)){
            array_push($this->row, $param);
        }
    }

    public function html(){

    }

}
