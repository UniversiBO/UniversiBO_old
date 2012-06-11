<?php
use Universibo\Bundle\LegacyBundle\Framework\BaseReceiver;

use Universibo\Bundle\LegacyBundle\Entity\Ruolo;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/autoload.php';
require_once __DIR__.'/../app/AppKernel.php';

if (in_array(@$_SERVER['REMOTE_ADDR'], array(
        '127.0.0.1',
        '::1',
))) {
    $env = 'dev';
} else {
    $env = 'prod';
}

//list($usec, $sec) = explode(" ", microtime());
//$page_time_start = ((float) $usec + (float) $sec);

// TODO hack orrendo per caricare ruolo e le relative costanti
class_exists('Universibo\\Bundle\\LegacyBundle\\Entity\\Ruolo');

class Receiver extends BaseReceiver {}

$receiver = new Receiver('main', '../config.xml', '../framework', '../universibo', new AppKernel($env, $env !== 'prod'));
$receiver->main();

//list($usec, $sec) = explode(" ", microtime());
//$page_time_end = ((float) $usec + (float) $sec);

//printf("%01.5f", $page_time_end - $page_time_start);
