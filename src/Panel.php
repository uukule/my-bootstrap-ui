<?php


namespace uukule\BootstrapUi;

/**
 * Class Panel
 * @package ui\driver\bootstrap
 * @method Panel primary() 情境 primary
 * @method Panel success() 情境 success
 * @method Panel info() 情境 info
 * @method Panel warning() 情境 warning
 * @method Panel danger() 情境 danger
 */
class Panel
{

    protected static $is_col = [];
    protected $sign;

    /**
     * @var bool|\file_get_html|null
     */
    protected $panel = null;


    public function __construct($content = null)
    {
        $this->sign = rand(100000, 999999);
        self::$is_col[$this->sign] = false;
        if ('/' === $content) {
            return self::end();
        }
        $panel = file_get_html(__DIR__ . '/template/panel.html');
        $panel->find('.panel', 0)->setAttribute('data-sortable-id', 'ui-widget-' . $this->sign);
        $this->panel = $panel;
        return $this;
    }

    public function __call($name, $arguments)
    {
        switch ($name) {
            case 'success':
            case 'inverse':
            case 'warning':
            case 'primary':
            case 'danger':
            case 'info':
                $this->panel->find('.panel', 0)->addClass('panel-' . $name);
                break;
        }
        return $this;
    }

    public function addClass(string $class, string $position = '.panel')
    {
        $this->panel->find($position, 0)->addClass($class);
        return $this;
    }


    public function col(int $xs = 12, int $sm = null, int $md = null, int $lg = null)
    {
        self::$is_col[$this->sign] = true;
        $sm = $sm ?? $xs;
        $md = $md ?? $sm;
        $lg = $lg ?? $md;
        $panel = str_get_html('<div>' . $this->fetch() . '</div>');
        $panel->find('div', 0)->addClass("col-xs-{$xs}");
        $panel->find('div', 0)->addClass("col-sm-{$sm}");
        $panel->find('div', 0)->addClass("col-md-{$md}");
        $panel->find('div', 0)->addClass("col-lg-{$lg}");
        $this->panel = $panel;
        return $this;
    }


    /**
     * 设置TITLE
     * @param string $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->panel->find('h4', 0)->innertext = $this->panel->find('h4', 0)->innertext . $title;
        return $this;
    }

    public function form()
    {
        $this->panel->find('.panel-body', 0)->addClass('panel-form');
        return $this;
    }

    /**
     * 输出显示标签
     * @return string 显示渲染输出
     */
    public function show()
    {
        $dom = $this->fetch();
        $dom = mb_substr($dom, 0, mb_strrpos($dom, '</div>'));
        $dom = mb_substr($dom, 0, mb_strrpos($dom, '</div>'));
        if (self::$is_col[$this->sign]) {
            $dom = mb_substr($dom, 0, mb_strrpos($dom, '</div>'));
        }
        return $dom;
    }

    /**
     * 输出隐藏标签
     * @return string 显示渲染输出
     */
    public function hide()
    {
        $this->panel->find('.panel-body', 0)->style = 'display: none;';
        $this->panel->find('i.fa-minus', 0)->class = 'fa fa-plus';

        $dom = $this->fetch();

        $dom = mb_substr($dom, 0, mb_strrpos($dom, '</div>'));
        $dom = mb_substr($dom, 0, mb_strrpos($dom, '</div>'));
        if (self::$is_col[$this->sign]) {
            $dom = mb_substr($dom, 0, mb_strrpos($dom, '</div>'));
        }
        return $dom;
    }

    private function fetch()
    {
        $str = $this->panel->save();
        $this->panel->clear();
        return $str;
    }


    public function icon($icon)
    {
        $i = "<i class='{$icon}'></i> ";
        $this->panel->find('h4', 0)->innertext = $i . $this->panel->find('h4', 0)->innertext;
        return $this;
    }

    /**
     * 闭合盒子
     * @return string
     */
    static public function end()
    {
        $re = '</div> </div>';
        array_pop(self::$is_col);
        if (array_pop(self::$is_col)) {
            $re .= '</div>';
        }
        return $re;
    }


}