<?php

require_once('../vendor/autoload.php');

$config = require_once('../config/config.php');
require_once('../config/services.php');

$framework = $di->newInstance('Modus\FrontController\Http');
$framework->execute($_SERVER);