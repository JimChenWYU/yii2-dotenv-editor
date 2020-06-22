<?php

namespace JimChen\Yii2DotenvEditor;

use yii\web\AssetBundle;

class VueAsset extends AssetBundle
{
    public $sourcePath = '@bower/vue/dist';
    public $js = [
        'vue.js',
    ];
}
