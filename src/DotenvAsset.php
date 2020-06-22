<?php

namespace JimChen\Yii2DotenvEditor;

use yii\web\AssetBundle;

class DotenvAsset extends AssetBundle
{
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'JimChen\Yii2DotenvEditor\VueAsset',
        'JimChen\Yii2DotenvEditor\FontawesomeAsset',
    ];
}
