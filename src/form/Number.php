<?php


namespace uukule\BootstrapUi\form;


use uukule\BootstrapUi\InputItem;
use KubAT\PhpSimple\HtmlDomParser;
use function GuzzleHttp\debug_resource;

/**
 * Class Text
 * @property bool $feedback
 * @method self min(int $value)
 * @method self max(int $value)
 * @method self step(int $value)
 * @package uukule\BootstrapUi\form
 */
class Number extends InputItem
{
    protected $inTemp = '<div class="form-group"><label></label><input type="text" class="form-control" ><span class="fas form-control-feedback" aria-hidden="true"></span><small id="emailHelp" class="form-text text-muted"></small></div>';

    public function __construct(array $option = [])
    {
        $this->options['attr']['type'] = 'number';
        $this->options['feedback'] = false;
        parent::__construct($option);
    }



    /**
     *
     * @return $this
     */
    public function feedback(){
        $this->options['feedback'] = true;
        return $this;
    }

}