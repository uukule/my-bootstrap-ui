<?php

namespace uukule\BootstrapUi;

/**
 *
 * @author Mr.zhang
 * @create_time 2018-11-8
 * @
 */
class Box {

    protected $status = 'show';
    protected $title = '';
    protected $icon = null;
    protected $col = 'col-md-12';
    protected $box_icon = '';
    protected $display = '';
    protected $form = false;



    /**
     * 输出显示标签
     * @return string 显示渲染输出
     */
    public function show() {
        $this->box_icon .= '<a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>';
        return $this->fetch();
    }

    /**
     * 设置TITLE
     * @param string $title
     * @return $this
     */
    public function title(string $title){
        $this->title = $title;
        return $this;
    }

    public function form(){
        $this->form = true;
    }

    /**
     * 输出隐藏标签
     * @return string 显示渲染输出
     */
    public function hide() {
        $this->box_icon .= '<a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-down"></i></a>';
        $this->display = 'display: none;';
        return $this->fetch();
    }

    private function fetch() {
        $doc = file_get_contents(__DIR__ . '/template/box.html');
        $replace = [
            '{__col__}' => $this->col,
            '{__title__}' => $this->title,
            '{__icon__}' => $this->icon,
            '{__box-icon__}' => $this->box_icon,
            '{__rand__}' => rand(10000, 99999),
            '{__display__}' => $this->display,
            '{__form__}' => $this->form ? 'panel-form' : ''
        ];

        return str_replace(array_keys($replace), array_values($replace), $doc);
    }


    public function icon($icon) {
        $this->icon = sprintf('<i class="glyphicon glyphicon-%s"></i>', $icon);
        return $this;
    }

    /**
     * 闭合盒子
     * @return string
     */
    static public function end() {
        return '</div> </div>';
    }

}
