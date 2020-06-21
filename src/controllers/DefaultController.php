<?php

namespace JimChen\Yii2DotenvEditor\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use JimChen\Yii2DotenvEditor\DotEnvException;

class DefaultController extends Controller
{
	public $layout = 'main';

	/**
	 * @var \JimChen\Yii2DotenvEditor\components\DotenvEditor
	 */
	protected $editor;

	public function init()
	{
		$this->editor = \Yii::$app->get('dotenveditor');
	}

	/**
	 * Shows the overview, where you can visually edit your .env-file
	 */
	public function actionIndex()
	{
		$data['values'] = $this->editor->getContent();
		try {
			$data['backups'] = $this->editor->getBackupVersions();
		} catch (DotEnvException $e) {
			$data['backups'] = false;
		}
		$data['url'] = Yii::$app->request->getPathInfo();
		return $this->render('index', $data);
	}

	/**
	 * Returns the content as JSON
	 *
	 * @param null $timestamp
	 */
	public function actionGetdetails($timestamp = null)
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		Yii::$app->response->content = $this->editor->getAsJson($timestamp);
	}
	
	/**
	 * Adds a new entry to your .env-file.
	 */
	public function actionAdd()
	{
		$key = Yii::$app->request->post('key');
		$value = Yii::$app->request->post('value');
		$this->editor->set($key, $value);
		$this->editor->save();

		return $this->json();
	}

	/**
	 * Updates the given entry from your .env.
	 */
	public function actionUpdate()
	{
		$key = Yii::$app->request->post('key');
		$value = Yii::$app->request->post('value');
		$this->editor->set($key, $value);
		$this->editor->save();

		return $this->json();
	}

	/**
	 * Deletes the given entry from your .env-file
	 *
	 * @return void
	 */
	public function actionDelete()
	{
		$key = Yii::$app->request->post('key');
		$this->editor->unset($key);
		$this->editor->save();
	}

	/**
	 * Lets you download the choosen backup-file.
	 *
	 * @param bool $filename filename
	 */
	public function actionDownload($filename = false)
	{
		if ($filename) {
			$file = $this->editor->backupPath . $filename . '_env';
			return $this->download($file, $filename . '.env');
		}
		return $this->download($this->editor->env, '.env');
	}

	/**
	 * Upload a .env-file and replace the current one
	 *
	 */
	public function actionUpload()
	{
		/** @var UploadedFile $file */
		$file = UploadedFile::getInstanceByName('backup');
		$file->saveAs($this->editor->env);
		return $this->redirect('default');
	}

	/**
	 * Creates a backup of the current .env.
	 */
	public function actionCreatebackup()
	{
		$this->editor->createBackup();
		Yii::$app->session->setFlash(
			'dotenv',
			Yii::t('dotenv', 'controller_backup_created')
		);
		return $this->goBack();
	}

	/**
	 * Delete Backup
	 *
	 * @param string $timestamp timestamp
	 */
	public function actionDeletebackup($timestamp)
	{
		$this->editor->deleteBackup($timestamp);
		Yii::$app->session->setFlash(
			'dotenv',
			Yii::t('dotenv', 'controller_backup_deleted')
		);
		return $this->goBack();
	}

	/**
	 * Restore a backup
	 *
	 * @param void $backuptimestamp backuptimestamp
	 */
	public function actionRestore($backuptimestamp)
	{
		$this->editor->restoreBackup($backuptimestamp);
		return $this->redirect('default');
	}

	/**
	 * @param array $data
	 */
	private function json(array $data = [])
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		Yii::$app->response->data = $data;
	}

	/**
	 * @param string $filePath
	 * @param string $attachmentName
	 * @param array  $options
	 */
	private function download($filePath, $attachmentName = null, $options = [])
	{
		Yii::$app->response->sendFile($filePath, $attachmentName, $options);
	}
}
