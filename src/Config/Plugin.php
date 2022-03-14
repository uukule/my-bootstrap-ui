<?php

namespace uukule\BootstrapUi\Config;

class Plugin {


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

    public $list = [
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
        'bootstrap-table' => [
            'version' => ['1.18.3'],
            'head' => ['dist/bootstrap-table.min.css'],
            'bottom' => [
                'dist/bootstrap-table.min.js',
                'dist/extensions/treegrid/bootstrap-table-treegrid.min.js'
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
}
