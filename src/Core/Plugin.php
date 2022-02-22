<?php


namespace uukule\BootstrapUi\Core;


use \Exception;

class Plugin
{
    static protected $domain = '';

    static public $view = [
        'head' => '',
        'bottom' => ''
    ];
    /**
     * 已加载插件列表
     * @var array
     */
    static protected $loaded_list = [
        'jquery' => '',
        'bootstrap' => '',
        'vue' => '',
        'axios' => '',
        'lodash' => '',
    ];


    public function __construct()
    {
        self::$domain = config('view_plugin.libs_cdn');
    }


    /**
     * 加载插件
     *
     * @param array|string $param 批量加载|插件名称
     * @param string|null $version
     */
    static public function load_plugin($param, string $version = null)
    {
        $domain =  config('view_plugin.libs_cdn');
        if (is_string($param)) {
            $param = [$param => $version];
        }
        $list = (new \uukule\BootstrapUi\Config\Plugin())->list;
        foreach ($param as $plugin => $version) {
            if (is_int($plugin)) {
                $plugin = $version;
                $version = null;
            }
            if (array_key_exists($plugin, self::$loaded_list)) {
                continue;
            }
            if (!array_key_exists($plugin, $list)) {
                throw new Exception('插件不存在！');
            }
            if (empty($version)) {
                $version = $list[$plugin]['version'][0];
            }
            self::$loaded_list[$plugin] = $version;
            if (array_key_exists('head', $list[$plugin])) {
                $file_list = $list[$plugin]['head'];
                if (is_string($file_list)) {
                    $file = $domain . '/' . $plugin . '/' . $version . '/' . $file_list;

                    self::$view['head'] .= self::include_file($file);
                } elseif (is_array($file_list)) {
                    foreach ($file_list as $filepath) {
                        $file = $domain . '/' . $plugin . '/' . $version . '/' . $filepath;
                        self::$view['head'] .= self::include_file($file);
                    }
                }
            }
            if (array_key_exists('bottom', $list[$plugin])) {
                $file_list = $list[$plugin]['bottom'];
                if (is_string($file_list)) {
                    $file = $domain . '/' . $plugin . '/' . $version . '/' . $file_list;
                    self::$view['bottom'] .= self::include_file($file);
                } elseif (is_array($file_list)) {
                    foreach ($file_list as $filepath) {
                        $file = $domain . '/' . $plugin . '/' . $version . '/' . $filepath;
                        self::$view['bottom'] .= self::include_file($file);
                    }
                }
            }
        }

    }

    static public function form()
    {

        if (array_key_exists('form', self::$loaded_list)) {
            return;
        }
        self::$loaded_list['form'] = true;

        $domain = config('view.libs_cdn');
        $head_file = [
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/ionRangeSlider/css/ion.rangeSlider.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/ionRangeSlider/css/ion.rangeSlider.skinNice.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/password-indicator/css/password-indicator.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-combobox/css/bootstrap-combobox.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-select/bootstrap-select.min.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/jquery-tag-it/css/jquery.tagit.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-daterangepicker/daterangepicker.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/select2/dist/css/select2.min.css',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css',
        ];
        foreach ($head_file as $file) {
            self::$view['head'] .= self::include_file($file);
        }
        $bottom_file = [
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/masked-input/masked-input.min.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/password-indicator/js/password-indicator.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-select/bootstrap-select.min.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/jquery-tag-it/js/tag-it.min.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-daterangepicker/moment.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-daterangepicker/daterangepicker.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/select2/dist/js/select2.min.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-show-password/bootstrap-show-password.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js',
            $domain . '/color-admin/4.2-BS4/assets/plugins/clipboard/clipboard.min.js',
        ];
        foreach ($bottom_file as $file) {
            self::$view['bottom'] .= self::include_file($file);
        }

        //self::$view['bottom'] .= '<script>$(document).ready(function () {FormPlugins.init();});</script>';
    }

    static public function list()
    {

    }

    static public function include_file(string $file)
    {
        $ext = strrchr($file, '.');
        switch ($ext) {
            case '.css':
                return "    <link href=\"{$file}\" rel=\"stylesheet\"/>\r\n";
                break;
            case '.js':
                return "    <script src=\"{$file}\"></script>\r\n";
                break;
        }

    }

}
