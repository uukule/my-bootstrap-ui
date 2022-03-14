<?php


namespace uukule\BootstrapUi\form;


use uukule\BootstrapUi\InputItem;
use KubAT\PhpSimple\HtmlDomParser;
use function GuzzleHttp\debug_resource;

/**
 * Class Text
 * @property bool $feedback
 * @package uukule\BootstrapUi\form
 */
class Month extends Text
{
    protected $inTemp = '<div class="form-group" ><label></label><small><span class="text-danger"></span></small><div class="input-group date" data-type="datetimepicker"><input type="text" class="form-control" ><div class="input-group-addon"><i class="fa fa-calendar"></i></div></div><span class="fas form-control-feedback" aria-hidden="true"></span><small id="emailHelp" class="form-text text-muted"></small></div>';

    public function __construct(array $option = [])
    {
        parent::__construct($option);

        load_plugin('bootstrap-daterangepicker');
        load_plugin('eonasdan-bootstrap-datetimepicker');
        $this->options['attr']['type'] = 'text';
        $this->data('format', 'YYYY年MM月');
        //$this->attr('type', 'YYYY-MM-DD');

    }

}