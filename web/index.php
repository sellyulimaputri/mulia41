<?php

defined("YII_DEBUG") or define("YII_DEBUG", true);
defined("YII_ENV") or define("YII_ENV", "dev");

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../vendor/yiisoft/yii2/Yii.php";

// Alias keras ke bootstrap5
if (!class_exists("yii\\bootstrap\\Alert")) {
    class_alias("yii\\bootstrap5\\Alert", "yii\\bootstrap\\Alert");
}
if (!class_exists("yii\\bootstrap\\Html")) {
    class_alias("yii\\bootstrap5\\Html", "yii\\bootstrap\\Html");
}
if (!class_exists("yii\\bootstrap\\Modal")) {
    class_alias("yii\\bootstrap5\\Modal", "yii\\bootstrap\\Modal");
}
if (!class_exists("yii\\bootstrap\\ActiveForm")) {
    class_alias("yii\\bootstrap5\\ActiveForm", "yii\\bootstrap\\ActiveForm");
}
if (!class_exists("yii\\bootstrap\\BootstrapAsset")) {
    class_alias(
        "yii\\bootstrap5\\BootstrapAsset",
        "yii\\bootstrap\\BootstrapAsset"
    );
}

$config = require __DIR__ . "/../config/web.php";

(new yii\web\Application($config))->run();
