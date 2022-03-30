<?php


namespace uukule\BootstrapUi;


class Raw
{

    protected string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function __toString()
    {
        return $this->content;
    }
}
