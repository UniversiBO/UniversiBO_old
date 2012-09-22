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

class MySmarty extends SmartyBC {
	
	
    /**
     * The name of the directory where default templates are located.
     *
     * @var string
     */
    var $default_template_dir    =  'templates';
    
    public function assignUnicode($tpl_var, $value)
    {
        $this->assign($tpl_var, $value);
    }
    
    /**
     * @param string $tpl_var
     * @param mixed $value
     */
    public function assignLatin1($tpl_var, $value)
    {
        $this->assign($tpl_var, $this->latin1ToUtf8($value));
    }

	private function latin1ToUtf8($value)
	{
	    return is_array($value) ? array_map(array($this, 'latin1ToUtf8'), $value) : mb_convert_encoding($value, 'utf-8', 'iso-8859-1');
	}
}