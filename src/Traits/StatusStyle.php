<?php


namespace uukule\BootstrapUi\Traits;


trait StatusStyle
{


    /**
     * 鼠标悬停在行或单元格上时所设置的颜色
     * @return $this
     */
    public function active(): self
    {
        return $this->addClass('active', $this->statusStylePreOpen);
    }

    /**
     * 标识成功或积极的样式
     * @return $this
     */
    public function success(): self
    {
        return $this->addClass('success', $this->statusStylePreOpen);
    }

    /**
     * 标识普通的提示信息或动作
     * @return $this
     */
    public function info(): self
    {
        return $this->addClass('info', $this->statusStylePreOpen);
    }

    /**
     * 标识警告或需要用户注意
     * @return $this
     */
    public function warning(): self
    {
        return $this->addClass('warning', $this->statusStylePreOpen);
    }

    /**
     * 标识危险或潜在的带来负面影响的动作
     * @return $this
     */
    public function danger(): self
    {
        return $this->addClass('danger', $this->statusStylePreOpen);
    }

    /**
     * 默认样式
     * @return $this
     */
    public function default(): self
    {
        return $this->addClass('default', $this->statusStylePreOpen);
    }

    /**
     * 首选项
     * @return $this
     */
    public function primary(): self
    {
        return $this->addClass('primary', $this->statusStylePreOpen);
    }

    /**
     * 链接（按钮用）
     *
     * @return $this
     */
    public function link(): self
    {
        return $this->addClass('link', $this->statusStylePreOpen);
    }


}
