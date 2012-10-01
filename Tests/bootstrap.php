<?php

/**
 * Part of this PHPUnit bootstrap file comes from FOSUserBundle
 * Copyright (c) 2010-2011 FriendsOfSymfony
 */
if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/, Did you run "composer install --dev"?');
}

require $autoloadFile;
