<?php
/**
 * Project:     Smarty: the PHP compiling template engine
 * File:        Smarty.class.php
 *
 * Estensione della normale classe Smarty affinché i template da mostrare vengano cercati in maniera differenziale:
 * si definisce uno stile/cartella default cui cercare se nellla cartella dello stile corrente non si trova il tpl 
 * desiderato
 *
 * @link http://smarty.php.net/
 * @copyright 2001-2004 ispi of Lincoln, Inc.
 * @author Fabrizio Pinto 
 * @package Smarty
 * @version 2.6.5-dev
 */

class MySmarty extends SmartyBC 
{
    /**
     * The name of the directory where default templates are located.
     *
     * @var string
     */
    public $default_template_dir    =  'templates';
}
