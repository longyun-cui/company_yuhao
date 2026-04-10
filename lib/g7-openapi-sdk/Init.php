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
include_once 'Psr4AutoLoader.php';
$psr = new Psr4AutoLoader();
$psr->addNamespace('OpenApiSDK\\Http','Http');
$psr->addNamespace('OpenApiSDK\\Constant','Constant');
$psr->addNamespace('OpenApiSDK\\Util','Util');
$psr->register();