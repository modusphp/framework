<?php

$config = require_once('../config/config.php');
require_once '../src/Modus/FrontController/Http.php';

$framework = new Modus\FrontController\Http($config);
$framework->execute($_SERVER);