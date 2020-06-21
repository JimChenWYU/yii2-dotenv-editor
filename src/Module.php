<?php

namespace JimChen\Yii2DotenvEditor;

use Yii;
use yii\web\Application;
use yii\base\BootstrapInterface;
use JimChen\Yii2DotenvEditor\components\DotenvEditor;

class Module extends \yii\base\Module implements BootstrapInterface
{
	public $controllerNamespace = 'JimChen\Yii2DotenvEditor\controllers';

	/**
	 * the path of .env
	 *
	 * @var string
	 */
	public $env;

	/**
	 * @var string
	 */
	public $backupPath;

	/**
	 * @var bool
	 */
	public $autoBackup = true;


	/**
	 * @inheritDoc
	 */
	public function bootstrap($app)
	{
		Yii::$app->set('dotenveditor', [
			'class' => DotenvEditor::class,
			'env' => $this->env,
			'backupPath' => $this->backupPath,
			'autoBackup' => $this->autoBackup,
		]);

		if ($app instanceof Application) {
			$app->getUrlManager()->addRules($this->urlRules(), false);
		}
	}

	/**
	 * @return array
	 */
	protected function urlRules()
	{
		return [
			['class' => 'yii\web\UrlRule', 'pattern' => $this->id, 'route' => $this->id . '/default/index'],
			['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/default/deletebackup/<timestamp:\d+>', 'route' => $this->id . '/default/deletebackup'],
			['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/default/restore/<backuptimestamp:\d+>', 'route' => $this->id . '/default/restore'],
			['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/default/download/<filename:\w+>', 'route' => $this->id . '/default/download'],
			['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/default/getdetails/<timestamp:[\d\-]+>', 'route' => $this->id . '/default/getdetails'],
			['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>', 'route' => $this->id . '/<controller>/<action>'],
		];
	}
}
