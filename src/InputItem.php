<?php


namespace uukule\BootstrapUi;

/**
 * required 属性适用于以下 <input> 类型：text, search, url, telephone, email, password, date pickers, number, checkbox, radio 以及 file。
 */

use KubAT\PhpSimple\HtmlDomParser;

/**
 * Class InputItem
 * @property string $title 标题
 * @property string $value 值
 * @property string $describedby 描述
 * @property int|array $col 栅格
 * @property-write string $status 样式
 * @property-write string $size 尺寸
 * @method $this xs() 超小尺寸
 * @method $this sm() 小尺寸
 * @method $this md() 中等尺寸
 * @method $this lg() 大尺寸
 * @method $this size(string $value) 尺寸
 * @method $this before(string $value) 在前面追加
 * @method $this after(string $value) 在后面追加
 * @method $this success() Input with success
 * @method $this warning() Input with warning
 * @method $this info() Input with info
 * @method $this error() Input with error
 * @method $this status(string $value) 样式
 * @method $this title(string $value)
 * @method $this value(string|array $value)
 * @method $this placeholder(string $value)
 * @method $this maxlength(int $value)
 * @method $this name(string $value)
 * @method $this pattern(string $value)
 * @package uukule\BootstrapUi
 */
abstract class InputItem
{
    public $is_inline = false;

    public $inputAttrArr = ['max', 'maxlength', 'min', 'multiple', 'name', 'pattern', 'placeholder', 'required', 'size', 'step', 'type'];

    public $options = [
        'attr' => [],
        'data' => [],
        'value' => '',
        'name'  => '',
        'placeholder' => '',
        'describedby' => null,
        'title' => null,
        'col' => [12, 12, 12, 12],
        'size' => 'md',
        'status' => 'info',
        'required' => false, //是否必须
        'disabled' => false, //是否禁用
        'readonly' => false, //只读
        'before' => null,
        'after'  => null,
    ];

    /**
     * @var string
     */
    public $item_id;

    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            foreach ($options as $name => $value) {
                $this->__call($name, [$value]);
            }
        }
        $this->item_id = md5(microtime());
    }


    public function __set($name, $value)
    {
        return $this->__call($name, [$value]);
    }

    public function __get($name)
    {
        return $this->options[$name];
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
            case 'name':
            case 'describedby':
            case 'placeholder':
            case 'before':
            case 'after':
                $this->options[$name] = $arguments[0];
                break;
            case 'xs':
            case 'sm':
            case 'md':
            case 'lg':
                $this->options['size'] = $name;
                break;
            case 'success':
            case 'warning':
            case 'error':
                $this->options['status'] = $name;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * @param string|array $arguments
     * @param string|int $value
     * @return InputItem
     */
    public function data($arguments, $value = null): InputItem
    {
        if(is_array($arguments)){
            $this->options['data'] = array_merge($this->options['data'], $arguments);
        }else{
            $this->options['data'][$arguments] = $value;
        }
        return $this;
    }

    /**
     * @param string|array $arguments
     * @param string|int $value
     * @return InputItem
     */
    public function attr($arguments, $value = null): InputItem
    {
        if(is_array($arguments)){
            $this->options['attr'] = array_merge($this->options['data'], $arguments);
        }else{
            $this->options['attr'][$arguments] = $value;
        }
        return $this;
    }

    /**
     * 设置禁用状态
     * @param bool $value
     * @return $this
     */
    public function disabled(bool $value = true)
    {
        $this->options['disabled'] = $value;
        return $this;
    }

    /**
     * 设置禁用
     * @param bool $value
     * @return $this
     */
    public function readonly(bool $value = true)
    {
        $this->options['readonly'] = $value;
        return $this;
    }

    /**
     * 设置必须
     * @param bool $value
     * @return $this
     */
    public function required(bool $value = true)
    {
        $this->options['required'] = $value;
        return $this;
    }


    /**
     * @param int|array $xs
     * @param int|null $sm
     * @param int|null $md
     * @param int|null $lg
     * @return $this
     */
    public function col($xs = 12, int $sm = null, int $md = null, int $lg = null)
    {
        if(is_array($xs)){
            $arg = $xs;
            $xs = $arg[0];
            $sm = $arg[1] ?? $xs;
            $md = $arg[2] ?? $sm;
            $lg = $arg[3] ?? $md;
        }else{
            $sm = $sm ?? $xs;
            $md = $md ?? $sm;
            $lg = $lg ?? $md;
        }
        $this->options['col'] = [$xs, $sm, $md, $lg];
        return $this;
    }

    public function remind(string $info)
    {
        $this->options['remind'] = $info;
        return $this;
    }

    public function show()
    {
        echo $this->out();
    }
}