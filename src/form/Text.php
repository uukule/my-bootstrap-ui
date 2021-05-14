<?php


namespace uukule\BootstrapUi\form;


use uukule\BootstrapUi\InputItem;
use KubAT\PhpSimple\HtmlDomParser;

/**
 * Class Text
 * @property bool $feedback
 * @package uukule\BootstrapUi\form
 */
class Text extends InputItem
{
    protected $inTemp = '<div class="form-group"><label></label><input type="text" class="form-control" ><span class="fas form-control-feedback" aria-hidden="true"></span><small id="emailHelp" class="form-text text-muted"></small></div>';

    public function __construct(array $option = [])
    {
        $this->options['attr']['type'] = 'text';
        $this->options['feedback'] = false;
        parent::__construct($option);
    }

}