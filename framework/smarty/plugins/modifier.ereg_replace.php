<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     regex_replace
 * Purpose:  regular epxression search/replace
 * -------------------------------------------------------------
 *
 */
function smarty_modifier_ereg_replace($string, $search, $replace)
{
    return ereg_replace($search, $replace, $string);
}

/* vim: set expandtab: */
