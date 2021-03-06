<?php

namespace JimChen\Yii2DotenvEditor;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\i18n\PhpMessageSource;
use yii\web\Application;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'JimChen\Yii2DotenvEditor\controllers';

	/**
	 * ```example
	 * [
	 *      'env' => Yii::getAlias('@app/.env'),
	 *      'backupPath' => Yii::getAlias('@app/backupPath'),
	 *      'autoBackup' => true,
	 * ]
	 * ```
	 *
	 * @var array
	 */
	public $dotenvOptions;

	/**
	 * the layout that should be applied for views within this module.
	 *
	 * @var string
	 */
	public $layout = 'main';

	/**
	 * @var string
	 */
	public $entry = 'index';

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        $this->createDotenvEditor();
		$this->createTranslator();
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
            ['class' => 'yii\web\UrlRule', 'verb' => 'GET', 'pattern' => $this->id, 'route' => $this->id . '/default/index'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'POST', 'pattern' => $this->id . '/add', 'route' => $this->id . '/default/add'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'POST', 'pattern' => $this->id . '/update', 'route' => $this->id . '/default/update'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'GET', 'pattern' => $this->id . '/createbackup', 'route' => $this->id . '/default/createbackup'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'GET', 'pattern' => $this->id . '/deletebackup/<timestamp:\d+>', 'route' => $this->id . '/default/deletebackup'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'GET', 'pattern' => $this->id . '/restore/<backuptimestamp:\d+>', 'route' => $this->id . '/default/restore'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'POST', 'pattern' => $this->id . '/delete', 'route' => $this->id . '/default/delete'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'GET', 'pattern' => $this->id . '/download/<filename:\d+>', 'route' => $this->id . '/default/download'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'GET', 'pattern' => $this->id . '/download', 'route' => $this->id . '/default/download'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'POST', 'pattern' => $this->id . '/upload', 'route' => $this->id . '/default/upload'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'GET', 'pattern' => $this->id . '/getdetails/<timestamp:\d+>', 'route' => $this->id . '/default/getdetails'],
            ['class' => 'yii\web\UrlRule', 'verb' => 'GET', 'pattern' => $this->id . '/getdetails', 'route' => $this->id . '/default/getdetails'],
            ['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>', 'route' => $this->id . '/<controller>/<action>'],
        ];
    }

	/**
	 * @throws InvalidConfigException
	 */
	protected function createDotenvEditor()
	{
		$this->dotenvOptions['class'] = 'JimChen\Yii2DotenvEditor\components\DotenvEditor';
		Yii::$app->set('dotenveditor', $this->dotenvOptions);
    }

	public function createTranslator()
	{
		Yii::$app->getI18n()->translations['dotenv'] = [
			'class' => PhpMessageSource::class,
			'basePath' => __DIR__ . '/messages',
			'forceTranslation' => true,
		];
    }
}
