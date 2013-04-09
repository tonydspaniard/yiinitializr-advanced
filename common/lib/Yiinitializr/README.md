Yiinitializr
============

Library that will help boost your application installation with ease and also to run Yii applications from its
bootstrap files on a much cleaner way that the framework currently proposes. For example:

```
// yii has been installed via composer
require('./../app/lib/vendor/yiisoft/yii/framework/yii.php');

// set alias for namespacing 
// make sure the path is correct as it can also be installed via composer (see above)
Yii::setPathOfAlias('Yiinitializr', './../app/lib/Yiinitializr');

// use its initializr
use Yiinitializr\Helpers\Initializer;

// tell the Initializer class providing the root, the application config name, 
// and the files to merge -very useful when working with advanced boilerplates 
// and different environments
Initializer::create('./../app', 'main', array('common', 'env', 'local'))->run();
```

INSTALLATION
------------
If you are going to use Yiinitializr to make use of `Yiinitializr\Helpers\Initializr` you can easily install it via
`composer`, but if you are going to use it within your application structure in order to configure your application
according to your custom needs, then the recommended use is that you [download](https://github.com/2amigos/yiinitializr/archive/master.zip)
its source files and place them on a top level folder.

###Configuration Settings
As with Yii, you need to go through a bit of configuration settings if you wish to handle your project structure setup
with `composer`. Don't worry, is not going to be too hard, the following is an example configuration file:

```
\\ where am i?
$dirname = dirname(__FILE__);
\\ where is the application folder?
$app = $dirname . '/../../..';
\\ where is the root?
$root = $app . '/..';

return array(
    // yii configurations
	'yii' => array(
		// where is the path of the yii framework?
		// On this example we have installed yii with composer
		// and as it is used after composer installation, we 
		// can safely point to the vendor folder.
		'path' => $app . '/lib/vendor/yiisoft/yii/framework'
	),
	// yiinitializr specific settings
	'yiinitializr' => array(
	    // config folders
		'config' => array(
		    // we just need the console settings
		    // On this example, and due that I used environments
		    // i created a custom console.php app for 
		    // Yiinitializr\Composer\Callbak class (see below example)
			'console' => $dirname . '/console.php'
		),
		// application structure settings
		'app' => array(
			// where is the root?
			'root' => $root,
			// directories setup
			'directories' => array(
				// where are the different configuration files settings?
				'config' => array(
					// 'key' is the configuration name (see above init example)
					'main' => $app . '/config',
					'console' => $app . '/config',
					'test' => $app . '/config'
				),
				// where are my runtime folders?
				'runtime' => array(
					// heads up! only the folder location as "/config" will be 
					// appended
					$app
				),
				'assets' => array(
					// where to write the "assets folders"?
					$root . '/www'
				)
			)
		),
	)
);
```

Here is an example of a custom `console.php` settings file when working with environments. As you saw on the previous
code, this file on the example was located on the same `Yiinitializr\config` folder:

```
require_once dirname(__FILE__) . '/../Helpers/Initializer.php';
require_once dirname(__FILE__) . '/../Helpers/ArrayX.php';

return Yiinitializr\Helpers\ArrayX::merge(
	Yiinitializr\Helpers\Initializer::config('console', array('common', 'env', 'local')),
	array(
		'params' => array(
			// here is where the composer magic start.
			// Thanks! mr Tobias a.k.a Phundament man!
			'composer.callbacks' => array(
				'post-update' => array('yiic', 'migrate'),
				'post-install' => array('yiic', 'migrate'),
			)
		),
	)
);
```

REQUIREMENTS
------------
It works in conjunction with `composer` to install the boilerplate but 
The minimum requirements by Yiinitializr that you have installed `composer` or have a `composer.phar` on your application
root in order to run and ** PHP 5.3+**


###Resources  
- [Composer](http://getcomposer.org)  
- [Phundament](http://phundament.com/)
- [Download latest ZIPball](https://github.com/2amigos/yiinitializr/archive/master.zip)
- [2amigOS Packagist Profile](https://packagist.org/packages/2amigos/)

> [![2amigOS!](http://www.gravatar.com/avatar/55363394d72945ff7ed312556ec041e0.png)](http://www.2amigos.us)    
<i>web development has never been so fun</i>  
[www.2amigos.us](http://www.2amigos.us)