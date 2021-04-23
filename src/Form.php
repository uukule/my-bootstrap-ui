<?php

namespace uukule\BootstrapUi;

/**
 *
 * @author Mr.zhang
 * @create_time 2018-7-27
 * @method string password() 输出邮件表单
 * @method string email() 输出邮件表单
 * @method string number() 输出邮件表单
 * @method string url() 输出邮件表单
 * @method Form min(int $number) 最小值
 * @method Form max(int $number) 最大值
 */
class Form
{

    protected $remind = '';
    protected $type;
    protected $attr = [
        'data' => [],
    ];
    protected $col = 12;
    protected $value = null;
    protected $box;
    protected $label = '';
    protected $options = [];
    protected $class = [];

    public function __construct()
    {
        $this->attr('id', md5(microtime()));
        $this->col(12);
    }

    public function start($url = '', $method = 'get')
    {
        switch ($method) {
            case 'put':
                $dom = "<form action=\"{$url}\" method=\"post\">";
                $_method = 'PUT';
                $dom .= sprintf('<input type="hidden" name="_method" value="%s" >', $_method);
                break;
            default :
                $dom = "<form action=\"{$url}\" method=\"{$method}\">";
                break;
        }
        return $dom;
    }

    public function end()
    {
        return '</form>';
    }

    public function package($config = [])
    {
        $dom = '';
        foreach ($config as $name => $row) {
            //判断HTML代码片段
            if (is_string($row) && '<' === $row[0]) {
                $dom .= $row;
                continue;
            }

            $self = new self();
            if(array_key_exists('type', $row)){
                $type = $row['type'];
                unset($row['type']);
            }else{
                $type = 'text';
            }
            if (!array_key_exists('name', $row))
                $row['name'] = $name;
            foreach ($row as $name => $value) {
                if(!is_array($value)){
                    $value = [$value];
                }
                if('options' === $name){
                    $value = [$value];
                }
                call_user_func_array([$self, $name], $value);
            }
            $dom .= $self->$type();
        }
        return $dom;
    }

    public function options(array $options)
    {
        $this->options = $options;
        return $this;
    }

    public function __call($name, $arguments)
    {
        switch ($name) {
            //类型
            case 'success':
            case 'warning':
            case 'error':
                $this->class['style'] = "has-{$name}";
                return $this;
                break;
            case 'text':
            case 'password':
            case 'email':
            case 'number':
            case 'float':
            case 'url':
            case 'date':
            case 'range':
            case 'color':
            case 'hidden':
            case 'datetime':
            case 'html':
            case 'select':
            case 'group_select':
            case 'multiple_select':
            case 'textarea':
            case 'upload':
            case 'checkbox_switch':
            case 'range_datepicker':
            case 'upload_multiple':
                return call_user_func_array([$this, 'exec'], array_merge([$name], $arguments));
                break;
            case 'min':
            case 'max':
            case 'step':
                $this->data($name, $arguments[0]);
            default :
                try {
                    return $this->attr($name, $arguments[0]);
                } catch (\Exception $e) {
                    echo $name;
                    exit();
                }

                break;
        }
    }

    public function name(string $value)
    {
        $this->attr['name'] = $value;
        $data = request()->param();
        if (is_null($this->value)) {
            $this->value = $data[$value] ?? '';
        }
        return $this;
    }

    public function attr($name, $value = null)
    {
        if (is_null($value)) {
            return $this->attr[$name] ?? null;
        } else {
            $this->attr[$name] = $value;
            return $this;
        }
    }

    public function title($value)
    {
        $dom = '<label  class="control-label" for="%s">%s</label>';
        $this->label = sprintf($dom, $this->attr['id'], $value);
        return $this;
    }

    public function value($value = null)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 设置备注信息
     * @param string $info 备注内容
     * @return \ui\driver\bootstrap\Form
     */
    public function remind(string $info)
    {
        $dom = '<small><span class="text-danger "> * %s</span></small>';
        $this->remind = sprintf($dom, $info);
        return $this;
    }

    public function range($value = null)
    {
        $this->data('type', 'ionRangeSlider');
        $this->before_exec($value);
        $this->attr('value', $this->value);
        $dom = sprintf('<input class="form-control" %s />', $this->join_attr());
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }
    public function color($value = null)
    {
        load_plugin('jquery-simplecolorpicker');
        $this->data('type', 'simplecolorpicker');
        $this->before_exec($value);
        $options = $this->options;
        if(empty($options)){
            $options = [
                '#2d353c' => 'Black',
                '#f0f3f4' => 'Silver',
                '#b6c2c9' => 'Grey',
                '#727cb6' => 'Purple',
                '#348fe2' => 'Primary',
                '#49b6d6' => 'Aqua',
                '#00acac' => 'Green',
                '#90ca4b' => 'Lime',
                '#f59c1a' => 'Orange',
                '#ffd900' => 'Yellow',
                '#ff5b57' => 'Red',
            ];
        }
        $option_dom = '';
        foreach ($options as $_val => $name) {
            $selected = $_val === $this->value ? 'selected="selected "' : '';
            $option_dom .= sprintf('<option value="%s" %s>%s</option>', $_val, $selected, $name);
        }
        $dom = '<div class="input-group"><select class="form-control" %s>%s</select></div>';
        $dom = sprintf($dom, $this->join_attr(), $option_dom);
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function data($name, $value = null)
    {
        if (is_null($value) && !is_array($name)) {
            return $this->attr['data'][$name] ?? null;
        } elseif (is_array($name)) {
            $this->attr['data'] = array_merge($this->attr['data'], $name);
        } else {
            $this->attr['data'][$name] = $value;
        }
        return $this;
    }

    public function box_class(string $value)
    {
        if (!in_array($value, $this->class)) {
            array_push($this->class, $value);
        }
        return $this;
    }

    public function box()
    {
        $class = [];
        $class[] = 'form-group';
        if (is_numeric($this->col)) {
            $class[] = 'col-md-' . $this->col;
        } elseif (is_array($this->col)) {
            $class[] = 'col-xs-' . $this->col[0];
            $class[] = 'col-sm-' . $this->col[1];
            $class[] = 'col-md-' . $this->col[2];
            $class[] = 'col-lg-' . $this->col[3];
        }
        $class = array_merge($class, $this->class);
        $class = join(' ', $class);
        $dom = "<div class=\"{$class}\"> %s %s %s</div>";
        $this->box = $dom;
        return $this;
    }

    /**
     * 设置AJAX参数
     * @param string $url 发送地址
     * @param string $method 发送方式 GET|POST|PUT|DELETE
     * @return \ui\driver\bootstrap\Form
     */
    public function ajax(string $url, string $method = 'GET'): Form
    {
        $this->data('ajax', $url);
        $this->data('method', $method);
        return $this;
    }

    public function exec($type, $value = null)
    {
        switch ($type) {
            case 'number':
            case 'password':
            case 'email':
            case 'url':
            case 'date':
            case 'float';
            case 'color':
            case 'hidden':
                $this->attr('type', $type);
                return $this->common($value);
                break;
            case 'range':
            case 'html':
            case 'select':
            case 'group_select':
            case 'multiple_select':
            case 'textarea':
            case 'upload':
            case 'checkbox_switch':
            case 'upload_multiple':
            case 'range_datepicker':
            case 'text':
                return $this->$type($value);
                break;
        }
    }

    public function text(string $value = null)
    {
        $this->before_exec($value);
        $this->attr('value', $this->value);
        $datalist = $this->attr('datalist');
        if (!is_null($datalist) && is_array($datalist)) {
            $_id = "input-id-" . rand(0, 999999999);
            $this->attr('list', $_id);
            $dom = sprintf('<input class="form-control" %s />', $this->join_attr());
            foreach ($datalist as &$vo) {
                $vo = "<option value=\"{$vo}\">";
            }
            $datalistOptionsDom = join('', $datalist);
            $datalistDom = "<datalist id=\"{$_id}\">{$datalistOptionsDom}</datalist>";
            $dom .= $datalistDom;
        } else {
            $dom = sprintf('<input class="form-control" %s />', $this->join_attr());
        }


        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function col(int $xs = 12, int $sm = null, int $md = null, int $lg = null)
    {
        $sm = $sm ?? $xs;
        $md = $md ?? $sm;
        $lg = $lg ?? $md;
        $this->col = [$xs, $sm, $md, $lg];
        return $this;
    }

    public function range_datepicker(array $datetime = [])
    {
        $this->before_exec($datetime);
        $this->attr('value', $this->value);
        $dom = sprintf('<input class="form-control" %s />', $this->join_attr());
        $name = $this->attr['name'];
        $dom = "<div class=\"input-group input-daterange\" data-type='input-daterange'>
											<input type=\"text\" class=\"form-control\" name=\"{$name}[]\" placeholder=\"开始时间\" />
											<span class=\"input-group-addon\">to</span>
											<input type=\"text\" class=\"form-control\" name=\"{$name}[]\" placeholder=\"结束时间\" />
										</div>";
        echo sprintf($this->box, $this->label, $this->remind, $dom);
    }

    /**
     * 通用类型表单
     * @return type
     */
    public function common(string $value = null): string
    {
        $this->before_exec($value);
        $this->attr('value', $this->value);
        $dom = sprintf('<input class="form-control" %s />', $this->join_attr());
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    /**
     * 隐藏类型表单
     * @return string
     */
    public function hidden(string $value = null): string
    {
        $this->before_exec($value);
        if ($this->value) {
            $this->attr('value', $this->value);
        }
        $dom = '<input type="hidden" %s  />';
        return sprintf($dom, $this->join_attr());
    }

    /**
     * 文本框类型表单
     * @return string
     */
    public function textarea(string $value = null): string
    {
        $this->before_exec($value);
        $this->attr('rows', 6);
        $dom = '<textarea class="form-control" %s>%s</textarea>';
        $dom = sprintf($dom, $this->join_attr(), $this->value);
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    /**
     * SWITCH开关
     * @return string
     */
    public function checkbox_switch(bool $value = null): string
    {
        $domId = 'unique-id-' . str_replace([' ', '.'], '-', microtime());
        $this->before_exec($value);
        $this->attr('type', 'checkbox');
        $this->data('type', 'bootstrapSwitch');
        $name = $this->attr['name'];
        unset($this->attr['name'], $this->attr['id']);
        $this->data('for-id', $domId);
        $this->data('for-name', $name);
        $this->data('no-uniform', 'true');
        $value = (bool)$this->value;
        if ($value) {

            $this->attr('checked', 'checked');
        }
        $dom = '<div ><input type="hidden" id="%s"   name="%s" value="%s" ><input %s></div>';
        $dom = sprintf($dom, $domId, $name, (int)$value, $this->join_attr());
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function upload(string $value = null): string
    {
        load_plugin('bootstrap-fileinput');
        $this->before_exec($value);
        if (!$this->data('upload-url')) {
            $this->data('upload-url', \think\Config::get('storage.global.url'));
        }
        $name = $this->attr['name'];
        $value = $this->value;
        $upload_url = $this->data('upload-url');
        $ext = $this->data('ext') ? "data-ext='{$this->data('ext')}'" : '';
        $id_name = str_replace('.', '_', $name);
        $dom = "
    <ul class='nav nav-pills' style='clear: both'>
        <li class='nav-items'>
            <a href='#view-upload-{$id_name}' data-toggle='tab' class=\"nav-link active\">
                <span class=\"d-sm-none\">文件上传</span>
                <span class=\"d-sm-block d-none\">文件上传</span>
            </a>
        </li>
        <li class='nav-items'>
            <a href='#view-picurl-{$id_name}' data-toggle='tab' class=\"nav-link\">
                <span class=\"d-sm-none\">文件URL提交</span>
                <span class=\"d-sm-block d-none\">文件URL提交</span>
            </a>
        </li>
    </ul>
    <div class='tab-content' style='clear: both;padding: 0'>
        <div class='tab-pane fade active show' id='view-upload-{$id_name}'>
            <br />
            <input type='file' id='js-fileinput' data-type='bootstrap-fileinput' title='请上传文件' class='file' value='{$value}' data-upload-url='{$upload_url}' {$ext} to-name='{$name}' />
        </div>
        <div class='tab-pane fade' id='view-picurl-{$id_name}'>
            <label class='control-label'>请填写文件的网址:</label>
            <input class='form-control' type='text' id='js-fileinput-val' value='{$value}' name='{$name}' />
        </div>
    </div>";

        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function upload_multiple(array $value = null): string
    {
        load_plugin(['bootstrap-fileinput', 'base64.js']);
        $this->before_exec($value);
        $this->remind('图片选择完请再次点上传!');
        if (!$this->data('upload-url')) {
            $this->data('upload-url', \think\Config::get('storage.url'));
        }
        $name = $this->attr['name'];
        $value = is_array($this->value) ? $this->value : [];
        $upload_url = $this->data('upload-url');
        $doc = file_get_contents(__DIR__ . '/template/fileinput_multiple.html');
        $replace = [
            '{__name__}' => $this->attr['name'],
            '{__value__}' => json_encode($value, 256),
            '{__upload-url__}' => $this->data('upload-url'),
            '{__id__}' => 'id-' . md5(microtime() . rand(0,999999)),
        ];
        $dom = str_replace(array_keys($replace), array_values($replace), $doc);

        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function select($value = null)
    {
        $this->before_exec($value);
        $options = $this->options;
        $option_dom = '';
        foreach ($options as $value => $name) {
            $selected = $value === $this->value ? 'selected="selected "' : '';
            $option_dom .= sprintf('<option value="%s" %s>%s</option>', $value, $selected, $name);
        }
        $dom = '<select class="form-control" %s>%s</select>';
        $dom = sprintf($dom, $this->join_attr(), $option_dom);
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    /**
     * Group Select
     * 调用chosen插件
     * 列表格式
     * {"115":{"value":115,"name":"产品中心"},···,"120":{"value":120,"name":"新闻资讯","sub":{"121":{"value":121,"name":"资讯A",···},"122":{"value":122,"name":"资讯B"}}}}
     * @return string
     */
    public function group_select(array $value = null): string
    {
        $this->before_exec($value);
        $options = $this->options;
        $this->data('rel', 'chosen');
        if ($this->attr('placeholder')) {
            $this->data('placeholder', $this->attr('placeholder'));
        }
        $option_dom = '';
        foreach ($options as $vo) {

            if (isset($vo['sub'])) {
                $option_dom .= "<optgroup label=\"{$vo['name']}\">";
                foreach ($vo['sub'] as $_vo) {
                    $selected = $_vo['value'] == $this->value ? 'selected="selected "' : '';
                    $option_dom .= sprintf('<option value="%s" %s>%s</option>', $_vo['value'], $selected, $_vo['name']);
                }
                $option_dom .= "</optgroup>";
            } else {
                $selected = $vo['value'] == $this->value ? 'selected="selected "' : '';
                $option_dom .= sprintf('<option value="%s" %s>%s</option>', $vo['value'], $selected, $vo['name']);
            }
        }
        $dom = '<div class="controls"><select class="form-control" %s ><option value=""></option> %s </select></div>';
        $dom = sprintf($dom, $this->join_attr(), $option_dom);
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function multiple_select(array $value = []): string
    {
        if (empty($value)) {
            $value = $this->value;
        }

        $this->before_exec($value);

        $attrName = $this->attr('name');
        $this->attr('name', "{$attrName}[]");
        if (empty($value)) {
            $value = [];
        } elseif (is_string($value)) {
            $value = explode(',', $value);
        }
        $options = $this->options;
        $options_dom = '';
        foreach ($options as $val => $name) {
            $selected = in_array($val, $value) ? 'selected' : '';
            $options_dom .= sprintf('<option %s value="%s">%s</option>', $selected, $val, $name);
        }
        $this->data('rel', 'chosen');
        $this->attr('multiple', '');
        $dom = '<div class="controls"><select data-placeholder="请选择" class="form-control" %s >%s</select></div>';

        $dom = sprintf($dom, $this->join_attr(), $options_dom);
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function html(string $value = null): string
    {
        load_plugin('summernote');
        $this->before_exec($value);
        $this->data('type', 'summernote');
        //$this->data('summernote', 'true');
        if (!$this->data('image-upload-url')) {
            $this->data('image-upload-url', config('storage.image')['url']);
        }
        $dom = '<textarea class="summernote"  %s >%s</textarea>';
        $dom = sprintf($dom, $this->join_attr(), $this->value);
        return $dom;
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function date(string $value = null): string
    {
        $this->data('format', 'YYYY-MM-DD');
        return $this->datetime($value);
    }

    public function time(string $value = null): string
    {
        $this->data('format', 'HH:mm:ss');
        return $this->datetime($value);
    }

    public function datetime(string $value = null): string
    {
        load_plugin('eonasdan-bootstrap-datetimepicker');
        $this->before_exec($value);
        if (!$this->data('format')) {
            $this->data('format', 'YYYY-MM-DD HH:mm:ss');
        }
        if ($this->value) {
            $this->attr('value', $this->value);
        }
        $dom = '<div class="input-group date" data-type="datetimepicker">
											<input type="text" class="form-control" %s />
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div></div>';
        $dom = sprintf($dom, $this->join_attr());
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    /**
     * 多选框
     * @param type $value
     * @return string
     */
    public function checkbox(array $value = null): string
    {
        $this->before_exec($value);
        $options = $this->options;
        $remind = $this->remind . '<br>';
        $value = is_array($this->value) ? $this->value : [];
        $name = $this->attr['name'];
        $li_dom = '<label class="checkbox-inline" style="margin-right: 10px"><input type="checkbox" name="%s[]" %s value="%s"> %s </label>';
        $dom = '<div class="input-group m-t-10">';
        foreach ($options as $v => $n) {
            $checked = in_array($v, $value) ? ' checked="checked" ' : '';
            $dom .= sprintf($li_dom, $name, $checked, $v, $n);
        }
        $dom .= '</div>';
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    public function radio(string $value = null): string
    {
        $this->before_exec($value);
        $options = $this->options;
        $remind = $this->remind . '<br>';
        $value = is_array($this->value) ? $this->value : [];
        $name = $this->attr['name'];
        $li_dom = '<label class="checkbox-inline" style="margin-right: 10px;"><input type="radio" name="%s" %s value="%s"> %s </label>';
        $dom = '';
        foreach ($options as $v => $n) {
            $checked = in_array($v, $value) ? ' checked="checked" ' : '';
            $dom .= sprintf($li_dom, $name, $checked, $v, $n);
        }
        $dom = "<div style=\"clear:both;margin-top: 10px;\">{$dom}</div>";
        return sprintf($this->box, $this->label, $this->remind, $dom);
    }

    private function join_attr()
    {
        $attr = $this->attr;
        if (array_key_exists('data', $attr)) {
            $data = $attr['data'];
            unset($attr['data']);
            foreach ($data as $name => $value) {
                $attr["data-{$name}"] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            }
        }
        $_arr = [];
        try {
            foreach ($attr as $name => $value) {
                if (is_array($value) || is_object($value)) {
                    continue;
                }
                $_arr[] = "{$name}=\"{$value}\"";
            }
        } catch (\Exception $e) {
            dump($attr);
        }
        return join(' ', $_arr);
    }

    protected function before_exec($value)
    {
        $this->box();
        if (!is_null($value)) {
            $this->value = $value;
        } elseif (!isset($this->value) && in_array('name', $this->attr)) {
            $this->value = request()->param($this->attr['name'], '');
        }
    }

}
