The Modus Framework
===================

(Note: This README is a work in progress.)

The Modus Framework is a collection of components included through Composer and bound together by "glue code" that makes the components useful as a unit.

Modus makes use of many components of Aura, as well as some Symfony components and others.

Installing Modus
================

After cloning and checking out the repository, you'll want to install the dependencies with Composer.

Once these components are installed, you can run the Modus command line, which will place the files in the correct directory structure.

```
$ php vendor/bin/modus
```

Modus expects to be used as a full framework for your application, operating in the root directory. It uses an htaccess file to route all calls to the index file in the public/ directory. The following vhost configuration is recommended.

```
<VirtualHost *:80>
    ServerName example.com
    DocumentRoot /path/to/modus/public
    
    #Custom log file locations
    LogLevel warn
    ErrorLog   /path/to/modus/logs/error.log
    CustomLog  /path/to/modus/logs/access.log combined

    php_flag log_errors On
    php_value error_log  /path/to/modus/logs/php_errors.log

    <Directory /path/to/modus/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

Modus Configuration Files
=========================

Modus uses a configuration system that incorporates dependency inversion through Aura's Dependency Injection package.

In addition, there are some files you'll want to modify on your own for your setup:

* config.php - This contains configuration options for your application.
* routes.php - This contains route information. There are a few example routes already provided, which you can copy and reuse.

Configuring Routes
==================

Routes are often the most complex part of an application, but the goal is to make them as simple as possible. 

At their most basic, a route has a key which names the route, and the value is an array containing two keys: the route itself and the parameters for the route.

```
<?php

array(
    "dashboard" => [
        "path" => "/dashboard",
        "args" => ["values" => ['controller' => 'dashboard', 'action' => 'index']]
    ],
);
```

Of course, some routes are more complex than this and require additional options. For example, to specify a ID value on the end of a route, you add a params key to the args array, like so:

```
<?php

array(
    "dashboard" => [
        "path" => "/dashboard/{:id}",
        "args" => ["values" => ['controller' => 'dashboard', 'action' => 'index'], 'params' => ['id' => '(/d+)']]
    ],
);
```

Note that all parameter rules of the Aura.Router package are followed. Check the documentation for more.

If you have complex namespace requirements, you can also specify a fully-qualified namespace path to the controller:

```
<?php

array(
    "dashboard" => [
        "path" => "/dashboard",
        "args" => ["values" => ['controllerns' => 'Full\Namespace\Goes\Here', 'action' => 'index']]
    ],
);
```
