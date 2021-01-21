<?php

/**
 * @var \CMain                         $APPLICATION
 * @var \original_simpleshop\base\Shop $shop
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

\CJSCore::Init(array("jquery"));

spl_autoload_register(function ($className) {
    if (strpos($className, 'original_simpleshop') === 0) {
        $path = str_replace(['original_simpleshop', '\\'], [__DIR__, '/'], $className) . '.php';

        if (file_exists($path)) {
            require_once $path;
        }
    }
});

require_once __DIR__ . '/install/index.php';