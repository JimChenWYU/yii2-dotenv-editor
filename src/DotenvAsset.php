<?php

namespace JimChen\Yii2DotenvEditor;

use yii\web\AssetBundle;

class DotenvAsset extends AssetBundle
{
	public $sourcePath = '@vendor/jimchen/yii2-dotenv-editor/assets';

	public $depends = [
		'yii\web\YiiAsset',
		'JimChen\Yii2DotenvEditor\VueAsset',
	];
}
