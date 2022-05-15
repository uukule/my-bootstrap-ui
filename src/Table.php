<?php

namespace uukule\BootstrapUi;

/**
 *
 * @author Mr.zhang
 * @create_time 2018-7-14
 * @method Table striped() 条纹状表格
 * @method Table bordered() 带边框的表格
 * @method Table hover() 鼠标悬停
 * @method Table condensed() 紧缩表格
 */
class Table {

    protected $class = ['table'];

    public function __construct() {
        ;
    }

    public function __call($name, $arguments) {
        switch ($name) {
            case 'striped':
            case 'bordered':
            case 'hover':
            case 'condensed':
                $this->class[] = "table-{$name}";
                return $this;
                break;
            case 'responsive':
                $this->class[] = "responsive";
                return $this;
                break;
        }
    }

    public function tp_data($data) {
        if(isset($data->style)){
            foreach ($data->style as $class) {
                $this->__call($class, []);
            }
        }
        $table_dom = '<table class="%s" id="vm-index-list-table"><thead>%s</thead><tbody>%s</tbody><tfoot><tr><td colspan="%s">%s</td> </tr></tfoot></table> %s';

        $thead_dom = '<tr>';
        $thead_dom .= '<th><input type="checkbox" v-on:click="idsBatch($event)" v-model="batch_checkbox"></th>';
        $ths_str = '';
        foreach ($data->field as $field => $title)
        {
            $ths_str .= "<th data-field='{$field}'>" . (is_array($title) ? array_keys($title)[0] : $title) . "</th>\r\n";
        }
        $thead_dom .= $ths_str;
        $thead_dom .= '</tr>';
        $tbody_dom = [];
        foreach ($data as $row) {
            $tr = '<tr class="'. ($row->style ?? '') .'">'; //active、success、warning、danger、info
            $tr .= '<td><input type="checkbox" v-model="ids" value="' . $row->id . '"></td>';
            foreach ($data->field as $attr => $field_name) {
                $tr .= '<td>';
                $value = $row->$attr;
                if (is_string($field_name)) {
                    $tr .= $value;
                    continue;
                } elseif (is_array($field_name)) {
                    $con = reset($field_name);
                    if (false === stripos($con, '|')) {
                        $tr .= $con($value);
                    } else {
                        list($fun, $arg) = explode('|', $con);
                        $arg = explode(',', $arg);
                        $i = array_search('###', $arg);
                        $arg[$i] = $value;
                        $tr .= call_user_func_array($fun, $arg);
                    }
                }
                $tr .= '</td>';
            }
            $tr .= '</tr>';
            $tbody_dom[] = $tr;
        }
        $tfoot = $data->tfoot ?? '';
        return sprintf($table_dom, join(' ', $this->class), $thead_dom, join('', $tbody_dom),(count($data->field) + 1), $tfoot, $data->render());
    }

    public function exec($data) {
        $table_dom = '<table class="%s"><thead>%s</thead><tbody>%s</tbody><tfoot>%s</tfoot></table>';
        $thead_row = array_shift($data);
        $thead = '<tr>';
        foreach ($thead_row as $th) {
            $thead .= "<th>{$th}</th>";
        }
        $thead .= '</tr>';
        $tbody = [];
        foreach ($data as $row) {
            $tbody[] = '<tr>' . join('',array_map(function($td){
                return "<td>{$td}</td>";
            }, $row)) . '</tr>';
        }
        return sprintf($table_dom, join(' ', $this->class), $thead, join('', $tbody), '');

    }

}
