<h1 align="center"> yii2-dotenv-editor </h1>

<p align="center"> A Yii2 package for editing the .env file dynamically.</p>


## Installing

```shell
$ composer require jimchen/yii2-dotenv-editor -vvv
```

## Usage

```php
[   
    'modules' => [
        'dotenv' => [
            'class' => 'JimChen\Yii2DotenvEditor\Module',
            'dotenvOptions' => [
                'env' => '@app/.env',
                'backupPath' => '@runtime/backups',
                'autoBackup' => true,
                'maxBackup'  => 10,
            ],
        ],           
    ],
];
```

Then you can open your Browser surface `http://host:port/dotenv`. Enjoy it!

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/JimChenWYU/yii2-dotenv-editor/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/JimChenWYU/yii2-dotenv-editor/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT