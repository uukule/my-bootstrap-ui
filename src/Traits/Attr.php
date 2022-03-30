<?php

namespace uukule\BootstrapUi\Traits;

trait Attr
{


    protected array $attr = [
        'class' => [],
        //'style' => []
    ];

    /**
     * 设置样式
     * @param string $value
     * @param string|null $pre
     * @return $this
     */
    public function addClass(string $value, string $pre = null): self
    {
        array_push($this->attr['class'], (is_null($pre) ? $value : $pre . '-' . $value));
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setAttr(string $name, string $value): self
    {
        $this->attr[$name] = $value;
        return $this;
    }

    protected function filterAttr()
    {
        $this->attr['class'] = array_unique($this->attr['class']);
        $this->attr['class'] = array_filter($this->attr['class']);
    }


    /**
     * 输出属性字符串
     * @return string
     */
    protected function getAttrString(): string
    {
        $this->filterAttr();
        $attr = $this->attr;
        if (empty($attr['class'])) {
            unset($attr['class']);
        } else {
            $attr['class'] = join(' ', $attr['class']);
        }

        $attrArr = [];
        foreach ($attr as $name => $val) {
            $attrArr[] = "{$name}=\"{$val}\"";
        }
        return join(' ', $attrArr);
    }
}
