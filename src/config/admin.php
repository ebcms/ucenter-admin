<?php

use DigPHP\Router\Router;
use Ebcms\Framework\Framework;

return [
    'menus' => Framework::execute(function (
        Router $router
    ): array {
        $res = [];
        $res[] = [
            'title' => '用户管理',
            'url' => $router->build('/ebcms/ucenter-admin/user/index'),
            'tags' => ['menu'],
            'priority' => 30,
            'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg t="1611840588490" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1408" width="20" height="20"><path d="M215.325613 304.432432c0 168.821622 132.843243 304.432432 296.12973 304.432433s296.12973-136.994595 296.129729-304.432433S674.741829 0 511.455343 0 215.325613 136.994595 215.325613 304.432432z" fill="#007bff" p-id="1409"></path><path d="M796.514802 600.562162c-23.524324-19.372973-56.735135-16.605405-78.875676 5.535135-53.967568 55.351351-127.308108 89.945946-208.951351 89.945946s-154.983784-34.594595-208.951351-89.945946c-22.140541-22.140541-55.351351-26.291892-78.875676-5.535135C130.914802 679.437838 64.493181 780.454054 42.35264 920.216216 34.049937 976.951351 72.795883 1024 129.531018 1024h765.232433c55.351351 0 95.481081-47.048649 87.178378-103.783784-24.908108-139.762162-91.32973-240.778378-185.427027-319.654054z" fill="#007bff" p-id="1410"></path></svg>'),
        ];
        $res[] = [
            'title' => '用户消息',
            'url' => $router->build('/ebcms/ucenter-admin/message/index'),
            'priority' => 30,
            'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="1024" height="1024" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" xmlns:se="http://svg-edit.googlecode.com" class="icon"><g class="layer"><title>Layer 1</title><g id="svg_3"><path class="selected" d="m76.21947,230.9546c1.34507,1.5599 2.75222,3.05097 4.24213,4.51911l338.74956,333.77227a139.67987,154.8428 0 0 0 185.57766,0l338.74956,-333.77227c1.48992,-1.46814 2.89706,-2.95922 4.22143,-4.49618c0.49664,3.99151 0.74496,8.07477 0.74496,12.22685l0,537.56833a82.77326,91.75869 0 0 1 -82.77326,91.75869l-707.46305,0a82.77326,91.75869 0 0 1 -82.77326,-91.75869l0,-537.56833c0,-3.07391 0.14485,-6.10196 0.41387,-9.10704l0.31039,-3.14274z" data-spm-anchor-id="a313x.7781069.0.i0" fill="#279CFF" id="svg_1"/><path d="m566.98213,476.75321l330.16184,-325.28459l-770.28795,0l330.16184,325.28459a82.77326,91.75869 0 0 0 109.96427,0z" data-spm-anchor-id="a313x.7781069.0.i1" fill="#279CFF" fill-opacity="0.5" id="svg_2"/></g></g></svg>'),
        ];
        return $res;
    })
];
