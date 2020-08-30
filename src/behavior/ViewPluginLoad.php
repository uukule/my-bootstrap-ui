<?php


namespace uukule\BootstrapUi\behavior;


class ViewPluginLoad
{
    static protected $domain = '';

    static protected $view = [
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

    static public $list = [
        'jquery' => [
            'version' => ['1.9.1', '2.0.3', '3.3.1'],
            'head' => 'jquery.min.js'
        ],
        'axios' => [
            'version' => ['0.12.0'],
            'head' => ['axios.min.js']
        ],
        'lodash' => [
            'version' => ['4.13.1'],
            'head' => ['lodash.min.js']
        ],
        'vue' => [
            'version' => ['2.3.3'],
            'head' => 'vue.min.js'
        ],
        'bootstrap' => [
            'version' => ['4.1.3'],
            'head' => 'css/bootstrap.min.css',
            'bottom' => 'js/bootstrap.bundle.min.js'
        ],
        'bootstrap-fileinput' => [
            'version' => ['4.5.0'],
            'head' => ['css/fileinput.css', 'themes/explorer-fa/theme.min.css', 'css/glyphicon.css'],
            'bottom' => [
                'js/fileinput.min.js',
                'themes/explorer-fa/theme.min.js',
                'js/locales/zh.js',
            ]
        ],
        'parsley.js' => [
            'version' => ['2.8.1'],
            'bottom' => ['parsley.min.js', 'parsley.zh-cn.js']
        ],
        'jquery-treegrid' => [
            'version' => ['0.3.0'],
            'head' => ['css/jquery.treegrid.css'],
            'bottom' => ['js/jquery.treegrid.min.js']
        ],
        'summernote' => [
            'note' => '富文本编辑器',
            'version' => ['0.8.16'],
            'head' => 'summernote.min.css',
            'bottom' => ['summernote.min.js', 'theme/color-admin/form-summernote.demo.min.js']
        ],
        'highlight.js' => [
            'version' => ['9.12.0', '10.0.3'],
            'bottom' => ['highlight.js']
        ],
        'jquery-simplecolorpicker' => [
            'version' => ['0.3.1'],
            'head' => ['jquery.simplecolorpicker.css', 'jquery.simplecolorpicker-fontawesome.css', 'jquery.simplecolorpicker-glyphicons.css'],
            'bottom' => ['jquery.simplecolorpicker.js']

        ],
        'bootstrap-datepicker' => [
            'version' => '1.8.0',
            'head' => ['bootstrap-datepicker.min.css', 'bootstrap-datepicker3.min.css'],
            'bottom' => ['bootstrap-datepicker.min.js']
        ],
        'base64.js' => [
            'version' => ['1.1.0'],
            'head' => ['base64.min.js']
        ],
        'eonasdan-bootstrap-datetimepicker' => [
            'version' => ['4.17.47'],
            'head' => 'build/css/bootstrap-datetimepicker.min.css',
            'bottom' => 'build/js/bootstrap-datetimepicker.min.js'
        ]
    ];

    public function run(&$params)
    {
        self::$domain = config('view_libs_cdn');
    }

    public function viewFilter(&$params)
    {
        //判断是否加载验证
        if (false !== stripos($params, 'data-parsley-validate')) {
            self::load_plugin('parsley.js');
        }
        //判断是否需要代码高亮
        if (false !== stripos($params, '</code>')) {
            self::load_plugin('highlight.js', '9.12.0');
        }
        $params = str_replace(
            ['<!--ViewBetweenHead-->', '<!--ViewBetweenBottom-->'],
            [self::$view['head'], self::$view['bottom']],
            $params
        );
    }

    /**
     * 加载插件
     *
     * @param array|string $param 批量加载|插件名称
     * @param string|null $version
     */
    static public function load_plugin($param, string $version = null)
    {
        $domain = config('view_libs_cdn');
        if (is_string($param)) {
            $param = [$param => $version];
        }
        foreach ($param as $plugin => $version) {
            if (is_int($plugin)) {
                $plugin = $version;
                $version = null;
            }
            if (array_key_exists($plugin, self::$loaded_list)) {
                continue;
            }
            if (!array_key_exists($plugin, self::$list)) {
                throw new Exception('插件不存在！');
            }
            if (empty($version)) {
                $version = self::$list[$plugin]['version'][0];
            }
            self::$loaded_list[$plugin] = $version;
            if (array_key_exists('head', self::$list[$plugin])) {
                $file_list = self::$list[$plugin]['head'];
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
            if (array_key_exists('bottom', self::$list[$plugin])) {
                $file_list = self::$list[$plugin]['bottom'];
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

        $domain = config('view_libs_cdn');
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