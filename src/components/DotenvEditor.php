<?php

namespace JimChen\Yii2DotenvEditor\components;

use Yii;
use JimChen\Yii2DotenvEditor\DotEnvException;
use JimChen\Yii2DotenvEditor\models\Backup;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
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

    /**
     * @var int
     */
    public $maxBackup = 10;

    public function init()
    {
        if ($this->env === null) {
            throw new InvalidConfigException('Unknown DotenvEditor::class parameter `env`');
        }
        if ($this->backupPath === null) {
            throw new InvalidConfigException('Unknown DotenvEditor::class parameter `backupPath`');
        }
        $this->env = Yii::getAlias($this->env);
        $this->backupPath = rtrim(Yii::getAlias($this->backupPath), DIRECTORY_SEPARATOR);
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
        $envs = $this->filter($envs);
        foreach ($envs as $key => $value) {
            $array[$c] = new \stdClass();
            $array[$c]->key = (string)$key;
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
        if (file_exists($this->getBackupPath() . $timestamp . "_env")) {
            return $this->getBackupPath() . $timestamp . "_env";
        }
        throw new DotEnvException(Yii::t('dotenv', 'requested_backup_not_found'), 0);
    }

    public function getBackupPath()
    {
        return $this->backupPath . DIRECTORY_SEPARATOR;
    }

    /**
     * Returns an array with all available backups.
     * Array contains the formatted and unformatted version of each backup.
     * Throws exception, if no backups were found.
     *
     * @return Backup[]
     * @throws DotEnvException
     */
    public function getBackupVersions($sort = SORT_ASC)
    {
        $versions = $this->scanBackupDir();

        if (count($versions) > 0) {
            $output = array();
            $count  = 0;
            foreach ($versions as $version) {
                $part                          = explode("_", $version);
                $output[$count] = new Backup([
                    'formatted' => date("Y-m-d H:i:s", (int) $part[0]),
                    'unformatted' => $part[0],
                ]);
                $count++;
            }
            usort($output, function (Backup $a, Backup $b) use ($sort) {
                if ($a->unformatted === $b->unformatted) {
                    return 0;
                }
                if ($sort === SORT_ASC) {
                    return ($a->unformatted < $b->unformatted) ? -1 : 1;
                }
                return ($a->unformatted < $b->unformatted) ? 1 : -1;
            });
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
        if ($this->getBackupCount() >= (int)$this->maxBackup) {
            $oldestBackup = $this->getOldestBackup();
            $this->deleteBackup($oldestBackup->unformatted);
        }

        return copy(
            $this->env,
            $this->getBackupPath() . time() . "_env"
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
        $file = $this->getBackupPath() . $timestamp . "_env";
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
            $file = $this->getFile($this->getLatestBackup()->unformatted);
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
        $file = $this->getBackupPath() . $timestamp . "_env";

        if (file_exists($file)) {
            return $file;
        }

        throw new DotEnvException(Yii::t('dotenv', 'requested_backup_not_found'), 0);
    }

    /**
     * Returns the timestamp of the latest version.
     *
     * @return Backup
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
     * Returns the timestamp of the oldest version.
     *
     * @return Backup
     */
    protected function getOldestBackup()
    {
        $backups      = $this->getBackupVersions();
        $oldestBackup = PHP_INT_MAX;
        foreach ($backups as $backup) {
            if ($backup < $oldestBackup) {
                $oldestBackup = $backup;
            }
        }
        return $oldestBackup;
    }

    /**
     * Counts all backups
     *
     * @return int
     */
    protected function getBackupCount()
    {
        return count($this->scanBackupDir());
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    protected function scanBackupDir()
    {
        if (!file_exists($this->backupPath)) {
            @FileHelper::createDirectory($this->backupPath, 0775, true);
        }

        return array_diff(scandir($this->backupPath), array('..', '.'));
    }

    /**
     * @param array $config
     * @return array
     */
    protected function filter(array $config)
    {
        return array_filter($config, function ($value, $key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_BOTH);
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
