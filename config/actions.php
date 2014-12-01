<?php


$di->setter['Application\Controller\Index']['setAuth'] = $di->lazyNew('Modus\Auth\Service');