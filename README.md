YIInitializr-advanced
=======
The following is a proposed project structure for advanced applications that works in conjunction with YIInitializr components. 

YIInitializr vanilla projects make extensive use of Composer. We found at [2amigos.us](http://2amigos.us) that is easier to your extensions bundle outside of your application scope - thanks to [Phundament](http://phundament.com) and Tobias Munk for his knowledge and shares. Composer is your dependency package best friend. 

The package is cleaned from extensions, you choose what you wish to include in your composer.json files. The only ones included are Yii Framework (obviously), [YiiStrap](https://github.com/yii-twbs/yiistrap) and [YiiWheels](https://github.com/2amigos/yiiwheels), the rest is up to you. We do not want to confuse you. 


## Setup

 * Set up Git by following the instructions [here](https://help.github.com/articles/set-up-git).
 * Composer is required The package includes already a `composer.phar` file. 
 * Browse through the `composer.json` and remove the dependencies you don't need.
 * Run `composer install` to download all the dependencies.
 * `Yiinitializr\Composer\Callback` will configure everything required on your application: `runtime` and `assets` folders and migrations.
 * Update the configurations in `api/config/`, `frontend/config/`, `console/config/`, `backend/config/` and `common/config/` to suit your needs.

For more information about using Composer please see its [documentation](http://getcomposer.org/doc/).

###How to configure the application

This boilerplate is very similar to YiiBoilerplate but differs from it for the easiness of its configuration. We focused to release the pain of configuring your application and combine your configuration files. `Yiinitializr\Helpers\Initializr` is very easy to use, check for example the bootstrap `index.php` file at the frontend:

```
require('./../../common/lib/vendor/autoload.php');
require('./../../common/lib/vendor/yiisoft/yii/framework/yii.php');

Yii::setPathOfAlias('Yiinitializr', './../../common/lib/Yiinitializr');

use Yiinitializr\Helpers\Initializer;

Initializer::create('./../', 'frontend', array(
	__DIR__ .'/../../common/config/main.php', // files to merge with
	__DIR__ .'/../../common/config/env.php',
	__DIR__ .'/../../common/config/local.php',
))->run();
```

For more information about Yiinitializr please check it at [its github repo](https://github.com/2amigos/yiinitializr).

## Overall Structure

Bellow the directory structure used:

```
   |-api
   |---config
   |-----env
   |---controllers
   |---extensions
   |-----components
   |-----filters
   |---models
   |---www
   |-backend
   |---components
   |---config
   |-----env
   |---controllers
   |---extensions
   |---helpers
   |---lib
   |---models
   |---modules
   |---tests
   |---views
   |-----layouts
   |-----site
   |---widgets
   |---www
   |-----css
   |-------fonts
   |-----img
   |-----js
   |-------libs
   |-common
   |---components
   |---config
   |-----env
   |---extensions
   |-----components
   |---helpers
   |---lib
   |-----YiiRestTools
   |-------Common
   |---------Enum
   |---------Exception
   |-------Helpers
   |-----Yiinitializr
   |-------Cli
   |-------Composer
   |-------Helpers
   |-------config
   |---messages
   |---models
   |---schema
   |---widgets
   |-console
   |---commands
   |---components
   |---config
   |-----env
   |---data
   |---extensions
   |---migrations
   |---models
   |-frontend
   |---components
   |---config
   |-----env
   |---controllers
   |---extensions
   |---helpers
   |---lib
   |---models
   |---modules
   |---tests
   |---views
   |-----layouts
   |-----site
   |---widgets
   |---www
   |-----assets
   |-----css
   |-------fonts
   |-----img
   |-----js
   |-------libs
  
 ```

## Extensions

The following extensions are part of YIInitializr-basic template:

 * Yiistrap [https://github.com/yii-twbs/yiistrap](https://github.com/yii-twbs/yiistrap)
 * Yiiwheels [https://github.com/2amigos/yiiwheels](https://github.com/2amigos/yiiwheels)
 * Yiinitializr [https://github.com/2amigos/yiinitializr](https://github.com/2amigos/yiinitializr)

> [![2amigOS!](http://www.gravatar.com/avatar/55363394d72945ff7ed312556ec041e0.png)](http://www.2amigos.us)    
<i>web development has never been so fun</i>  
[www.2amigos.us](http://www.2amigos.us) 
