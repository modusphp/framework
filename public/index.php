<?php

$config = require_once('../config/config.php');
require_once('../vendor/autoload.php');

$framework = new Modus\FrontController\Http($config);
$framework->execute($_SERVER);