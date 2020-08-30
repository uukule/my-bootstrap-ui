<?php


/**
 * UI助手函数
 * @return \UI
 */

if (!function_exists('ui')) {
    function ui()
    {
        \uukule\BootstrapUi\behavior\ViewPluginLoad::form();
        return new \uukule\BootstrapUi\Main();
    }
}


/**
 * 加载插件
 *
 * @param array|string $param 批量加载|插件名称
 * @param string|null $version
 */
if (!function_exists('load_plugin')) {
    function load_plugin($param, string $version = null)
    {
        \uukule\BootstrapUi\behavior\ViewPluginLoad::load_plugin($param, $version);
    }
}