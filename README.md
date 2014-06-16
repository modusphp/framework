The Modus Framework
===================

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

