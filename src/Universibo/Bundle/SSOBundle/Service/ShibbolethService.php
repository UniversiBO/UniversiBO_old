<?php
namespace Universibo\Bundle\SSOBundle\Service;

class ShibbolethService
{
    public function destroyCookies()
    {
        $past = time() - 3600;
        foreach ($_COOKIE as $key => $value) {
        	setcookie($key, $value, $past, '/');
        }
    }
}
