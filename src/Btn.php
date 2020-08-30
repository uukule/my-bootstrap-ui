<?php


namespace uukule\BootstrapUi;


/**
 *
 * @author Mr.zhang
 * @create_time 2018-7-14
 * @method Btn lg() 大按钮
 * @method Btn sm() 小按钮
 * @method Btn default() 默认样式
 * @method Btn primary() 首选项
 * @method Btn success() 成功
 * @method Btn info() 一般信息
 * @method Btn warning() 警告
 * @method Btn danger() 危险
 * @method Btn link() 链接
 */
class Btn {

    public $class = [
        'btn',
        'site' => '',
        'style' => 'btn-default'
    ];
    public $href;
    public $disabled = false;
    public $attr = [];
    public $content = '';
    protected $type = 'button';
    protected $css = [];

    public function __call($name, $arguments) {
        switch ($name) {
            //尺寸
            case 'lg':
            case 'sm':
            case 'xs':
                $this->class['site'] = "btn-{$name}";
                return $this;
                break;
            case 'default':
            case 'primary':
            case 'success':
            case 'info':
            case 'warning':
            case 'danger':
            case 'link':
                $this->class['style'] = "btn-{$name}";
                return $this;
                break;
            default :
                return $this->attr($name, $arguments[0]);
        }
    }

    public function css(string $name,string  $value) {
        $this->css[$name] = $value;
        return $this;
    }

    public function attr(string $name,string  $value) {
        if (is_array($value)) {
            $value = join(' ', $value);
        }
        $this->attr[$name] = $value;
        return $this;
    }

    public function href(string $link) {
        $this->href = $link;
        return $this;
    }

    /**
     * @param string $url
     * @param array|string $vars = ''
     * @return $this
     */
    public function url(string $url, $vars = ''){
        $this->href = url($url, $vars);
        return $this;
    }

    public function setClass($value){
        $this->class[] = $value;
        return $this;
    }

    public function block() {
        $this->class[] = 'btn-block';
        return $this;
    }

    /**
     * 激活状态
     * @param type $this
     */
    public function active() {
        $this->class[] = 'active';
        return $this;
    }

    /**
     * 禁用状态
     * @param type $this
     */
    public function disabled() {
        $this->disabled = true;
        return $this;
    }

    public function content(string $content) {
        $this->content .= $content;
        return $this;
    }

    public function icon(string $icon, string $style = 'white') {
        $this->content = "<i class=\"fas fa-{$icon} icon-{$style}\"></i> " . $this->content;
        return $this;
    }

    /**
     * 设置按钮类型
     * @param type $value
     * @return $this
     */
    public function type($value) {
        $this->type = $value;
        return $this;
    }

    public function value($value) {
        $this->attr('value', $value);
        return $this;
    }

    public function data($name, $value = false) {
        if (false === $value) {
            return $this->attr['data'][$name] ?? false;
        } elseif (is_array($name)) {
            $this->attr['data'] = array_merge($this->attr['data'], $name);
        } else {
            $this->attr['data'][$name] = $value;
        }
        return $this;
    }

    public function show() : string
    {
        $dom = "\r\n";
        if (is_null($this->href)) {
            $dom .= "<button type=\"{$this->type}\" ";
            if ($this->disabled)
                $this->attr('disabled', 'disabled');
        }else {
            $dom .= "<a href=\"{$this->href}\"  role=\"button\" ";
            if ($this->disabled)
                $this->class[] = 'disabled';
        }
        $style = '';
        foreach ($this->css as $n => $v) {
            $style .= "{$n}:{$v};";
        }
        if (!empty($style)) {
            $dom .= " style=\"{$style}\" ";
        }


        $class = join(' ', $this->class);
        $attr = [];

        if (array_key_exists('data', $this->attr)) {
            $data = $this->attr['data'];
            unset($this->attr['data']);
            foreach ($data as $name => $value) {
                if (is_array($value)) {
                    $this->attr["data-{$name}"] = str_replace('"', '||||', json_encode($value, JSON_UNESCAPED_UNICODE));
                } else {
                    $this->attr["data-{$name}"] = $value;
                }
            }
        }
        foreach ($this->attr as $name => $value) {
            $attr[] = "{$name}=\"{$value}\"";
        }
        $attr = join(' ', $attr);
        $dom .= " class=\"{$class}\" {$attr} > {$this->content}";
        $dom .= is_null($this->href) ? '</button>' : '</a>';
        return $dom;
    }

    public function exec() {
        return $this->show();
    }

}
