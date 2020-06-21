<?php

namespace JimChen\Yii2DotenvEditor\components;

use Yii;
use yii\base\Component;
use JimChen\Yii2DotenvEditor\DotEnvException;
use yii\helpers\Json;

/**
 * Class DotenvEditor
 * @mixin \sixlive\DotenvEditor\DotenvEditor
 */
final class DotenvEditor extends Component
{
	/**
	 * @var \sixlive\DotenvEditor\DotenvEditor
	 */
	private $editor;

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

	public function init()
	{
		$this->env = Yii::getAlias($this->env);
		$this->backupPath = Yii::getAlias($this->backupPath);
		$this->editor = new \sixlive\DotenvEditor\DotenvEditor();
		$this->editor->load($this->env);
	}

	/**
	 * @param int|null $timestamp
	 * @return array|string
	 * @throws DotEnvException
	 */
	public function getContent($timestamp = null)
	{
		if ($timestamp === null) {
			return $this->editor->getEnv();
		}

		$editor = new \sixlive\DotenvEditor\DotenvEditor();
		$editor->load($this->getBackupFile($timestamp));
		return $editor->getEnv();
	}

	/**
	 * Returns the given .env as JSON array containing all entries as object
	 * with key and value
	 *
	 * @param null $timestamp timestamp
	 *
	 * @return string
	 */
	public function getAsJson($timestamp = null)
	{
		$array = [];
		$c     = 0;
		$envs = $this->getContent($timestamp);
		foreach ($envs as $key => $value) {
			$array[$c] = new \stdClass();
			$array[$c]->key = $key;
			$array[$c]->value = $value;
			$c++;
		}

		return Json::encode($array);
	}

	/**
	 * Returns filename and path for the given timestamp
	 *
	 * @param null $timestamp timestamp
	 *
	 * @return string
	 * @throws DotEnvException
	 */
	public function getBackupFile($timestamp)
	{
		if (file_exists($this->backupPath . $timestamp . "_env")) {
			return $this->backupPath . $timestamp . "_env";
		}
		throw new DotEnvException(Yii::t('dotenv', 'requested_backup_not_found'), 0);
	}

	/**
	 * Returns an array with all available backups.
	 * Array contains the formatted and unformatted version of each backup.
	 * Throws exception, if no backups were found.
	 *
	 * @return array
	 * @throws DotEnvException
	 */
	public function getBackupVersions()
	{
		$versions = array_diff(scandir($this->backupPath), array('..', '.'));

		if (count($versions) > 0) {
			$output = array();
			$count  = 0;
			foreach ($versions as $version) {
				$part                          = explode("_", $version);
				$output[$count]['formatted']   = date("Y-m-d H:i:s", (int) $part[0]);
				$output[$count]['unformatted'] = $part[0];
				$count++;
			}
			return $output;
		}
		throw new DotEnvException(Yii::t('dotenv', 'no_backups_available'), 0);
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	public function save($path = '')
	{
		if ($this->autoBackup) {
			$this->createBackup();
		}

		return $this->editor->save($path);
	}

	/**
	 * Used to create a backup of the current .env.
	 * Will be assigned with the current timestamp.
	 *
	 * @return bool
	 */
	public function createBackup()
	{
		return copy(
			$this->env,
			$this->backupPath . time() . "_env"
		);
	}

	/**
	 * Delete the given backup-file
	 *
	 * @param null $timestamp timestamp
	 *
	 * @return void
	 * @throws DotEnvException
	 */
	public function deleteBackup($timestamp)
	{
		$file = $this->backupPath . $timestamp . "_env";
		if (file_exists($file)) {
			unlink($file);
		} else {
			throw new DotEnvException(Yii::t('dotenv', 'backup_not_deletable'), 0);
		}
	}

	/**
	 * Restores the latest backup or a backup from a given timestamp.
	 * Restores the latest version when no timestamp is given.
	 *
	 * @param null $timestamp timestamp
	 *
	 * @return string
	 */
	public function restoreBackup($timestamp = null)
	{
		$file = null;
		if ($timestamp !== null) {
			if ($this->getFile($timestamp)) {
				$file = $this->getFile($timestamp);
			}
		} else {
			$file = $this->getFile($this->getLatestBackup()['unformatted']);
		}

		return copy($file, $this->env);
	}

	/**
	 * Returns the file for the given backup-timestamp
	 *
	 * @param null $timestamp timestamp
	 *
	 * @return string
	 * @throws DotEnvException
	 */
	protected function getFile($timestamp)
	{
		$file = $this->backupPath . $timestamp . "_env";

		if (file_exists($file)) {
			return $file;
		} else {
			throw new DotEnvException(Yii::t('dotenv', 'requested_backup_not_found'), 0);
		}

	}

	/**
	 * Returns the timestamp of the latest version.
	 *
	 * @return int|mixed
	 */
	protected function getLatestBackup()
	{
		$backups      = $this->getBackupVersions();
		$latestBackup = 0;
		foreach ($backups as $backup) {
			if ($backup > $latestBackup) {
				$latestBackup = $backup;
			}
		}
		return $latestBackup;
	}

	/**
	 * @param string $method
	 * @param array  $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if (method_exists($this->editor, $method)) {
			return call_user_func([$this->editor, $method], ...$parameters);
		}

		return parent::__call($method, $parameters);
	}
}
