<?php

namespace uukule\BootstrapUi\Middleware;

use uukule\BootstrapUi\Core\Plugin;

class ViewPluginMiddleware
{

    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);
        if (is_null($response)) {
            return null;
        }
        $content = $response->getContent();


        //判断是否加载验证
        if (false !== stripos($content, 'data-parsley-validate')) {
            Plugin::load_plugin('parsley.js');
        }
        //判断是否需要代码高亮
        if (false !== stripos($content, '</code>')) {
            Plugin::load_plugin('highlight.js', '9.12.0');
        }
        $content =  str_replace(
            ['<!--ViewPluginHead-->', '<!--ViewBetweenBottom-->'],
            [Plugin::$view['head'], Plugin::$view['bottom']],
            $content
        );
        $response->setContent($content);
        return $response;
    }
}
