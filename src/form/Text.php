<?php


namespace uukule\BootstrapUi\form;


use uukule\BootstrapUi\InputItem;
use KubAT\PhpSimple\HtmlDomParser;

/**
 * Class Text
 * @package uukule\BootstrapUi\form
 */
class Text extends InputItem
{
    protected $inTemp = '  <div class="form-group"><label></label><input type="text" class="form-control" ><small id="emailHelp" class="form-text text-muted"></small></div>';

    public function __construct(array $config = [])
    {
        $this->attr['type'] = 'text';
        parent::__construct($config);
    }

    public function __get($name)
    {
        switch ($name){
            case 'plaintext':
                return (string) $this->out();
        }
        return parent::__get($name);
    }


}