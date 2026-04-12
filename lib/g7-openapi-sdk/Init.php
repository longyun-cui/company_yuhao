<?php
/**
 * Copyright (c) 2017 Chinaway ltd.
 *     Developed By Team-Public
 *
 * PHP Version 7.1
 *
 * @author chenhaibin <chenhaibin@huoyunren.com>
 * @since  2017/11/16 下午3:35
 */
require_once __DIR__ .  '/Psr4AutoLoader.php';

//include_once 'Psr4AutoLoader.php';
$psr = new Psr4AutoLoader();
$psr->addNamespace('OpenApiSDK\\Http',__DIR__ . '/Http');
$psr->addNamespace('OpenApiSDK\\Constant',__DIR__ . '/Constant');
$psr->addNamespace('OpenApiSDK\\Util',__DIR__ . '/Util');
$psr->register();