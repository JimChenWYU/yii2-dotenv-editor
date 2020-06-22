<?php

namespace JimChen\Yii2DotenvEditor;

use yii\base\Exception;

class DotEnvException extends Exception
{
    public function getName()
    {
        return 'DotEnvException';
    }
}
